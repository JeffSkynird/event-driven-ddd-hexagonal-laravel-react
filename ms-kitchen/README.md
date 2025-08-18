## Levantamiento del proyecto (DOCKER)

### Requisitos
Docker 

### 1. Inicia el entorno
Asegurate de haber creado la red ms-network

```bash
docker network create ms-network
```

### 2. Ejecuta el comando para iniciar los contenedores

```bash
docker-compose up --build
```

### 3. Ejecuta el siguiente comando para instalar las dependencias
```bash
docker exec ms-kitchen-ms-kitchen-1 composer install
```

### 4. Ejecutar el siguiente comando para ejecutar las migraciones necesarias

```bash
docker exec ms-kitchen-ms-kitchen-1 php artisan migrate
```

### 5. Inicia los listeners de las colas RABBITMQ

ms-kitchen

```bash
docker exec ms-kitchen-ms-kitchen-1 php artisan inventory-response:listen
```
```bash
docker exec ms-kitchen-ms-kitchen-1 php artisan inventory-ready:listen
```

## Pruebas unitarias
Si se desea ejecutar las pruebas unitarias se debe ejecutar el siguiente comando
```bash
./vendor/bin/pest
```

### :) Listo!
