# Contrato de Frontend: Gamificación

El módulo de Gamificación es 100% independiente de la Inteligencia Artificial y expone los datos de nivel, XP, rachas, retos y logros de manera determinista. 

**Importante:** La Gamificación NO DEBE MODIFICAR el estado del aprendizaje (las lecciones o evaluaciones no se completan aquí). Al contrario, cuando el usuario completa una lección en el módulo de Educación, el sistema de Backend se encarga de procesar atómicamente la entrega de XP.

## Endpoints (Lectura)

El Frontend debe construir las vistas utilizando el Dashboard consolidado.

### 1. Perfil y Dashboard (Global)
`GET /api/gamification/dashboard`

Retorna la información principal consolidada del perfil gamificado. El perfil es **global por usuario**, no por empresa.

**Response:**
```json
{
  "profile": {
    "total_xp": 340,
    "current_level": 3,
    "current_streak": 5,
    "longest_streak": 7
  },
  "level_progress": {
    "current_level": 3,
    "current_xp": 340,
    "next_level": 4,
    "xp_required_for_next": 110,
    "progress_percentage": 45
  },
  "recent_achievements": [
    {
      "id": 12,
      "achievement_id": 3,
      "unlocked_at": "2026-09-04T12:00:00Z",
      "progress": 1,
      "achievement": {
        "key": "primer_paso",
        "name": "Primer Paso",
        "description": "Completa tu primera lección.",
        "icon_key": "star",
        "xp_reward": 20
      }
    }
  ],
  "active_challenges": [
    {
      "id": 4,
      "status": "active",
      "progress": 1,
      "target": 2,
      "expires_at": "2026-09-11T23:59:59Z",
      "challenge": {
        "title": "Dos lecciones de CxC",
        "description": "Completa 2 lecciones de cuentas por cobrar para ganar XP extra.",
        "xp_reward": 50,
        "challenge_type": "complete_lessons"
      }
    }
  ],
  "recent_xp_activity": [
    {
      "amount": 10,
      "reason": "lesson_completed",
      "created_at": "2026-09-04T15:30:00Z"
    }
  ]
}
```

## Acciones (Write)

El frontend **NUNCA DEBE ENVIAR** mutaciones de estado de gamificación como "sumar XP", "completar reto" o "actualizar racha". Todas las recompensas se calculan en el servidor tras interceptar eventos orgánicos (ej. `markLessonCompleted`). 

## Eventos de UI (WebSockets/Polling/Toast)
El frontend puede implementar notificaciones Toast cuando ocurra:
- `LevelUp`: Mostrar celebración de "Has alcanzado el Nivel X".
- `AchievementUnlocked`: Mostrar "Logro desbloqueado: Nombre".
- `ChallengeCompleted`: Mostrar "Reto completado: +XP extra".

## Multitenancy en Retos
Los retos (Challenges) son, en su mayoría, globales (`company_id = NULL`). Sin embargo, está preparado para el futuro donde un reto se origine a partir del diagnóstico financiero de una empresa específica.
Si `UserChallenge.company_id` existe, el Frontend debe asegurarse de mostrarlo únicamente si la empresa activa coincide, o etiquetarlo como perteneciente a esa empresa.

## UI Data & Mobile-First (Estilos SaaS)
Siguiendo las convenciones de diseño actualizadas (ver `FRONTEND_STRUCTURE.md`), la UI debe sentirse limpia y moderna:
1. **Header Perfil**: Nivel destacado con tipografía `font-display` (Gliker). Barra de progreso circular o lineal usando `progress_percentage` con acentos en `brand-gold-500` o `brand-green-500`.
2. **Card de Racha**: Icono de fuego 🔥 y `current_streak` días. Usar un fondo sutil tintado (ej. `bg-brand-soft-bg`).
3. **Logros**: Grid con los iconos de los logros recientes. Sombras suaves `shadow-sm` y bordes redondeados `rounded-xl`.
4. **Retos Activos**: Lista con checkbox circular que se rellena según `progress / target`. Textos principales en `text-brand-navy-900` y secundarios en `text-brand-soft-textSec`.
