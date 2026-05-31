<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->foreignId('journal_id')->nullable()->constrained();

            $table->foreignId('reversed_entry_id')->nullable()
            ->constrained('journal_entries')
            ->nullOnDelete();

            $table->string('reference')->nullable();
            $table->text('description')->nullable();

            $table->date('date')->index();
           

            $table->enum('status', ['draft', 'posted', 'reversed'])->default('draft');

            $table->timestamp('posted_at')->nullable();

            $table->nullableMorphs('journalable'); // renamed from transactionable

            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
};