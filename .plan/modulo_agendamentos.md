# Plano: Módulo de Agendamentos

**Objetivo:** Módulo de agendamentos com UX inspirada no Google Calendar, permitindo visualizar, criar e gerenciar compromissos por dia/semana.

---

## 1. Modelo de Dados

### Tabela `agendamentos`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT PK | |
| cliente_id | INT FK | → clientes.id |
| data | DATE | Dia do agendamento |
| hora_inicio | TIME | Horário início |
| hora_fim | TIME | Horário fim (opcional, ou derivar de duração padrão) |
| descricao | TEXT | O que precisa ser feito |
| status | ENUM | `agendado`, `concluido`, `cancelado` (default: agendado) |
| observacoes | TEXT | Observações internas |
| criado_em | DATETIME | |
| atualizado_em | DATETIME | |

**Índices:** (data), (cliente_id, data), (data, hora_inicio) para evitar conflitos.

**Validação:** Não permitir sobreposição de horários no mesmo dia (ou definir slots fixos).

---

## 2. UX/UI – Fluxo Principal (estilo Google Calendar)

### 2.1 Tela inicial – visualização mensal
- **Layout:** Grid de calendário (7 colunas: seg–dom), linhas por semana do mês
- **Cabeçalho:** Mês/Ano + setas anterior/próximo
- **Cada célula:** Número do dia + badge com quantidade de agendamentos (ex: `3`)
- **Interação:** Clique no dia → abre painel lateral ou modal com lista do dia
- **Destaque:** Sábado/Domingo com cor suave diferente (ex: fundo cinza-claro)

### 2.2 Alternância de visualização
- **Tabs ou botões:** `Mês` | `Semana` | `Dia`
- **Mês:** Grid mensal (como acima)
- **Semana:** 7 colunas (dias da semana), cada coluna com slots de hora (8h–18h ou configurável)
- **Dia:** Lista vertical do dia selecionado, estilo agenda

### 2.3 Ao clicar no dia
- **Painel lateral direito** (ou modal responsivo em mobile):
  - Título: “Agendamentos – 15/03/2026”
  - Lista ordenada por hora
  - Cada item: hora, cliente, descrição curta, ações (editar, cancelar)
  - Botão: **+ Novo agendamento**

### 2.4 Formulário – Novo/Editar agendamento
- **Campos:**
  - Data (pré-preenchida se veio do clique no dia)
  - Horário início (select ou input time)
  - Horário fim (ou duração padrão ex: 1h)
  - Cliente (select com busca/autocomplete)
  - Descrição (textarea)
  - Observações (opcional)
- **Feedback:** Ao escolher data/hora, indicar se o slot está livre ou ocupado (ex: “Disponível” / “Conflito com…”)

---

## 3. Componentes de Interface

| Componente | Descrição |
|-------------|-----------|
| Calendário mensal | Grid 7x(4–6), células clicáveis |
| Calendário semanal | Grade de dias x horas |
| Lista do dia | Cards ou lista com horário, cliente, descrição |
| Modal/Painel lateral | Detalhes do dia + lista + novo agendamento |
| Formulário agendamento | Form com validação em tempo real |

---

## 4. Rotas e Controller

| Rota | Método | Função |
|------|--------|--------|
| /admin/agendamentos | GET | Página principal (calendário) |
| /admin/agendamentos/dia/{date} | GET | Lista do dia (pode retornar JSON para AJAX) |
| /admin/agendamentos/novo | GET | Form novo (com ?data=YYYY-MM-DD) |
| /admin/agendamentos/salvar | POST | Criar/atualizar |
| /admin/agendamentos/{id}/editar | GET | Form edição |
| /admin/agendamentos/{id}/excluir | POST | Cancelar (status) |
| /admin/agendamentos/api/mes | GET | JSON: agendamentos do mês (para renderizar grid) |
| /admin/agendamentos/api/dia | GET | JSON: agendamentos do dia |
| /admin/agendamentos/api/slots | GET | JSON: slots disponíveis de um dia |

---

## 5. Regras de Negócio

1. **Slots de horário:** Definir faixa padrão (ex: 8h–18h, blocos de 30min ou 1h).
2. **Conflitos:** Não permitir 2 agendamentos no mesmo horário/slot.
3. **Cliente:** Somente clientes ativos (não bloqueados, não deletados).
4. **Status:** `agendado` exibido normalmente; `concluido` e `cancelado` em lista separada ou filtro.
5. **Fuso horário:** Usar `America/Sao_Paulo` conforme o restante do sistema.

---

## 6. Estrutura de Arquivos

```
app/
├── Models/
│   └── AgendamentoModel.php
├── Controllers/
│   └── Admin.php (métodos agendamentos*)
├── Views/admin/
│   ├── agendamentos.php          # Tela principal (calendário)
│   ├── agendamento_form.php      # Form novo/editar
│   └── agendamento_dia.php      # Lista do dia (ou partial)
└── Database/Migrations/
    └── YYYY-MM-DD_HHMMSS_Create_agendamentos_table.php
```

---

## 7. Fases de Implementação

### Fase 1 – Base (prioridade alta)
1. Migration: tabela `agendamentos`
2. AgendamentoModel
3. Rotas e métodos básicos no Admin
4. Listagem por dia (visualização “Dia”)
5. Formulário novo/editar agendamento
6. Inclusão no menu lateral

### Fase 2 – Calendário mensal
1. View do calendário mensal (HTML/CSS)
2. API ou dados para contar agendamentos por dia
3. Clique no dia → abrir lista do dia (modal ou painel)
4. Setas mês anterior/próximo

### Fase 3 – Calendário semanal
1. View da semana (dias x horas)
2. Exibir agendamentos nos slots
3. Clique no dia → lista do dia

### Fase 4 – Refino de UX
1. Indicador de slots ocupados no formulário
2. Autocomplete no select de cliente
3. Responsividade e ajustes visuais
4. Atalhos (ex: “Hoje”)

---

## 8. Referências de UX (Google Calendar)

- Navegação intuitiva mês/semana/dia
- Cores suaves para fins de semana
- Badge com quantidade por dia
- Clique no dia abre detalhes sem sair da tela
- Formulário rápido (data, hora, cliente, descrição)
- Feedback visual de conflitos

---

## 9. Considerações Técnicas

- **CSS:** Bootstrap 5 já utilizado; calendário pode usar CSS Grid ou Flexbox.
- **JS:** Vanilla ou jQuery leve para navegação de mês e clique nos dias.
- **Duração padrão:** 1h se não informado `hora_fim`.
- **Paginação:** Não necessária no calendário; lista do dia terá scroll se houver muitos itens.
