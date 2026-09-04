# 06 — Educación y gamificación

## Estructura

```text
Curso / Ruta
└── Unidad
    ├── Clase
    │   ├── explicación
    │   ├── ejemplo
    │   ├── fuente
    │   └── mini actividad
    ├── Tarea
    ├── Examen
    └── Reto
```

## Contenido

Cada curso debe tener:

- objetivo;
- nivel;
- competencias;
- prerequisitos;
- fuentes principales;
- unidades obligatorias;
- criterio de finalización.

Cada clase debe distinguir claramente:

1. contenido educativo redactado por la plataforma;
2. material o extracto permitido de la fuente;
3. explicación del tutor IA;
4. referencia/enlace original.

## Dificultad real

No premiar solo por hacer clic. La progresión debe ser:

`explicación → práctica guiada → práctica sin guía → examen → reto`

Tipos de reto:

- conceptual;
- cálculo;
- decisión justificada;
- caso simulado;
- aplicado opcional a indicadores del negocio;
- reflexión.

## Evaluaciones

El examen debe mezclar:

- selección múltiple;
- verdadero/falso solo cuando sea útil;
- cálculo;
- análisis de caso;
- respuesta abierta corta.

No usar únicamente preguntas memorizables.

## Calificación

- preguntas cerradas: cálculo determinístico;
- respuesta abierta: rúbrica definida + evaluador asistido IA;
- la IA devuelve puntaje sugerido y evidencia; el sistema conserva rúbrica y feedback;
- en actividades críticas, permitir revisión manual futura.

## XP

XP se obtiene solo por actividades válidas.

Propuesta MVP:

- completar clase: 20 XP;
- tarea aprobada: 50 XP;
- examen aprobado: 80 XP;
- reto aprobado: 120 XP;
- mejora después de un reintento: bonus pequeño, nunca duplicar recompensa completa sin límite.

Los valores deben estar configurados, no dispersos en componentes.

## Nivel

Fórmula simple MVP:

```text
nivel = floor(xp_total / 500) + 1
```

Puede cambiar después. No usar nivel como sustituto de dominio de competencias.

## Racha

Se incrementa por **actividad educativa válida**, no por abrir la aplicación. No debe usar castigos agresivos.

## Logros MVP

1. **Primer paso** — completar primera clase.
2. **Sin miedo a los números** — aprobar primer ejercicio de cálculo.
3. **Cobrador estratégico** — completar ruta de CxC.
4. **Inventario bajo control** — aprobar reto de inventario.
5. **Constancia** — racha de 7 días.
6. **Aprendizaje aplicado** — completar un reto vinculado a una señal del negocio.

## Competencias

Guardar temas de dominio, por ejemplo:

- flujo de caja;
- cuentas por cobrar;
- cuentas por pagar;
- inventario;
- margen;
- presupuesto;
- toma de decisiones.

La IA puede recomendar repaso cuando el usuario falla, pero la decisión de “dominado” debe basarse en criterios medibles.

## Fuentes iniciales

Para el MVP usar una colección **curada manualmente** de documentos públicos/oficiales de universidades o instituciones reconocidas relacionadas con finanzas/administración.

Reglas:

- no usar nombre institucional como decoración si la fuente real no es de esa institución;
- conservar URL;
- no afirmar que la universidad certifica o avala la aplicación;
- no copiar material completo si su uso no lo permite;
- mostrar enlace original.

## Tres rutas MVP sugeridas

### Ruta 1 — Flujo de caja y liquidez
- diferencia entre utilidad y efectivo;
- entradas/salidas;
- planificación de caja;
- examen;
- caso con pagos próximos.

### Ruta 2 — Cuentas por cobrar y cobranza
- crédito;
- vencimientos;
- antigüedad;
- seguimiento;
- examen;
- reto de política de cobro.

### Ruta 3 — Inventario para pequeños negocios
- costo de inventario;
- rotación;
- mínimos;
- faltantes/sobrantes;
- examen;
- reto de reposición.
