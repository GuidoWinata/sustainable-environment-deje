<?php 

namespace App\Http\Controllers\Admin;

use App\Entities\ResponseEntity;
use App\Http\Controllers\Controller;
use App\Usecases\ProductCategoryUsecase;  
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductCategoryController extends Controller 
{
    protected $usecase;
    protected $page = [
        "route" => "product-category",  
        "title" => "Kategori Produk",  
    ];
    protected $baseRedirect;

    public function __construct(ProductCategoryUsecase $usecase)  
    {
        $this->usecase = $usecase;
        $this->baseRedirect = "admin/" . $this->page['route'];
    }

    public function index(Request $req): View | Response
    {
        $data = $this->usecase->getAll();

        return render_view("_admin.product-category.index", [  
            'data' => $data['data']['list'] ?? [],
            'page' => $this->page,
        ]);
    }

    public function add(): View | Response
    {
        return render_view("_admin.product-category.add", [ 
            'page' => $this->page,
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $createProcess = $this->usecase->create($request);
    
        if (empty($createProcess['error'])) {
            return redirect('/admin/product-category')
                ->with('success', ResponseEntity::SUCCESS_MESSAGE_CREATED);
        } else {
            return redirect()->back()
                ->withErrors(['name' => ResponseEntity::DEFAULT_ERROR_MESSAGE])
                ->withInput();
        }
    }

    public function update(int $id): View | Response | RedirectResponse
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseEntity::DEFAULT_ERROR_MESSAGE);
        }
        $data = $data['data'] ?? [];

        return render_view("_admin.product-category.update", [  
            'data' => (object) $data,
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, Request $request): JsonResponse
    {
        $process = $this->usecase->update(
            data: $request,
            id: $id,
        );

        if (empty($process['error'])) {
            return response()->json([
                "success" => true, 
                "message" => ResponseEntity::SUCCESS_MESSAGE_UPDATED,
                "redirect" => "product-category"  
            ]);
        } else {
            return response()->json([
                "success" => true, 
                "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                "redirect" => "product-category"  
            ]);
        }
    }

    public function doDelete(int $id, Request $request): JsonResponse
    {
        $process = $this->usecase->delete(
            id: $id,
        );

        if (empty($process['error'])) {
            return response()->json([
                "success" => true, 
                "message" => ResponseEntity::SUCCESS_MESSAGE_DELETED,
                "redirect" => "product-category"  
            ]);
        } else {
            return response()->json([
                "success" => false, 
                "message" => ResponseEntity::DEFAULT_ERROR_MESSAGE,
                "redirect" => "product-category" 
            ]);
        }
    }
}
