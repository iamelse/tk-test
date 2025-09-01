<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    User::factory()->create([
      'name' => 'Test User',
      'username' => 'testuser',
      'email' => 'test@example.com',
    ]);

    Hospital::factory()->count(30)->create();
    Patient::factory()->count(30)->create();
  }
}
