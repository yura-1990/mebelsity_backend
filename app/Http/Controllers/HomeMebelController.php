<?php

namespace App\Http\Controllers;

use App\Http\Requests\MebelControlRequest;
use App\Models\HomeMebel;
use App\Models\Toggle;
use App\Services\MebelSevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class HomeMebelController extends Controller
{
    public function toggle(Request $request)
    {
        $toggle = Toggle::find(5);
        $toggle->toggle = !$toggle->toggle;
        $toggle->save();

        return 'ok';
    }
    /**
     * @OA\Get(
     *      path="/api/homemebel",
     *      tags={"HomeMebel"},
     *      summary="Get all mebels` items",
     *      description="Multiple status values can be provided with comma separated string",
     *      operationId="getHometData",
     *     @OA\Parameter(
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
     *      )
     * )
     */

    public function getHometData()
     {
         try {
             $toggle = Toggle::find(5);

             if ($toggle->toggle) {
                 $datas = new HomeMebel;
                 $result = $this->success($datas);
                 return response()->json([
                     "status"=>true,
                     "datas"=>$result
                 ], Response::HTTP_OK);
             } else{
                 return response()->json([
                     "status"=>true,
                     "message"=>['message' => "The site is in the proccess of updating"]
                 ]);
             }
         }
         catch (\Exception $e) {
             response()->json([
                 "status"=>false,
                 "message"=> $e->getMessage()
             ]);

         }
     }


    public function index(Request $request)
    {
        $toggle = Toggle::find(5);
        if ($request->ajax()) {
            $data = HomeMebel::all();
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

        return view('homeMebels.homeMebels')->with(['toggle'=>$toggle]);
    }

    public function store(MebelControlRequest $request)
    {
        $request->validated();

        $homeMebels = new MebelSevices($request, 'homeMebels', new HomeMebel);
        $homeMebels->updateOrCreate();

        return response()->json(['success'=>'data successfully saved']);
    }

    /**
     * @OA\Get(
     *      path="/api/homemebel/edit/{id}",
     *      tags={"HomeMebel"},
     *      summary="Detailed info about the mebel item",
     *      operationId="editHome",
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
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
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

    public function editHome($id)
    {
        $datas = HomeMebel::find($id);
        if (request()->ajax()) {
            return response()->json(['result'=>$datas]);
        }
        $data = new HomeMebel;
        $result = $this->success($data,  $id);
        return response()->json(['result'=>$result]);
    }

    public function update(MebelControlRequest $request)
    {
        $request->validated();
        $mebelData =  HomeMebel::find($request->id);

        $homeMebels = new MebelSevices($request, 'homeMebels', $mebelData);
        $homeMebels->updateOrCreate();

        return response()->json(['success'=>$homeMebels]);

    }

    public function destroy($dataId)
    {
        $deleteMebel = HomeMebel::find($dataId);
        Storage::disk('public')->delete($deleteMebel->image);
        $deleteMebel->delete();

        return response()->json(['success'=>'Data successfully deleted']);
    }
}
