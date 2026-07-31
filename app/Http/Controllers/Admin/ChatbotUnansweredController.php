<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotCategory;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotAlternativeQuestion;
use App\Models\ChatbotRelatedQuestion;
use App\Models\ChatbotUnansweredQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChatbotUnansweredController extends Controller
{
    public function index(Request $request)
    {
        $items = ChatbotUnansweredQuestion::query()
            ->with(['user', 'provider', 'answeredBy', 'department'])
            ->when($request->filled('search'), fn ($query) => $query->where('user_question', 'like', '%'.$request->string('search')->trim().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('last_asked_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.chatbot.unanswered', [
            'items' => $items,
            'categories' => ChatbotCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, ChatbotUnansweredQuestion $unanswered)
    {
        $data = $request->validate([
            'admin_answer' => ['nullable', 'string', 'max:30000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['pending', 'answered', 'ignored'])],
        ]);
        $data['answered_by'] = $data['status'] === 'answered' ? $request->user()->id : null;
        $data['answered_at'] = $data['status'] === 'answered' ? now() : null;
        $unanswered->update($data);

        return back()->with('success', 'Unanswered item updated.');
    }

    public function promote(Request $request, ChatbotUnansweredQuestion $unanswered)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:chatbot_categories,id'],
            'answer' => ['required', 'string', 'min:2', 'max:30000'],
            'priority' => ['nullable', 'integer', 'between:0,1000'],
            'alternatives' => ['nullable', 'string', 'max:10000'],
            'related_questions' => ['nullable', 'string', 'max:10000'],
        ]);

        DB::transaction(function () use ($request, $unanswered, $data) {
            $knowledge = ChatbotKnowledgeBase::create([
                'category_id' => $data['category_id'] ?? null,
                'question' => $unanswered->user_question,
                'answer' => $data['answer'],
                'status' => 'approved',
                'priority' => $data['priority'] ?? 0,
                'answer_origin' => 'admin_unanswered',
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]);
            foreach ($this->lines($data['alternatives'] ?? null) as $question) {
                ChatbotAlternativeQuestion::create([
                    'knowledge_base_id' => $knowledge->id,
                    'question' => $question,
                ]);
            }
            foreach ($this->lines($data['related_questions'] ?? null) as $index => $question) {
                ChatbotRelatedQuestion::create([
                    'knowledge_base_id' => $knowledge->id,
                    'question' => $question,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
            $unanswered->update([
                'admin_answer' => $data['answer'],
                'status' => 'answered',
                'answered_by' => $request->user()->id,
                'answered_at' => now(),
            ]);
        });

        return back()->with('success', 'Question promoted to the approved knowledge base.');
    }

    /**
     * @return array<int, string>
     */
    private function lines(?string $value): array
    {
        return collect(preg_split('/\R/u', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->unique()
            ->take(50)
            ->values()
            ->all();
    }
}
