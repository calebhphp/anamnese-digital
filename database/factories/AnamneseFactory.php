<?php

namespace Database\Factories;

use App\Models\Anamnese;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnamneseFactory extends Factory
{
    protected $model = Anamnese::class;

    public function definition()
    {
        return [
            'session_id' => Str::random(32),
            'responses' => [
                'step_1' => [
                    'nascimento' => $this->faker->date(),
                    'sexo' => $this->faker->randomElement(['feminino', 'masculino', 'outro']),
                    'menopausa' => $this->faker->randomElement(['sim', 'nao', 'nao_aplica']),
                    'saved_at' => now()->toDateTimeString()
                ],
                'step_2' => [
                    'objetivo' => $this->faker->sentence(),
                    'gordura_eliminar' => $this->faker->randomFloat(1, 5, 30),
                    'impedimentos' => $this->faker->sentence(),
                    'saved_at' => now()->toDateTimeString()
                ]
            ],
            'completed' => false,
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'completed' => true,
                'responses' => array_merge($attributes['responses'] ?? [], [
                    'step_10' => [
                        'finalizado' => true,
                        'saved_at' => now()->toDateTimeString()
                    ]
                ])
            ];
        });
    }
}