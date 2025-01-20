<?php

namespace Database\Factories;
use App\Models\User;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // mot de passe par défaut
            'is_admin' => false,  // Par défaut, l'utilisateur est un employé
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'nationality' => $this->faker->country(),
            'contry' => $this->faker->country(),
            'city' => $this->faker->city(),
            'personal_email' => $this->faker->unique()->safeEmail(),
            'family_situation' => $this->faker->randomElement(['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)', 'Séparé(e)']),
            'cin' => $this->faker->bothify('??#######'),
            'address' => $this->faker->address(),
            'user_folder' => $this->faker->word(),
            'hiring_date' => $this->faker->date(),
            'work_files' => json_encode([]),  // Fichiers de travail vide
        ];
    }

    public function admin()
    {
        return $this->state([
            'is_admin' => true,
        ]);
    }




















    /**
     * Define the model's default state.
     *
     * @return array
     */
    // public function definition()
    // {
    //     return [
    //         'name' => $this->faker->name(),
    //         'email' => $this->faker->unique()->safeEmail(),
    //         'email_verified_at' => now(),
    //         'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    //         'remember_token' => Str::random(10),
    //     ];
    // }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // public function unverified()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'email_verified_at' => null,
    //         ];
    //     });
    // }








}
