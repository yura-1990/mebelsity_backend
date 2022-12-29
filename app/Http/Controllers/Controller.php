<?php

namespace App\Http\Controllers;

use App\Traits\LanguageTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *     description="This is mebels` api",
 *     version="1.0.0",
 *     title="Mebels` categories",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="yurayur1990@gmail.com"
 *     ),
 * )
 * @OA\Server(
 *     description="MEbel api host",
 *     url="http://localhost:8000"
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [], $id = null){

        return $id ? $data->select('id',
            LanguageTrait::convert('name'),
            LanguageTrait::convert('size'),
            LanguageTrait::convert('material'),
            'price', 'image', 'created_at', 'updated_at'
        )->where('id', $id)->get() ?? [] : $data->select('id',
            LanguageTrait::convert('name'),
            LanguageTrait::convert('size'),
            LanguageTrait::convert('material'),
            'price', 'image', 'created_at', 'updated_at'
        )->get() ?? [];
    }



}
