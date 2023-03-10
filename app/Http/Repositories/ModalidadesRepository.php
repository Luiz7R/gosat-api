<?php

namespace App\Http\Repositories;

use App\Models\Modalidades;
use Illuminate\Database\Eloquent\Collection;

class ModalidadesRepository {
    private $modalidadesModel;

    public function __construct() {
        $this->modalidadesModel = new Modalidades();
    }

    public function create($modalidades)
    {
        return $this->modalidadesModel->create($modalidades);
    }

    public function where($coluna,$valor): Modalidades | null
    {
        return $this->modalidadesModel->where($coluna,$valor)->first();
    }

    public function whereIn($coluna,$valores): Collection
    {
        return $this->modalidadesModel->whereIn($coluna,$valores)->get();
    }
}