<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CreditoRepository;
use App\Http\Requests\ConsultarCreditoRequest;
use App\Services\ApiGoSatService;
use App\Models\Instituicoes;
use App\Http\Requests\UpdateInstituicoesRequest;

class InstitucoesController extends Controller
{

    private $clientApiGoSat;
    private $creditoRepository;

    public function __construct() {
        $this->clientApiGoSat = new ApiGoSatService();
        $this->creditoRepository = new CreditoRepository();
    }

    public function consultarCpf(ConsultarCreditoRequest $request) {

        $data = $request->validated();

        $consultaOfertasCredito = $this->creditoRepository->consultarOfertaCredito($data['cpf']);
        if ( !empty($consultaOfertasCredito['errorMsg']) ){
            return response($consultaOfertasCredito, 404);
        }
        $consultaOfertasCredito = json_decode($consultaOfertasCredito,true);
        $parametrosConsulta = $this->creditoRepository->getParametrosConsulta($consultaOfertasCredito['instituicoes']);
        $ofertasCredito = $this->creditoRepository->consultaSimulacaoCredito($parametrosConsulta,$data);
        if ( !$ofertasCredito )
            return response()->json(['msg' => 'Não foi encontrado ofertas de credito, para os valores informados'], 404);
        
        $ofertasCreditoJurosCalculado = $this->creditoRepository->calcularJuros($data,$ofertasCredito);
        $ofertasCreditoOrdenados = $this->creditoRepository->ordenarOfertasCredito($ofertasCreditoJurosCalculado);

        return response()->json($ofertasCreditoOrdenados); 
    }
}
