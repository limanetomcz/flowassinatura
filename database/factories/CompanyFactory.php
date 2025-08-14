<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->company(),
            'document'       => $this->faker->numerify('##.###.###/####-##'),
            'contact_email'  => $this->faker->unique()->safeEmail(),
            'contact_number' => $this->faker->phoneNumber(),
        ];
    }
}
