<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
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

    
    public function store(Request $request)
    {
        $dados = $request->all();
        $curso = new Curso;
        $curso->ttl = $dados['titulo'];
        $curso->dt_lcmt = $dados['data_lancamento'];
        $curso->cghr = $dados['carga_horaria'];
        $curso->img = $dados['img'];
        $curso->url = $dados['url'];
        $curso->tip = $dados['tipo'] || 0;
        $curso->ntc = $dados['noticia'] || 0;
        $curso->emp = $dados['empresa'];
        $curso->save();
        return response()->json($curso, 201);

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
