<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('content', 1000);
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('position')->comment('1 to 4');
            $table->timestamps();

            $table->unique(['question_id','position']);
            $table->index(['question_id','is_correct']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alternatives');
    }
};
