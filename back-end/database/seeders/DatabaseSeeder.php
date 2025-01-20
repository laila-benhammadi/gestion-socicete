<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     AdminUserSeeder::class, // Ajouter le seeder admin ici
        // ]);  
    
        User::factory(10)->create();

        User::factory(5)->admin()->create();
    
    }


}
