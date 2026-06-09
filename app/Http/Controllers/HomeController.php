<?php

namespace App\Http\Controllers;

use App\Models\HomePage;
use App\Models\HeroSlide;
use App\Models\NewsItem;

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
}
