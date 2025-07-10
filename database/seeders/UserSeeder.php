<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = "IT Mekar Armada Jaya";
        $user->email = "itmekararmadajaya@gmail.com";
        $user->password = Hash::make("password");
        $user->save();

        $user2 = new User;
        $user2->name = "Promosi Mekar Armada Jaya";
        $user2->email = "promosi@newarmada.co.id";
        $user2->password = Hash::make("glorynewarmada2024");
        $user2->save();
    }
}
