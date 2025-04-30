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
            'username' => 'Super Admin',
            'first_name' => 'Super Admin',
            'last_name' => 'Super Admin',
            'user_type' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'role_id' => 1,
        ]);
        User::factory()->create([
          'username' => 'Admin',
          'first_name' => 'Admin',
          'last_name' => 'Admin',
          'user_type' => 'Admin',
          'email' => 'admin@example.com',
          'password' => Hash::make('12345678'),
          'status' => 'active',
          'role_id' => 2,
      ]);
        User::factory()->create([
            'username' => 'Viewer',
            'first_name' => 'Viewer',
            'last_name' => 'Viewer',
            'user_type' => 'Viewer',
            'email' => 'viewer@example.com',
            'password' => Hash::make('12345678'),
            'status' => 'active',
            'role_id' => 3,
        ]);
  }
}
