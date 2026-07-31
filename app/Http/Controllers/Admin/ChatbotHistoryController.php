<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\ChatbotCategory;
use App\Models\ChatbotMessage;
use Illuminate\Http\Request;

class ChatbotHistoryController extends Controller
{
    public function index(Request $request)
    {
        $messages = ChatbotMessage::query()
            ->where('role', 'assistant')
            ->with(['parent', 'conversation.user', 'provider', 'category'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $search = '%'.$request->string('search')->trim().'%';
                $query->where('content', 'like', $search)
                    ->orWhereHas('parent', fn ($parent) => $parent->where('content', 'like', $search));
            }))
            ->when($request->filled('source'), fn ($query) => $query->where('answer_source', $request->string('source')))
            ->when($request->filled('provider_id'), fn ($query) => $query->where('ai_provider_id', $request->integer('provider_id')))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('created_at', $request->date('date')))
            ->when($request->filled('user'), fn ($query) => $query->whereHas('conversation', function ($query) use ($request) {
                $search = '%'.$request->string('user')->trim().'%';
                $query->where('guest_session_id', 'like', $search)
                    ->orWhere('metadata->student_profile->student_id', 'like', $search)
                    ->orWhere('metadata->student_profile->full_name', 'like', $search)
                    ->orWhere('metadata->student_profile->department_name', 'like', $search)
                    ->orWhereHas('user', fn ($user) => $user
                        ->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search));
            }))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('answer_status'), function ($query) use ($request) {
                $request->string('answer_status')->toString() === 'unanswered'
                    ? $query->where('answer_source', 'unanswered')
                    : $query->where('answer_source', '!=', 'unanswered');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.chatbot.history', [
            'messages' => $messages,
            'providers' => AiProvider::orderBy('name')->get(),
            'categories' => ChatbotCategory::orderBy('name')->get(),
            'sources' => ['knowledge_base', 'admin_answer', 'website_data', 'openai', 'openrouter', 'claude', 'gemini', 'custom_api', 'unanswered', 'security'],
        ]);
    }

    public function correct(ChatbotMessage $message)
    {
        abort_unless($message->role === 'assistant' && $message->parent, 404);

        return redirect()
            ->route('admin.chatbot.knowledge.index')
            ->withInput([
                'category_id' => $message->category_id,
                'status' => 'approved',
                'question' => $message->parent->content,
                'answer' => $message->content,
                'priority' => 100,
            ])
            ->with('success', 'Review the answer below, correct it, then save it to override AI next time.');
    }
}
