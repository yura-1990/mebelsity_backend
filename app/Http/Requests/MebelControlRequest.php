<?php

namespace App\Http\Requests;

use App\Models\Mebel;
use Illuminate\Foundation\Http\FormRequest;

class MebelControlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_uz'=>'required',
            'name_ru'=>'required',
            'user_id'=>'required',
            'toggle_id'=>'required',
            'size_uz'=>'required',
            'size_ru'=>'required',
            'material_uz'=>'required',
            'material_ru'=>'required',
            'price'=>'required',
            'image'=>"nullable|image"
        ];
    }
}
