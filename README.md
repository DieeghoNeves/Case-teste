## Requisitos

* PHP 8.2 ou superior
* Composer
* Redis (opcional — recomendado para filas em produção)

## Como rodar o projeto baixado

Duplicar o arquivo ".env.example" e renomear para ".env".<br>
Alterar no arquivo .env as credenciais do banco de dados, e ajustar as variáveis de fila se necessário (ex.: QUEUE_CONNECTION=database ou redis).<br>

## Instalar as dependências do PHP

## Instalar o composer
```
composer install
```

## instalar o sanctum
```
composer config -g repos.packagist composer https://packagist.org
composer clear-cache
composer require laravel/sanctum --no-interaction --prefer-dist
```
## Gerar a chave no arquivo .env
```
php artisan key:generate
```

## Executar as migration para criar a base de dados e as tabelas
```
php artisan migrate 
```

## Rodar o projeto laravel no Windows
```
php artisan serve
```

## Iniciar o processo da geração do token
Abra um cmd na pasta onde esta o projeto base do laravel e excute o comando abaixo
```
php artisan tinker
```

## Se tiver tudo certo, voce vai ver isso no terminal algo assim
Psy Shell v0.12.12 (PHP 8.2.12 — cli) by Justin Hileman

## Em seguida rode este comando
```
$user = \App\Models\User::where('email', 'tester@example.com')->first();
```
## Na sequencia finalize com este comando para imprimir a chave
```
$token = $user->createToken('api-token')->plainTextToken;
```

## End-Points testados no PostMan

* Criar pedidos
```
URL: http://127.0.0.1:8000/api/v1/orders 
Method: POST

Body
  "meta": {
    "notes": "Pedido de teste criado via API"
  },
  "items": [
    {
      "product_name": "Notebook Gamer Lenovo",
      "unit_price": 1000.90,
      "quantity": 1
    },
    {
      "product_name": "Placa de video RTX3050",
      "unit_price": 888.90,
      "quantity": 2
    }
  ]
}
```

* Cancelar pedidos
```
URL: http://127.0.0.1:8000/api/v1/orders/10/cancel
Method: POST

Retorno json
{
    "success": true,
    "status": "cancelled",
    "message": "Pedido já estava cancelado."
}
```

* Listar um pedido

```
URL: http://127.0.0.1:8000/api/v1/orders/4
Method: GET

Retorno Json

{
    "data": {
        "id": 4,
        "user_id": 1,
        "status": "cancelled",
        "total": null,
        "meta": {
            "notes": "Pedido de teste criado via API"
        },
        "created_at": "2025-10-15 11:21:00",
        "updated_at": "2025-10-15 11:22:05",
        "items": [
            {
                "id": 7,
                "product_name": "Teclado 2 Mecânico RGB",
                "unit_price": 299.9,
                "quantity": 1,
                "line_total": 299.9
            },
            {
                "id": 8,
                "product_name": "Mouse 3 Gamer",
                "unit_price": 199.9,
                "quantity": 2,
                "line_total": 399.8
            }
        ]
    }
}

```

* Listar todos os pedidos para paginação

```
URL: http://127.0.0.1:8000/api/v1/orders
Method: GET

Retorno Json
{
    "data": [
        {
            "id": 9,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:34:40",
            "updated_at": "2025-10-15 11:34:40",
            "items": [
                {
                    "id": 17,
                    "product_name": "Notebook Gamer",
                    "unit_price": 999.9,
                    "quantity": 1,
                    "line_total": 999.9
                },
                {
                    "id": 18,
                    "product_name": "Placa de video RTX5090",
                    "unit_price": 888.9,
                    "quantity": 2,
                    "line_total": 1777.8
                }
            ]
        },
        {
            "id": 8,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:34:15",
            "updated_at": "2025-10-15 11:34:15",
            "items": [
                {
                    "id": 15,
                    "product_name": "Notebook Gamer",
                    "unit_price": 999.9,
                    "quantity": 1,
                    "line_total": 999.9
                },
                {
                    "id": 16,
                    "product_name": "Placa de video RTX5090",
                    "unit_price": 888.9,
                    "quantity": 2,
                    "line_total": 1777.8
                }
            ]
        },
        {
            "id": 7,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:31:56",
            "updated_at": "2025-10-15 11:31:56",
            "items": [
                {
                    "id": 13,
                    "product_name": "Teclado 2 Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 14,
                    "product_name": "Mouse 3 Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 6,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:31:14",
            "updated_at": "2025-10-15 11:31:14",
            "items": [
                {
                    "id": 11,
                    "product_name": "Teclado 2 Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 12,
                    "product_name": "Mouse 3 Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 5,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:30:59",
            "updated_at": "2025-10-15 11:30:59",
            "items": [
                {
                    "id": 9,
                    "product_name": "Teclado 2 Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 10,
                    "product_name": "Mouse 3 Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 4,
            "user_id": 1,
            "status": "cancelled",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:21:00",
            "updated_at": "2025-10-15 11:22:05",
            "items": [
                {
                    "id": 7,
                    "product_name": "Teclado 2 Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 8,
                    "product_name": "Mouse 3 Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 3,
            "user_id": 1,
            "status": "cancelled",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 11:05:26",
            "updated_at": "2025-10-15 11:07:27",
            "items": [
                {
                    "id": 5,
                    "product_name": "Teclado 2 Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 6,
                    "product_name": "Mouse 3 Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 2,
            "user_id": 1,
            "status": "pending",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 10:59:40",
            "updated_at": "2025-10-15 10:59:40",
            "items": [
                {
                    "id": 3,
                    "product_name": "Teclado Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 4,
                    "product_name": "Mouse Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        },
        {
            "id": 1,
            "user_id": 1,
            "status": "cancelled",
            "total": null,
            "meta": {
                "notes": "Pedido de teste criado via API"
            },
            "created_at": "2025-10-15 01:59:19",
            "updated_at": "2025-10-15 10:47:16",
            "items": [
                {
                    "id": 1,
                    "product_name": "Teclado Mecânico RGB",
                    "unit_price": 299.9,
                    "quantity": 1,
                    "line_total": 299.9
                },
                {
                    "id": 2,
                    "product_name": "Mouse Gamer",
                    "unit_price": 199.9,
                    "quantity": 2,
                    "line_total": 399.8
                }
            ]
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/v1/orders?page=1",
        "last": "http://127.0.0.1:8000/api/v1/orders?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/v1/orders?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/v1/orders",
        "per_page": 15,
        "to": 9,
        "total": 9,
        "status_filter": null
    }
}
```
