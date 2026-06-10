# Documentacao da API CS2

Esta API fornece dados organizados de eventos de CS2, com foco em fases suicas: eventos, stages, times, partidas, mapas, resultados e classificacao dos times dentro da fase.

## Base URL

Local:

```text
http://127.0.0.1:8000/api/cs2
```

Producao:

```text
https://SEU_DOMINIO/api/cs2
```

## Autenticacao

A API aceita um token Bearer opcional configurado no backend via:

```env
CS2_API_CONSUMER_TOKEN=token_do_consumidor
```

Se `CS2_API_CONSUMER_TOKEN` estiver vazio, as rotas ficam sem bloqueio por token. Se estiver preenchido, toda requisicao deve enviar:

```http
Authorization: Bearer token_do_consumidor
Accept: application/json
```

Resposta para token invalido:

```json
{
  "success": false,
  "message": "Invalid API consumer.",
  "data": null
}
```

## Padrao de resposta

Sucesso:

```json
{
  "success": true,
  "data": {}
}
```

Erro de registro nao encontrado:

```json
{
  "success": false,
  "message": "Match not found.",
  "data": null
}
```

## Conceitos

### Fase suica

Na fase suica, cada time joga ate atingir 3 vitorias ou 3 derrotas.

Registros finais de classificados:

```text
3-0
3-1
3-2
```

Registros finais de eliminados:

```text
2-3
1-3
0-3
```

Registros intermediarios:

```text
0-0
1-0
0-1
2-0
1-1
0-2
2-1
1-2
2-2
```

Status possiveis de um time na fase:

```text
active
qualified
eliminated
```

### TBD

`TBD` significa `To Be Determined`, ou seja, ainda nao definido.

Em fases suicas e playoffs, e comum a fonte externa ja informar data e horario da partida, mas ainda nao informar os times porque os confrontos dependem dos resultados anteriores.

Na API, isso normalmente aparece como:

```json
{
  "team_one": null,
  "team_two": null
}
```

O frontend pode exibir como:

```text
TBD vs TBD
```

## Endpoints

### Listar eventos

```http
GET /events
```

Parametros opcionais:

```text
source=pandascore
status=scheduled|in_progress|completed
per_page=15
```

Exemplo:

```http
GET /api/cs2/events?source=pandascore&per_page=20
```

Resposta:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "source": "pandascore",
      "external_id": "10488",
      "name": "Cologne Major 2026",
      "slug": "cs-go-iem-cologne-major-2026",
      "region": "IEM",
      "starts_on": "2026-06-02",
      "ends_on": "2026-06-21",
      "metadata": {
        "league_id": 4161,
        "year": 2026
      }
    }
  ]
}
```

Campos importantes:

```text
id          ID interno da API, usado nas rotas locais
external_id ID da fonte externa, por exemplo PandaScore
source      Fonte do dado
metadata    Dados extras da fonte externa
```

### Detalhar evento

```http
GET /events/{event}
```

Exemplo:

```http
GET /api/cs2/events/1
```

### Listar fases de um evento

```http
GET /events/{event}/stages
```

Parametros opcionais:

```text
format=swiss|bracket
status=scheduled|in_progress|completed
```

Exemplo:

```http
GET /api/cs2/events/1/stages
```

Resposta:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "event_id": 1,
      "source": "pandascore",
      "external_id": "21115",
      "name": "Stage 3",
      "slug": "cs-go-iem-cologne-major-2026-stage-3",
      "format": "swiss",
      "status": "scheduled",
      "starts_at": "2026-06-11T09:00:00.000000Z",
      "ends_at": "2026-06-15T21:00:00.000000Z",
      "metadata": {
        "tier": "s",
        "region": "WEU",
        "live_supported": true
      }
    }
  ]
}
```

### Listar times de uma fase

```http
GET /stages/{stage}/teams
```

Exemplo:

```http
GET /api/cs2/stages/1/teams
```

Resposta:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "stage_id": 1,
      "team": {
        "id": 1,
        "source": "pandascore",
        "external_id": "3272",
        "name": "TheMongolz",
        "slug": "themongolz",
        "country": "MN",
        "logo_url": "https://cdn-api.pandascore.co/images/team/image/3272/...",
        "metadata": {
          "acronym": "MGLZ"
        }
      },
      "wins": 0,
      "losses": 0,
      "record": "0-0",
      "status": "active",
      "metadata": null
    }
  ]
}
```

### Listar partidas de uma fase

```http
GET /stages/{stage}/matches
```

Parametros opcionais:

```text
status=scheduled|live|completed
round=1
record_group=1-1
per_page=25
```

Exemplos:

```http
GET /api/cs2/stages/1/matches
GET /api/cs2/stages/1/matches?status=scheduled&per_page=100
GET /api/cs2/stages/1/matches?round=1
```

Resposta:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "event_id": 1,
      "stage_id": 1,
      "source": "pandascore",
      "external_id": "1513126",
      "round": 1,
      "record_group": null,
      "best_of": "bo3",
      "status": "scheduled",
      "starts_at": "2026-06-11T09:00:00.000000Z",
      "team_one": {
        "id": 1,
        "name": "TheMongolz",
        "external_id": "3272"
      },
      "team_two": {
        "id": 2,
        "name": "BetBoom Team",
        "external_id": "133458"
      },
      "winner_team": null,
      "team_one_score": 0,
      "team_two_score": 0,
      "maps": [],
      "metadata": {
        "name": "Round 1: MGLZ vs BB",
        "streams": [
          {
            "language": "en",
            "official": true,
            "raw_url": "https://www.twitch.tv/ESLCS"
          }
        ]
      }
    }
  ]
}
```

Campos importantes:

```text
starts_at       Horario em UTC. O frontend deve converter para o timezone do usuario.
best_of         bo1, bo3, bo5 etc.
status          scheduled, live ou completed.
team_one/two    Pode ser null quando o confronto ainda nao foi definido.
record_group    Grupo suico quando a fonte externa informa, por exemplo 1-1 ou 2-2.
metadata.name   Nome original da partida na fonte externa.
```

### Detalhar partida

```http
GET /matches/{match}
```

Exemplo:

```http
GET /api/cs2/matches/1
```

Resposta inclui dados da partida e mapas quando a fonte externa fornece.

Exemplo de mapa:

```json
{
  "id": 10,
  "order": 1,
  "map_name": "Mirage",
  "winner_team": null,
  "team_one_score": null,
  "team_two_score": null,
  "status": "scheduled",
  "metadata": {
    "begin_at": null,
    "end_at": null,
    "length": null,
    "complete": false,
    "forfeit": false
  }
}
```

### Consultar standings de uma fase

```http
GET /stages/{stage}/standings
```

Exemplo:

```http
GET /api/cs2/stages/1/standings
```

Resposta:

```json
{
  "success": true,
  "data": [
    {
      "record": "0-0",
      "status": "active",
      "teams": [
        {
          "team": {
            "id": 1,
            "name": "TheMongolz"
          },
          "wins": 0,
          "losses": 0,
          "record": "0-0",
          "status": "active"
        }
      ]
    }
  ]
}
```

## Status de partidas

Status normalizados pela API:

```text
scheduled  Partida agendada ou ainda nao iniciada
live       Partida em andamento
completed  Partida finalizada
```

## IDs atuais do IEM Cologne Major 2026 no banco local

Apos a sincronizacao feita durante o desenvolvimento:

```text
Evento: Cologne Major 2026
event_id interno: 1
external_id PandaScore: 10488

Stage 3:
stage_id interno: 1
external_id PandaScore: 21115

Playoffs:
stage_id interno: 2
external_id PandaScore: 20710
```

Esses IDs internos podem mudar se o banco for recriado. Para integracao robusta, o frontend pode primeiro buscar `/events`, depois `/events/{event}/stages`, e usar os IDs retornados.

## Fluxo recomendado para o site do bolao

1. Buscar eventos:

```http
GET /api/cs2/events
```

2. Selecionar o evento desejado.

3. Buscar fases do evento:

```http
GET /api/cs2/events/{event}/stages
```

4. Buscar partidas da fase:

```http
GET /api/cs2/stages/{stage}/matches?per_page=100
```

5. Buscar standings da fase suica:

```http
GET /api/cs2/stages/{stage}/standings
```

6. O site do bolao calcula pontuacao usando suas proprias regras.

## Sincronizacao com PandaScore

O backend usa PandaScore como fonte real de dados.

Variaveis de ambiente:

```env
PANDASCORE_BASE_URL=https://api.pandascore.co
PANDASCORE_TOKEN=token_da_pandascore
```

Sincronizar evento especifico:

```bash
php artisan cs2:sync-events --provider=pandascore --serie-id=10488
```

Sincronizar partidas futuras do evento:

```bash
php artisan cs2:sync-upcoming-matches --provider=pandascore --serie-id=10488 --per-page=100
```

Sincronizar partidas ao vivo:

```bash
php artisan cs2:sync-live-matches --provider=pandascore --serie-id=10488 --per-page=100
```

Sincronizar resultados:

```bash
php artisan cs2:sync-results --provider=pandascore --serie-id=10488 --per-page=100
```

Recalcular campanhas suicas de uma fase:

```bash
php artisan cs2:recalculate-swiss-records {stage_id}
```

Exemplo:

```bash
php artisan cs2:recalculate-swiss-records 1
```

## Automatizacao da sincronizacao

Para nao rodar os comandos manualmente, o projeto usa o scheduler do Laravel.

Configuracao no `.env`:

```env
CS2_DEFAULT_SERIE_ID=10488
CS2_SWISS_STAGE_IDS=1
```

Tarefas agendadas atualmente:

```text
A cada 1 hora      cs2:sync-events
A cada 10 minutos  cs2:sync-upcoming-matches
A cada 1 minuto    cs2:sync-live-matches
A cada 5 minutos   cs2:sync-results
A cada 5 minutos   cs2:recalculate-swiss-records
```

Em ambiente local, para deixar o scheduler rodando durante o desenvolvimento:

```bash
php artisan schedule:work
```

Em servidor de producao, configure um cron para chamar o scheduler do Laravel a cada minuto:

```cron
* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

Com isso, o Laravel decide internamente quais tarefas devem executar em cada minuto.

## Observacoes importantes para integracao

- `starts_at` sempre deve ser tratado como UTC.
- `team_one` e `team_two` podem ser `null` enquanto a partida estiver como TBD.
- O frontend nao deve depender de nomes como `Stage 3` para identificar dados; use os IDs retornados pela API.
- `external_id` e `source` existem para rastrear a fonte externa e evitar duplicidade.
- A API pode receber atualizacoes repetidas da PandaScore; o backend usa `source + external_id` para atualizar registros existentes.
- A API nao deve receber palpites nem calcular pontuacao do bolao.

## Exemplos rapidos com cURL

```bash
curl -H "Accept: application/json" \
  http://127.0.0.1:8000/api/cs2/events
```

```bash
curl -H "Accept: application/json" \
  http://127.0.0.1:8000/api/cs2/events/1/stages
```

```bash
curl -H "Accept: application/json" \
  "http://127.0.0.1:8000/api/cs2/stages/1/matches?per_page=100"
```

Com Bearer token:

```bash
curl -H "Accept: application/json" \
  -H "Authorization: Bearer token_do_consumidor" \
  http://127.0.0.1:8000/api/cs2/events
```
