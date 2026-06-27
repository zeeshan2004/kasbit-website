<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use App\Models\HeaderMenuPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HeaderMenuPageController extends Controller
{
    public function edit(HeaderMenu $headerMenu)
    {
        $page = $this->pageFor($headerMenu)->load([
            'slides',
            'programSchemaTables.rows',
            'academicCalendarTables.rows',
            'departments',
            'galleryImages',
            'eventAlbums' => fn ($query) => $query->withCount('images'),
        ]);

        return view('admin.cms.header-menu-page', compact('headerMenu', 'page'));
    }

    public function update(Request $request, HeaderMenu $headerMenu)
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:20480'],
            'remove_pdf' => ['nullable', 'boolean'],
        ]);

        $page = $this->pageFor($headerMenu);

        if ($request->boolean('remove_pdf') || $request->hasFile('pdf_file')) {
            $this->deletePdf($page);
            $data['pdf_file'] = null;
            $data['pdf_original_name'] = null;
        }

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $directory = public_path('uploads/page-pdfs');
            File::ensureDirectoryExists($directory);
            $filename = Str::uuid().'.pdf';
            $file->move($directory, $filename);

            $data['pdf_file'] = 'uploads/page-pdfs/'.$filename;
            $data['pdf_original_name'] = Str::limit(
                pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                180,
                ''
            ).'.pdf';
        }

        unset($data['remove_pdf']);
        $page->update($data);

        $headerMenu->update([
            'link' => str_starts_with(strtolower($headerMenu->name), 'about')
                ? '/about'
                : '/pages/' . $page->slug,
        ]);

        return back()->with('success', $headerMenu->name . ' page updated.');
    }

    private function pageFor(HeaderMenu $headerMenu): HeaderMenuPage
    {
        return HeaderMenuPage::firstOrCreate(
            ['header_menu_id' => $headerMenu->id],
            [
                'slug' => $this->uniqueSlug($headerMenu),
                'eyebrow' => $headerMenu->parent?->name ?: 'Website Page',
                'title' => $headerMenu->name,
                'accent_color' => '#07559d',
                'show_image' => true,
            ]
        );
    }

    private function uniqueSlug(HeaderMenu $headerMenu): string
    {
        $base = Str::slug($headerMenu->name) ?: 'page-' . $headerMenu->id;
        $slug = $base;
        $suffix = 2;

        while (HeaderMenuPage::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    private function deletePdf(HeaderMenuPage $page): void
    {
        if (!$page->pdf_file) {
            return;
        }

        $path = public_path($page->pdf_file);

        if (File::isFile($path)) {
            File::delete($path);
        }
    }

}
