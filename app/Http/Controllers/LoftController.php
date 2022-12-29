<?php

namespace App\Http\Controllers;

use App\Http\Requests\MebelControlRequest;
use App\Models\Loft;
use App\Models\Toggle;
use App\Services\MebelSevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class LoftController extends Controller
{
    public function toggle(Request $request)
    {
        $toggle = Toggle::find(3);
        $toggle->toggle = !$toggle->toggle;
        $toggle->save();

        return 'ok';
    }


    /**
     * @OA\Get(
     *      path="/api/loftmebel",
     *      tags={"LoftMebel"},
     *      summary="Get all mebels` items",
     *      description="Multiple status values can be provided with comma separated string",
     *      operationId="getLoftData",
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
     *      )
     * )
     */

    public function getLoftData()
     {
         try {
             $toggle = Toggle::find(3);
             if ($toggle->toggle) {
                 $datas = new Loft;
                 $result = $this->success($datas);
                 return response()->json([
                     "status"=>true,
                     "datas"=>$result
                 ], Response::HTTP_OK);
             }else{
                 response()->json([
                     "success"=>"ok",
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
        $toggle = Toggle::find(3);
        if ($request->ajax()) {
            $data = Loft::all();
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

        return view('offices.loft-mebel')->with(['toggle'=>$toggle]);
    }

    public function store(MebelControlRequest $request)
    {
        $request->validated();
        $loftMebels = new MebelSevices($request, 'loft', new Loft);
        $loftMebels->updateOrCreate();
        return response()->json(['success'=>'data successfully saved']);
    }

    /**
     * @OA\Get(
     *      path="/api/loftmebel/edit/{id}",
     *      tags={"LoftMebel"},
     *      summary="Detailed info about the mebel item",
     *      operationId="editLoft",
     *      @OA\Parameter(
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

    public function editLoft($id)
    {
        $datas = Loft::find($id);
        if (request()->ajax()) {
            return response()->json(['result'=>$datas]);
        }
        $data = new Loft;
        $result = $this->success($data, $id);
        return response()->json(['result'=>$result]);
    }

    public function update(MebelControlRequest $request)
    {
        $request->validated();
        $mebelData =  Loft::find($request->id);

        $loftMebel = new MebelSevices($request, 'loft', $mebelData);
        $loftMebel->updateOrCreate();

        return response()->json(['success'=>$loftMebel]);

    }

    public function destroy($dataId)
    {
        $deleteMebel = Loft::find($dataId);
        Storage::disk('public')->delete($deleteMebel->image);
        $deleteMebel->delete();

        return response()->json(['success'=>'Data successfully deleted']);
    }
}
