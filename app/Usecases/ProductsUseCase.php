<?php

namespace App\Usecases;

use App\Entities\DatabaseEntity;
use App\Entities\ResponseEntity;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductsUseCase extends Usecase
{
    public string $className;

    public function __construct()
    {
        $this->className = "ProductsUseCase";
    }

    public function getAll(array $filterData = []): array
    {
        $funcName = $this->className . ".getAll";

        $page       = $filterData['page'] ?? 1;
        $limit      = $filterData['limit'] ?? 10;
        $search     = $filterData['search'] ?? '';
        $filterCtg  = $filterData['filter_categories_id'] ?? '';

        try {
            $query = DB::connection(DatabaseEntity::SQL_READ)
                ->table(DatabaseEntity::PRODUCT . ' as p')
                ->leftJoin('product_categories as c', 'c.id', '=', 'p.category_id')
                ->whereNull('p.deleted_at');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('p.name', 'like', '%' . $search . '%');
                }); 
            }

            if (!empty($filterCtg)) {
                $query->where('p.category_id', (int) $filterCtg);
            }

            $fields = ['p.*', 'c.name as category'];

            $data = $query->orderBy('p.created_at', 'desc')
                        ->paginate($limit, $fields)
                        ->appends(request()->query());

            return Response::buildSuccess([
                'list' => $data,
                'pagination' => [
                    'current_page' => (int) $page,
                    'limit'        => (int) $limit,
                    'payload'      => $filterData
                ]
            ], ResponseEntity::HTTP_SUCCESS);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByID(int $id): array
    {
        $funcName = $this->className . ".getByID";

        try {
            $data = DB::connection(DatabaseEntity::SQL_READ)
                ->table(DatabaseEntity::PRODUCT . ' as p')
                ->leftJoin('product_categories as c', 'c.id', '=', 'p.category_id')
                ->whereNull("p.deleted_at")
                ->where('p.id', $id)
                ->select('p.*', 'c.name as category_name', 'c.id as category_id')
                ->first();

            return Response::buildSuccess(data: collect($data)->toArray());
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $request): array
    {
        $funcName = $this->className . ".create";

        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required',
                'price'       => 'required|numeric',
                'stock'       => 'required|numeric|min:0',
            ]);

            $customAttributes = [
                'name'  => 'Nama Produk',
                'price' => 'Harga',
                'stock' => 'Stock',
            ];
            $validator->setAttributeNames($customAttributes);
            
            if ($validator->fails()) {
                return Response::buildErrorService($validator->errors()->first());
            }

            DB::beginTransaction();
            
            DB::table(DatabaseEntity::PRODUCT)
                ->insert([
                    'name'        => $request->input('name'),
                    'description' => $request->input('description'),
                    'category_id' => $request['category_id'],
                    'price'       => $request->input('price'),
                    'stock'       => $request->input('stock'),  
                    'created_by'  => Auth::user()->id,
                    'created_at'  => datetime_now()
                ]);

            DB::commit();
            return Response::buildSuccessCreated();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return Response::buildErrorService($e->getMessage());
        }
    }

    public function update(Request $data, int $id): array
    {
        $return = [];
        $funcName = $this->className . ".update";
    
        $validator = Validator::make($data->all(), [
            'name'        => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric|min:0',
        ]);
    
        $customAttributes = [
            'name'        => 'Nama Produk',
            'category_id' => 'Kategori',
            'price'       => 'Harga',
            'stock'       => 'Stock',
        ];
        $validator->setAttributeNames($customAttributes);
        $validator->validate();
    
        $update = [
            'name'        => $data['name'],
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'updated_by'  => Auth::user()->id,
            'updated_at'  => datetime_now()
        ];
    
        DB::beginTransaction();
    
        try {
            DB::table(DatabaseEntity::PRODUCT)
                ->where("id", $id)
                ->update($update);
    
            DB::commit();
            $return = Response::buildSuccess(
                message: ResponseEntity::SUCCESS_MESSAGE_UPDATED
            );
        } catch (\Exception $e) {
            DB::rollback();
    
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return Response::buildErrorService($e->getMessage());
        }
    
        return $return;
    }

    public function delete(int $id): array
    {
        $funcName = $this->className . ".delete";

        if (!Auth::check()) {
            return Response::buildErrorService("Pengguna belum login.");
        }

        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseEntity::PRODUCT)
                ->where('id', $id)
                ->update([
                    'deleted_by' => Auth::user()->id,  
                    'deleted_at' => now(),
                ]);

            if (!$delete) {
                DB::rollback();
                throw new Exception("FAILED DELETE DATA");
            }

            DB::commit();
            return Response::buildSuccess();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return Response::buildErrorService($e->getMessage());
        }
    }

    public function getByKeywordName(array $filterData = []): array
    {
        $funcName = $this->className . ".getByKeywordName";
        $term = $filterData['term'] ?? '';

        try {
            $data = DB::connection(DatabaseEntity::SQL_READ)
                ->table(DatabaseEntity::PRODUCT)
                ->whereNull("deleted_at")
                ->where('name', 'like', '%' . $term . '%')
                ->orderBy("created_at", "desc")
                ->limit(30)
                ->get();

            return Response::buildSuccess([
                'list' => $data,
            ], ResponseEntity::HTTP_SUCCESS);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return Response::buildErrorService($e->getMessage());
        }
    }
}
