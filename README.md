
# Proyecto DDD, Hexagonal, Event-Driven
Proyecto con arquitectura de microservicios usando Laravel (API backend) y React (frontend). 

## Información
Implementa RabbitMQ para mensajería, siguiendo principios de DDD y arquitectura Hexagonal para lograr escalabilidad, desacoplamiento y mantenibilidad.

## Importante
Antes de iniciar los microservicios, el frontend y el message broker con Docker, es necesario crear una red para la conectividad entre ellos:

```bash
docker network create ms-network
```

## Tecnologías Utilizadas
1. **Laravel 11** - Backend
2. **React JS** - Frontend
3. **PostgreSQL** - Base de datos
4. **RabbitMQ** - Message Broker
5. **Amazon AWS API Gateway** - Proxy para acceso centralizado a los microservicios
6. **Amazon MQ** - Motor para ejecutar RabbitMQ en AWS
7. **Heroku Workers** - Para ejecutar en segundo plano los listeners de RabbitMQ

## Arquitectura

### Infraestructura
Debido a la necesidad de escalar el sistema para manejar concurrencia y alto tráfico, se ha optado por una infraestructura basada en microservicios, con los siguientes componentes:

- **Microservicio de Cocina (`ms-kitchen`)**: Maneja las órdenes, recetas y la creación de platos.
- **Microservicio de Inventario (`ms-inventories`)**: Gestiona el inventario, el stock y el historial de despachos.
- **Microservicio de Compras (`ms-purchases`)**: Realiza compras a proveedores en caso de faltantes de ingredientes.

### Patrones de Diseño Aplicados
1. **Abstracción de microtareas**: Los procesos principales se dividen en subtareas de gran granularidad para cada microservicio.
2. **Segregación de microtareas**: Cada microservicio tiene control total sobre su base de datos, garantizando un fuerte aislamiento.
3. **Base de datos dedicada**: Cada microservicio cuenta con su propia base de datos para evitar competencia de recursos.
4. **API Gateway / Proxy**: Implementado para centralizar el acceso a los microservicios, facilitando el balanceo de carga y el escalamiento.

### Diseño de Código
Para asegurar escalabilidad, se ha optado por una arquitectura orientada al dominio (Domain-Driven Design, DDD) junto con principios de Clean Architecture, dada la clara separación de dominios entre microservicios.

### Patrones de Diseño en los Microservicios

#### `ms-kitchen`
- **Factory Method**: Para generar distintos tipos de platos con ingredientes específicos.
- **Command**: Un nuevo pedido se envía como "comando" a la cocina para su preparación.
- **Observer**: Se utiliza para notificar cuando un plato está listo o cuando faltan ingredientes.

#### `ms-inventories`
- **Repository**: Desacopla la lógica de negocio de la persistencia de datos.
- **Command**: Recibe solicitudes de despacho de ingredientes.
- **Observer**: Notifica cuando los ingredientes están listos o cuando es necesario realizar una compra.

#### `ms-purchases`
- **Adapter**: Adapta el formato de las respuestas de la API externa del proveedor al formato interno.
- **Strategy**: Maneja estrategias alternativas de compra ante cambios en la configuración del proveedor.
- **Circuit Breaker**: Gestiona fallos en el proveedor externo para evitar colapsar el sistema en caso de inactividad.

## Documentación
Para más detalles, consulta la documentación disponible en la carpeta `/docs`.
