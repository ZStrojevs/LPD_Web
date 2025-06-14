<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // get any user for ownership, or create one

        if (!$user) {
            $user = User::factory()->create(); // create a user if none exists
        }

        Item::create([
            'title' => 'Camera',
            'description' => 'DSLR camera available for rent.',
            'price' => 25.00,
            'user_id' => $user->id,  // **Important!**
        ]);

        Item::create([
            'title' => 'Bicycle',
            'description' => 'Mountain bike in great condition.',
            'price' => 15.00,
            'user_id' => $user->id,
        ]);

        Item::create([
            'title' => 'Laptop',
            'description' => 'Gaming laptop, RTX 3060.',
            'price' => 40.00,
            'user_id' => $user->id,
        ]);
    }
}
