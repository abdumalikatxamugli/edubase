<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'first_name'=>$this->faker->firstName(),
            'last_name'=>$this->faker->lastName(),
            'patronym'=>$this->faker->firstName(),
            'gender'=>$this->faker->randomElement(['m', 'f']),
            'pass_sery'=>strtoupper($this->faker->lexify('??')),
            'pass_number'=>$this->faker->numerify('#######'),
            'pinfl'=>$this->faker->numerify('##############'),
            'phone'=>'9989'.$this->faker->numerify('########'),
            'address'=>$this->faker->address(),
            'birth_date'=>$this->faker->date('Y-m-d')

        ];
    }
}
