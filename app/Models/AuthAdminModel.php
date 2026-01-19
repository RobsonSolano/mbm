<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthAdminModel extends Model
{
    protected $table            = 'auth_admin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'senha', 'nome', 'ativo', 'ultimo_acesso'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'email' => 'required|valid_email|is_unique[auth_admin.email,id,{id}]',
        'senha' => 'required|min_length[6]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash da senha antes de inserir/atualizar
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['senha'])) {
            $data['data']['senha'] = password_hash($data['data']['senha'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    /**
     * Verifica credenciais de login
     */
    public function verificarLogin($email, $senha)
    {
        // DEBUG
        log_message('debug', '=== AuthAdminModel::verificarLogin ===');
        log_message('debug', 'Email: ' . $email);
        
        $admin = $this->where('email', $email)
                      ->where('ativo', 1)
                      ->first();
        
        // DEBUG
        if ($admin) {
            log_message('debug', 'User found - ID: ' . $admin['id'] . ', Name: ' . $admin['nome']);
            log_message('debug', 'Hash from DB (30 chars): ' . substr($admin['senha'], 0, 30));
            
            $passwordVerify = password_verify($senha, $admin['senha']);
            log_message('debug', 'password_verify result: ' . ($passwordVerify ? 'TRUE' : 'FALSE'));
            
            if ($passwordVerify) {
                // Atualiza último acesso
                $this->update($admin['id'], ['ultimo_acesso' => date('Y-m-d H:i:s')]);
                log_message('debug', 'Login SUCCESS - Returning admin data');
                return $admin;
            } else {
                log_message('debug', 'Login FAILED - password_verify returned FALSE');
            }
        } else {
            log_message('debug', 'User NOT FOUND or NOT ACTIVE');
        }

        return false;
    }
}
