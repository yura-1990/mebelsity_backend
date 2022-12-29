<?php

namespace App\Http\Controllers;

use App\Http\Requests\MebelControlRequest;
use App\Models\allOfficeFurniture;
use App\Models\Toggle;
use App\Services\MebelSevices;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\LanguageTrait;

class AllOfficeFurnitureController extends Controller
{
    use LanguageTrait;

    public function toggle(Request $request)
    {
        $toggle = Toggle::find(1);
        $toggle->toggle = !$toggle->toggle;
        $toggle->save();

        return 'ok';
    }

    /**
     * @OA\Get(
     *      path="/api/allmebel",
     *      tags={"AllMebel"},
     *      summary="Get all mebels` items",
     *      description="Multiple status values can be provided with comma separated string",
     *      operationId="getAllData",
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

    public function getAllData()
    {
        try {
            $toggle = Toggle::find(1);

            $datas = new allOfficeFurniture;
            $result = $this->success($datas);

            if ($toggle->toggle) {
                return response()->json([
                    "status"=>true,
                    "datas" => $result
                ], Response::HTTP_OK);

            }else{
                response()->json([
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
        $toggle = Toggle::find(1);
        if ($request->ajax()) {
            $data = allOfficeFurniture::all();
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
                    return '<img src='.$url.' border="0" class="img-rounded img-fluid" align="center" />';
                })
                ->rawColumns(['edit','delete', 'image'])
                ->make(true);
        }

        return view('offices.all-office-furniture')->with(['toggle'=>$toggle]);
    }


    public function store(MebelControlRequest $request)
    {
       $request->validated();
        $mebelData = new MebelSevices($request, 'allOfficeMebel', new allOfficeFurniture);
        $mebelData->updateOrCreate();

        return response()->json(['success'=>'data successfully saved']);
    }

    /**
     * @OA\Get(
     *      path="/api/allmebel/show/{id}",
     *      tags={"AllMebel"},
     *      summary="Detailed info about the mebel item",
     *      operationId="editAllData",
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


    public function editAllData($id)
    {

        if (request()->ajax()) {
            $datas=allOfficeFurniture::find($id);
            return response()->json(['result'=>$datas]);
        }
        $data = new allOfficeFurniture;
        $result = $this->success($data, $id);
        return response()->json(['result'=>$result]);
    }

    public function update(MebelControlRequest $request)
    {
        $request->validated();

        $mebelDatas =  allOfficeFurniture::find($request->id);
        $mebelData = new MebelSevices($request, 'allOfficeMebel',$mebelDatas);

        return response()->json(['success'=>$mebelData->updateOrCreate()]);
    }

    public function destroy($dataId)
    {
        $deleteMebel = allOfficeFurniture::find($dataId);
        Storage::disk('public')->delete($deleteMebel->image);
        $deleteMebel->delete();

        return response()->json(['success'=>'Data successfully deleted']);
    }
}
