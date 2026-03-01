<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitacaoModel extends Model
{
    protected $table            = 'solicitacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nome', 'email', 'celular', 'cidade', 'observacao', 'observacao_admin', 'status', 'lido', 'ip', 'navegador'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nome' => 'required|min_length[3]',
        'email' => 'required|valid_email',
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
     * Busca solicitações por status
     */
    public function buscarPorStatus($status = null)
    {
        if ($status) {
            return $this->where('status', $status)
                         ->orderBy('criado_em', 'DESC')
                         ->findAll();
        }
        return $this->orderBy('criado_em', 'DESC')->findAll();
    }

    /**
     * Conta solicitações por status
     */
    public function contarPorStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }
}
