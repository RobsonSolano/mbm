<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create_fornecedores_and_link_estoque extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nome'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'contato'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'observacoes'  => ['type' => 'TEXT', 'null' => true],
            'ativo'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'criado_em'    => ['type' => 'DATETIME', 'null' => true],
            'atualizado_em' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fornecedores', true);

        $this->forge->addColumn('estoque_pecas', [
            'fornecedor_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'descricao'],
        ]);
        $prefix = $this->db->getPrefix() ?: '';
        $this->db->query("ALTER TABLE `{$prefix}estoque_pecas` ADD CONSTRAINT `estoque_pecas_fornecedor_fk` FOREIGN KEY (`fornecedor_id`) REFERENCES `{$prefix}fornecedores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down(): void
    {
        $prefix = $this->db->getPrefix() ?: '';
        $this->db->query("ALTER TABLE `{$prefix}estoque_pecas` DROP FOREIGN KEY `estoque_pecas_fornecedor_fk`");
        $this->forge->dropColumn('estoque_pecas', 'fornecedor_id');
        $this->forge->dropTable('fornecedores', true);
    }
}
