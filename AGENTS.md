# AGENTS.md — reglas permanentes para cualquier agente de desarrollo

## 1. Antes de programar

Lee, en este orden: `README.md`, `docs/00_VISION_SCOPE.md`, `docs/01_REQUIREMENTS_BASELINE.md`, `docs/02_STACK_ARCHITECTURE.md`, `docs/07_DESIGN_SYSTEM.md`, `docs/08_SECURITY_PRIVACY.md`, `docs/09_TESTING_ACCEPTANCE.md` y `TASKS.md`.

No inventes funcionalidades fuera del alcance. Si encuentras una decisión ambigua, usa la opción más simple que mantenga modularidad, seguridad y trazabilidad; registra la decisión en `docs/13_DECISIONS_LOG.md`.

## 2. Forma de trabajo

- Trabaja por fases pequeñas y verificables.
- No intentes construir todo el proyecto en una sola iteración.
- Antes de cambiar código existente, inspecciona la estructura, rutas, migraciones, pruebas y convenciones actuales.
- Después de cada bloque funcional ejecuta pruebas relacionadas y una comprobación general.
- Mantén `TASKS.md` actualizado: pendiente, en proceso, terminado o bloqueado.
- No marques algo como terminado si no existe evidencia ejecutable.
- No borres una implementación estable para “rehacerla mejor” sin una razón documentada.

## 3. Arquitectura obligatoria

El producto es un **monolito modular Laravel**. Los módulos deben estar separados por responsabilidad y comunicarse mediante servicios, acciones, eventos o contratos claros. Evita dependencias circulares.

Áreas mínimas:

- Core / empresas / usuarios / roles / auditoría / configuración
- Module Registry
- Sales / Facturación
- Inventory
- Purchases
- Receivables (CxC)
- Payables (CxP)
- Cash
- Reporting
- Education
- Gamification
- Intelligence / AI

## 4. Finanzas: regla crítica

Los saldos, totales, vencimientos, existencias, porcentajes, indicadores, XP y reglas se calculan con código determinístico. **Nunca delegues a un LLM un cálculo que el sistema puede calcular de forma exacta.**

La IA recibe resultados ya calculados y puede:

- explicar;
- recomendar aprendizaje;
- orientar;
- responder con fuentes aprobadas;
- generar retos y preguntas dentro de esquemas controlados.

La IA no puede modificar facturas, inventario, saldos, cobros, pagos o cuentas automáticamente.

## 5. IA mantenible

Toda interacción con proveedores de IA debe pasar por contratos internos, nunca llamarse directamente desde controladores o componentes Livewire.

Contratos conceptuales:

- `AiProvider`
- `FinancialWeaknessExplainer`
- `LearningPathRecommender`
- `GroundedTutor`
- `ChallengeGenerator`
- `QuizGenerator`
- `OpenAnswerGrader`
- `SourceRetriever`

Los prompts son plantillas versionadas. Las respuestas importantes deben usar salida estructurada y validarse antes de usarse.

## 6. Multiempresa y autorización

- Toda información empresarial pertenece a una `company`.
- Un usuario solo accede a empresas de las que es miembro.
- Nunca confíes en `company_id` enviado por el navegador sin verificar pertenencia.
- Políticas y autorización se validan en servidor.
- Consultas, exports y dashboards respetan aislamiento por empresa.

## 7. Diseño

Respeta `docs/07_DESIGN_SYSTEM.md`. No introduzcas un nuevo lenguaje visual sin aprobación. Usa componentes reutilizables, contraste suficiente, diseño responsivo y lenguaje sencillo.

## 8. Base de datos

- Usa migraciones reversibles.
- Usa claves foráneas e índices cuando aporten integridad o rendimiento.
- No uses columnas JSON para reemplazar un modelo relacional claro; JSON se reserva para payloads flexibles, metadatos, esquemas IA o snapshots.
- Los documentos confirmados no se eliminan físicamente si afecta trazabilidad; se anulan o archivan según la regla de negocio.
- Usa `decimal`, nunca `float`, para dinero.

## 9. Seguridad

- Secrets solo en variables de entorno.
- No registrar API keys, contraseñas, tokens ni contenido financiero sensible en logs.
- Minimizar datos enviados al proveedor de IA.
- Las fuentes educativas deben conservar origen, URL e información de uso.
- Si no existe evidencia suficiente, la IA debe poder responder que no dispone de una fuente aprobada.

## 10. Calidad

Para cada flujo crítico agrega pruebas. Como mínimo:

- autenticación y autorización;
- aislamiento entre empresas;
- activación/desactivación de módulos;
- venta → CxC → cobro → caja;
- compra → CxP → pago → caja;
- inventario entradas/salidas;
- indicadores y reglas;
- progreso, XP, logros y reintentos;
- schemas de IA y grounding;
- demo integral.

## 11. Definition of Done

Una tarea está terminada cuando:

1. cumple el requerimiento;
2. funciona con datos válidos y maneja casos inválidos;
3. respeta permisos y empresa activa;
4. tiene pruebas razonables;
5. no rompe pruebas existentes;
6. usa el sistema visual definido;
7. está reflejada en `TASKS.md`;
8. cualquier decisión nueva quedó documentada.
