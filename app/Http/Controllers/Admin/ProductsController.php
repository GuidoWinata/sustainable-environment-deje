<?php

namespace App\Http\Controllers\Admin;

use App\Entities\ResponseEntity;
use App\Http\Controllers\Controller;
use App\Usecases\ProductsUseCase;
use App\Usecases\ProductCategoryUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    protected $usecase;
    protected $categoryUsecase;
    protected $page = [
        "route" => "product",
        "title" => "Produk",
    ];
    protected $baseRedirect;

    public function __construct(ProductsUseCase $usecase, ProductCategoryUsecase $categoryUsecase)
    {
        $this->usecase = $usecase;
        $this->categoryUsecase = $categoryUsecase;
        $this->baseRedirect = "admin/" . $this->page['route'];
    }

    public function index(Request $req): View | Response
    {
        $result = $this->usecase->getAll(filterData: $req->all());
        $categories = $this->categoryUsecase->getAll();

        return render_view("_admin.product.index", [
            'products' => $result['data']['list'] ?? [],
            'page' => $this->page,
            'filter' => $req->all(),
            'productCategories' => $categories['data']['list'] ?? [],
        ]);
    }

    public function add(): View | Response
    {
        $categories = $this->categoryUsecase->getAll();

        return render_view("_admin.product.add", [
            'page' => $this->page,
            'productCategories' => $categories['data']['list'] ?? [],
        ]);
    }

    public function doCreate(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $data['created_by'] = Auth::id();  
            $data['stock'] = $request->input('stock', 0);

            $result = $this->usecase->create($request);

            if (empty($result['error'])) {
                return response()->json([
                    "success" => true,
                    "message" => ResponseEntity::SUCCESS_MESSAGE_CREATED,
                    "redirect" => "product"
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                    "redirect" => "product"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => 'Terjadi kesalahan: ' . $e->getMessage(),
                "redirect" => "product"
            ]);
        }
    }

    public function update(int $id): View | RedirectResponse | Response
    {
        $data = $this->usecase->getByID($id);
        if (empty($data['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseEntity::DEFAULT_ERROR_MESSAGE);
        }

        $data = $data['data'] ?? [];

        $categories = $this->categoryUsecase->getAll();
        $categories = $categories['data']['list'] ?? [];

        return render_view("_admin.product.update", [
            'data' => (object) $data,
            'page' => $this->page,
            'productCategories' => $categories,
        ]);
    }

        public function doUpdate(int $id, Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $data['updated_by'] = Auth::id();  
            $data['stock'] = $request->input('stock', 0);

            $result = $this->usecase->update($request, $id);

            if (empty($result['error'])) {
                return response()->json([
                    "success" => true,
                    "message" => ResponseEntity::SUCCESS_MESSAGE_UPDATED,
                    "redirect" => "product"
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                    "redirect" => "product"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => 'Terjadi kesalahan: ' . $e->getMessage(),
                "redirect" => "product"
            ]);
        }
    }

    public function doDelete(int $id, Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                "success" => false,
                "message" => 'Pengguna belum login.',
                "redirect" => "product"
            ]);
        }

        try {
            $result = $this->usecase->delete($id);

            if (empty($result['error'])) {
                return response()->json([
                    "success" => true,
                    "message" => ResponseEntity::SUCCESS_MESSAGE_DELETED,
                    "redirect" => "product"
                ]);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                    "redirect" => "product"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => 'Gagal menghapus produk: ' . $e->getMessage(),
                "redirect" => "product"
            ]);
        }
    }

    public function detail(int $id): View | RedirectResponse | Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseEntity::DEFAULT_ERROR_MESSAGE);
        }

        return render_view("_admin.product.detail", [
            'data' => (object) $data['data'],
            'page' => $this->page,
        ]);
    }

    public function searchAPI(Request $req): JsonResponse
    {
        $data = $this->usecase->getByKeywordName($req->input());
        $data = $data['data']['list'] ?? [];

        if (!count($data)) {
            return response()->json([]);
        }

        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'name'  => $row->name,
                'price' => $row->price,
                'stock' => $row->stock,
            ];
        }
        
        return response()->json($result);
    }
}
