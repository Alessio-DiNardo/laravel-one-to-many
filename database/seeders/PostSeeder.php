<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Type;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {

        $typeIds = Type::all()->pluck('id');
        //
        for ($i=0; $i < 100; $i++) {
            $newPost = new Post();
            $newPost->title = ucfirst($faker->unique()->words(4, true));
            $newPost->type_id = $faker->randomElement($typeIds);
            $newPost->content = $faker->paragraphs(10, true);
            $newPost->image= $faker->imageUrl(480, 360, 'post', true, 'posts', true, 'png');
            $newPost->save();
            $newPost->slug = Str::of("$newPost->id " . $newPost->title)->slug('-');
            $newPost->save();
        }

    }
}
