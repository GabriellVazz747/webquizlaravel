<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('alternative_id')->constrained('alternatives')->cascadeOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('time_taken')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['quiz_id','question_id']);
            $table->index(['quiz_id','is_correct']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_answers');
    }
};
