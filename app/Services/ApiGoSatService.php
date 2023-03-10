<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class ApiGoSatService {

    public $clientApiGoSat;

    public function __construct() {

        $this->clientApiGoSat = new Client(
            [
                'base_uri' => env('API_GOSAT_URL'),
                'verify' => false,
            ]
        );
    }

    public function consultaOfertaCredito($cpf) {

        try {
            $response = $this->clientApiGoSat->post('credito', [
                    'headers' => [
                        'Accept' => 'application/json'
                    ],
                    'json' => [
                        'cpf' => $cpf
                    ]
                ]);
            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return [
                    'errorMsg' => $response->getBody()->getContents(),
                    'statusCode' => $response->getStatusCode(),
                    'msg'=>'Não foi encontrado ofertas de credito, para o CPF Informado.'
                ];
            // echo Psr7\Message::toString($e->getRequest());
            // echo Psr7\Message::toString($e->getResponse());
        }

        // $response = $this->clientApiGoSat->post('credito', [
        //     'headers' => [
        //         'Accept' => 'application/json'
        //     ],
        //     'json' => [
        //         'cpf' => $cpf
        //     ]
        // ]);
        // return $response->getBody()->getContents();
    }

    public function consultaSimulacaoCredito($dados,$cpf) {
        $response = $this->clientApiGoSat->post('oferta', [
            'headers' => [
                'Accept' => 'application/json'
            ],
            'json' => [
                'cpf' => $cpf,
                'instituicao_id' => $dados['instituicao_id'],
                'codModalidade' => $dados['cod']
            ]
        ]);
        $simulacaoCredito = json_decode($response->getBody()->getContents(),true);
        $simulacaoCredito['instituicaoFinanceira'] = $dados['instituicao_id'];
        $simulacaoCredito['modalidadeCredito'] = $dados['name'];
        $simulacaoCredito['codModalidade'] = $dados['cod'];

        return $simulacaoCredito;
    }
}