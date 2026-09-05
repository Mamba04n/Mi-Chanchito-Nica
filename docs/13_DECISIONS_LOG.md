# 13 — Registro de decisiones

Este archivo evita que el equipo cambie decisiones importantes sin dejar rastro.

## Formato

```md
### ADR-XXX — Título
- Fecha:
- Estado: Propuesta | Aceptada | Reemplazada
- Contexto:
- Decisión:
- Alternativas consideradas:
- Consecuencias:
- Requerimientos afectados:
```

---

### ADR-001 — Monolito modular Laravel
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: hackathon con necesidad de rapidez y crecimiento modular.
- Decisión: una sola aplicación Laravel organizada por dominios/módulos; no microservicios.
- Alternativas consideradas: monolito sin límites; microservicios.
- Consecuencias: despliegue simple y responsabilidades claras; requiere disciplina de dependencias.
- Requerimientos afectados: arquitectura completa.

### ADR-002 — Cálculos financieros fuera de IA
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: los LLM no deben ser fuente de verdad para saldos.
- Decisión: PHP/SQL calcula; IA interpreta.
- Alternativas consideradas: enviar transacciones completas al LLM para analizar.
- Consecuencias: mayor confiabilidad y menor exposición de datos.
- Requerimientos afectados: indicadores, recomendaciones, seguridad.

### ADR-003 — Retrieval reemplazable
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: el MVP no necesita infraestructura vectorial compleja desde el día 1.
- Decisión: `SourceRetriever` como contrato; empezar con fuentes curadas + búsqueda simple y permitir vector search después.
- Consecuencias: menor riesgo del hackathon y evolución sin reescribir tutor.
- Requerimientos afectados: tutor, fuentes, RAG.

### ADR-004 — Educación separada de la pertenencia empresarial
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: una persona puede aprender aunque cambie de empresa o pertenezca a varias.
- Decisión: progreso educativo pertenece al usuario; recomendaciones pueden enlazar opcionalmente una empresa/indicador.
- Consecuencias: progreso portable; requiere cuidado al mostrar contexto financiero.
- Requerimientos afectados: educación, gamificación, IA.

### ADR-005 — Catálogo de Productos y Servicios Unificado
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: Tanto productos como servicios pueden facturarse.
- Decisión: Combinar ambos en la tabla products con una columna 	ype (product/service) y 	rack_inventory.
- Alternativas consideradas: Tablas separadas products y services.
- Consecuencias: Facilita la facturación híbrida sin duplicar esquemas. Un servicio no afectará existencias en almacenes.
- Requerimientos afectados: Catálogo, Facturación, Inventario.

### ADR-006 — Desactivación Lógica vs SoftDeletes
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: Manejo de eliminaciones en datos financieros e históricos (Clientes, Proveedores, etc.).
- Decisión: Usar un campo booleano ctive en lugar del rasgo SoftDeletes de Laravel para desactivar entidades.
- Alternativas consideradas: Illuminate\Database\Eloquent\SoftDeletes (deleted_at).
- Consecuencias: Consultas más simples y explícitas (where('active', true)). Evita conflictos ocultos de Laravel en relaciones complejas y mantiene mejor la trazabilidad Odoo-style.
- Requerimientos afectados: Historial, Integridad, Base de Datos.

### ADR-007 — Movimientos como única fuente de verdad para el stock
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: Riesgo de inconsistencias si el stock se altera manualmente.
- Decisión: La cantidad de stock (\quantity\) en \InventoryStock\ NO se edita de manera arbitraria; todo ajuste de inventario se centraliza mediante los \InventoryMovement\.
- Alternativas consideradas: Actualización en vivo sin historial (descartado por falta de trazabilidad).
- Consecuencias: Inventario trazable y seguro. El Kardex se genera leyendo el log de movimientos.
- Requerimientos afectados: Inventario, Kardex, Auditoría.

### ADR-008 — Prevención de stock negativo
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: Control inicial simple y estricto del almacén.
- Decisión: El Backend validará e impedirá registrar salidas (\egisterExit\, transferencias o ajustes) si la cantidad excede el stock actual.
- Alternativas consideradas: Permitir stock en negativo y ajustarlo luego al cerrar compras (descartado para simplicidad MVP).
- Consecuencias: Obliga a realizar la entrada (compras/ajustes in) antes de la salida (ventas/ajustes out).
- Requerimientos afectados: Inventario, Facturación.

### ADR-009 — Concurrencia de Inventario con Bloqueo de Base de Datos
- Fecha: 2026-09-03
- Estado: Aceptada
- Contexto: Evitar inconsistencias cuando dos operaciones consumen el mismo stock simultáneamente.
- Decisión: Implementar \DB::transaction()\ junto a \lockForUpdate()\ en los registros de inventario.
- Alternativas consideradas: Bloqueos a nivel de aplicación (Redis) - descartado por ser infraestructura externa innecesaria.
- Consecuencias: Previene colisiones a nivel de base de datos de manera robusta y estandarizada en MySQL.
- Requerimientos afectados: Rendimiento, Inventario.

### ADR-010 — Transacciones Estrictas en Facturación y Cobros
- Fecha: 2026-09-04
- Estado: Aceptada
- Contexto: Consistencia entre Facturación, Inventario y CxC durante confirmaciones y anulaciones.
- Decisión: Usar `DB::transaction` en todas las Acciones de facturación y cobros. Bloqueo pesimista (`lockForUpdate`) al generar números correlativos y al aplicar abonos (bloqueando la CxC).
- Alternativas consideradas: Cronjobs para recalcular saldos; Bloqueo Redis.
- Consecuencias: Consistencia absoluta a costa de ligeros bloqueos transaccionales temporales. No permite facturar sin stock (hace rollback).
- Requerimientos afectados: Facturación, CxC, Inventario.


### ADR-011: Lógica de Compras e Integración Opcional con Inventario

**Contexto:**
El flujo de Compras adquiere bienes y/o servicios. Algunos de estos afectan existencias, mientras que otros no. El módulo de inventario puede ni siquiera estar activo para empresas de servicios puros.

**Decisión:**
1. Separar estrictamente la responsabilidad: `ConfirmPurchase` transacciona los estados financieros de la compra y la creación de `AccountPayable`, pero **delega** la creación del `InventoryMovement` a `InventoryService->registerEntry()`.
2. Validar que la compra especifique un `warehouse_id` si y solo si al menos una línea tiene un `Product` con `track_inventory = true` y el `ModuleManager` confirma que `inventory` está activo.
3. El número de compra (`COM-XXXXXX`) se genera **antes** de afectar inventarios para pasarlo como metadato a las notas de inventario.

**Consecuencias:**
Garantiza que el sistema pueda operar solo con compras (sin requerir obligatoriamente configurar almacenes o Kardex). Se preservan los principios SOLID y la trazabilidad contable.


### ADR-012: Tesorería Opcional, Saldos Derivados y Regla de Efectivo

**Contexto:**
El Bloque 6 introduce la Tesorería para registrar dónde se encuentra el dinero físico o bancario. Es necesario definir cómo se integran las cuentas con los módulos operativos (CxC/CxP) y cómo se asegura la integridad de los saldos.

**Decisión:**
1. **Tesorería Opcional:** Si el módulo está activo, todos los abonos a CxC y CxP **deberán** especificar una cuenta financiera destino/origen. Si el módulo está apagado, seguirán funcionando libremente.
2. **Saldos Derivados (Fuente de Verdad):** El balance de la cuenta (`current_balance`) no puede ser sobrescrito directamente. Siempre es el resultado matemático del balance anterior más o menos el monto del movimiento actual (`FinancialMovement`).
3. **Regla de Efectivo Restricto:** Las cuentas de tipo `CASH` (Caja) rechazarán, vía excepción con `lockForUpdate`, cualquier egreso o transferencia saliente que intente llevar el saldo a negativo, a diferencia de cuentas bancarias que podrían contemplar sobregiros en futuras versiones.
4. **Reversiones:** Los movimientos no se eliminan físicamente (Hard Delete ni Soft Delete). En caso de error, se aplica una reversión a través de un ajuste compensatorio (`ReverseFinancialMovement`) vinculando el nuevo registro al registro anulado mediante polimorfismo (`reference_id`).

**Consecuencias:**
Establece un sistema financiero en partida simple altamente auditable que elimina la posibilidad de descuadres manuales de caja sin dejar rastro de quién y por qué se alteró el dinero.


### ADR-013: Dashboard Analítico, Alertas Deterministas y Mapeo de Competencias

**Contexto:**
El Bloque 7 requiere centralizar métricas financieras. Se necesita diferenciar claramente 'Ventas' de 'Flujos de Efectivo', respetar módulos desactivados y generar alertas tempranas de riesgo sin requerir a la IA.

**Decisión:**
1. **Sin duplicidad de datos:** No se creará una tabla `dashboard_statistics`. Todas las métricas son calculadas determinísticamente por `BusinessDashboardService` orquestando a los submódulos. 
2. **Separación Contable:** Se separan los KPIs de `sales` y `purchases` de `treasury`. Una venta a crédito figura en ventas pero no en cash flow. 
3. **Alertas Operativas y Taxonomía:** Se creó `BusinessAlertService` y `config/business_health.php` para definir umbrales duros (e.g. 30% CxC Vencida = crítico). Cada alerta retorna un `recommended_learning_topic` (e.g. `accounts_receivable_management`) que servirá de *puente futuro* para recomendar rutas de Educación sin quemar recursos de IA evaluando los números brutos.
4. **Fallback Modular:** Si una empresa deshabilita el inventario, el orquestador retorna el valor `unavailable` en la llave correspondiente, previniendo que se grafiquen ceros absolutos falsos.

**Consecuencias:**
El frontend debe manejar validaciones de tipo string (`unavailable`) para ciertos KPIs. El sistema se mantiene ligero y determinista, aislando los datos operativos de las capas de Inteligencia Artificial.


### ADR-014: Estructura Central de Educación y Evaluación Determinística

**Contexto:**
Se requiere una base académica sólida (Bloque 8) para 'Mi Chanchito Nica' donde el progreso del usuario persista de manera segura, se protejan las respuestas de los exámenes del frontend y se manejen competencias educativas sin recurrir inmediatamente a sugerencias de la Inteligencia Artificial.

**Decisión:**
1. **Jerarquía Académica Rígida:** Se crearon modelos específicos de currícula (`LearningProgram`, `LearningUnit`, `Lesson`).
2. **Separación de Fuentes Reales:** Se abstrajo la entidad `EducationalSource` del propio contenido para salvaguardar el origen, la URL original, y evitar suplantar o adjudicar contenido propio falso a universidades.
3. **Evaluación de Backend Segura:** `GradeAssessment` se diseñó para sumar puntos consultando directamente en la base de datos si la opción provista por el frontend (`AssessmentQuestionOption`) era correcta. Nunca se delega el puntaje final a la web.
4. **Competencias Vinculantes (Bridge):** Se estableció un mapeo transversal a través de `LearningProgramCompetency`. Si el dashboard arroja una alerta de 'alta_cxc', el servicio de catálogo lo acopla determinísticamente recuperando el curso correspondiente, sin llamadas a IA.

**Consecuencias:**
La carga cognitiva del motor IA futuro disminuye dramáticamente, delegando la responsabilidad de retención, navegación secuencial y control académico a la infraestructura estable del framework. El progreso educativo pertenece al usuario de manera global, independiente del tenant.

