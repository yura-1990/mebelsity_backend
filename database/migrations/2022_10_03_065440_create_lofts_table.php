<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lofts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onUpdate('cascade');
            $table->foreignId('toggle_id')->constrained('toggles')->onUpdate('cascade')->onUpdate('cascade');
            $table->string('name_uz');
            $table->string('name_ru');
            $table->text('size_uz')->nullable();
            $table->text('size_ru')->nullable();
            $table->text('material_uz')->nullable();
            $table->text('material_ru')->nullable();
            $table->string('price')->nullable();;
            $table->string('image')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lofts');
    }
};
