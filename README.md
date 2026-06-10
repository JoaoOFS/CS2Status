# CS2Status API

API Laravel para consultar eventos, fases, times, partidas, resultados e standings de CS2 em formato de fase suica.

Esta API nao calcula pontuacao de bolao e nao salva palpites de usuarios. Ela serve apenas como fonte de dados para o site do bolao.

Documentacao para integracao:

- [Documentacao da API CS2](docs/API_CS2.md)

Servidor local:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Base URL local:

```text
http://127.0.0.1:8000/api/cs2
```
