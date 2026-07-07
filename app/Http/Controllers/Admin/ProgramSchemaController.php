<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenuPage;
use App\Models\ProgramSchemaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProgramSchemaController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureProgramPage($page);
        $data = $this->validatedData($request);

        DB::transaction(function () use ($page, $data, $request) {
            $table = $page->programSchemaTables()->create([
                'title' => $data['title'],
                'qec_serial_label' => $data['qec_serial_label'] ?? 'S. No',
                'qec_col1_label' => $data['qec_col1_label'] ?? 'Title of Event',
                'qec_col2_label' => $data['qec_col2_label'] ?? 'Date Held',
                'qec_col3_label' => $data['qec_col3_label'] ?? 'Host',
                'qec_col4_label' => filled($data['qec_col4_label'] ?? null) ? $data['qec_col4_label'] : null,
                'qec_show_col4' => $request->boolean('qec_show_col4'),
                'qec_col5_label' => filled($data['qec_col5_label'] ?? null) ? $data['qec_col5_label'] : null,
                'qec_show_col5' => $request->boolean('qec_show_col5'),
                'sort_order' => $data['sort_order']
                    ?? ((int) $page->programSchemaTables()->max('sort_order') + 1),
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($request, $table, $data['rows']);
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
                'qec_serial_label' => $data['qec_serial_label'] ?? 'S. No',
                'qec_col1_label' => $data['qec_col1_label'] ?? 'Title of Event',
                'qec_col2_label' => $data['qec_col2_label'] ?? 'Date Held',
                'qec_col3_label' => $data['qec_col3_label'] ?? 'Host',
                'qec_col4_label' => filled($data['qec_col4_label'] ?? null) ? $data['qec_col4_label'] : null,
                'qec_show_col4' => $request->boolean('qec_show_col4'),
                'qec_col5_label' => filled($data['qec_col5_label'] ?? null)
                    ? $data['qec_col5_label']
                    : $schemaTable->qec_col5_label,
                'qec_show_col5' => $request->has('qec_show_col5')
                    ? $request->boolean('qec_show_col5')
                    : $schemaTable->qec_show_col5,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($request, $schemaTable, $data['rows']);
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
            'qec_serial_label' => ['nullable', 'string', 'max:1000'],
            'qec_col1_label' => ['nullable', 'string', 'max:255'],
            'qec_col2_label' => ['nullable', 'string', 'max:255'],
            'qec_col3_label' => ['nullable', 'string', 'max:255'],
            'qec_col4_label' => ['nullable', 'string', 'max:255'],
            'qec_show_col4' => ['nullable', 'boolean'],
            'qec_col5_label' => ['nullable', 'string', 'max:255'],
            'qec_show_col5' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.subject' => ['required', 'string', 'max:500'],
            'rows.*.image_path' => ['nullable', 'string', 'max:255'],
            'rows.*.image_file' => ['nullable', 'image', 'max:10240'],
            'rows.*.credit_hours' => ['nullable', 'string', 'max:500'],
            'rows.*.col3_text' => ['nullable', 'string', 'max:500'],
            'rows.*.col4_text' => ['nullable', 'string', 'max:500'],
            'rows.*.col5_text' => ['nullable', 'string', 'max:500'],
            'rows.*.is_total' => ['nullable', 'boolean'],
            'rows.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function replaceRows(Request $request, ProgramSchemaTable $table, array $rows): void
    {
        $table->rows()->delete();
        foreach ($rows as $index => $row) {
            $table->rows()->create([
                'semester' => null,
                'image_path' => $this->resolveRowImage($request, $row, $index),
                'subject' => trim($row['subject']),
                'credit_hours' => filled($row['credit_hours'] ?? null) ? trim($row['credit_hours']) : null,
                'col3_text' => filled($row['col3_text'] ?? null) ? trim($row['col3_text']) : null,
                'col4_text' => filled($row['col4_text'] ?? null) ? trim($row['col4_text']) : null,
                'col5_text' => filled($row['col5_text'] ?? null) ? trim($row['col5_text']) : null,
                'is_total' => (bool) ($row['is_total'] ?? false),
                'sort_order' => $row['sort_order'] ?? $index,
            ]);
        }
    }

    private function resolveRowImage(Request $request, array $row, int $index): ?string
    {
        $file = $request->file("rows.$index.image_file");

        if (! $file) {
            return filled($row['image_path'] ?? null) ? $row['image_path'] : null;
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $filename = now()->timestamp.'_'.Str::random(10).'_membership_logo.'.$extension;
        File::ensureDirectoryExists(public_path('uploads/memberships'));
        $file->move(public_path('uploads/memberships'), $filename);

        return 'uploads/memberships/'.$filename;
    }

    private function ensureProgramPage(HeaderMenuPage $page): void
    {
        abort_unless(
            $page->menu?->isDescendantOf('Programs')
                || strtolower($page->slug) === 'qec-activities'
                || strtolower($page->slug) === 'memberships'
                || strtolower($page->slug) === 'at-pt-notification',
            404
        );
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
