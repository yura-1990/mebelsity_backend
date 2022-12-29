<?php

namespace App\Http\Controllers;

use App\Http\Requests\MebelControlRequest;
use App\Models\AllSoftMebel;
use App\Models\Toggle;
use App\Services\MebelSevices;
use App\Traits\LanguageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AllSoftMebelController extends Controller
{
    use LanguageTrait;

    public function toggle(Request $request)
    {
        $toggle = Toggle::find(4);
        $toggle->toggle = !$toggle->toggle;
        $toggle->save();

        return 'ok';
    }

    /**
     * @OA\Get(
     *      path="/api/softmebel",
     *      tags={"AllSoftMebel"},
     *      summary="Get all mebels` items",
     *      description="Multiple status values can be provided with comma separated string",
     *      operationId="getSoftData",
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

    public function getSoftData()
    {

        try {

            $toggle = Toggle::find(4);

            if ($toggle->toggle) {
                $datas = new AllSoftMebel;
                $result = $this->success($datas);
                return response()->json([
                    "status"=>true,
                    "datas"=>$result
                ], Response::HTTP_OK);
            }else{
                response()->json([
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
        $toggle = Toggle::find(4);

        if ($request->ajax()) {
            $data = AllSoftMebel::all();
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('edit', function ($row) {
                    $btn = "<button id='$row->id' class=\"btn btn-primary btn-sm edit\" >Edit</button>";
                    return $btn;
                })
                ->addColumn('delete', function ($row) {
                    $btn = "<button data-id='$row->id' class=\"btn btn-primary btn-sm deleteData\" >Delete</button>";
                    return $btn;
                })->addColumn('image', function ($images) {
                $url = asset('storage/'.$images->image);
                return '<img src=' . $url . ' border="0" width="60" height="80" class="img-rounded" align="center" />';
            })
                ->rawColumns(['edit', 'delete', 'image'])
                ->make(true);
        }

        return view('allSoftMebel')->with(['toggle'=>$toggle]);
    }

    public function store(MebelControlRequest $request)
    {
        $request->validated();
        $allSoftMebels = new MebelSevices($request, 'soft', new AllSoftMebel);
        $allSoftMebels->updateOrCreate();

        return response()->json(['success'=>'data successfully saved']);
    }

    /**
     * @OA\Get(
     *      path="/api/softmebel/edit/{id}",
     *      tags={"AllSoftMebel"},
     *      summary="Detailed info about the mebel item",
     *      operationId="editSoft",
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

    public function editSoft($id)
    {
        $datas = AllSoftMebel::find($id);
        if (request()->ajax()) {
            return response()->json(['result' => $datas]);
        }
        $data = new AllSoftMebel;
        $result = $this->success($data, $id);
        return response()->json(['result' => $result]);
    }

    public function update(MebelControlRequest $request)
    {
        $request->validated();
        $mebelData =  AllSoftMebel::find($request->id);
        $allSoftMebel = new MebelSevices($request, 'soft', $mebelData);
        $allSoftMebel->updateOrCreate();

        return response()->json(['success'=>$allSoftMebel]);
    }

    public function destroy($dataId)
    {
        $deleteMebel = AllSoftMebel::find($dataId);
        Storage::disk('public')->delete($deleteMebel->image);
        $deleteMebel->delete();

        return response()->json(['success' => 'Data successfully deleted']);
    }
}
