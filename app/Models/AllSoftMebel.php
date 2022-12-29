<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllSoftMebel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function toggle(){
        return $this->belongsTo(Toggle::class);
    }
}
