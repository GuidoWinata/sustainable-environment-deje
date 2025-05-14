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

class CategoriesUsecase extends Usecase
{
    public string $className;

    public function __construct()
    {
        $this->className = "CategoriesUsecase";
    }

    public function getAll(array $filterData = []): array
    {
        $funcName = $this->className . ".getAll";

        $page  = $filterData['page'] ?? 1;
        $limit = $filterData['limit'] ?? 10;
        $page  = ($page > 0 ? $page : 1);

        try {
            $data = DB::connection(DatabaseEntity::SQL_READ)
                ->table('categories', 'c');

            $fields = ['c.*'];

            $data = $data->orderBy("c.id", "desc")->paginate(20, $fields);

            return Response::buildSuccess(
                [
                    'list' => $data,
                    'pagination' => [
                        'current_page' => (int) $page,
                        'limit'        => (int) $limit,
                        'payload'      => $filterData
                    ]
                ],
                ResponseEntity::HTTP_SUCCESS
            );
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
                ->table('categories')
                ->where('id', $id)
                ->first();

            return Response::buildSuccess(
                data: collect($data)->toArray()
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);

            return Response::buildErrorService($e->getMessage());
        }
    }

    public function create(Request $data): array
    {
        $funcName = $this->className . ".create";
    
        $validator = Validator::make($data->all(), [
            'name' => 'required',
        ]);
    
        $customAttributes = [
            'name' => 'Nama Kategori',
        ];
        $validator->setAttributeNames($customAttributes);
        $validator->validate();
    
        DB::beginTransaction();
        try {
            DB::table('categories')
                ->insert([
                    'name' => $data['name'],
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
            'name' => 'required',
        ]);

        $validator->setAttributeNames([
            'name' => 'Nama Kategori',
        ]);

        $validator->validate();

        $update = [
            'name' => $data['name'],
        ];

        DB::beginTransaction();

        try {
            DB::table('categories')
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
        $return = [];
        $funcName = $this->className . ".delete";

        DB::beginTransaction();

        try {
            $delete = DB::table('categories')
                ->where('id', $id)
                ->delete();

            if (!$delete) {
                DB::rollback();

                throw new Exception("FAILED DELETE DATA");
            }

            DB::commit();

            $return = Response::buildSuccess();
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
}
