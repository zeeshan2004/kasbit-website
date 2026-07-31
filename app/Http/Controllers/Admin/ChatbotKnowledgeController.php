<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChatbotKnowledgeRequest;
use App\Models\ChatbotAlternativeQuestion;
use App\Models\ChatbotCategory;
use App\Models\ChatbotKnowledgeBase;
use App\Models\ChatbotRelatedQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatbotKnowledgeController extends Controller
{
    public function index(Request $request)
    {
        $knowledge = ChatbotKnowledgeBase::query()
            ->with(['category', 'alternatives', 'relatedQuestions'])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $search = '%'.$request->string('search')->trim().'%';
                $query->where('question', 'like', $search)->orWhere('answer', 'like', $search);
            }))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderByDesc('priority')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.chatbot.knowledge', [
            'knowledgeItems' => $knowledge,
            'categories' => ChatbotCategory::withCount('knowledge')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(ChatbotKnowledgeRequest $request)
    {
        $knowledge = DB::transaction(fn () => $this->saveKnowledge(
            new ChatbotKnowledgeBase(['created_by' => $request->user()->id]),
            $request,
        ));

        return back()->with('success', "Knowledge entry #{$knowledge->id} added.");
    }

    public function update(ChatbotKnowledgeRequest $request, ChatbotKnowledgeBase $knowledge)
    {
        DB::transaction(fn () => $this->saveKnowledge($knowledge, $request));

        return back()->with('success', 'Knowledge entry updated.');
    }

    public function destroy(ChatbotKnowledgeBase $knowledge)
    {
        $knowledge->delete();

        return back()->with('success', 'Knowledge entry removed.');
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:chatbot_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'between:0,1000'],
        ]);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));
        $data['is_active'] = true;
        ChatbotCategory::create($data);

        return back()->with('success', 'Category added.');
    }

    public function destroyCategory(ChatbotCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Category removed; its knowledge entries were preserved.');
    }

    private function saveKnowledge(
        ChatbotKnowledgeBase $knowledge,
        ChatbotKnowledgeRequest $request,
    ): ChatbotKnowledgeBase {
        $knowledge->fill($request->safe()->except(['alternatives', 'related_questions']));
        $knowledge->updated_by = $request->user()->id;
        $knowledge->save();

        $knowledge->alternatives()->delete();
        foreach ($this->lines($request->input('alternatives')) as $question) {
            ChatbotAlternativeQuestion::create([
                'knowledge_base_id' => $knowledge->id,
                'question' => $question,
            ]);
        }

        $knowledge->relatedQuestions()->delete();
        foreach ($this->lines($request->input('related_questions')) as $index => $question) {
            ChatbotRelatedQuestion::create([
                'knowledge_base_id' => $knowledge->id,
                'question' => $question,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        return $knowledge;
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
