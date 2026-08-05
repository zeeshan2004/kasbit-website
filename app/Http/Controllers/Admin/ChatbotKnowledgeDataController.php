<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotKnowledgeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ChatbotKnowledgeDataController extends Controller
{
    public function index(Request $request)
    {
        $data = ChatbotKnowledgeData::query()
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $s = '%' . $request->string('search')->trim() . '%';
                $q->where('title', 'like', $s)->orWhere('content', 'like', $s)->orWhere('keywords', 'like', $s);
            }))
            ->when($request->filled('intent'), fn ($q) => $q->where('intent', $request->input('intent')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.chatbot.knowledge-data', [
            'items' => $data,
            'intents' => ChatbotKnowledgeData::INTENTS,
            'counts' => ChatbotKnowledgeData::active()
                ->select('intent', DB::raw('count(*) as total'))
                ->groupBy('intent')
                ->pluck('total', 'intent')
                ->all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'intent' => ['required', Rule::in(array_keys(ChatbotKnowledgeData::INTENTS))],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:5', 'max:50000'],
            'keywords' => ['nullable', 'string', 'max:500'],
        ]);

        ChatbotKnowledgeData::create([
            ...$data,
            'is_active' => true,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Knowledge data added.');
    }

    public function update(Request $request, ChatbotKnowledgeData $knowledgeData)
    {
        $data = $request->validate([
            'intent' => ['required', Rule::in(array_keys(ChatbotKnowledgeData::INTENTS))],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:5', 'max:50000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $knowledgeData->update($data);

        return back()->with('success', 'Knowledge data updated.');
    }

    public function destroy(ChatbotKnowledgeData $knowledgeData)
    {
        $knowledgeData->delete();

        return back()->with('success', 'Knowledge data removed.');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => ['required', 'file', 'max:10240'],
            'import_intent' => ['required', Rule::in(array_keys(ChatbotKnowledgeData::INTENTS))],
        ]);

        $intent = $request->input('import_intent');
        $totalImported = 0;

        foreach ($request->file('files') as $file) {
            $content = file_get_contents($file->getRealPath());

            // Remove BOM
            if (str_starts_with($content, "\xEF\xBB\xBF")) {
                $content = substr($content, 3);
            }

            $content = trim($content);
            if (Str::length($content) < 10) continue;

            // Try CSV parsing — if headers found, create separate entries per row
            $rows = $this->parseCsvToEntries($content, $intent);

            if (!empty($rows)) {
                foreach ($rows as $row) {
                    ChatbotKnowledgeData::create([
                        'intent' => $intent,
                        'title' => $row['title'],
                        'content' => $row['content'],
                        'keywords' => $row['keywords'] ?? null,
                        'is_active' => true,
                        'created_by' => $request->user()->id,
                    ]);
                    $totalImported++;
                }
            } else {
                // Not a CSV or couldn't parse — store entire file as one entry
                ChatbotKnowledgeData::create([
                    'intent' => $intent,
                    'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'content' => $content,
                    'keywords' => null,
                    'is_active' => true,
                    'created_by' => $request->user()->id,
                ]);
                $totalImported++;
            }
        }

        return back()->with('success', "{$totalImported} entries imported.");
    }

    /**
     * Parse CSV content into structured knowledge entries.
     */
    private function parseCsvToEntries(string $content, string $intent): array
    {
        $lines = explode("\n", str_replace("\r\n", "\n", $content));
        if (count($lines) < 2) return [];

        // Detect delimiter
        $firstLine = $lines[0];
        $delimiter = str_contains($firstLine, "\t") ? "\t" : ',';

        $header = str_getcsv($firstLine, $delimiter);
        $header = array_map(fn($h) => strtolower(trim(trim($h), '"\'  ')), $header);

        // Need at least one meaningful column — find "name" or first column as title
        $nameIndex = $this->findColumnIndex($header, ['name', 'title', 'question', 'subject', 'program']);

        if ($nameIndex === false) {
            // Can't determine structure — return empty
            return [];
        }

        $entries = [];
        for ($i = 1; $i < count($lines) && count($entries) < 500; $i++) {
            $row = str_getcsv($lines[$i], $delimiter);
            if (empty(array_filter($row))) continue;

            $title = trim($row[$nameIndex] ?? '');
            if (Str::length($title) < 2) continue;

            // Build content from all columns
            $contentParts = [];
            foreach ($header as $colIdx => $colName) {
                $value = trim($row[$colIdx] ?? '');
                if ($value === '' || $colIdx === $nameIndex) continue;
                $label = ucfirst(str_replace('_', ' ', $colName));
                $contentParts[] = "{$label}: {$value}";
            }

            if (empty($contentParts)) continue;

            $entries[] = [
                'title' => $title,
                'content' => implode("\n", $contentParts),
                'keywords' => $title,
            ];
        }

        return $entries;
    }

    private function findColumnIndex(array $header, array $candidates): int|false
    {
        foreach ($candidates as $candidate) {
            $index = array_search($candidate, $header);
            if ($index !== false) return $index;
        }
        // Fallback: first column
        return count($header) > 0 ? 0 : false;
    }
}
