<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nome_completo',
        'celular',
        'email',
        'endereco',
        'cidade',
        'observacoes',
        'bloqueado',
        'deletado',
        'lido',
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
     * Busca clientes não deletados
     */
    public function buscarAtivos($ordenacao = 'id', $direcao = 'DESC')
    {
        return $this->where('deletado', 0)
                    ->orderBy($ordenacao, $direcao)
                    ->findAll();
    }

    /**
     * Busca cliente por ID (não deletado)
     */
    public function buscarPorId($id)
    {
        return $this->where('id', $id)
                    ->where('deletado', 0)
                    ->first();
    }

    /**
     * Conta clientes não lidos
     */
    public function contarNaoLidos()
    {
        return $this->where('deletado', 0)
                    ->where('lido', 0)
                    ->countAllResults();
    }

    /**
     * Marca como lido
     */
    public function marcarComoLido($id)
    {
        return $this->update($id, ['lido' => 1]);
    }

    /**
     * Marca como não lido
     */
    public function marcarComoNaoLido($id)
    {
        return $this->update($id, ['lido' => 0]);
    }

    /**
     * Conta total de clientes ativos
     */
    public function contarAtivos()
    {
        return $this->where('deletado', 0)->countAllResults();
    }

    /**
     * Conta clientes bloqueados
     */
    public function contarBloqueados()
    {
        return $this->where('deletado', 0)
                    ->where('bloqueado', 1)
                    ->countAllResults();
    }

    /**
     * Soft delete
     */
    public function deletar($id)
    {
        return $this->update($id, ['deletado' => 1]);
    }
}
