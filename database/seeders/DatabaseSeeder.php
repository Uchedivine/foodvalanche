<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        $user = new User([
            'name' => 'uchedivine',
            'email' => 'uchedivine65@gmail.com',
            'password' => Hash::make('Sniper6.5'),
        ]);

        // Directly assign the guarded attribute
        $user->role = 'admin'; 
        $user->save();
    }
    
}