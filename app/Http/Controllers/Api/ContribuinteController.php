<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribuinte;
use App\Models\Doacao;
use Illuminate\Support\Facades\Validator;

class ContribuinteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $limite = $request->query('_limit', 10);
        $pagina = $request->query('_page', 1);

        $contribuicoes = Contribuinte::query()->paginate($limite, ['*'], 'page', $pagina);
        $doacoes = Doacao::query()->paginate($limite, ['*'], 'page', $pagina);
        
        return response()->json([
            "contribuicoes" => $contribuicoes->items(),
            "doacoes" => $doacoes->items(),
        ]);
    }
   
    public function store(Request $request)
    {
        $ip = $request->ip();
        $dados = $request->only('url');
        $contribuicao = new Contribuinte;
        $contribuicao->ip = $ip;
        $contribuicao->url = $dados['url'];
        $contribuicao->save();
        return response()->json($contribuicao, 201);
    }

   
    public function show(string $id)
    {
        //
    }

    public function destroy(string $id)
    {
    }
}
