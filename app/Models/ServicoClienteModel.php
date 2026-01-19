<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicoClienteModel extends Model
{
    protected $table = 'servicos_clientes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'cliente_id',
        'titulo',
        'descricao',
        'data_inicio',
        'data_finalizacao',
        'data_criacao',
        'data_edicao'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'data_criacao';
    protected $updatedField = 'data_edicao';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Busca serviços por cliente
     */
    public function buscarPorCliente($clienteId, $ordenacao = 'data_inicio', $direcao = 'DESC')
    {
        return $this->where('cliente_id', $clienteId)
                    ->orderBy($ordenacao, $direcao)
                    ->findAll();
    }

    /**
     * Conta serviços de um cliente
     */
    public function contarPorCliente($clienteId)
    {
        return $this->where('cliente_id', $clienteId)->countAllResults();
    }
}
