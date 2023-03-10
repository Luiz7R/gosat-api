<?php

namespace App\Http\Repositories;

use App\Services\ApiGoSatService;

class CreditoRepository {
    
    private $clientApiGoSat;
    private $instituicoesRepository;
    private $modalidadesRepository;

    public function __construct() {
        $this->instituicoesRepository = new InstituicoesRepository();
        $this->modalidadesRepository = new ModalidadesRepository();
        $this->clientApiGoSat = new ApiGoSatService();
    }

    public function consultarOfertaCredito($cpf)
    {
        return $this->clientApiGoSat->consultaOfertaCredito($cpf);
    }

    public function getParametrosConsulta($dados)
    {
        $modalidades = [];
        foreach($dados as $parametro) {                         
            $instituicao = $this->instituicoesRepository->where('instituicao',$parametro['id']);

            if ( !count($instituicao) ){
                $dadosInstituicao = Array("instituicao" => $parametro['id'],"name" => $parametro['nome']);
                $instituicao = $this->instituicoesRepository->create($dadosInstituicao);
            }
            if ( $parametro['modalidades'] ) {
                $modalidadeCodigos = array_column($parametro['modalidades'], 'cod');
                $buscarModalidade = $this->modalidadesRepository->whereIn('cod',$modalidadeCodigos);

                if ( !count($buscarModalidade) ) {
                    foreach($parametro['modalidades'] as $modalidade) {
                        $buscarModalidade = $this->modalidadesRepository->where('cod',$modalidade['cod']);

                        if ( !$buscarModalidade ) {
                            $dadosModalidades = Array(
                                "cod" => $modalidade['cod'],
                                "name" => $modalidade['nome'],
                                "instituicao_id" => $parametro["id"],
                            );
                            $buscarModalidade = $this->modalidadesRepository->create($dadosModalidades);
                        }
                    }
                    array_push($modalidades,$buscarModalidade);
                } 
                else
                {
                    array_push($modalidades,$buscarModalidade->toArray()); 
                }
            }
        }
        return call_user_func_array('array_merge', $modalidades);
    }

    public function consultaSimulacaoCredito($parametros,$payload)
    {
        $simulacaoCredito = array();
            foreach($parametros as $parametro) {
                $ofertaCredito = $this->clientApiGoSat->consultaSimulacaoCredito($parametro,$payload['cpf']);
                if ( !empty($payload['valorSolicitado']) ) {
                    if ( $payload['valorSolicitado'] >= $ofertaCredito['valorMin'] && $payload['valorSolicitado'] <= $ofertaCredito['valorMax'] ) {
                        if ( $payload['quantidadeParcelas'] >= $ofertaCredito['QntParcelaMin'] && $payload['quantidadeParcelas'] <= $ofertaCredito['QntParcelaMax'] ) {
                            $simulacaoCredito[] = $ofertaCredito;
                        }
                    }
                } else {
                    $simulacaoCredito[] = $ofertaCredito;
                }
            }
        return $simulacaoCredito;
    }

    public function calcularJuros($data,$ofertasCredito) {
        foreach($ofertasCredito as $key => $ofertaCredito) {  
            $totalDeJuros = $data['valorSolicitado'] * $ofertaCredito['jurosMes'] * $data['quantidadeParcelas'];
            $ofertasCredito[$key]['valorSolicitado'] = $data['valorSolicitado']; 
            $ofertasCredito[$key]['valorAPagar'] = $data['valorSolicitado'] + $totalDeJuros;     
        }
        return $ofertasCredito;
    }

    public function ordenarOfertasCredito($ofertasCredito) {
        usort($ofertasCredito, function($a,$b) {
            return $a['jurosMes'] > $b['jurosMes'];
        });

        return $ofertasCredito;
    }
}