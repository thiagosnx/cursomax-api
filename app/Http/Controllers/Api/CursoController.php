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
    public function index(Request $request)
    {
        $limite = $request->query('_limit', 10);
        $pagina = $request->query('_page', 1);

        $cursos = Curso::query()->paginate($limite, ['*'], 'page', $pagina);
        
        return response()->json($cursos->items());
    }

    
    public function store(Request $request)
    {
        $dados = $request->all();
        $curso = new Curso;
        $curso->ttl = $dados['ttl'];
        $curso->dett = $dados['dett'];
        $curso->dt_lcmt = $dados['dt_lcmt'];
        $curso->cghr = $dados['cghr'];
        $curso->img = $dados['img'];
        $curso->url = $dados['url'];
        $curso->tip = $dados['tip'] || 0;
        $curso->ntc = $dados['ntc'] || 0;
        $curso->emp = $dados['emp'];
        $curso->save();
        return response()->json($curso, 201);

    }

    public function show(int $id)
    {
        $curso = DB::table('cursos')
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
                    ->where('id', $id)
                    ->first();
        return response()->json($curso);
    }

    public function update(Request $request, int $id)
    {
        $curso = Curso::findOrFail($id);
        $dados = $request->all();
        $curso->ttl = $dados['titulo'] ?? $curso->ttl;
        $curso->dett = $dados['dett'] ?? $curso->dett;
        $curso->dt_lcmt = $dados['data_lancamento'] ?? $curso->dt_lcmt;
        $curso->cghr = $dados['carga_horaria'] ?? $curso->cghr;
        $curso->img = $dados['img'] ?? $curso->img;
        $curso->url = $dados['url'] ?? $curso->url;
        $curso->tip = $dados['tipo'] ?? $curso->tip;
        $curso->ntc = $dados['noticia'] ?? $curso->ntc;
        $curso->emp = $dados['empresa'] ?? $curso->emp;
        $curso->save();
        return response()->json($curso, 200);
    }

    
    public function destroy(int $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();
        return response()->json('Curso deletado com sucesso', 204);
    }
}
