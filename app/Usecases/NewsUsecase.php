<?php

namespace App\Usecases;

use App\Entities\DatabaseEntity;
use Illuminate\Support\Str;
use App\Entities\ResponseEntity;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NewsUseCase extends Usecase
{
    public string $className;

    public function __construct()
    {
        $this->className = "NewsUseCase";
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
                ->table(DatabaseEntity::NEWS . ' as n')
                ->leftJoin('categories as c', 'c.id', '=', 'n.category_id');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('n.title', 'like', '%' . $search . '%');
                });
            }

            if (!empty($filterCtg)) {
                $query->where('n.category_id', (int) $filterCtg);
            }

            $fields = ['n.*', 'c.name as category'];

            $data = $query->orderBy('n.created_at', 'desc')
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
                ->table(DatabaseEntity::NEWS . ' as n')
                ->leftJoin('categories as c', 'c.id', '=', 'n.category_id')
                ->where('n.id', $id)
                ->select('n.*', 'c.name as category_name', 'c.id as category_id')
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

        $validator = Validator::make($request->all(), [
            'title'        => 'required',
            'content'      => 'required',
            'thumbnail_small' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $customAttributes = [
            'title'           => 'Judul Berita',
            'content'         => 'Konten',
            'thumbnail_small' => 'Gambar Thumbnail Kecil',
        ];
        $validator->setAttributeNames($customAttributes);
        $validator->validate();

        try {
            DB::beginTransaction();

            $thumbnailSmallPath = null;
            if ($request->hasFile('thumbnail_small')) {
                $file = $request->file('thumbnail_small');
                $filename = 'small_' . time() . '_' . $file->getClientOriginalName();

                $publicPath = '/storage/images/news';
                $file->move(public_path($publicPath), $filename);

                $thumbnailSmallPath = $publicPath . '/' . $filename;
            }

            DB::table(DatabaseEntity::NEWS)
                ->insert([
                    'title'           => $request->input('title'),  
                    'slug'            => Str::slug($request->input('title')),
                    'category_id'     => $request->input('category_id'),
                    'content'         => $request->input('content'),
                    'thumbnail_small' => $thumbnailSmallPath,
                    'thumbnail_large' => $thumbnailSmallPath,
                    'status'          => $request->input('status', 1),
                    'vendor_id'       => $request->input('vendor_id'),
                    'user_id'         => Auth::user()->id,
                    'created_by'      => Auth::user()->id,
                    'created_at'      => datetime_now()
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
        $funcName = $this->className . ".update";

        $validator = Validator::make($data->all(), [
            'title'        => 'required',
            'category_id'  => 'required|exists:categories,id',
            'content'      => 'required',
            'thumbnail_small' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $customAttributes = [
            'title'           => 'Judul Berita',
            'category_id'     => 'Kategori',
            'content'         => 'Konten',
            'thumbnail_small' => 'Gambar Thumbnail Kecil',
        ];
        $validator->setAttributeNames($customAttributes);

        if ($validator->fails()) {
            return Response::buildErrorService($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $news = DB::table(DatabaseEntity::NEWS)->where('id', $id)->first();
            if (!$news) {
                DB::rollback();
                return Response::buildErrorService('Berita tidak ditemukan');
            }

            $thumbnailSmallPath = $news->thumbnail_small;
            if ($data->hasFile('thumbnail_small')) {
                if (!empty($thumbnailSmallPath) && file_exists(public_path($thumbnailSmallPath))) {
                    unlink(public_path($thumbnailSmallPath));
                }

                $file = $data->file('thumbnail_small');
                $filename = 'small_' . time() . '_' . $file->getClientOriginalName();
                $publicPath = 'admin_ui/storage/images/berita';
                $file->move(public_path($publicPath), $filename);

                $thumbnailSmallPath = $publicPath . '/' . $filename;
            }

            $update = [
                'title'           => $data['title'],
                'slug'            => $data['slug'],
                'category_id'     => $data['category_id'],
                'content'         => $data['content'],
                'thumbnail_small' => $thumbnailSmallPath,
                'thumbnail_large' => $thumbnailSmallPath,
                'status' => $data->input('status', 1),
                'vendor_id'       => $data['vendor_id'],
                'updated_by'      => Auth::user()->id,
                'updated_at'      => datetime_now()
            ];

            DB::table(DatabaseEntity::NEWS)
                ->where("id", $id)
                ->update($update);

            DB::commit();
            return Response::buildSuccess(
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
    }

    public function delete(int $id): array
    {
        $funcName = $this->className . ".delete";

        if (!Auth::check()) {
            return Response::buildErrorService("Pengguna belum login.");
        }

        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseEntity::NEWS)
                ->where('id', $id)
                ->delete();

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

    public function getByKeywordTitle(array $filterData = []): array
    {
        $funcName = $this->className . ".getByKeywordName";
        $term = $filterData['term'] ?? '';

        try {
            $data = DB::connection(DatabaseEntity::SQL_READ)
                ->table(DatabaseEntity::NEWS)
                ->where('title', 'like', '%' . $term . '%')
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
    public function getNewsBySlug(string $slug): ?object
    {
        $funcName = $this->className . ".getNewsBySlug";

        try {
            $news = DB::connection(DatabaseEntity::SQL_READ)
                ->table(DatabaseEntity::NEWS . ' as n')
                ->leftJoin('categories as c', 'c.id', '=', 'n.category_id')
                ->where('n.slug', $slug)
                ->select('n.*', 'c.name as category_name')
                ->first();

            return $news;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                "func_name" => $funcName,
                'user' => Auth::user()
            ]);
            return null;
        }
    }
}
