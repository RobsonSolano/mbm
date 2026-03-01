<?php

namespace App\Models;

use CodeIgniter\Model;

class EstoqueHistoricoModel extends Model
{
    protected $table            = 'estoque_historico';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['peca_id', 'tipo', 'quantidade', 'quantidade_anterior', 'quantidade_nova', 'descricao'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = ''; // tabela não tem atualizado_em

    /**
     * Busca histórico por peça
     */
    public function buscarPorPeca($pecaId)
    {
        return $this->where('peca_id', $pecaId)
                    ->orderBy('criado_em', 'DESC')
                    ->findAll();
    }
}
