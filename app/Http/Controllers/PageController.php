<?php

namespace App\Http\Controllers;

use App\Models\HeaderMenuPage;
use App\Models\HomePage;

class PageController extends Controller
{
    public function show(HeaderMenuPage $page)
    {
        abort_unless($page->menu?->is_active, 404);
        $page->load(['slides' => fn ($query) => $query->where('is_active', true)]);

        return view('frontend.dynamic-page', [
            'page' => $page,
            'home' => HomePage::first() ?? new HomePage(),
        ]);
    }
}
