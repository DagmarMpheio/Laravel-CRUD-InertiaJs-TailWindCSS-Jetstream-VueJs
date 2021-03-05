<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlesCollection;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ArticleController extends Controller
{
    //

    public function index()
    {

//        return Inertia::render(
//            'Article',
//            [
//                'data' => Article::query()->paginate(5)
//            ]
//        );
        return Inertia::render('Article', [
            'articles' => Article::query()->paginate(5)
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'body' => ['required'],
        ])->validate();

        Article::create($request->all());

        return redirect()->back()
            ->with('message', 'Artigo criado com sucesso!');
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'title' => ['required'],
            'body' => ['required'],
        ])->validate();

        if ($request->has('id')) {
            Article::find($request->input('id'))->update($request->all());
            return redirect()->back()
                ->with('message', 'Artigo actualizado com sucesso!');
        }
    }

    public function delete(Request $request)
    {

        $request->has('id') ?
            Article::find($request->input('id'))->delete() :
            redirect()->back()
                ->with('errors', 'Algo correu mal.');

        return redirect()->back()
            ->with('message', 'Artigo excluido com sucesso!');
    }
}
