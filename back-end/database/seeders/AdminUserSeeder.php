<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Str; // Assurez-vous que cette ligne est présente

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Créer un utilisateur admin
      User::create([
        'first_name' => 'Admin User', 
        'last_name' => 'Admin User',               // Nom de l'admin
        'cin' => 'l123123 ',               // Nom de l'admin
        'date_of_birth' => '1990-05-15 ',               // Nom de l'admin
        'password' => bcrypt('admin123'), // Mot de passe de l'admin
        'remember_token' => Str::random(10),  // Token pour se souvenir de l'utilisateur
        'is_admin' => true,                   // Champ qui définit l'admin (assurez-vous qu'il existe dans votre table users)

        'personal_email' => 'laila@gmail.com',
        'email'=>'admin@societe.com',
        'city'=>'tanger',
        'contry'=>'Maroc',
        'nationality'=>'marocain',
        'gender'=>'femme',
        'family_situation'=>'Célibataire'


      ]);
    }
}
