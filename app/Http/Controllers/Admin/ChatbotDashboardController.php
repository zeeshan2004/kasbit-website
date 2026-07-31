<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotMessage;
use App\Models\ChatbotUnansweredQuestion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChatbotDashboardController extends Controller
{
    public function index()
    {
        $aiSources = ['openai', 'openrouter', 'claude', 'gemini', 'custom_api'];
        $stats = [
            'conversations' => ChatbotConversation::count(),
            'questions' => ChatbotMessage::where('role', 'user')->count(),
            'knowledge' => ChatbotKnowledgeBase::approved()->count(),
            'unanswered' => ChatbotUnansweredQuestion::where('status', 'pending')->count(),
            'knowledge_answers' => ChatbotMessage::where('role', 'assistant')
                ->whereIn('answer_source', ['knowledge_base', 'admin_answer'])->count(),
            'ai_answers' => ChatbotMessage::where('role', 'assistant')
                ->whereIn('answer_source', $aiSources)->count(),
            'average_ai_ms' => (int) round(ChatbotMessage::where('role', 'assistant')
                ->whereIn('answer_source', $aiSources)->avg('response_time_ms') ?? 0),
        ];

        $sources = ChatbotMessage::query()
            ->where('role', 'assistant')
            ->selectRaw('answer_source, COUNT(*) as total')
            ->groupBy('answer_source')
            ->orderByDesc('total')
            ->pluck('total', 'answer_source');

        $dailyRaw = ChatbotMessage::query()
            ->where('role', 'user')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
        $daily = collect(range(6, 0))->mapWithKeys(function (int $daysAgo) use ($dailyRaw) {
            $date = Carbon::today()->subDays($daysAgo);

            return [$date->format('d M') => (int) ($dailyRaw[$date->toDateString()] ?? 0)];
        });

        $providerUsage = ChatbotMessage::query()
            ->where('role', 'assistant')
            ->whereNotNull('ai_provider_id')
            ->join('ai_providers', 'ai_providers.id', '=', 'chatbot_messages.ai_provider_id')
            ->select('ai_providers.name', DB::raw('COUNT(chatbot_messages.id) as total'))
            ->groupBy('ai_providers.id', 'ai_providers.name')
            ->pluck('total', 'name');

        $categoryUsage = ChatbotMessage::query()
            ->where('role', 'assistant')
            ->whereNotNull('category_id')
            ->join('chatbot_categories', 'chatbot_categories.id', '=', 'chatbot_messages.category_id')
            ->select('chatbot_categories.name', DB::raw('COUNT(chatbot_messages.id) as total'))
            ->groupBy('chatbot_categories.id', 'chatbot_categories.name')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'name');

        return view('admin.chatbot.dashboard', [
            'stats' => $stats,
            'sources' => $sources,
            'daily' => $daily,
            'providerUsage' => $providerUsage,
            'categoryUsage' => $categoryUsage,
            'provider' => AiProvider::where('is_active', true)->orderByDesc('is_default')->first(),
            'recentUnanswered' => ChatbotUnansweredQuestion::latest('last_asked_at')->limit(8)->get(),
        ]);
    }
}
