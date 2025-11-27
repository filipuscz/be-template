<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $table->id();
        //     $table->string('title');
        //     $table->text('content');
        //     $table->foreignId('user_id')->constrained()->onDelete('cascade');
        //     $table->boolean('is_published')->default(false);
        //     $table->timestamp('published_at')->nullable();
        //     $table->string('slug')->unique();
        //     $table->integer('views')->default(0);
        //     $table->string('featured_image')->nullable();
        //     $table->text('excerpt')->nullable();
        //     $table->json('metadata')->nullable();
        //     $table->softDeletes();
        //     $table->timestamps();

        \App\Models\Post::factory()
        ->count(2)
        ->create();
    }
}
