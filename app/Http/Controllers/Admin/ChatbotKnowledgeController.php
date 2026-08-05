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
            ->when($request->filled('origin'), fn ($query) => $query->where('answer_origin', $request->string('origin')))
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

    public function import(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => ['required', 'file', 'max:10240'], // 10MB max per file
        ]);

        // Accept CSV, TXT, and common document formats
        foreach ($request->file('files') as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (! in_array($ext, ['csv', 'txt', 'md', 'text'])) {
                return back()->with('error', 'Only .csv, .txt, and .md files are allowed.');
            }
        }

        $totalImported = 0;

        foreach ($request->file('files') as $file) {
            $content = file_get_contents($file->getRealPath());

            // Remove UTF-8 BOM
            if (str_starts_with($content, "\xEF\xBB\xBF")) {
                $content = substr($content, 3);
            }

            $content = trim($content);

            if (Str::length($content) < 10) {
                continue;
            }

            \App\Models\ChatbotDocument::create([
                'filename' => Str::random(20) . '.' . $file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
                'content' => $content,
                'content_length' => Str::length($content),
                'is_active' => true,
                'uploaded_by' => $request->user()->id,
            ]);

            $totalImported++;
        }

        if ($totalImported === 0) {
            return back()->with('error', 'Files were empty or too small. Please upload files with actual data.');
        }

        return back()->with('success', "{$totalImported} file(s) imported successfully. The AI will now use this data to answer questions.");
    }

    public function deleteDocument(\App\Models\ChatbotDocument $document)
    {
        $document->delete();

        return back()->with('success', 'File removed successfully.');
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

    /**
     * Parse CSV file into rows with question/answer columns.
     *
     * @return array<int, array<string, string>>
     */
    private function parseCsv(string $path): array
    {
        $rows = [];
        $content = file_get_contents($path);

        // Remove UTF-8 BOM (Excel adds this)
        if ($content && str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Write cleaned content to temp file
        $tempPath = tempnam(sys_get_temp_dir(), 'csv_');
        file_put_contents($tempPath, $content);

        $handle = fopen($tempPath, 'r');

        if (! $handle) {
            @unlink($tempPath);
            return [];
        }

        $header = fgetcsv($handle);

        if (! $header) {
            fclose($handle);
            @unlink($tempPath);
            return [];
        }

        // Normalize headers to lowercase, trim whitespace and quotes
        $header = array_map(fn ($col) => strtolower(trim(trim($col), '"\'  ')), $header);

        // Handle semicolon-separated CSVs (common in some locales)
        if (count($header) === 1 && str_contains($header[0], ';')) {
            rewind($handle);
            $header = fgetcsv($handle, 0, ';');
            if (! $header) {
                fclose($handle);
                @unlink($tempPath);
                return [];
            }
            $header = array_map(fn ($col) => strtolower(trim(trim($col), '"\'  ')), $header);

            $questionIndex = array_search('question', $header);
            $answerIndex = array_search('answer', $header);

            if ($questionIndex === false || $answerIndex === false) {
                fclose($handle);
                @unlink($tempPath);
                return [];
            }

            $keywordsIndex = array_search('keywords', $header);
            $priorityIndex = array_search('priority', $header);

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($rows) >= 500) break;
                $rows[] = [
                    'question' => $row[$questionIndex] ?? '',
                    'answer' => $row[$answerIndex] ?? '',
                    'keywords' => $keywordsIndex !== false ? ($row[$keywordsIndex] ?? '') : '',
                    'priority' => $priorityIndex !== false ? ($row[$priorityIndex] ?? '50') : '50',
                ];
            }

            fclose($handle);
            @unlink($tempPath);
            return $rows;
        }

        $questionIndex = array_search('question', $header);
        $answerIndex = array_search('answer', $header);

        if ($questionIndex === false || $answerIndex === false) {
            fclose($handle);
            @unlink($tempPath);
            return [];
        }

        $keywordsIndex = array_search('keywords', $header);
        $priorityIndex = array_search('priority', $header);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($rows) >= 500) break;

            $rows[] = [
                'question' => $row[$questionIndex] ?? '',
                'answer' => $row[$answerIndex] ?? '',
                'keywords' => $keywordsIndex !== false ? ($row[$keywordsIndex] ?? '') : '',
                'priority' => $priorityIndex !== false ? ($row[$priorityIndex] ?? '50') : '50',
            ];
        }

        fclose($handle);
        @unlink($tempPath);
        return $rows;
    }
}
