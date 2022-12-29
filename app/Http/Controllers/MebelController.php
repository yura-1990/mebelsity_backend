<?php

namespace App\Http\Controllers;

use App\Models\Mebel;
use App\Services\MebelSevices;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use App\Http\Requests\MebelControlRequest;
use App\Models\Toggle;
use Illuminate\Support\Facades\Storage;

class MebelController extends Controller
{
    public function toggle(Request $request)
    {
        $toggle = Toggle::find(2);
        $toggle->toggle = !$toggle->toggle;
        $toggle->save();

        return 'ok';
    }
    /**
     * @OA\Get(
     *      path="/api/mebel",
     *      tags={"Office Mebels"},
     *      summary="Get all mebels` items",
     *      description="Multiple status values can be provided with comma separated string",
     *      operationId="getData",
     *      @OA\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Set language parameter by RFC2616 <https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4>",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     * )
     */

     public function getData()
     {
         try {
             $toggle = Toggle::find(2);
             if ($toggle->toggle) {
                 $datas = new Mebel;
                 $result = $this->success($datas);
                 return response()->json([
                     "status"=>true,
                     "datas"=>$result
                 ], Response::HTTP_OK);
             } else {
                 return response()->json([
                     "status"=>true,
                     "message"=>['message' => "The site is in the proccess of updating"]
                 ]);
             }
         }
         catch (\Exception $e){
             response()->json([
                 "status"=>false,
                 "message"=> $e->getMessage()
             ]);
         }
     }

    public function index(Request $request)
    {
        $data = Mebel::all();
        $toggle = Toggle::find(2);
        if ($request->ajax()) {
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('edit', function($row){
                    $btn = "<button id='$row->id' class=\"btn btn-primary btn-sm edit\" >Edit</button>";
                    return $btn;
                })
                ->addColumn('delete', function($row){
                    $btn = "<button data-id='$row->id' class=\"btn btn-primary btn-sm deleteData\" >Delete</button>";
                    return $btn;
                })->addColumn('image', function ($images) {
                    $url=asset('storage').'/'. $images->image;
                    return '<img src='.$url.' border="0" width="60" height="80" class="img-rounded" align="center" />';
                })
                ->rawColumns(['edit','delete', 'image'])
                ->make(true);
        }

        return view('home')->with(['toggle'=>$toggle]);
    }

    /**
     * @OA\Post(
     *      path="/api/mebel",
     *      tags={"Office Mebels"},
     *      summary="Place an order for a mebel",
     *      operationId="store",
     *      @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                     property="name_uz",
     *                     description="Name the bebel type",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="user_id",
     *                     description="User",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="name_ru",
     *                     description="Name the bebel type",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="size",
     *                     description="Give the size to the mebel",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="material",
     *                     description="Give the material name",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="price",
     *                     description="Give a price for the mebel",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="image",
     *                     description="Chose an image",
     *                     type="file",
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      )
     * )
     *
     */

    public function store(MebelControlRequest $request)
    {
       $request->validated();
        $mebel = new MebelSevices($request, 'officeMebels', new Mebel);
        $mebel->updateOrCreate();

        return response()->json([
           'success'=>'data successfully saved'
       ]);
    }

    /**
     * @OA\Get(
     *      path="/api/mebel/show/{id}",
     *      tags={"Office Mebels"},
     *      summary="Detailed info about the mebel item",
     *      operationId="edit",
     *     @OA\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Set language parameter by RFC2616 <https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4>",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *   )
     *
     */

    public function edit($id)
    {
        $datas = Mebel::find($id);
        if (request()->ajax()) {
            return response()->json(['result'=>$datas]);
        }
        $data = new Mebel;
        $data = $this->success($data, $id);
        return response()->json(['result'=>$data]);
    }

    /**
     * @OA\Put(
     *      path="/api/mebel/{id}/update/",
     *      tags={"Office Mebels"},
     *      summary="Update the data",
     *      operationId="updateData",
     *     @OA\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Set language parameter by RFC2616 <https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4>",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          description="Input data format",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                     property="name_uz",
     *                     description="Name the bebel type",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="user_id",
     *                     description="User",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="name_ru",
     *                     description="Name the bebel type",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="size",
     *                     description="Give the size to the mebel",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="material",
     *                     description="Give the material name",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="price",
     *                     description="Give a price for the mebel",
     *                     type="string",
     *                  ),
     *                  @OA\Property(
     *                     property="image",
     *                     description="Chose an image",
     *                     type="file",
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

     public function updateData(MebelControlRequest $request, $id)
     {
        $mebelData =  Mebel::find($id);
        $data = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($mebelData->image);
            $path = $request->file('image')->store('officeMebels', 'public');
        }



        return response()->json(['success'=>$mebelData]);
    }

    public function update(MebelControlRequest $request)
    {
        $request->validated();

        $mebelData =  Mebel::find($request->id);

        $mebelData = new MebelSevices($request, 'officeMebels', $mebelData);

        return response()->json(['success'=>$mebelData->updateOrCreate()]);
    }

    public function destroy($dataId)
    {
        $deleteMebel = Mebel::find($dataId);
        Storage::disk('public')->delete($deleteMebel->image);
        $deleteMebel->delete();

        return response()->json(['success'=>'Data successfully deleted']);
    }
}
