<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body  { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
  .header  { background: #0d6efd; padding: 22px 30px; color: #fff; }
  .header h2 { margin: 0; font-size: 1.15rem; }
  .header p  { margin: 4px 0 0; font-size: .85rem; opacity: .85; }
  .body    { padding: 24px 30px; }
  .saudacao { font-size: 1rem; color: #333; margin-bottom: 18px; }
  .item    { border: 1px solid #e9ecef; border-radius: 6px; padding: 14px 18px; margin-bottom: 14px; }
  .item-num { font-size: .72rem; font-weight: bold; color: #0d6efd; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
  .row     { display: flex; margin-bottom: 6px; }
  .label   { color: #888; font-size: .8rem; width: 90px; flex-shrink: 0; padding-top: 1px; }
  .value   { color: #333; font-size: .88rem; font-weight: 600; }
  .obs     { background: #f8f9fa; border-left: 3px solid #dee2e6; padding: 8px 12px; margin-top: 8px; font-size: .82rem; color: #666; border-radius: 3px; }
  .footer  { background: #f8f9fa; padding: 14px 30px; font-size: .72rem; color: #aaa; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <h2>Agendamentos de hoje</h2>
    <p><?php echo date('l, d \d\e F \d\e Y', strtotime($data)) ?></p>
  </div>

  <div class="body">
    <p class="saudacao">
      Olá <strong><?php echo esc($responsavel_nome) ?></strong>,<br>
      segue abaixo <?php echo count($itens) === 1 ? 'o agendamento' : 'os agendamentos' ?> para hoje:
    </p>

    <?php foreach ($itens as $i => $item): ?>
    <div class="item">
      <div class="item-num"><?php echo ($i + 1) ?>.</div>

      <div class="row">
        <span class="label">Cliente</span>
        <span class="value"><?php echo esc($item['cliente_nome']) ?></span>
      </div>

      <div class="row">
        <span class="label">Horário</span>
        <span class="value">
          <?php echo date('H:i', strtotime($item['hora_inicio'])) ?>
          <?php if (!empty($item['hora_fim'])): ?> – <?php echo date('H:i', strtotime($item['hora_fim'])) ?><?php endif; ?>
        </span>
      </div>

      <div class="row">
        <span class="label">Local</span>
        <span class="value">
          <?php
          $local = trim(($item['endereco'] ?? '') . ((!empty($item['cidade'])) ? ' — ' . $item['cidade'] : ''));
          echo $local ? esc($local) : '<span style="color:#bbb">Não informado</span>';
          ?>
        </span>
      </div>

      <div class="row">
        <span class="label">Descrição</span>
        <span class="value"><?php echo esc($item['descricao']) ?></span>
      </div>

      <?php if (!empty($item['observacoes'])): ?>
      <div class="obs"><?php echo nl2br(esc($item['observacoes'])) ?></div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="footer">MBM Climatização — notificação automática, não responda este e-mail.</div>
</div>
</body>
</html>
