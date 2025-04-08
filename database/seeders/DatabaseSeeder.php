<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sample;
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
        Sample::factory(10)->create();

        User::factory()->create([
            'username' => 'Test_Admin',
            'first_name' => 'Test_Admin',
            'last_name' => 'Test_Admin',
            'user_type' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'role_id' => 1,
        ]);
        User::factory()->create([
            'username' => 'Test_User',
            'first_name' => 'Test_User',
            'last_name' => 'Test_User',
            'user_type' => 'Viewer',
            'email' => 'roseannejoydelacruz@gmail.com',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'role_id' => 2,
        ]);
  }
}
