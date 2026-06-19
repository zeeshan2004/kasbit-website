<?php

namespace App\Http\Controllers;

use App\Models\HeaderMenuPage;
use App\Models\HomePage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    public function show(HeaderMenuPage $page)
    {
        abort_unless($page->menu?->is_active, 404);
        $page->load([
            'slides' => fn ($query) => $query->where('is_active', true),
            'programSchemaTables' => fn ($query) => $query
                ->where('is_active', true)
                ->with('rows'),
        ]);

        return view('frontend.dynamic-page', [
            'page' => $page,
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }

    public function downloadPdf(HeaderMenuPage $page): BinaryFileResponse
    {
        abort_unless($page->menu?->is_active && $this->supportsPdf($page), 404);
        abort_unless($page->pdf_file && is_file(public_path($page->pdf_file)), 404);

        return response()->download(
            public_path($page->pdf_file),
            $page->pdf_original_name ?: $page->slug.'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    private function supportsPdf(HeaderMenuPage $page): bool
    {
        return in_array(strtolower($page->menu?->name ?? ''), [
            'fee structure',
            'program profile',
            'admission policy',
        ], true);
    }
}
