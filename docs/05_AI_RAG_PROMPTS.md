# 05 — IA, RAG, prompts y contratos

## Objetivo

La IA debe ser **mantenible, reemplazable, verificable y limitada**. El producto no debe quedar acoplado a un proveedor ni depender de un “prompt gigante”.

## Flujo obligatorio para diagnóstico

```text
Datos del negocio
    ↓
Cálculos exactos en PHP/SQL
    ↓
IndicatorSnapshot
    ↓
DiagnosticRule
    ↓
Señal objetiva
    ↓
SourceRetriever + catálogo educativo
    ↓
Servicio IA especializado
    ↓
Salida estructurada validada
    ↓
AIRecommendation
    ↓
Usuario decide estudiar / posponer / ver fuente
```

## Contratos

```php
interface AiProvider
{
    public function structured(string $systemPrompt, array $messages, array $schema, array $options = []): array;
}
```

Servicios de dominio que dependen de `AiProvider`, no del SDK:

- `FinancialWeaknessExplainer`
- `LearningPathRecommender`
- `GroundedTutor`
- `ChallengeGenerator`
- `QuizGenerator`
- `OpenAnswerGrader`

Retrieval independiente:

```php
interface SourceRetriever
{
    public function retrieve(string $query, array $topicKeys = [], int $limit = 6): array;
}
```

El MVP puede comenzar con búsqueda relacional/FullText por fuentes curadas. La interfaz permite migrar a embeddings/vector search sin reescribir el tutor.

## Proveedores

Configurar mediante `.env`:

```text
AI_PROVIDER=
AI_API_KEY=
AI_MODEL_FAST=
AI_MODEL_REASONING=
AI_EMBEDDING_MODEL=
```

Nunca poner claves en frontend, repositorio o base de datos en texto sin protección.

## Prompt templates

Guardar prompts con:

- `key`;
- propósito;
- versión;
- instrucciones del sistema;
- esquema de entrada;
- esquema de salida;
- estado activo;
- notas de cambio.

Templates mínimos:

1. `financial_weakness_explainer`
2. `learning_path_recommender`
3. `source_grounded_tutor`
4. `challenge_generator`
5. `quiz_generator`
6. `open_answer_grader`

## Reglas de prompting

### financial_weakness_explainer

Entrada:

```json
{
  "indicator_key": "AR_OVERDUE_RATIO",
  "value": 35,
  "period": "2026-08",
  "rule": "AR_OVERDUE_HIGH",
  "threshold": 25,
  "severity": "high"
}
```

Salida:

```json
{
  "summary": "string",
  "why_it_matters": "string",
  "priority": "low|medium|high",
  "next_step": "string",
  "learning_topic_key": "string"
}
```

Prohibiciones:

- no recalcular el indicador;
- no inventar causas;
- no prescribir acciones legales/fiscales;
- no garantizar resultados.

### learning_path_recommender

La IA solo puede elegir entre rutas recibidas en el contexto. Si ninguna coincide, debe devolver `no_match`.

### source_grounded_tutor

El tutor responde **solo** con fragmentos recuperados de fuentes aprobadas y debe devolver referencias.

Salida conceptual:

```json
{
  "answer": "...",
  "references": [
    {"source_id": 4, "title": "...", "url": "...", "locator": "p. 12"}
  ],
  "confidence": "low|medium|high",
  "limitations": "..."
}
```

Si el retrieval no tiene evidencia suficiente:

```json
{
  "answer": null,
  "references": [],
  "confidence": "low",
  "limitations": "No encontré una fuente aprobada suficiente para responder."
}
```

### challenge_generator

Entrada obligatoria:

- objetivo de aprendizaje;
- nivel;
- tipo de reto;
- fragmentos/fuentes aprobadas;
- restricciones;
- duración estimada;
- rúbrica deseada.

Salida:

- título;
- contexto;
- consigna;
- dificultad;
- entregable;
- criterios de evaluación;
- pistas opcionales;
- solución/rúbrica separada;
- referencias.

La IA puede cambiar nombres, cifras y contexto de un caso simulado para evitar memorización, pero no puede alterar el conocimiento fuente.

## RAG / fuentes

### Ingesta

1. registrar metadatos;
2. verificar que la fuente sea pública/oficial;
3. registrar condición de uso;
4. decidir si se almacena texto, extracto o solo enlace;
5. dividir contenido permitido en chunks;
6. etiquetar temas;
7. revisión humana inicial;
8. publicar.

### Retrieval MVP

Prioridad de coincidencia:

1. topic key exacto;
2. curso/unidad vinculada;
3. búsqueda FullText/términos;
4. filtros por institución/nivel.

Evolución: embeddings/vector search detrás de `SourceRetriever`.

## Guardrails

- esquema JSON obligatorio en funciones críticas;
- validar longitud, referencias y campos enumerados;
- retry limitado solo para formato inválido;
- timeout y fallback;
- rate limit por usuario/empresa;
- cache en consultas educativas repetidas cuando sea seguro;
- registrar feedback útil/no útil;
- no guardar chain-of-thought del modelo;
- no exponer prompts internos al usuario.

## Privacidad del contexto

Preferir esto:

```json
{"overdue_ratio":35,"period":"2026-08","severity":"high"}
```

En lugar de enviar:

```json
{"customers":[{"name":"...","email":"...","invoice":"..."}]}
```

## Falla del proveedor

La aplicación financiera debe seguir funcionando. La UI puede mostrar:

> “La recomendación inteligente no está disponible en este momento. Tus datos financieros siguen guardados correctamente.”
