<?php

namespace App\Models;

use CodeIgniter\Model;

class ContatoModel extends Model
{
    protected $table            = 'contatos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'email', 'celular', 'observacao', 'status', 'lido'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'data_criacao';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nome' => 'required|min_length[3]',
        'email' => 'permit_empty|valid_email',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Busca contatos por status
     */
    public function buscarPorStatus($status = null)
    {
        if ($status && $status !== 'todas') {
            return $this->where('status', $status)
                         ->orderBy('data_criacao', 'DESC')
                         ->findAll();
        }
        return $this->orderBy('data_criacao', 'DESC')->findAll();
    }

    /**
     * Conta contatos por status
     */
    public function contarPorStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Conta contatos não lidos
     */
    public function contarNaoLidos()
    {
        return $this->where('lido', 0)->countAllResults();
    }
}
