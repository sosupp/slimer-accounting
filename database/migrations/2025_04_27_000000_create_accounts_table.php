<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();

            $table->foreignId('parent_id')->nullable()
            ->constrained('accounts')->nullOnDelete();

            $table->string('name')->unique();
            $table->string('key')->unique()->nullable();
            $table->string('code')->nullable();

            $table->enum('type', [
                'asset', 'liability', 'equity', 'income', 'expense'
            ]);
            
            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};