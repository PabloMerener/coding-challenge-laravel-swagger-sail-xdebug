# tournaments

## Requirements

<a href="/challenge.pdf">Challenge</a>

Docker

## Instalation

```sh
git clone https://github.com/PabloMerener/tournament.git && cd tournament
```
```sh
cp .env.example .env
```

```sh
composer install
```

- Sail

Liberar los puertos 80 y 3306

```sh
vendor/bin/sail up -d

vendor/bin/sail artisan key:generate

vendor/bin/sail artisan migrate --seed

```

- Con base en una lista de jugadores, retorna el resultado del torneo.

Ejecutar un GET a http://127.0.0.1:80/tournaments/test con el siguiente body

```sh
{
  "gender": "male",
  "players": [
    {
      "name": "Ilie Năstase",
      "skill_level": 40,
      "strength": 40,
      "speed": 40
    },
    {
      "name": "Brian Gottfried",
      "skill_level": 90,
      "strength": 90,
      "speed": 90
    }
  ]
}
```
Se debería obtener un resultado como el siguiente:

```sh
{
  "winner": "Brian Gottfried",
  "games": [
    {
      "player1": {
        "name": "Ilie Năstase",
        "score": 32.2
      },
      "player2": {
        "name": "Brian Gottfried",
        "score": 83.6
      },
      "winner": "Brian Gottfried"
    }
  ]
}
```
- Consultar el resultado de los torneos finalizados exitosamente filtrando por año y/o género

Ejemplo: http://127.0.0.1/tournaments/results?year=1977&gender=male

Resultado,

```sh
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Roland Garros",
      "gender": "male",
      "date": "1977-05-23",
      "winner": 6,
      "games": [
        {
          "id": 1,
          "tournament_id": 1,
          "player1": {
            "id": 1,
            "name": "Ilie Năstase",
            "gender": "male"
          },
          "score1": "51.00",
          "player2": {
            "id": 2,
            "name": "Brian Gottfried",
            "gender": "male"
          },
          "score2": "87.60"
        },
        {
          "id": 3,
          "tournament_id": 1,
          "player1": {
            "id": 2,
            "name": "Brian Gottfried",
            "gender": "male"
          },
          "score1": "72.60",
          "player2": {
            "id": 3,
            "name": "Phil Dent",
            "gender": "male"
          },
          "score2": "58.20"
        },
        {
          "id": 7,
          "tournament_id": 1,
          "player1": {
            "id": 2,
            "name": "Brian Gottfried",
            "gender": "male"
          },
          "score1": "85.40",
          "player2": {
            "id": 6,
            "name": "Guillermo Vilas",
            "gender": "male"
          },
          "score2": "89.80"
        },
        {
          "id": 2,
          "tournament_id": 1,
          "player1": {
            "id": 3,
            "name": "Phil Dent",
            "gender": "male"
          },
          "score1": "58.80",
          "player2": {
            "id": 4,
            "name": "José Higueras",
            "gender": "male"
          },
          "score2": "32.20"
        },
        {
          "id": 4,
          "tournament_id": 1,
          "player1": {
            "id": 5,
            "name": "Wojtek Fibak",
            "gender": "male"
          },
          "score1": "50.60",
          "player2": {
            "id": 6,
            "name": "Guillermo Vilas",
            "gender": "male"
          },
          "score2": "94.20"
        },
        {
          "id": 6,
          "tournament_id": 1,
          "player1": {
            "id": 6,
            "name": "Guillermo Vilas",
            "gender": "male"
          },
          "score1": "97.20",
          "player2": {
            "id": 7,
            "name": "Raúl Ramírez",
            "gender": "male"
          },
          "score2": "71.00"
        },
        {
          "id": 5,
          "tournament_id": 1,
          "player1": {
            "id": 7,
            "name": "Raúl Ramírez",
            "gender": "male"
          },
          "score1": "72.00",
          "player2": {
            "id": 8,
            "name": "Adriano Panatta",
            "gender": "male"
          },
          "score2": "59.00"
        }
      ]
    }
  ],
  "first_page_url": "http://127.0.0.1/tournaments/results?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://127.0.0.1/tournaments/results?page=1",
  "links": [
    {
      "url": null,
      "label": "&laquo; Previous",
      "active": false
    },
    {
      "url": "http://127.0.0.1/tournaments/results?page=1",
      "label": "1",
      "active": true
    },
    {
      "url": null,
      "label": "Next &raquo;",
      "active": false
    }
  ],
  "next_page_url": null,
  "path": "http://127.0.0.1/tournaments/results",
  "per_page": 8,
  "prev_page_url": null,
  "to": 1,
  "total": 1
}
```
