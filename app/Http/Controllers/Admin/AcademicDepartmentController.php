<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicDepartment;
use App\Models\HeaderMenuPage;
use App\Support\WebpImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AcademicDepartmentController extends Controller
{
    public function store(Request $request, HeaderMenuPage $page)
    {
        $this->ensureDepartmentsPage($page);
        $data = $this->validatedData($request);

        $page->departments()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'head_of_department' => $data['head_of_department'] ?? null,
            'link' => $data['link'] ?? null,
            'image' => $request->hasFile('image')
                ? $this->storeImage($request->file('image'))
                : null,
            'sort_order' => $data['sort_order']
                ?? ((int) $page->departments()->max('sort_order') + 1),
            'is_active' => $request->boolean('is_active'),
        ]);

        return $this->respond($request, 'Department added.', $page->menu);
    }

    public function update(Request $request, AcademicDepartment $department)
    {
        $this->ensureDepartmentsPage($department->page);
        $data = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($department);
            $data['image'] = $this->storeImage($request->file('image'));
        } else {
            unset($data['image']);
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        $department->update($data);

        return $this->respond($request, 'Department updated.', $department->page->menu);
    }

    public function destroy(Request $request, AcademicDepartment $department)
    {
        $this->ensureDepartmentsPage($department->page);
        $menu = $department->page->menu;
        $this->deleteImage($department);
        $department->delete();

        return $this->respond($request, 'Department deleted.', $menu);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'head_of_department' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'image.image' => 'Please select a valid JPG, PNG, GIF, BMP or WebP image.',
            'image.max' => 'The image must not be larger than 10MB.',
        ]);
    }

    private function ensureDepartmentsPage(HeaderMenuPage $page): void
    {
        abort_unless(strcasecmp($page->menu?->name ?? '', 'Academic Departments') === 0, 404);
    }

    private function storeImage(UploadedFile $file): string
    {
        return app(WebpImageOptimizer::class)->store(
            $file,
            'uploads/departments',
            time() . '_' . uniqid() . '_department'
        );
    }

    private function deleteImage(AcademicDepartment $department): void
    {
        if ($department->image && file_exists(public_path($department->image))) {
            unlink(public_path($department->image));
        }
    }

    private function respond(Request $request, string $message, $menu)
    {
        $url = route('header-menu.page.edit', $menu, false);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'refresh_url' => $url,
            ]);
        }

        return redirect($url)->with('success', $message);
    }
}
