<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique()->nullable();
            $table->foreignId('branch_id')->nullable();
            $table->foreignId('branch_uid')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique()->nullable();
            $table->json('type')->nullable(); // account types allowed: revenue, assets
            $table->string('description')->nullable();
            
            $table->softDeletes();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('journals');
    }
};