<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendarTable;
use App\Models\HeaderMenuPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicCalendarController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureAcademicCalendarPage($page);
        $data = $this->validatedData($request);

        DB::transaction(function () use ($page, $data, $request) {
            $table = $page->academicCalendarTables()->create([
                'title' => $data['title'] ?? null,
                'type' => $data['type'],
                'col1_label' => $data['col1_label'] ?? null,
                'col2_label' => $data['col2_label'] ?? null,
                'col3_label' => $data['col3_label'] ?? null,
                'sort_order' => $data['sort_order']
                    ?? ((int) $page->academicCalendarTables()->max('sort_order') + 1),
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($table, $data['rows']);
        });

        return $this->respond($request, 'Academic calendar table added.', $page);
    }

    public function update(Request $request, AcademicCalendarTable $calendarTable)
    {
        $this->ensureAcademicCalendarPage($calendarTable->page);
        $data = $this->validatedData($request);

        DB::transaction(function () use ($calendarTable, $data, $request) {
            $calendarTable->update([
                'title' => $data['title'] ?? null,
                'type' => $data['type'],
                'col1_label' => $data['col1_label'] ?? null,
                'col2_label' => $data['col2_label'] ?? null,
                'col3_label' => $data['col3_label'] ?? null,
                'sort_order' => $data['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->replaceRows($calendarTable, $data['rows']);
        });

        return $this->respond($request, 'Academic calendar table updated.', $calendarTable->page, false);
    }

    public function destroy(Request $request, AcademicCalendarTable $calendarTable)
    {
        $this->ensureAcademicCalendarPage($calendarTable->page);
        $page = $calendarTable->page;
        $calendarTable->delete();

        return $this->respond($request, 'Academic calendar table deleted.', $page);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:semester,holidays,note'],
            'col1_label' => ['nullable', 'string', 'max:120'],
            'col2_label' => ['nullable', 'string', 'max:120'],
            'col3_label' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.occasion' => ['nullable', 'string', 'max:500'],
            'rows.*.days' => ['nullable', 'string', 'max:255'],
            'rows.*.date_text' => ['nullable', 'string', 'max:255'],
            'rows.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function replaceRows(AcademicCalendarTable $table, array $rows): void
    {
        $table->rows()->delete();

        foreach ($rows as $index => $row) {
            if (!filled($row['occasion'] ?? null)
                && !filled($row['days'] ?? null)
                && !filled($row['date_text'] ?? null)) {
                continue;
            }

            $table->rows()->create([
                'occasion' => filled($row['occasion'] ?? null) ? trim($row['occasion']) : null,
                'days' => filled($row['days'] ?? null) ? trim($row['days']) : null,
                'date_text' => filled($row['date_text'] ?? null) ? trim($row['date_text']) : null,
                'sort_order' => $row['sort_order'] ?? $index,
            ]);
        }
    }

    private function ensureAcademicCalendarPage(HeaderMenuPage $page): void
    {
        abort_unless(in_array(strtolower($page->menu?->name ?? ''), [
            'academic calendar',
            'academic scholarship',
        ], true), 404);
    }

    private function respond(
        Request $request,
        string $message,
        HeaderMenuPage $page,
        bool $refresh = true
    ) {
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
