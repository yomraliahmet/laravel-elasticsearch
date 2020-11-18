<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Elasticsearch;

class ElasticsearchController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = [
            'index' => 'elasticsearch',
            'type' => 'article',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => \request("q","*"),
                        'fuzziness' => 'auto' // yazım yanlışlarını tahminler.
                    ]
                ]
            ]
        ];

       $d = Elasticsearch::search($data);

       return response()->json($d);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleRequest $request)
    {
        $model = new Article();
        $model->title = $request->title;
        $model->detail = $request->detail;
        if($model->save()){

            $data = [
                'body' => [
                    'id' => $model->id,
                    'title' => $model->title,
                    'detail' => $model->detail,
                ],
                'index' => 'elasticsearch',
                'type' => 'article',
                'id' => $model->id,
            ];

            Elasticsearch::index($data);
        }

        return response()->json([
            "status" => "success",
            "message" => "The article insert database.",
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = [
            'index' => 'elasticsearch',
            'type' => 'article',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $id,
                    ]
                ]
            ]
        ];

        $d = Elasticsearch::search($data);

        return response()->json($d);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Article::destroy($id);

        $data = [
            'index' => 'elasticsearch',
            'id' => $id
        ];


        $d = Elasticsearch::delete($data);

        return response()->json($d);
    }
}
