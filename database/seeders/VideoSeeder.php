<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $video_types = ['tutorial', 'vlog', 'review', 'gameplay', 'music'];

        for ($i = 0; $i < 500; $i++) {
            Video::create([
                'user_id' => $faker->numberBetween(1, 10),
                'type' => $faker->randomElement($video_types),
                'title' => $faker->sentence(6, true),
                'url' => $faker->url,
                'thumbnail' => $faker->imageUrl(640, 360, 'video'), // Generates a placeholder image URL
                'view_count' => $faker->numberBetween(0, 10000),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
