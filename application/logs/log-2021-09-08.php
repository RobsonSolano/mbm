<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-09-08 15:24:31 --> Query error: Table 'afiliado.produto' doesn't exist - Invalid query: SELECT t.*, md5(t.id) as id_md5, cat.nome AS categoria_nome, md5(cat.id) as categoria_id FROM produto t INNER JOIN categoria cat ON t.categoria_id = cat.id  WHERE t.deletado = 0 AND cat.deletado = 0 ORDER BY t.data_cadastro DESC  LIMIT 15
ERROR - 2021-09-08 15:24:31 --> Severity: error --> Exception: Erro ao capturar os itens. /app/_estudos/codeigniter/fit/site/application/models/Home_model.php 41
ERROR - 2021-09-08 15:25:56 --> Query error: Table 'afiliado.produto' doesn't exist - Invalid query: SELECT t.*, md5(t.id) as id_md5, cat.nome AS categoria_nome, md5(cat.id) as categoria_id FROM produto t INNER JOIN categoria cat ON t.categoria_id = cat.id  WHERE t.deletado = 0 AND cat.deletado = 0 ORDER BY t.data_cadastro DESC  LIMIT 15
ERROR - 2021-09-08 15:25:56 --> Severity: error --> Exception: Erro ao capturar os itens. /app/_estudos/codeigniter/fit/site/application/models/Home_model.php 41
ERROR - 2021-09-08 15:35:54 --> Query error: Table 'afiliado.categoria' doesn't exist - Invalid query: SELECT t.*, md5(t.id) as id_md5, cat.nome AS categoria_nome, md5(cat.id) as categoria_id FROM produtos t INNER JOIN categoria cat ON t.categoria_id = cat.id  WHERE t.deletado = 0 AND cat.deletado = 0 ORDER BY t.data_cadastro DESC  LIMIT 15
ERROR - 2021-09-08 15:35:54 --> Severity: error --> Exception: Erro ao capturar os itens. /app/_estudos/codeigniter/fit/site/application/models/Home_model.php 41
ERROR - 2021-09-08 15:36:08 --> Query error: Table 'afiliado.categoria' doesn't exist - Invalid query: SELECT md5(id) AS id_md5 ,nome FROM categoria
ERROR - 2021-09-08 15:36:08 --> Severity: error --> Exception: Call to a member function result_array() on boolean /app/_estudos/codeigniter/fit/site/application/helpers/utility_helper.php 11
