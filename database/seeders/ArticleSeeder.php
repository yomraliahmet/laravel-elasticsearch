<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Elasticsearch;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Article::factory(10)->create()->each(function($article) {
           /*
            $data = [
                'body' => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'detail' => $article->detail,
                ],
                'index' => 'elasticsearch',
                'type' => 'article',
                'id' => $article->id,
            ];

            Elasticsearch::index($data);
           */
        });
    }
}
