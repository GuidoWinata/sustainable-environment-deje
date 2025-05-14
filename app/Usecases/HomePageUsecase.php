<?php

namespace App\Usecases;

use Illuminate\Support\Facades\DB;

class HomePageUsecase extends Usecase
{
    public string $className;

    public function __construct()
    {
        $this->className = "HomePageUsecase";
    }

    public function getHomepageNews(): array
    {
        $berita = DB::table('news')
            ->join('categories', 'categories.id', '=', 'news.category_id')
            ->select('news.id', 'news.title', 'news.thumbnail_small', 'categories.name as category', 'news.created_at', 'news.slug')
            ->orderBy('news.created_at', 'desc')
            ->limit(6)
            ->get();

        return [
            'berita_terbaru' => $berita
        ];
    }
}
