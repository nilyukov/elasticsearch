<?php

namespace App\Http\Controllers;


use App\Models\Article;
use App\Repositories\Articles\EloquentRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $articlesRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->articlesRepository = app(EloquentRepository::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $articles = Article::get();
        return view('home', compact('articles'));
    }

    public function search(Request $request){
        $query = $request->only('q');
        $articles = $this->articlesRepository->search($query['q']);
        return view('home', compact('articles'));
    }


}
