<?php

namespace Database\Factories;

use App\Models\Anamnese;
use App\Models\AnamneseHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnamneseHistoryFactory extends Factory
{
    protected $model = AnamneseHistory::class;

    public function definition()
    {
        return [
            'anamnese_id' => Anamnese::factory(),
            'responses' => [
                'step_1' => [
                    'nascimento' => $this->faker->date(),
                    'sexo' => $this->faker->randomElement(['feminino', 'masculino']),
                ],
                'step_2' => [
                    'objetivo' => $this->faker->sentence(),
                    'gordura_eliminar' => $this->faker->randomFloat(1, 5, 30),
                ]
            ],
        ];
    }
}