<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) { 
            User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->email(),
                'phone' => fake()->unique()->phoneNumber(),
                'password' => bcrypt(fake()->unique()->password())
            ]);
        }
    }
}
