<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribuinte;
use App\Models\Doacao;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

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

    public function iniciaPgto(Request $request)
    {
        $ip = $request->ip();
        $valor = $request['valor'];
        $idempotence = $ip.time();
        $email = filter_var($ip, FILTER_SANITIZE_NUMBER_INT).'@email.com';
        
        $pagador = array(
            "email"=>$email,
        );

        $info = array(
            "transaction_amount" => $valor,
            "description" => "Doação ao CursoMax",
            "payment_method_id" => "pix"
        );
        $pagamento = array_merge(["payer" => $pagador], $info);
        $resposta = Http::withHeaders([
            'Authorization' => 'Bearer '. env('MP_SECRET'),
            'X-Idempotency-Key' => (string)$idempotence,
        ])->post('https://api.mercadopago.com/v1/payments', $pagamento);
        $pix = $resposta['point_of_interaction']['transaction_data'];
        $retorno = array(
            "qr_code_base64" => $pix['qr_code_base64'],
            "qr_code" => $pix['qr_code'],
            "status" => $resposta['status'],
            "valor" => $valor,
            "id_pgto" => $resposta['id'],
            "idempotence" => (string)$idempotence,
            "ip" => $ip,
            "email" => $email
        );
            
        if(!$retorno['qr_code_base64']){
            return response()->json(['message' => 'Algo deu errado ao gerar o pagamento'], 400);
        }
        $this->salvaPgto($retorno);

        return response()->json($retorno);

    }
    public function salvaPgto(array $pgto){
        $doacao = new Doacao();
        $doacao->ip = $pgto['ip'];
        $doacao->email = $pgto['email'];
        $doacao->valor = $pgto['valor'];
        $doacao->id_pgto = $pgto['id_pgto'];
        $doacao->idempotence = $pgto['idempotence'];
        $doacao->status = $pgto['status'];
        $doacao->save();
        return response()->json($doacao, 201);
    }
   
    public function show(string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
