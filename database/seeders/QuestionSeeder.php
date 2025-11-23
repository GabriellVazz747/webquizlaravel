<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Alternative;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');
        $count = 100;

        for ($i = 1; $i <= $count; $i++) {
            $q = Question::create([
                'title' => "Pergunta {$i}: " . $faker->sentence(8),
                'explanation' => $faker->optional()->sentence(12),
                'difficulty' => $faker->numberBetween(1,3),
            ]);

            $correctPos = rand(1,4);
            for ($pos = 1; $pos <= 4; $pos++) {
                Alternative::create([
                    'question_id' => $q->id,
                    'content' => $faker->sentence(6),
                    'is_correct' => $pos === $correctPos,
                    'position' => $pos
                ]);
            }
        }
    }
}
