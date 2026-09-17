<?php

namespace App\Controllers;

use App\Models\AgendamentoModel;
use App\Models\FuncionarioModel;
use App\Models\ClienteModel;

class Cron extends BaseController
{
    /**
     * Endpoint para cron-job.org
     * GET /cron/lembretes?token=SEU_TOKEN
     */
    public function lembretes()
    {
        $token = env('cron.token', '');
        $tokenRecebido = $this->request->getGet('token') ?? '';

        if (empty($token) || !hash_equals($token, $tokenRecebido)) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $resultado = $this->dispararLembretes();

        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setBody($resultado);
    }

    /**
     * Rota de teste — somente admin logado
     * GET /admin/testar-lembretes
     */
    public function testar()
    {
        if (!session()->get('admin_logado')) {
            return redirect()->to(base_url('admin/login'));
        }

        $resultado = $this->dispararLembretes();

        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setBody($resultado);
    }

    // ---------------------------------------------------------------

    private function dispararLembretes(): string
    {
        helper('email_helper');

        $agendamentoModel = new AgendamentoModel();
        $funcionarioModel = new FuncionarioModel();
        $clienteModel     = new ClienteModel();

        $hoje         = date('Y-m-d');
        $sep          = str_repeat('=', 53);

        $agendamentos = $agendamentoModel
            ->where('data', $hoje)
            ->where('status', 'agendado')
            ->orderBy('hora_inicio', 'ASC')
            ->findAll();

        if (empty($agendamentos)) {
            $corpo = implode("\n", [
                $sep,
                '[' . date('Y-m-d H:i:s') . '] Nenhum agendamento para hoje (' . date('d/m/Y') . ')',
                $sep,
            ]);
            $this->salvarLog($hoje, $corpo);
            log_message('info', '[cron] Nenhum agendamento para hoje (' . $hoje . ')');
            return $corpo;
        }

        // Agrupar por responsável
        $porResponsavel = [];
        foreach ($agendamentos as $ag) {
            $rid = $ag['responsavel_id'] ?? 1;
            $porResponsavel[$rid][] = $ag;
        }

        $linhas = [
            $sep,
            '[' . date('Y-m-d H:i:s') . '] Data: ' . date('d/m/Y') . ' | ' . count($agendamentos) . ' agendamento(s)',
            '',
        ];

        foreach ($porResponsavel as $responsavelId => $lista) {
            $responsavel = $funcionarioModel->find($responsavelId);

            if (!$responsavel || empty($responsavel['email'])) {
                $linhas[] = "  Responsável ID {$responsavelId}: sem e-mail, ignorado.";
                continue;
            }

            // Montar lista com dados do cliente
            $itens = [];
            foreach ($lista as $ag) {
                $cliente  = $clienteModel->find($ag['cliente_id']);
                $itens[] = [
                    'hora_inicio'  => $ag['hora_inicio'],
                    'hora_fim'     => $ag['hora_fim'] ?? '',
                    'descricao'    => $ag['descricao'],
                    'observacoes'  => $ag['observacoes'] ?? '',
                    'cliente_nome' => $cliente['nome_completo'] ?? 'N/A',
                    'endereco'     => $cliente['endereco'] ?? '',
                    'cidade'       => $cliente['cidade'] ?? '',
                ];
            }

            $enviado = send_email_lembrete_diario(
                $responsavel['email'],
                $responsavel['nome'],
                $itens,
                $hoje
            );

            $status   = $enviado ? '✓ ENVIADO' : '✗ FALHOU';
            $linhas[] = "  {$responsavel['nome']} <{$responsavel['email']}> — {$status} (" . count($itens) . " agendamento(s))";
        }

        $linhas[] = $sep;

        $corpo = implode("\n", $linhas);
        $this->salvarLog($hoje, $corpo);

        return $corpo;
    }

    private function salvarLog(string $data, string $conteudo): void
    {
        $dir = WRITEPATH . 'logs/agendamentos/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents(
            $dir . 'lembrete_' . $data . '.log',
            $conteudo . PHP_EOL . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}
