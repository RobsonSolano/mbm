<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrapper { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
  .header { padding: 20px 30px; color: #fff; }
  .header.criado    { background: #0d6efd; }
  .header.cancelado { background: #dc3545; }
  .header.concluido { background: #198754; }
  .header h2 { margin: 0; font-size: 1.2rem; }
  .body { padding: 24px 30px; }
  .row { display: flex; margin-bottom: 12px; }
  .label { color: #888; font-size: .8rem; width: 130px; flex-shrink: 0; padding-top: 2px; }
  .value { font-weight: bold; color: #333; }
  .obs  { background: #f8f9fa; border-left: 3px solid #dee2e6; padding: 10px 14px; border-radius: 4px; margin-top: 8px; font-size: .9rem; color: #555; }
  .footer { background: #f8f9fa; padding: 14px 30px; font-size: .75rem; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header <?php echo esc($tipo, 'attr') ?>">
    <h2>
      <?php if ($tipo === 'criado'):    ?>Novo agendamento criado
      <?php elseif ($tipo === 'cancelado'): ?>Agendamento cancelado
      <?php else: ?>Atendimento concluído
      <?php endif; ?>
    </h2>
  </div>
  <div class="body">
    <div class="row">
      <span class="label">Cliente</span>
      <span class="value"><?php echo esc($cliente_nome) ?></span>
    </div>
    <div class="row">
      <span class="label">Data</span>
      <span class="value"><?php echo date('d/m/Y', strtotime($data)) ?></span>
    </div>
    <div class="row">
      <span class="label">Horário</span>
      <span class="value"><?php echo date('H:i', strtotime($hora_inicio)) ?>
        <?php if (!empty($hora_fim)): ?> – <?php echo date('H:i', strtotime($hora_fim)) ?><?php endif; ?>
      </span>
    </div>
    <div class="row">
      <span class="label">Serviço</span>
      <span class="value"><?php echo esc($descricao) ?></span>
    </div>
    <?php if (!empty($observacoes)): ?>
    <div class="obs"><?php echo nl2br(esc($observacoes)) ?></div>
    <?php endif; ?>
  </div>
  <div class="footer">MBM Climatização — notificação automática, não responda este e-mail.</div>
</div>
</body>
</html>
