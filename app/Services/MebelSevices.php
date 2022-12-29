<?php

namespace App\Services;

use App\Models\allOfficeFurniture;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Array_;

class MebelSevices
{
    protected $request, $upload='', $model;

    public function __construct($request, $upload, $model)
    {
        $this->request = $request;
        $this->upload = $upload;
        $this->model = $model;
    }

    public function updateOrCreate(){

        if ($this->request->hasFile('image')) {
            if ($this->model->id){
                Storage::disk('public')->delete($this->model->image);
            }
            $path = $this->request->file('image')->store($this->upload, 'public');
        }

        $this->model->updateOrCreate(
            ['id'=>$this->request->id],
            [
            'name_uz'=> $this->request->name_uz,
            'name_ru'=> $this->request->name_ru,
            'user_id'=> $this->request->user_id,
            'size_uz'=> $this->request->size_uz,
            'size_ru'=> $this->request->size_ru,
            'material_uz'=> $this->request->material_uz,
            'material_ru'=> $this->request->material_ru,
            'price'=> $this->request->price,
            'image'=> $path ?? $this->model->image,
            'toggle_id'=> $this->request->toggle_id
            ]
        );

        return $this->model;
    }


}
