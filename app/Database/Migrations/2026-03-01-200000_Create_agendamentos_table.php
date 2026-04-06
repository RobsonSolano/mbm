<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create_agendamentos_table extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cliente_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'data'         => ['type' => 'DATE'],
            'hora_inicio'  => ['type' => 'TIME'],
            'hora_fim'     => ['type' => 'TIME', 'null' => true],
            'descricao'    => ['type' => 'TEXT'],
            'status'       => ['type' => 'ENUM', 'constraint' => ['agendado', 'concluido', 'cancelado'], 'default' => 'agendado'],
            'observacoes'   => ['type' => 'TEXT', 'null' => true],
            'criado_em'    => ['type' => 'DATETIME', 'null' => true],
            'atualizado_em' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('data');
        $this->forge->addKey('cliente_id');
        // FK omitida: clientes.id pode ter tipo diferente (ex: BIGINT). Integridade garantida no Model.
        $this->forge->createTable('agendamentos', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('agendamentos', true);
    }
}
