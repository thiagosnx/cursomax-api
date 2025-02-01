<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = DB::table('cursos')
                    ->select(
                        'id'
                        , 'ttl'
                        , 'cghr'
                        , 'tip'
                        , 'dt_lcmt'
                        , 'img'   
                        , 'url'
                        , 'ntc'
                    )
                    ->orderBy('dt_lcmt')
                    ->get();
        return response()->json($cursos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $curso = new Curso;
        $curso->ttl = $dados['titulo'];
        $curso->rls_dt = $dados['data_lancamento'];
        $curso->cghr = $dados['carga_horaria'];
        $curso->img = $dados['img'];
        $curso->url = $dados['url'];
        $curso->ntc = $dados['noticia'];
        $curso->emp = $dados['empresa'];
        $curso->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
