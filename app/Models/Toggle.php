<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toggle extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'toggle'];

    public function allOfficeFurnitures()
    {
        return $this->hasMany(allOfficeFurniture::class);
    }

    public function Mebels()
    {
        return $this->hasMany(Mebel::class);
    }

    public function lofts()
    {
        return $this->hasMany(Loft::class);
    }

    public function ollSoftMebels()
    {
        return $this->hasMany(AllSoftMebel::class);
    }

    public function homeMebels()
    {
        return $this->hasMany(HomeMebel::class);
    }

    public function kitchenMebels()
    {
        return $this->hasMany(KitchenMebel::class);
    }
}
