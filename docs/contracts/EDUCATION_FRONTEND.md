# Contrato Frontend: Educación Financiera y Administrativa

Este documento define la estructura y el comportamiento esperado para la Academia de "Mi Chanchito Nica".

## Estructura de Navegación (Mobile-First)

La sección "Academia" operará globalmente por usuario, independiente de su contexto de empresa actual, y tendrá la siguiente jerarquía visual recomendada:

```text
Academia
├── Inicio (Dashboard de aprendizaje)
├── Explorar (Catálogo por niveles/competencias)
├── Mi aprendizaje (Programas iniciados)
└── Fuentes (Librería de documentos de referencia)
```

## Consumo del Catálogo

El catálogo provee acceso a `LearningProgram` mediante `LearningCatalogService`.

### 1. `Program Card`
Debe mostrar:
- Título del programa (`title`)
- Nivel visual (`level`: `beginner`, `intermediate`, `advanced`)
- Duración estimada (si `estimated_duration_minutes` > 0)
- Estado de progreso del usuario (calculado con base en lecciones completadas vs totales, o a través de `ProgramEnrollment`).

### 2. `Program Detail`
Debe mostrar la descripción y agrupar visualmente las lecciones dentro de sus Unidades (`LearningUnit`).
- Las lecciones (`Lesson`) que ya están en `LessonProgress` con status `Completed` deben mostrar un check o candado abierto.
- La navegación recomendada es lineal, sugiriendo la primera lección no completada.

## Proceso de Lección

### Visualización
- La Lección retornará el campo `content` que puede venir en `markdown`. El frontend debe tener un renderizador seguro o estructurado.
- Se debe mostrar explícitamente un bloque "Fuentes de la lección", exponiendo la Institución y el Enlace original para cumplir con reglas de curación transparente.

### Botón "Completar"
- Al finalizar de leer, el usuario presiona "Completar".
- El endpoint guardará mediante `LearningProgressService->markLessonCompleted()`.
- Automáticamente el frontend debe redirigir a la siguiente Lección de la unidad, o a la Evaluación si aplica.

## Exámenes y Calificación (Assessments)

**Regla de Seguridad Estricta:**
El frontend **NUNCA** recibe un flag `is_correct` en las opciones de preguntas, ni evalúa localmente.

### Flujo de Intento (`AssessmentAttempt`):
1. **Inicia**: El frontend solicita iniciar evaluación -> El backend crea un `AssessmentAttempt` con status `InProgress` y devuelve el ID.
2. **Responde**: El frontend presenta las preguntas (`AssessmentQuestion`).
3. **Envía**: El frontend envía un payload con el ID del intento y el mapeo de `$questionId => $optionId`.
4. **Califica**: El backend asigna `GradeAssessment->grade($attempt)` sumando los puntos reales de la DB oculta.
5. **Resultado**: El backend responde con el puntaje final, porcentaje, y si el usuario aprobó (`passed`).

### Pantalla de Resultado
- Si `passed == true`: Celebración simple, botón continuar a siguiente unidad/programa.
- Si `passed == false`: Revisar si el intento excedió `max_attempts`. Si todavía quedan, mostrar "Reintentar".

## Puente Analítica → Educación

Si el usuario hace clic en el Dashboard en una alerta (ej. "Alta Cartera Vencida"), el frontend leerá el `recommended_learning_topic` de la alerta (ej. `accounts_receivable_management`).

El Frontend invocará al servicio del Catálogo para buscar programas bajo esta competencia `findProgramsByCompetency('accounts_receivable_management')` y presentará las *Cards* directamente como sugerencias accionables.

---
**Nota:** Gamificación (Puntos XP, Niveles de Usuario, Retos o Tutores de IA) **NO está implementado en este bloque**. Solo se gestiona progreso crudo de currícula.
