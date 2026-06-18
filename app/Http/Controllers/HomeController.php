<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use App\Models\HeroSlide;
use App\Models\NewsItem;
use App\Models\HeaderMenuPage;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'home' => HomePage::first(),
            'heroSlides' => HeroSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'news' => NewsItem::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'gallery' => [],
        ]);
    }

    public function about()
    {
        $page = HeaderMenuPage::whereHas('menu', fn ($query) => $query
                ->whereNull('parent_id')
                ->where('name', 'like', 'About%'))
            ->with(['slides' => fn ($query) => $query->where('is_active', true)])
            ->first();

        return view('frontend.about', [
            'home' => HomePage::first() ?? new HomePage(),
            'page' => $page,
        ]);
    }
}
