<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            InstitutionSeeder::class,
            AllInstitutionsSeeder::class,
            FiftyInstitutionSeeder::class,
            QuickStatSeeder::class,
            SettingsSeeder::class,
        ]);

        // Create default admin user
        \App\Models\User::updateOrCreate(
            [ 'email' => 'admin@example.com' ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin1234'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
