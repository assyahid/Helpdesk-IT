<?php

namespace Database\Seeders;

use App\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // create default status tags
        $tags = ['open', 'closed', 'pending', 'spam', 'locked'];
        app(Tag::class)->insertOrRetrieve($tags, 'status');

        // Create one ticket category if none exist
        if (!Tag::where('type', 'category')->count()) {
            Tag::create([
                'name' => 'general',
                'display_name' => 'General',
                'type' => 'category',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
