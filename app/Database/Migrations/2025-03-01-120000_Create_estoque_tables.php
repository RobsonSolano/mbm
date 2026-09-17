<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create_estoque_tables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nome'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'quantidade'   => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'descricao'   => ['type' => 'TEXT', 'null' => true],
            'criado_em'   => ['type' => 'DATETIME', 'null' => true],
            'atualizado_em' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('estoque_pecas', true);

        $this->forge->addField([
            'id'                  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'peca_id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tipo'                => ['type' => 'ENUM', 'constraint' => ['aumento', 'diminuicao']],
            'quantidade'          => ['type' => 'INT', 'constraint' => 11],
            'quantidade_anterior'  => ['type' => 'INT', 'constraint' => 11],
            'quantidade_nova'     => ['type' => 'INT', 'constraint' => 11],
            'descricao'           => ['type' => 'TEXT', 'null' => true],
            'criado_em'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('peca_id', 'estoque_pecas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('estoque_historico', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('estoque_historico', true);
        $this->forge->dropTable('estoque_pecas', true);
    }
}
