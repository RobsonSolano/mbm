<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendamentoModel extends Model
{
    protected $table            = 'agendamentos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cliente_id', 'responsavel_id', 'data', 'hora_inicio', 'hora_fim',
        'descricao', 'status', 'observacoes'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    protected $validationRules = [
        'cliente_id'   => 'required|integer',
        'data'         => 'required|valid_date',
        'hora_inicio'  => 'required',
        'descricao'    => 'required|min_length[3]',
    ];

    /**
     * Busca agendamentos de um dia (status agendado por padrão)
     */
    public function buscarPorDia(string $data, bool $incluirCancelados = false)
    {
        $builder = $this->where('data', $data)->orderBy('hora_inicio', 'ASC');
        if (!$incluirCancelados) {
            $builder->whereIn('status', ['agendado', 'concluido']);
        }
        return $builder->findAll();
    }

    /**
     * Conta agendamentos por dia no mês
     */
    public function contarPorDiaNoMes(int $ano, int $mes): array
    {
        $inicio = sprintf('%04d-%02d-01', $ano, $mes);
        $fim   = date('Y-m-t', strtotime($inicio));

        $db = \Config\Database::connect();
        $result = $db->table('agendamentos')
            ->select('data, COUNT(*) as total')
            ->where('data >=', $inicio)
            ->where('data <=', $fim)
            ->whereIn('status', ['agendado', 'concluido'])
            ->groupBy('data')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($result as $row) {
            $map[$row['data']] = (int) $row['total'];
        }
        return $map;
    }

    /**
     * Verifica conflito de horário (sobreposição)
     */
    public function temConflito(string $data, string $horaInicio, string $horaFim, ?int $excluirId = null): bool
    {
        $horaFim = $horaFim ?: date('H:i', strtotime($horaInicio . ' +1 hour'));
        $agendados = $this->where('data', $data)->where('status', 'agendado');
        if ($excluirId) {
            $agendados->where('id !=', $excluirId);
        }
        $agendados = $agendados->findAll();
        foreach ($agendados as $a) {
            $exEnd = $a['hora_fim'] ?: date('H:i', strtotime($a['hora_inicio'] . ' +1 hour'));
            if ($horaInicio < $exEnd && $horaFim > $a['hora_inicio']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Busca agendamentos de uma semana (seg–dom)
     */
    public function buscarPorSemana(string $dataInicio, string $dataFim): array
    {
        return $this->where('data >=', $dataInicio)
            ->where('data <=', $dataFim)
            ->whereIn('status', ['agendado', 'concluido'])
            ->orderBy('data ASC, hora_inicio ASC')
            ->findAll();
    }

    /**
     * Retorna slots ocupados em um dia (horários com conflito)
     */
    public function getSlotsOcupados(string $data): array
    {
        $agendamentos = $this->where('data', $data)->where('status', 'agendado')->findAll();
        $slots = self::getSlotsHorario();
        $ocupados = [];
        foreach ($slots as $slot) {
            $horaFim = date('H:i', strtotime($slot . ' +1 hour'));
            if ($this->temConflito($data, $slot, $horaFim, null)) {
                $ocupados[] = $slot;
            }
        }
        return $ocupados;
    }

    /**
     * Slots de 30min das 8h às 18h
     */
    public static function getSlotsHorario(): array
    {
        $slots = [];
        for ($h = 8; $h <= 18; $h++) {
            for ($m = 0; $m < 60; $m += 30) {
                if ($h == 18 && $m > 0) break;
                $slots[] = sprintf('%02d:%02d', $h, $m);
            }
        }
        return $slots;
    }
}
