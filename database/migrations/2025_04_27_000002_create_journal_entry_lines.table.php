<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique()->nullable();
            $table->foreignId('journal_entry_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('account_id')->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('debit', 15, 4)->default(0.0000);
            $table->decimal('credit', 15, 4)->default(0.0000);

            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps(); 
            
            $table->index(['account_id', 'journal_entry_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entry_lines');
    }
};