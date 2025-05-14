<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Usecases\HomePageUsecase;
use Illuminate\Contracts\View\View;
use App\Usecases\NewsUseCase;
use Illuminate\Http\Request;

class HomeController 
{
    protected $homepageUseCase;
    public function __construct(HomePageUsecase $homepageUseCase)
    {
        $this->homepageUseCase = $homepageUseCase;
    }

    public function index(): View
    {
        $data = $this->homepageUseCase->getHomepageNews();

        return view('_front.homepage', [
            'berita_terbaru' => $data['berita_terbaru']
        ]);
    }
}
