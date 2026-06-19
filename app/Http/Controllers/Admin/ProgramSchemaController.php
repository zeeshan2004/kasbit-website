<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenuPage;
use App\Models\ProgramSchemaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramSchemaController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureProgramPage($page);
        $data = $this->validatedData($request);

        DB::transaction(function () use ($page, $data, $request) {
            $table = $page->programSchemaTables()->create([
                'title' => $data['title'],
                'sort_order' => $data['sort_order']
                    ?? ((int) $page->programSchemaTables()->max('sort_order') + 1),
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($table, $data['rows']);
        });

        return $this->respond($request, 'Program schema table added.', $page);
    }

    public function update(Request $request, ProgramSchemaTable $schemaTable)
    {
        $this->ensureProgramPage($schemaTable->page);
        $data = $this->validatedData($request);

        DB::transaction(function () use ($schemaTable, $data, $request) {
            $schemaTable->update([
                'title' => $data['title'],
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($schemaTable, $data['rows']);
        });

        return $this->respond($request, 'Program schema table updated.', $schemaTable->page, false);
    }

    public function destroy(Request $request, ProgramSchemaTable $schemaTable)
    {
        $this->ensureProgramPage($schemaTable->page);
        $page = $schemaTable->page;
        $schemaTable->delete();

        return $this->respond($request, 'Program schema table deleted.', $page);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.subject' => ['required', 'string', 'max:500'],
            'rows.*.credit_hours' => ['nullable', 'string', 'max:50'],
            'rows.*.is_total' => ['nullable', 'boolean'],
            'rows.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function replaceRows(ProgramSchemaTable $table, array $rows): void
    {
        $table->rows()->delete();

        foreach ($rows as $index => $row) {
            $table->rows()->create([
                'semester' => null,
                'subject' => trim($row['subject']),
                'credit_hours' => filled($row['credit_hours'] ?? null) ? trim($row['credit_hours']) : null,
                'is_total' => (bool) ($row['is_total'] ?? false),
                'sort_order' => $row['sort_order'] ?? $index,
            ]);
        }
    }

    private function ensureProgramPage(HeaderMenuPage $page): void
    {
        abort_unless($page->menu?->isDescendantOf('Programs'), 404);
    }

    private function respond(
        Request $request,
        string $message,
        HeaderMenuPage $page,
        bool $refresh = true
    )
    {
        $url = route('header-menu.page.edit', $page->menu, false);

        if ($request->expectsJson()) {
            return response()->json(array_filter([
                'message' => $message,
                'refresh_url' => $refresh ? $url : null,
            ]));
        }

        return redirect($url)->with('success', $message);
    }
}
