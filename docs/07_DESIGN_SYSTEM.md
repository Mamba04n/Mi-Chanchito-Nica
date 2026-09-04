# 07 — Sistema de diseño de Mi Chanchito Nica

Este archivo convierte las reglas visuales entregadas por el equipo en reglas implementables en web.

## Identidad

**Nombre:** Mi Chanchito Nica

El símbolo combina:

- chanchito: ahorro y cercanía;
- libro: aprendizaje;
- moneda C$: finanzas;
- flecha ascendente: crecimiento/progreso.

La interfaz debe sentirse **amigable, clara y seria sin ser fría**. Es una herramienta para PyMEs, no una app infantil. La gamificación puede ser alegre, pero las pantallas financieras deben mantener orden y confianza.

## Tipografías

### Principal — Gliker

Usar en:

- logotipo;
- títulos principales;
- metas cumplidas;
- felicitaciones;
- logros y elementos gráficos destacados.

No usar Gliker en tablas densas, formularios largos ni cifras financieras pequeñas.

### Secundaria — Open Sans

Usar en:

- cuerpo de texto;
- descripciones;
- inputs;
- tablas;
- botones;
- etiquetas;
- valores financieros.

### Jerarquía entregada

| Nivel | Fuente | Tamaño de referencia |
|---|---|---:|
| Logotipo | Gliker | 30 pt |
| Título principal | Gliker | 28 pt |
| Subtítulo | Open Sans Bold | 24 pt |
| Texto principal | Open Sans | 16 pt |
| Texto secundario | Open Sans | 12 pt |

Para web conservar la jerarquía y ajustar responsivamente con `rem/clamp()`; los pt son referencia de marca, no una obligación literal de CSS.

**Fuentes:** no almacenar ni redistribuir archivos binarios de fuentes sin licencia/autorización. Si Gliker no está disponible legalmente en el entorno, usar fallback redondeado temporal y documentar la sustitución.

## Paleta oficial

| Token | Hex | Uso recomendado |
|---|---|---|
| `brand-green-700` | `#1D6B46` | marca principal, navegación, botones primarios |
| `brand-green-500` | `#6FA65E` | progreso, estados positivos, acentos |
| `brand-pink-300` | `#F7B7A5` | ilustraciones, fondos suaves de gamificación |
| `brand-coral-500` | `#D98572` | acento secundario, advertencia suave |
| `brand-gold-500` | `#E9B63D` | moneda, XP, logros, recompensas |
| `brand-navy-900` | `#172238` | texto fuerte, íconos, bordes oscuros |
| `neutral-white` | `#FFFFFF` | fondos/tarjetas |
| `neutral-charcoal` | `#2C2C2C` | texto principal alterno |

## Uso de color

- Dashboard financiero: base blanca, texto navy/charcoal y verde como acción principal.
- No llenar tablas enteras con colores fuertes.
- Gold se reserva para XP/logros/dinero destacado, no para todos los botones.
- Pink/coral se usan más en educación/gamificación que en pantallas contables.
- Estados de error deben tener tratamiento accesible; no depender solo de la paleta de marca si compromete contraste.

## Tokens CSS sugeridos

```css
:root {
  --brand-green-700: #1D6B46;
  --brand-green-500: #6FA65E;
  --brand-pink-300: #F7B7A5;
  --brand-coral-500: #D98572;
  --brand-gold-500: #E9B63D;
  --brand-navy-900: #172238;
  --neutral-white: #FFFFFF;
  --neutral-charcoal: #2C2C2C;
}
```

Si se usan tonos claros adicionales, deben derivarse con transparencia/tints de estos tokens y mantenerse consistentes.

## Layout

### Escritorio

- sidebar colapsable con módulos activos;
- topbar con empresa activa, notificaciones y perfil;
- área central con máximo de lectura razonable;
- cards para KPIs;
- tablas para operaciones.

### Móvil

- navegación inferior o drawer;
- priorizar acciones principales;
- tablas se convierten en cards/listas cuando la lectura horizontal sea mala;
- formularios en una columna.

## Dos ambientes, una sola marca

### Finanzas

- más sobrio;
- fondo claro;
- verde/navy;
- cifras grandes y legibles;
- mínimo ruido visual.

### Aprende

- más expresivo;
- progreso, XP, insignias;
- pink/coral/gold como acento;
- mascota/ilustración donde ayude;
- no convertir todas las pantallas en caricatura.

## Componentes base

Crear componentes reutilizables:

- `AppShell`
- `SidebarModuleNav`
- `Topbar`
- `PageHeader`
- `KpiCard`
- `MoneyValue`
- `StatusBadge`
- `DataTable`
- `EmptyState`
- `ConfirmDialog`
- `ModuleCard`
- `LearningPathCard`
- `ProgressBar`
- `XpBadge`
- `AchievementCard`
- `AiRecommendationCard`
- `SourceReferenceCard`
- `TutorMessage`
- `ChallengeCard`

## Recomendación IA en UI

Debe distinguirse visualmente de un cálculo del sistema.

Ejemplo de tarjeta:

```text
[IA] Recomendación para aprender
Tu cartera vencida representa 35% de tus cuentas por cobrar.
Conviene reforzar crédito y cobranza.

[Estudiar ahora] [Ver fuente] [Posponer]
```

No mostrar texto generado como si fuera un dato oficial.

## Accesibilidad

- contraste suficiente;
- foco visible;
- labels explícitos;
- no depender solo de color para estados;
- botones con área táctil adecuada;
- tablas con encabezados claros;
- mensajes de error al lado del campo y resumen cuando sea necesario.
