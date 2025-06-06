<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Food', 'icon' => 'ti-burger'],
            ['name' => 'Games', 'icon' => 'ti-device-gamepad'],
            ['name' => 'Grocery', 'icon' => 'ti-shopping-cart'],
            ['name' => 'Home Stuff', 'icon' => 'ti-home'],
            ['name' => 'Online Shopping', 'icon' => 'ti-world'],
            ['name' => 'Transportation', 'icon' => 'ti-car'],
            ['name' => 'Subscription', 'icon' => 'ti-rosette-discount-check'],
            ['name' => 'Transfer Fees', 'icon' => 'ti-transfer'],
            ['name' => 'Shopping', 'icon' => 'ti-basket-pin'],
            ['name' => 'Utilities and Bills', 'icon' => 'ti-cash'],
            ['name' => 'Others', 'icon' => 'ti-dots'],
        ];

        foreach ($tags as $tag) {
            if(Tag::where('name', 'like', '%' . $tag['name'] . '%')->doesntExist()){
                Tag::create($tag);
            }
        }
    }
}
