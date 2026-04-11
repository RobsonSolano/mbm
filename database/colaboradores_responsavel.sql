-- ============================================================
-- MBM - Gestão de Colaboradores + Responsável no Agendamento
-- Executar manualmente via phpMyAdmin ou MySQL client
-- ============================================================

-- 1. Tabela funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id`                   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome`                 VARCHAR(255) NOT NULL,
  `email`                VARCHAR(255) NULL DEFAULT NULL,
  `telefone`             VARCHAR(20)  NULL DEFAULT NULL,
  `nivel`                ENUM('dono','lider','tecnico','assistente') NOT NULL DEFAULT 'assistente',
  `data_inicio_contrato` DATE NOT NULL DEFAULT (CURRENT_DATE),
  `data_fim_contrato`    DATE NULL DEFAULT NULL,
  `bloqueado`            TINYINT(1) NOT NULL DEFAULT 0,
  `deletado`             TINYINT(1) NOT NULL DEFAULT 0,
  `criado_em`            DATETIME NULL DEFAULT NULL,
  `atualizado_em`        DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Primeiro colaborador (dono)
INSERT INTO `funcionarios` (`id`, `nome`, `email`, `nivel`, `data_inicio_contrato`, `criado_em`, `atualizado_em`)
VALUES (1, 'Maicon José', 'climatizacaombm@gmail.com', 'dono', '2024-01-01', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- 3. Adicionar responsavel_id na tabela agendamentos
ALTER TABLE `agendamentos`
  ADD COLUMN `responsavel_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `cliente_id`,
  ADD CONSTRAINT `agendamentos_responsavel_fk`
    FOREIGN KEY (`responsavel_id`) REFERENCES `funcionarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- 4. Vincular agendamentos existentes ao responsavel_id = 1
UPDATE `agendamentos` SET `responsavel_id` = 1 WHERE `responsavel_id` IS NULL;
