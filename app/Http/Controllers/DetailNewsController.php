<?php

namespace App\Http\Controllers;

use App\Usecases\NewsUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DetailNewsController extends Controller
{
    protected $newsUseCase;

    public function __construct(NewsUsecase $newsUseCase)
    {
        $this->newsUseCase = $newsUseCase;
    }

    public function detail($slug): View
    {
        $news = $this->newsUseCase->getNewsBySlug($slug);

        if (!$news) {
            abort(404, 'Berita tidak ditemukan');
        }

        return view('_front.detail', [
            'news' => $news
        ]);
    }
}
