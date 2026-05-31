<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// LESSON: DatabaseSeeder is the main entry point for all seeders.
// You register all your seeders here and run: php artisan db:seed

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // LESSON: call() runs the listed seeders in order
        $this->call([
            OrderSeeder::class,
        ]);
    }
}
