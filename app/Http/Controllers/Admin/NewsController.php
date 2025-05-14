<?php

namespace App\Http\Controllers\Admin;

use App\Entities\ResponseEntity;
use App\Http\Controllers\Controller;
use App\Usecases\CategoriesUsecase;
use App\Usecases\NewsUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    protected $usecase;
    protected $categoryUsecase;
    protected $page = [
        "route" => "news",
        "title" => "Berita",
    ];
    protected $baseRedirect;

    public function __construct(
        NewsUsecase $usecase,
        CategoriesUsecase $categoriesUsecase
    ) {
        $this->usecase = $usecase;
        $this->categoryUsecase = $categoriesUsecase;
        $this->baseRedirect = "admin/" . $this->page['route'];
    }

    public function index(Request $req): View|Response
    {
        $data = $this->usecase->getAll($req->input());
        $categories = $this->categoryUsecase->getAll();
        $categories = $categories['data']['list'] ?? [];

        return render_view("_admin.news.index", [
            'data' => $data['data']['list'] ?? [],
            'categories' => $categories,
            'page' => $this->page,
            'filter' => $req->input(),
        ]);
    }

    public function add(): View|Response
    {
        $categories = $this->categoryUsecase->getAll();
        $categories = $categories['data']['list'] ?? [];

        return render_view("_admin.news.add", [
            'page' => $this->page,
            'categories' => $categories,
        ]);
    }

    public function doCreate(Request $request):JsonResponse
    {
        $process = $this->usecase->create($request);

        if (empty($process['error'])) {
            return response()->json([
                "success" => true,
                "message" => ResponseEntity::SUCCESS_MESSAGE_CREATED,
                "redirect" => "news"
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                "redirect" => "news"
            ]);
        }
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseEntity::DEFAULT_ERROR_MESSAGE);
        }
        $data = $data['data'];

        $categories = $this->categoryUsecase->getAll();
        $categories = $categories['data']['list'] ?? [];

        return render_view("_admin.news.update", [
            'data' => (object) $data,
            'page' => $this->page,
            'categories' => $categories,
        ]);
    }

    public function doUpdate(int $id, Request $request): JsonResponse
    {
        $process = $this->usecase->update($request, $id);

        if (empty($process['error'])) {
            return response()->json([
                "success" => true,
                "message" => ResponseEntity::SUCCESS_MESSAGE_UPDATED,
                "redirect" => "news"
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                "redirect" => "news"
            ]);
        }
    }

    public function doDelete(int $id, Request $request): JsonResponse
    {
        $process = $this->usecase->delete($id);

        if (empty($process['error'])) {
            return response()->json([
                "success" => true,
                "message" => ResponseEntity::SUCCESS_MESSAGE_DELETED,
                "redirect" => "news"  
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                "redirect" => "news"  
            ]);
        }
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseEntity::DEFAULT_ERROR_MESSAGE);
        }
        $data = $data['data'];

        return render_view("_admin.news.detail", [
            'data' => (object) $data,
            'page' => $this->page,
        ]);
    }

    public function searchAPI(Request $req): JsonResponse
    {
        $data = $this->usecase->getByKeywordTitle($req->input());
        $data = $data['data']['list'] ?? [];

        if (empty($data)) {
            return response()->json([]);
        }

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => $row->id,
                'title' => $row->title,
                'slug' => $row->slug,
            ];
        }

        return response()->json($result);
    }
}
