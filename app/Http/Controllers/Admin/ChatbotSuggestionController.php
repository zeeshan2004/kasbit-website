<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotCategory;
use App\Models\ChatbotSuggestedQuestion;
use Illuminate\Http\Request;

class ChatbotSuggestionController extends Controller
{
    public function index()
    {
        return view('admin.chatbot.suggestions', [
            'suggestions' => ChatbotSuggestedQuestion::with('category')->orderBy('display_order')->get(),
            'categories' => ChatbotCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        ChatbotSuggestedQuestion::create($this->validated($request));

        return back()->with('success', 'Suggested question added.');
    }

    public function update(Request $request, ChatbotSuggestedQuestion $suggestion)
    {
        $suggestion->update($this->validated($request));

        return back()->with('success', 'Suggested question updated.');
    }

    public function destroy(ChatbotSuggestedQuestion $suggestion)
    {
        $suggestion->delete();

        return back()->with('success', 'Suggested question removed.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'is_active' => $request->boolean('is_active'),
            'show_on_welcome' => $request->boolean('show_on_welcome'),
        ]);

        return $request->validate([
            'category_id' => ['nullable', 'exists:chatbot_categories,id'],
            'question' => ['required', 'string', 'min:3', 'max:500'],
            'answer' => ['nullable', 'string', 'max:30000'],
            'display_order' => ['required', 'integer', 'between:0,1000'],
            'is_active' => ['boolean'],
            'show_on_welcome' => ['boolean'],
        ]);
    }
}
