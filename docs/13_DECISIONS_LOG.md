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
