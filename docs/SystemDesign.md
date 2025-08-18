***

# Análisis y Diseño Técnico de Requerimientos de Software

El propósito de este documento es registrar el análisis y diseño técnico de los requerimientos de software del aplicativo.

---

## ANÁLISIS DE ESTIMACIÓN

### Número de usuarios concurrentes
Dado que se trata de una jornada de donación de alimentos gratuita, es probable que el tráfico de usuarios sea elevado, pero temporal. Se supone lo siguiente:

* **Eventos por hora:** La jornada de donación puede durar aproximadamente 4 horas.
* **Usuarios concurrentes:** Se asume que hay 200 usuarios por minuto que acceden al sistema para pedir platos, lo que da:
    * `200 usuarios/min × 60 min/hora = 12,000 usuarios/hora`
* **Usuarios totales:** Si el evento dura 4 horas:
    * `12,000 usuarios/hora × 4 horas = 48,000 usuarios totales`

### Número de pedidos esperados
Cada usuario hará 1 pedido, pero algunos podrían hacer múltiples pedidos. Se supone que en promedio cada usuario realiza 1.5 pedidos:

* `48,000 usuarios × 1.5 pedidos por usuario = 72,000 pedidos totales`

Esto da un estimado de **72,000 pedidos** que se enviarán a la cocina a lo largo de las 4 horas.

### Capacidad de la cocina
Se asume que la cocina puede preparar un plato cada 2 minutos. El número de usos de la cocina para preparar platos depende del número de pedidos concurrentes.

Si la cocina recibe 200 pedidos por minuto, se necesita un sistema capaz de atender varios pedidos a la vez. Si cada estación de cocina procesa un plato en 2 minutos, se necesita al menos **100 estaciones virtuales de cocina** operando en paralelo para cumplir con la demanda.

### Espacio de almacenamiento (Base de datos e inventario)
* **Recetas:** Si se tiene 6 recetas, cada receta incluye múltiples ingredientes. Se guarda información como nombre del plato, ingredientes y cantidades. Suponiendo que cada receta ocupe aproximadamente 1 KB de espacio:
    * `6 recetas × 1 KB = 6 KB para recetas`
* **Historial de pedidos:** Suponiendo que cada pedido guarde ID del pedido, plato seleccionado, ingredientes utilizados, estado y hora del pedido. Cada pedido puede ocupar alrededor de 2 KB:
    * `72,000 pedidos × 2 KB/pedido = 144,000 KB = 144 MB`
* **Inventario de ingredientes:** Suponiendo 10 tipos de ingredientes y que cada entrada de inventario ocupe 0.5 KB:
    * `10 ingredientes × 0.5 KB = 5 KB para inventario`
* **Historial de compras en el mercado:** Si se asume que cada compra ocupa 0.5 KB y cada ingrediente se repone 5 veces:
    * `10 ingredientes × 5 compras × 0.5 KB = 25 KB`

**Total de almacenamiento:**
* **Recetas:** 6 KB
* **Pedidos:** 144 MB
* **Inventario:** 5 KB
* **Historial de compras:** 25 KB
* **Estimación total:** Alrededor de **144 MB** de almacenamiento.

### Consumo de red y uso de la API externa
El microservicio de inventarios necesitará comunicarse con la plaza de mercado. Se supone que el sistema hace 200 solicitudes por minuto a la API. Cada solicitud y respuesta puede ocupar unos 0.5 KB:

* **Total de solicitudes:** `200 solicitudes/min × 60 min/hora × 4 horas = 48,000 solicitudes`
* **Datos totales transferidos:** `48,000 solicitudes × 0.5 KB = 24,000 KB = 24 MB`
* **Total de consumo de red esperado:** Aproximadamente **24 MB**.

### Recursos de servidor
* **Cocina:** Se necesita al menos 100 procesos concurrentes (contenedores Docker).
* **Inventario y compras:** Podrían ejecutarse con menos recursos (aproximadamente 5 procesos concurrentes).
* **Interfaz gráfica:** Necesita recursos escalables para manejar entre 200-300 conexiones concurrentes.
* **Base de datos:** Debe ser capaz de manejar 72,000 inserciones, gestionable en una base de datos relacional optimizada.

---

## ARQUITECTURA

### Infraestructura
Dada la necesidad de escalamiento y manejo de gran tráfico, se usa una infraestructura basada en microservicios:

* **Microservicio de Cocina (ms-kitchen):** Maneja órdenes, recetas y creación de platos.
* **Microservicio de Inventario (ms-inventories):** Maneja el inventario, stock y despachos.
* **Microservicio de compras (ms-purchases):** Realiza las compras a la plaza.

Se aplican los siguientes patrones de diseño a la infraestructura:

* **Abstracción de microtareas:** Desglose de procesos principales en subprocesos de granularidad fina.
* **Segregación de microtareas:** Cada microservicio tendrá acceso total sobre su base de datos.
* **Base de datos dedicada:** Cada microservicio tiene su propia base de datos para garantizar aislamiento.
* **API Gateway / Proxy:** Punto de entrada único para balanceo de carga y gestión de escalamiento.

### Tecnologías

| # | Tecnología | Comentario |
|---|---|---|
| 1 | Laravel 11 | Para el backend en cada microservicio. |
| 2 | PostgreSQL | Para cumplir reglas ACID y soportar bases de datos dedicadas. |
| 3 | RabbitMQ | Como message broker para comunicación asincrónica. |
| 4 | Restful | Para comunicación HTTP sincrónica. |
| 5 | Amazon MQ | Para servir el motor RabbitMQ desde AWS. |
| 6 | AWS API Gateway | Para servir de proxy entre el frontend y los microservicios. |
| 7 | Heroku Workers | Se escalan para trabajar en paralelo los listeners del message broker. |
| 8 | Contenerización | Se usa Docker para aislar microservicios y bases de datos. |

---

## COLAS DE MESSAGE BROKER

Se crearon diferentes colas para cada conexión para garantizar la interoperabilidad.

* **ms-kitchen:**
    * `inventory_response_queue`: Escucha el estado del stock de un ingrediente.
    * `kitchen_order_queue`: Escucha si se realizó el reabastecimiento de ingredientes.
* **ms-inventories:**
    * `inventory_request_queue`: Escucha nuevas órdenes que requieren comprobar stock.
    * `purchase_response_queue`: Escucha si se realizó una compra de ingredientes.
* **ms-purchases:**
    * `purchase_request_queue`: Escucha si es necesario realizar una nueva compra.

---

## CÓDIGO

A nivel de código, se optó por una arquitectura **DDD (Domain-Driven Design)** con principios de **Clean Architecture**.

| Microservicio | Patrones de diseño | Comentario |
|---|---|---|
| **ms-kitchen** | **Factory Method** | Para generar diferentes tipos de platos aleatoriamente. |
| | **Command** | Una nueva orden se envía como un "comando" a la cocina. |
| | **Observer** | Para notificar cuando un plato está listo o faltan ingredientes. |
| **ms-inventories**| **Repository** | Para desacoplar la lógica de negocios de los datos. |
| | **Command** | Para recibir las peticiones de despacho de ingredientes. |
| | **Observer** | Para notificar cuando los ingredientes están listos o requieren compra. |
| **ms-purchases**| **Adapter** | Para adaptar las respuestas de la API externa al formato interno. |
| | **Strategy** | Para manejar diferentes estrategias de compra si la plaza cambia. |
| | **Circuit Breaker** | Para manejar fallos de la API externa y evitar el colapso. |

---

## ESTADOS

### Órdenes
Las órdenes tienen los siguientes estados para manejar la trazabilidad:

* **preparing:** Estado inicial al generar una nueva orden en `ms-kitchen`. Cambia cuando `ms-inventories` actúa.
* **restocking:** Se activa cuando no hay stock suficiente. `ms-inventories` envía una petición de compra a `ms-purchases`.
* **error:** Se establece si ocurre un error, principalmente si la API de compras externa no está disponible. Permite un reintento.
* **completed:** Estado final. Se activa cuando los ingredientes del inventario abastecen la orden y se despachan correctamente.