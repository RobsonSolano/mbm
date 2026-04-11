<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionarioModel extends Model
{
    protected $table            = 'funcionarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome', 'email', 'telefone', 'nivel',
        'data_inicio_contrato', 'data_fim_contrato',
        'bloqueado', 'deletado',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    protected $validationRules = [
        'nome'  => 'required|min_length[2]',
        'nivel' => 'required|in_list[dono,lider,tecnico,assistente]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Retorna colaboradores ativos (não bloqueados e não deletados).
     */
    public function buscarAtivos(): array
    {
        return $this->where('deletado', 0)
                    ->where('bloqueado', 0)
                    ->where('data_fim_contrato IS NULL OR data_fim_contrato >=', date('Y-m-d'))
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }

    /**
     * Rótulo legível para o nível.
     */
    public static function labelNivel(string $nivel): string
    {
        return match($nivel) {
            'dono'       => 'Dono',
            'lider'      => 'Líder',
            'tecnico'    => 'Técnico',
            'assistente' => 'Assistente',
            default      => $nivel,
        };
    }
}
