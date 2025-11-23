<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('total_questions')->default(10);
            $table->unsignedTinyInteger('correct_count')->default(0);
            $table->unsignedTinyInteger('wrong_count')->default(0);
            $table->integer('score')->default(0);
            $table->integer('time_seconds')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->json('question_ids')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['score','time_seconds']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
