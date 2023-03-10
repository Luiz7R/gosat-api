<?php

namespace App\Http\Repositories;

use App\Models\Instituicoes;
use Illuminate\Contracts\Database\Eloquent\Builder;

class InstituicoesRepository {
    private $instituicoesModel;

    public function __construct() {
        $this->instituicoesModel = new Instituicoes();
    }

    public function create($instituicoes): Instituicoes
    {
        return $this->instituicoesModel->create($instituicoes);
    }

    public function where($coluna,$valor)
    {
        return $this->instituicoesModel->where($coluna,$valor)->get();
    }
}