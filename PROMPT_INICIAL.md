# PROMPT INICIAL — construir Mi Chanchito Nica

Copia este prompt completo en el agente de programación desde la raíz del repositorio.

---

Quiero que trabajes como arquitecto y desarrollador principal de **Mi Chanchito Nica**, un proyecto de hackathon en PHP/Laravel. No empieces a programar a ciegas.

## 1. Lee el contexto

Antes de modificar cualquier archivo, lee completamente:

1. `AGENTS.md`
2. `README.md`
3. `docs/00_VISION_SCOPE.md`
4. `docs/01_REQUIREMENTS_BASELINE.md`
5. `docs/02_STACK_ARCHITECTURE.md`
6. `docs/03_MODULES_DEPENDENCIES.md`
7. `docs/04_DATA_MODEL.md`
8. `docs/05_AI_RAG_PROMPTS.md`
9. `docs/06_EDUCATION_GAMIFICATION.md`
10. `docs/07_DESIGN_SYSTEM.md`
11. `docs/08_SECURITY_PRIVACY.md`
12. `docs/09_TESTING_ACCEPTANCE.md`
13. `docs/10_BACKLOG_ROADMAP.md`
14. `docs/11_DEMO_SCENARIO.md`
15. `docs/12_CODING_STANDARDS.md`
16. `docs/13_DECISIONS_LOG.md`
17. `TASKS.md`

Trata estos documentos como la línea base del proyecto. No cambies el alcance por tu cuenta.

## 2. Objetivo del producto

Construiremos una plataforma web con dos enfoques conectados y un único propósito:

- **gestión financiera modular para PyMEs/MIPyMEs**;
- **educación financiera y administrativa gamificada basada en fuentes públicas/oficiales curadas**.

La IA es el puente. El sistema calcula indicadores con PHP/SQL; reglas determinísticas detectan señales; la IA explica, recomienda aprendizaje, responde con fuentes y genera retos mediante prompts internos especializados.

La IA **nunca** será la fuente de verdad de saldos, inventario, facturas, CxC, CxP o indicadores.

## 3. Stack

Usa como base:

- PHP 8.3+
- Laravel 13.x
- Livewire 4.x
- Blade + Alpine.js
- Tailwind CSS
- MySQL 8.x
- Pest
- Vite

Si el repositorio ya existe con versiones distintas, no actualices destructivamente sin revisar compatibilidad. Primero informa qué encontraste y conserva lo estable si cumple el objetivo.

## 4. Arquitectura

Implementa un **monolito modular**. No microservicios.

Módulos:

- Core
- ModuleRegistry
- Sales
- Inventory
- Purchases
- Receivables
- Payables
- Cash
- Reporting
- Education
- Gamification
- Intelligence

Mantén límites claros, sin sobrearquitectura. Usa Actions/Services/Policies/Events solo cuando resuelvan una responsabilidad real.

## 5. Multiempresa

Diseña desde el inicio:

- `users`
- `companies`
- membresía usuario-empresa
- empresa activa
- módulos activos por empresa
- aislamiento estricto
- autorización en servidor

Toda entidad financiera debe quedar asociada a empresa. No confíes en IDs enviados desde el navegador sin autorización.

## 6. Diseño

Respeta la identidad definida:

- Gliker para marca/títulos/logros cuando esté disponible legalmente;
- Open Sans para cuerpo, formularios, tablas y botones;
- `#1D6B46`
- `#6FA65E`
- `#F7B7A5`
- `#D98572`
- `#E9B63D`
- `#172238`
- `#FFFFFF`
- `#2C2C2C`

Las finanzas deben verse ordenadas y profesionales. La educación puede ser más expresiva. No conviertas la aplicación completa en una interfaz infantil.

## 7. IA

No conectes la IA directamente desde Livewire o Controllers.

Primero crea contratos y servicios internos:

- `AiProvider`
- `SourceRetriever`
- `FinancialWeaknessExplainer`
- `LearningPathRecommender`
- `GroundedTutor`
- `ChallengeGenerator`

Los prompts deben ser versionados y las salidas críticas estructuradas/validadas. El proveedor debe poder cambiarse sin reescribir los módulos.

Para el MVP, el retrieval puede comenzar simple con fuentes curadas y búsqueda SQL/FullText detrás de `SourceRetriever`; deja preparado el contrato para vector search futuro.

## 8. Forma de trabajar

No construyas todo de una vez.

Primero realiza una **auditoría del repositorio y entorno**:

- versión PHP;
- versión Composer;
- si ya existe Laravel, versión;
- estructura actual;
- DB/config actual;
- paquetes instalados;
- estado Git;
- pruebas existentes;
- frontend existente.

Después presenta un plan corto y empieza inmediatamente la **Fase 0 y Fase 1**, salvo que encuentres un bloqueo real.

### Fase 0

- bootstrap Laravel si no existe;
- auth;
- Livewire;
- design tokens/layout;
- Pest;
- DB/config.

### Fase 1

- company + memberships;
- CompanyContext;
- permisos mínimos;
- module registry;
- onboarding de empresa;
- activación modular;
- auditoría;
- tests de aislamiento.

No avances a finanzas hasta que Fase 1 tenga pruebas y el aislamiento funcione.

## 9. Reglas de implementación

- dinero con decimal, nunca float;
- operaciones que cambian varias tablas usan transacciones;
- documentos confirmados no se eliminan físicamente;
- errores de proveedor IA no revierten operaciones financieras válidas;
- secrets solo en `.env`;
- código en inglés, UI en español;
- cada flujo crítico debe tener pruebas;
- usa seeders para demo reproducible;
- actualiza `TASKS.md` conforme avances;
- registra decisiones nuevas en `docs/13_DECISIONS_LOG.md`.

## 10. Qué quiero de tu primera ejecución

1. Lee todos los archivos indicados.
2. Audita el repositorio/entorno.
3. Resume en máximo 15 puntos lo que vas a construir y cualquier diferencia encontrada.
4. Implementa Fase 0.
5. Implementa Fase 1.
6. Ejecuta pruebas.
7. Corrige fallos que sean consecuencia de tus cambios.
8. Actualiza `TASKS.md` y el registro de decisiones si hizo falta.
9. Al terminar, dame:
   - archivos principales creados/modificados;
   - migraciones creadas;
   - rutas principales;
   - pruebas ejecutadas y resultado;
   - comandos exactos para levantar el proyecto;
   - siguiente bloque recomendado (Fase 2), **sin empezarlo todavía**.

No me des código ficticio ni digas que algo está terminado si no lo ejecutaste o verificaste. Si existe una ambigüedad menor, elige la opción más simple coherente con la documentación y sigue. Solo detente a preguntarme si hay un bloqueo que pueda cambiar de manera importante el producto o destruir datos existentes.

---
