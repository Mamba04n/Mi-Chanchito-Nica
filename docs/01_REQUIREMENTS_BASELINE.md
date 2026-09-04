# Línea base de requerimientos

> Este archivo es la conversión de la especificación funcional acordada para el hackathon. Es la fuente principal de verdad sobre alcance, requisitos, reglas, casos de uso, IA, aprendizaje, riesgos y criterios de aceptación.

**Regla:** si un cambio de código contradice este documento, primero debe registrarse la decisión en `docs/13_DECISIONS_LOG.md` y actualizar la línea base.

---

PROYECTO HACKATHON

ESPECIFICACIÓN DE VISIÓN, ALCANCE Y REQUERIMIENTOS

Plataforma modular de gestión financiera y aprendizaje inteligente para PyMEs y MIPyMEs

Documento interno para el equipo

> **ENFOQUE 1  •  Gestión financiera modular**

> **ENFOQUE 2  •  Educación gamificada basada en fuentes oficiales**

> **PUENTE  •  Inteligencia artificial como guía, diagnóstico y tutor**

Versión 1.0  |  03 de septiembre de 2026

# 0. Antes de leer: explicación sencilla para el equipo

> **La idea en una frase<br>La plataforma ayuda a una PyME a organizar sus finanzas y, al mismo tiempo, detecta qué conocimientos de finanzas o administración le hacen falta al usuario para recomendarle un aprendizaje práctico, medible y retador.**

El proyecto no se plantea como “dos aplicaciones pegadas”. Se plantea como una sola plataforma con dos áreas claras que comparten usuarios, datos, progreso y objetivos. El área financiera sirve para trabajar con información real del negocio. El área educativa sirve para enseñar y practicar. La inteligencia artificial funciona como intermediaria entre ambas: observa indicadores permitidos, identifica debilidades, recomienda contenidos y puede generar retos controlados para que el usuario mejore.

| Parte | Qué hace | Ejemplo sencillo |
| --- | --- | --- |
| Gestión financiera | Organiza las operaciones básicas de una empresa. | Facturas, inventario, cuentas por cobrar, cuentas por pagar y movimientos de dinero. |
| Educación gamificada | Convierte material serio en unidades, clases, tareas, exámenes, niveles y retos. | Una unidad de flujo de caja con teoría, práctica y examen. |
| IA | Conecta las dos partes y guía al usuario. | Detecta cartera vencida y recomienda estudiar gestión de crédito y cobranza. |

> **Qué significa “modular”<br>La empresa no tiene que usar todo. Durante la configuración puede activar solo los módulos que necesita. Por ejemplo, una microempresa de servicios podría usar Facturación + CxC + Caja, mientras una tienda podría activar también Inventario + Compras + CxP.**

# Contenido

1. Resumen ejecutivo

1. Visión del producto y propuesta de valor

1. Contexto y problema que se desea resolver

1. Objetivos del proyecto

1. Principios de diseño del producto

1. Usuarios e interesados

1. Alcance general y alcance del MVP

1. Arquitectura funcional por enfoques

1. Requerimientos funcionales

1. Requerimientos no funcionales

1. Reglas de negocio

1. Casos de uso principales

1. Modelo conceptual de datos

1. Diseño funcional de la inteligencia artificial

1. Diseño del aprendizaje y gamificación

1. Fuentes educativas y control de contenido

1. Seguridad, privacidad y uso responsable de IA

1. Plan de pruebas y criterios de aceptación

1. Riesgos y respuestas

1. Roadmap después del hackathon

1. Beneficios esperados

1. Glosario para el equipo

# 1. Resumen ejecutivo

El proyecto propone una plataforma web orientada a PyMEs y MIPyMEs que combina gestión financiera modular con educación financiera y administrativa gamificada. La intención es que un negocio pueda comenzar con herramientas básicas de operación —facturación, inventario, cuentas por cobrar, cuentas por pagar, caja y reportes— y activar únicamente los módulos que necesita.

La segunda parte del producto transforma documentos oficiales y públicos de instituciones académicas reconocidas en rutas de aprendizaje estructuradas. El contenido se organizará en cursos, unidades, clases, tareas, evaluaciones y retos. Cada material deberá conservar su fuente, enlace, institución, fecha y referencias para que el usuario pueda consultar el documento original.

La inteligencia artificial no sustituirá los cálculos financieros ni será la fuente principal del conocimiento. Su función será interpretar información ya procesada por el sistema, detectar señales de debilidad, recomendar rutas de aprendizaje, responder preguntas usando las fuentes disponibles y generar retos o ejercicios bajo reglas y prompts internos controlados.

El valor diferencial es cerrar el ciclo entre operación y aprendizaje: la plataforma no solo muestra que existe un problema financiero, sino que ayuda al usuario a entenderlo, aprender sobre él, practicar y comprobar si mejoró.

# 2. Visión del producto y propuesta de valor

## 2.1 Visión

Construir una plataforma que permita a pequeños y medianos negocios ordenar sus finanzas con módulos simples y, al mismo tiempo, desarrollar capacidades financieras y administrativas mediante aprendizaje práctico, gamificado y guiado por inteligencia artificial.

## 2.2 Propuesta de valor

> **Propuesta central<br>“La plataforma no solo te dice cómo está tu negocio; te ayuda a entender qué debes mejorar, te enseña cómo hacerlo y te reta a demostrarlo con tus propios resultados.”**

## 2.3 Diferenciadores

- Arquitectura modular: cada empresa activa únicamente lo que necesita.

- Aprendizaje conectado con situaciones reales del negocio, no separado de la operación.

- Contenido educativo basado en fuentes oficiales y públicas, con trazabilidad de la fuente.

- IA como guía y tutor, no como reemplazo de reglas financieras ni como fuente sin control.

- Retos personalizados que pueden surgir tanto del progreso educativo como de indicadores reales del negocio.

- Experiencia sencilla para usuarios no técnicos y negocios que todavía trabajan con Excel, cuadernos o procesos dispersos.

# 3. Contexto y problema que se desea resolver

Muchas PyMEs y MIPyMEs necesitan herramientas de gestión, pero un ERP completo suele sentirse pesado, caro o innecesario para su etapa. A la vez, el propietario o administrador puede ver números sin tener suficiente formación para interpretarlos o para decidir qué acción tomar.

## 3.1 Problemas principales

- Información de ventas, inventario, pagos y deudas distribuida en diferentes medios.

- Falta de una vista simple del dinero que entra, sale, se debe cobrar o se debe pagar.

- Empresas que necesitan solo algunos módulos y no un sistema completo desde el primer día.

- Usuarios que conocen el problema (“me deben mucho”, “no sé mi margen”, “me falta efectivo”) pero no necesariamente conocen el concepto o la acción correcta.

- Educación financiera demasiado teórica, extensa o desconectada de la realidad diaria del negocio.

- Contenido en internet de calidad variable y sin garantía de procedencia.

- IA utilizada como chat genérico sin límites, sin fuentes y sin relación con los datos reales del producto.

## 3.2 Pregunta guía

¿Cómo diseñar una plataforma modular para PyMEs y MIPyMEs que organice procesos financieros básicos y utilice aprendizaje gamificado e inteligencia artificial para ayudar al usuario a interpretar sus debilidades, adquirir conocimientos confiables y mejorar sus decisiones?

# 4. Objetivos del proyecto

## 4.1 Objetivo general

Diseñar y desarrollar un prototipo funcional de una plataforma web modular para PyMEs y MIPyMEs que integre gestión financiera básica, educación financiera y administrativa gamificada, e inteligencia artificial orientada a diagnóstico, recomendación, tutoría y generación controlada de retos.

## 4.2 Objetivos específicos

1. Definir un núcleo común de empresa, usuarios, roles, permisos, configuración y auditoría.

1. Implementar un sistema de módulos activables para adaptar la plataforma al tipo y tamaño del negocio.

1. Desarrollar como base los módulos de facturación, inventario, cuentas por cobrar, cuentas por pagar, compras, caja/movimientos y reportes.

1. Crear una estructura educativa de cursos, unidades, clases, tareas, exámenes, progreso, niveles, logros y retos.

1. Administrar una biblioteca de documentos oficiales y públicos con trazabilidad de institución, fuente, versión y enlace original.

1. Implementar una capa de IA capaz de recomendar aprendizaje a partir de indicadores financieros y del comportamiento educativo del usuario.

1. Permitir que el usuario consulte voluntariamente al tutor de IA y reciba rutas, explicaciones y documentos relacionados con lo que desea aprender.

1. Generar retos y actividades mediante prompts internos especializados, con controles de dificultad, fuente, contexto y criterios de evaluación.

1. Validar el prototipo mediante escenarios de uso reales y una demostración clara para el hackathon.

# 5. Principios de diseño del producto

| Principio | Significado para el equipo |
| --- | --- |
| P1. Modularidad | El sistema debe crecer por módulos. Agregar un módulo no debe obligar a rehacer el núcleo. |
| P2. Simplicidad | Las tareas frecuentes deben explicarse en términos entendibles para dueños y administradores no técnicos. |
| P3. Datos primero | Los cálculos financieros los realiza lógica determinística; la IA interpreta resultados, no inventa saldos. |
| P4. Fuente verificable | Toda lección derivada de material externo debe conservar de dónde salió. |
| P5. IA con límites | Cada función de IA debe tener propósito, entrada, salida y reglas definidas. |
| P6. Aprendizaje aplicado | La teoría debe terminar en práctica, reto o acción medible. |
| P7. Privacidad | La IA recibirá únicamente los datos necesarios y preferentemente resumidos o agregados. |
| P8. Evolución | El MVP debe funcionar con pocos módulos, pero quedar preparado para incorporar nuevos sin romper los existentes. |

# 6. Usuarios e interesados

| Usuario / interesado | Qué necesita |
| --- | --- |
| Propietario / Gerente | Ver el estado del negocio, indicadores, alertas, aprendizaje recomendado y decisiones prioritarias. |
| Administrador | Registrar y controlar operaciones, documentos, cobros, pagos, inventario y configuración permitida. |
| Ventas / Caja | Emitir facturas o comprobantes, registrar cobros y consultar clientes. |
| Compras / Bodega | Gestionar proveedores, compras, entradas, salidas y existencias. |
| Usuario aprendiz | Estudiar por interés propio o por recomendación de la IA; completar tareas, exámenes y retos. |
| Administrador de contenido | Registrar fuentes oficiales, revisar cursos y publicar material educativo. |
| Equipo del hackathon | Diseñar, implementar, probar y demostrar el producto. |
| Jurado / mentor | Comprender el problema, la innovación, el alcance y la evidencia de funcionamiento. |

# 7. Alcance general y alcance del MVP

## 7.1 Alcance funcional completo previsto

- Núcleo empresarial: empresa, sucursales básicas, usuarios, roles, permisos, configuración, monedas y auditoría.

- Activación de módulos según las necesidades del negocio.

- Facturación/ventas, clientes, cobros y cuentas por cobrar.

- Inventario, productos, existencias, movimientos y alertas de stock.

- Compras, proveedores, pagos y cuentas por pagar.

- Caja/cuentas financieras y flujo simple de entradas y salidas.

- Dashboard e indicadores financieros operativos.

- Biblioteca de fuentes educativas públicas y verificables.

- Cursos, unidades, clases, tareas, exámenes, rutas, progreso, XP, niveles, logros y retos.

- IA de diagnóstico, recomendación, tutoría y generación controlada de ejercicios/retos.

- Bitácora de acciones sensibles, de decisiones de IA y de versiones de prompts.

## 7.2 MVP recomendado para el hackathon

> **Regla del MVP<br>No intentar construir un ERP completo ni una universidad completa. El demo debe enseñar el ciclo completo: registrar operación → detectar situación → recomendar aprendizaje → estudiar → resolver reto → comprobar progreso.**

| Área | MVP obligatorio | Puede quedar simulado / reducido |
| --- | --- | --- |
| Empresa y acceso | Empresa, usuario, roles simples, activación de módulos. | Multiempresa avanzada, SSO, permisos extremadamente granulares. |
| Finanzas | Facturación simple, inventario, CxC, CxP, movimientos y dashboard. | Contabilidad formal, impuestos reales, bancos en tiempo real. |
| Educación | 3 rutas temáticas, unidades, clases, tareas, examen y progreso. | Catálogo masivo de cursos. |
| Gamificación | XP, nivel, racha, 4-6 logros y retos. | Rankings sociales, ligas, tienda de recompensas. |
| IA | Diagnóstico por reglas + explicación, recomendación, tutor con fuentes y generación de retos. | Modelos propios o entrenamiento especializado. |
| Fuentes | Colección curada de documentos públicos y oficiales. | Rastreo automático de toda la web. |

## 7.3 Fuera del MVP

- Facturación electrónica fiscal real o conexión directa con autoridades tributarias.

- Contabilidad de doble partida completa, declaraciones fiscales y cierres contables oficiales.

- Nómina y recursos humanos.

- Integración bancaria en tiempo real.

- Predicción financiera avanzada con modelos entrenados propios.

- Certificaciones académicas emitidas en nombre de universidades externas.

- Copiar o redistribuir material protegido que no sea de acceso/uso permitido.

- Generación de consejos financieros de alto riesgo presentados como asesoría profesional definitiva.

# 8. Arquitectura funcional por enfoques

## 8.1 Enfoque A: Gestión financiera modular

La plataforma parte de un núcleo común. Sobre ese núcleo se conectan módulos independientes. La empresa selecciona qué módulos desea activar durante el onboarding y posteriormente puede activar o desactivar otros según sus permisos y dependencias.

| Módulo | Función principal | Dependencias básicas |
| --- | --- | --- |
| Núcleo | Empresa, usuarios, roles, configuración, monedas, auditoría. | Ninguna. |
| Clientes y facturación | Clientes, documentos de venta, totales, estados y cobros. | Núcleo. |
| Inventario | Productos, existencias, entradas, salidas, ajustes y mínimos. | Núcleo; se conecta con ventas/compras si están activos. |
| Compras y proveedores | Proveedores, compras, recepciones y costos. | Núcleo; Inventario opcional. |
| CxC | Saldos por cobrar, vencimientos, abonos y antigüedad. | Facturación/ventas. |
| CxP | Obligaciones, vencimientos y pagos. | Compras/proveedores. |
| Caja / cuentas | Entradas, salidas, cuentas financieras y conciliación manual básica. | Núcleo; recibe movimientos de otros módulos. |
| Reportes | Indicadores y resumen ejecutivo. | Usa información de módulos activos. |

## 8.2 Enfoque B: Educación gamificada

El aprendizaje se construye como una ruta progresiva. Un curso contiene unidades; una unidad contiene clases; una clase puede incluir lectura, actividad, tarea o ejercicio; cada bloque puede cerrar con una evaluación. El usuario obtiene XP, niveles, rachas y logros, pero la dificultad debe depender del conocimiento demostrado y no solo del tiempo de uso.

| Nivel | Elemento | Ejemplo |
| --- | --- | --- |
| 1 | Ruta / Curso | Fundamentos financieros para administrar una PyME. |
| 2 | Unidad | Flujo de caja. |
| 3 | Clase | Diferencia entre utilidad y efectivo disponible. |
| 4 | Actividad / Tarea | Clasificar 10 operaciones como entrada o salida de efectivo. |
| 5 | Examen | Preguntas conceptuales y casos. |
| 6 | Reto aplicado | Mejorar el control de cobros durante una semana o resolver un caso simulado. |
| 7 | Evidencia | Puntaje, explicación, mejora y recomendación siguiente. |

## 8.3 Puente entre ambas áreas: IA

El puente debe ser explícito. Los módulos financieros generan indicadores y eventos. Un motor de reglas identifica señales objetivas. La IA recibe esas señales y contexto mínimo, las explica en lenguaje sencillo y selecciona o recomienda aprendizaje relacionado. Si el usuario entra al área educativa por voluntad propia, la IA puede actuar como orientador, preguntarle su objetivo y construir una ruta usando el catálogo y las fuentes disponibles.

> **Ejemplo completo<br>La empresa tiene 35 % de sus cuentas por cobrar vencidas. El motor financiero calcula el indicador. La regla marca “riesgo de cartera”. La IA explica por qué importa, recomienda una unidad sobre políticas de crédito y cobranza, muestra el documento fuente, y genera un reto: diseñar una política básica de seguimiento para un caso simulado. Después del examen, el sistema observa si la cartera real mejora en los siguientes periodos.**

# 9. Requerimientos funcionales

Las prioridades utilizan MoSCoW: M = imprescindible para el MVP o núcleo; S = importante; C = deseable; W = pospuesto. Los criterios de aceptación están escritos para que el equipo pueda comprobar si la función realmente existe.

## 9.1 Núcleo, empresa, usuarios y seguridad

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-001 | Permitir registro/inicio de sesión y cierre seguro de sesión. | M | Un usuario válido accede y un usuario inválido recibe un mensaje sin revelar información sensible. |
| RF-002 | Crear una empresa o negocio durante el onboarding. | M | El propietario registra nombre, moneda, país y datos mínimos y queda asociado como administrador. |
| RF-003 | Administrar usuarios de una empresa. | M | Un administrador puede invitar, activar, suspender y editar usuarios permitidos. |
| RF-004 | Administrar roles y permisos por módulo y acción. | M | Las acciones no autorizadas son rechazadas tanto en interfaz como en servidor. |
| RF-005 | Mantener aislamiento de datos entre empresas. | M | Un usuario de una empresa no puede consultar registros de otra empresa. |
| RF-006 | Registrar acciones sensibles en bitácora. | M | Se conserva usuario, fecha, módulo, acción y referencia del registro. |
| RF-007 | Permitir recuperación y cambio seguro de contraseña. | S | El usuario puede recuperar el acceso mediante un mecanismo temporal y seguro. |
| RF-008 | Mostrar un perfil de usuario con preferencias educativas y de notificación. | S | El usuario puede editar únicamente los campos autorizados. |

## 9.2 Configuración y activación modular

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-009 | Mostrar un catálogo de módulos disponibles para la empresa. | M | El administrador ve módulos activos, disponibles y dependencias. |
| RF-010 | Permitir activar módulos de acuerdo con las necesidades del negocio. | M | Al activar un módulo aparecen sus menús, permisos y configuración inicial. |
| RF-011 | Permitir desactivar un módulo sin eliminar su información histórica. | S | El módulo deja de operar, pero sus datos permanecen protegidos y recuperables. |
| RF-012 | Validar dependencias antes de activar o desactivar módulos. | M | El sistema explica qué módulo necesita otro y evita configuraciones inválidas. |
| RF-013 | Configurar moneda principal y formato de importes. | M | Los cálculos y visualizaciones usan la moneda configurada. |
| RF-014 | Configurar numeraciones y estados básicos de documentos. | S | Los nuevos documentos siguen la secuencia establecida. |
| RF-015 | Mostrar una guía inicial según los módulos seleccionados. | S | El usuario recibe pasos simples para comenzar a utilizar cada módulo activo. |

## 9.3 Clientes, facturación y ventas

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-016 | Registrar clientes naturales o jurídicos con datos básicos. | M | El cliente queda disponible para documentos y consultas. |
| RF-017 | Crear productos o servicios vendibles con código, descripción, precio y estado. | M | Cada artículo tiene identificador único dentro de la empresa. |
| RF-018 | Crear factura o comprobante interno con cliente, artículos, cantidades, descuentos e impuestos configurables. | M | El sistema calcula subtotal, ajustes y total correctamente. |
| RF-019 | Manejar estados del documento de venta: borrador, emitido, pagado, parcial, vencido o anulado. | M | Solo se permiten transiciones válidas y auditadas. |
| RF-020 | Registrar venta de contado o crédito. | M | La venta de crédito genera saldo por cobrar; la de contado puede quedar pagada. |
| RF-021 | Registrar cobros parciales o completos asociados a una venta. | M | El saldo pendiente se actualiza sin permitir valores inconsistentes. |
| RF-022 | Generar un comprobante interno imprimible o exportable. | S | El documento muestra datos de empresa, cliente, detalle, totales y número. |
| RF-023 | Permitir búsqueda y filtros de ventas por fecha, cliente, estado y monto. | S | Los filtros devuelven resultados y totales coherentes. |
| RF-024 | Permitir anular un documento emitido mediante motivo y usuario responsable. | M | La anulación no elimina físicamente el documento y conserva trazabilidad. |
| RF-025 | Integrar ventas con inventario cuando el módulo esté activo. | M | Una venta confirmada descuenta o reserva las cantidades según la política configurada. |
| RF-026 | Integrar ventas a crédito con CxC cuando el módulo esté activo. | M | La obligación muestra origen, vencimiento, saldo y estado. |
| RF-027 | Mostrar indicadores simples de ventas. | S | El usuario consulta ventas del periodo, ticket promedio y principales clientes/productos cuando existan datos. |

## 9.4 Inventario

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-028 | Administrar productos, servicios, categorías y unidades de medida. | M | Los artículos pueden crearse, editarse y desactivarse. |
| RF-029 | Registrar existencia inicial por producto. | M | El saldo inicial queda trazado como movimiento. |
| RF-030 | Registrar entradas, salidas y ajustes de inventario. | M | Cada movimiento modifica el saldo y conserva motivo y responsable. |
| RF-031 | Mantener historial/kardex por producto. | M | Se visualizan fecha, tipo, referencia, cantidad y saldo resultante. |
| RF-032 | Evitar existencias negativas salvo configuración expresa. | M | Una salida mayor que la disponibilidad es rechazada o advertida según política. |
| RF-033 | Configurar stock mínimo y punto de reposición. | S | El sistema identifica productos por debajo del límite. |
| RF-034 | Integrar recepción de compras con inventario. | S | Una compra recibida aumenta existencias si ambos módulos están activos. |
| RF-035 | Consultar inventario valorizado de forma operativa. | S | El reporte muestra cantidades y costo configurado sin presentarlo como contabilidad oficial. |
| RF-036 | Permitir inventario de servicios sin stock físico. | S | Un servicio puede venderse sin generar movimientos de existencias. |
| RF-037 | Generar señales para IA relacionadas con rotación, quiebres o exceso de inventario. | C | El sistema produce indicadores estructurados que la IA puede interpretar. |

## 9.5 Compras, proveedores y cuentas por pagar

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-038 | Registrar proveedores y condiciones básicas. | M | El proveedor queda disponible para compras y obligaciones. |
| RF-039 | Registrar una compra con proveedor, detalle, costos y fecha. | M | El sistema calcula el total y conserva el documento de origen. |
| RF-040 | Registrar compra de contado o crédito. | M | La compra a crédito genera una cuenta por pagar; la de contado puede quedar pagada. |
| RF-041 | Registrar pagos parciales o completos a proveedores. | M | El saldo de la obligación se actualiza correctamente. |
| RF-042 | Controlar vencimientos de cuentas por pagar. | M | Se muestran próximas a vencer, vencidas y pagadas. |
| RF-043 | Clasificar obligaciones por antigüedad. | S | La vista agrupa saldos por rangos de días configurados. |
| RF-044 | Permitir asociar documento o evidencia a una compra. | S | El archivo queda relacionado con la compra y protegido por permisos. |
| RF-045 | Integrar compras con inventario cuando corresponda. | S | La recepción incrementa stock y conserva referencia a la compra. |
| RF-046 | Generar señales para IA por concentración de proveedores, vencimientos o presión de pagos. | C | La IA recibe indicadores agregados, no necesita leer todos los documentos transaccionales. |

## 9.6 Cuentas por cobrar, caja y movimientos financieros

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-047 | Consultar cartera de cuentas por cobrar. | M | Cada cuenta muestra cliente, origen, vencimiento, saldo y estado. |
| RF-048 | Registrar abonos y cobros sin superar el saldo pendiente, salvo anticipo documentado. | M | El sistema mantiene saldo consistente. |
| RF-049 | Clasificar cartera por antigüedad. | M | El usuario ve rangos de cartera vigente y vencida. |
| RF-050 | Registrar cuentas financieras simples: caja, banco u otra. | M | Cada cuenta tiene nombre, moneda y saldo operativo. |
| RF-051 | Registrar entradas y salidas manuales de dinero con categoría. | M | El movimiento conserva cuenta, monto, fecha, categoría y responsable. |
| RF-052 | Generar movimientos automáticos desde cobros y pagos cuando se configure. | S | Cobros/pagos confirmados impactan la cuenta seleccionada una sola vez. |
| RF-053 | Consultar flujo simple de entradas y salidas por periodo. | M | El reporte diferencia ingresos y egresos y muestra saldo neto. |
| RF-054 | Impedir eliminación física de movimientos financieros confirmados. | M | Las correcciones se hacen mediante anulación/reversión auditada. |
| RF-055 | Calcular indicadores básicos para diagnóstico educativo. | M | El sistema puede producir métricas como cartera vencida, liquidez operativa simple, margen configurado o concentración de gastos cuando existan datos suficientes. |

## 9.7 Dashboard, reportes y notificaciones

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-056 | Mostrar dashboard según módulos activos y permisos. | M | El usuario solo ve indicadores que corresponden a sus módulos y rol. |
| RF-057 | Mostrar tarjetas de situación: ventas, cobros pendientes, pagos próximos, inventario y flujo. | M | Las cifras coinciden con los registros transaccionales. |
| RF-058 | Mostrar alertas operativas con severidad. | S | Las alertas explican el evento y llevan al registro relacionado. |
| RF-059 | Exportar reportes básicos a formatos comunes. | C | La exportación respeta filtros y permisos. |
| RF-060 | Mostrar una sección “Qué puedo mejorar” alimentada por reglas e IA. | M | Cada recomendación indica motivo, nivel de prioridad y ruta educativa sugerida cuando aplique. |
| RF-061 | Registrar si el usuario acepta, ignora o completa una recomendación. | S | El estado permite medir utilidad y evitar repetir recomendaciones innecesarias. |

## 9.8 Biblioteca de fuentes educativas

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-062 | Registrar instituciones fuente. | M | Cada institución tiene nombre y datos de referencia. |
| RF-063 | Registrar documentos o recursos oficiales de acceso público. | M | Cada fuente conserva título, institución, URL, fecha/versión conocida y estado de revisión. |
| RF-064 | Conservar metadatos sobre permiso/licencia o condición de uso cuando esté disponible. | M | El registro indica si el material puede almacenarse, resumirse o solo enlazarse. |
| RF-065 | Permitir asociar un documento a uno o varios temas. | M | El mismo documento puede apoyar distintas unidades sin duplicarse. |
| RF-066 | Permitir fragmentar/indexar una fuente para búsqueda semántica. | M | El tutor puede recuperar fragmentos relevantes y conservar referencia al documento original. |
| RF-067 | Mostrar al usuario la fuente original usada en una clase o respuesta. | M | La interfaz presenta institución, documento y enlace/referencia disponible. |
| RF-068 | Versionar o desactivar fuentes sin borrar el historial de cursos existentes. | S | La versión anterior queda trazable y la nueva puede reemplazarla para contenido futuro. |
| RF-069 | Permitir revisión humana del material generado antes de publicarlo en el catálogo principal. | S | El contenido puede quedar en borrador, revisado o publicado. |

## 9.9 Cursos, unidades, clases, tareas y exámenes

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-070 | Crear cursos con objetivo, nivel, descripción y temas. | M | El curso puede publicarse o mantenerse en borrador. |
| RF-071 | Dividir cada curso en unidades ordenadas. | M | El usuario avanza por una secuencia visible y medible. |
| RF-072 | Crear clases dentro de unidades. | M | Cada clase puede contener explicación, fuente, ejemplos y actividad. |
| RF-073 | Crear tareas prácticas con instrucciones, entrega y criterios de evaluación. | M | El usuario puede responder y recibir resultado o retroalimentación. |
| RF-074 | Crear exámenes con preguntas de opción, respuesta corta o casos. | M | El sistema calcula puntaje cuando corresponda y registra intentos. |
| RF-075 | Configurar nota mínima, intentos y reglas de aprobación. | M | La unidad se marca completada solo al cumplir las reglas configuradas. |
| RF-076 | Registrar progreso por clase, unidad y curso. | M | El porcentaje se calcula desde actividades reales y no se modifica manualmente por el usuario. |
| RF-077 | Permitir ruta voluntaria: el usuario elige qué quiere aprender. | M | El sistema muestra temas y la IA puede orientar la elección. |
| RF-078 | Permitir ruta recomendada: el sistema propone aprendizaje a partir del diagnóstico. | M | La recomendación explica por qué se sugiere y el usuario decide si iniciar. |
| RF-079 | Adaptar dificultad de actividades según desempeño previo. | S | El siguiente ejercicio puede aumentar, mantener o reducir dificultad dentro de límites definidos. |

## 9.10 Gamificación y retos

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-080 | Asignar XP por clases, tareas, exámenes y retos completados. | M | La XP se otorga una sola vez según reglas para evitar abuso. |
| RF-081 | Calcular nivel del usuario a partir de XP o competencias. | M | El nivel cambia automáticamente al alcanzar los umbrales. |
| RF-082 | Mantener racha de aprendizaje. | S | La racha se actualiza según actividad educativa válida y reglas de día. |
| RF-083 | Crear logros con condición y recompensa. | M | El logro se desbloquea únicamente cuando se cumple su condición. |
| RF-084 | Registrar retos educativos simulados generados por IA. | M | Cada reto conserva tema, dificultad, fuente, instrucciones y criterio de evaluación. |
| RF-085 | Registrar retos aplicados al negocio solo con consentimiento del usuario. | S | El sistema deja claro que es una recomendación y no ejecuta cambios financieros automáticamente. |
| RF-086 | Mostrar progreso, historial de intentos y retroalimentación. | M | El usuario puede ver en qué falló y qué debería repasar. |
| RF-087 | Evitar recompensar respuestas evidentemente vacías o actividades no verificadas. | S | Las reglas de validación impiden otorgar XP sin evidencia mínima. |

## 9.11 Inteligencia artificial: diagnóstico, tutor y generación controlada

| ID | Requerimiento | Pri. | Criterio de aceptación |
| --- | --- | --- | --- |
| RF-088 | Recibir indicadores financieros estructurados, no depender de leer la base completa. | M | El servicio de IA recibe un resumen con métricas y contexto mínimo necesario. |
| RF-089 | Combinar reglas determinísticas con IA para detectar debilidades. | M | La señal financiera se origina en una regla/cálculo; la IA explica y contextualiza. |
| RF-090 | Generar recomendaciones educativas vinculadas a una debilidad detectada. | M | La recomendación indica motivo y referencia a curso/unidad disponible. |
| RF-091 | Permitir al usuario pedir orientación voluntaria sobre qué aprender. | M | La IA pregunta objetivo/nivel cuando sea necesario y propone una ruta del catálogo. |
| RF-092 | Responder preguntas educativas utilizando recuperación de fuentes aprobadas. | M | La respuesta muestra fuente; si no hay evidencia suficiente, lo indica. |
| RF-093 | Permitir consultar o abrir el documento original relacionado. | M | El usuario puede identificar de qué documento proviene la explicación. |
| RF-094 | Generar tareas o retos con prompts internos especializados por tipo. | M | La salida respeta tema, dificultad, formato y fuente definidos por el sistema. |
| RF-095 | Generar preguntas de examen solo a partir de contenido permitido y seleccionado. | M | Cada pregunta conserva referencia de la fuente utilizada. |
| RF-096 | Generar rúbrica o respuesta esperada para evaluar actividades abiertas. | S | La rúbrica queda almacenada y puede ser revisada antes de usarse. |
| RF-097 | Registrar versión del prompt y modelo usado para ejecuciones importantes. | S | Una ejecución puede auditarse y reproducirse conceptualmente. |
| RF-098 | Validar formato de salida estructurado para recomendaciones y retos. | M | El sistema rechaza o regenera una respuesta que no cumpla el esquema esperado. |
| RF-099 | No permitir que la IA modifique saldos, facturas, inventario o pagos directamente. | M | Toda operación financiera requiere lógica del sistema y acción/autorización del usuario. |
| RF-100 | Aplicar límites y mensajes de seguridad ante consultas fuera del alcance. | M | La IA diferencia educación general de asesoría profesional y evita afirmaciones no sustentadas. |
| RF-101 | Registrar retroalimentación del usuario sobre recomendaciones de IA. | C | El usuario puede marcar útil/no útil y el sistema conserva el dato para mejorar reglas futuras. |
| RF-102 | Permitir cambiar el proveedor/modelo de IA mediante una capa de servicio. | S | La lógica principal no depende directamente de un proveedor específico. |

# 10. Requerimientos no funcionales

| ID | Categoría | Requerimiento | Métrica o evidencia |
| --- | --- | --- | --- |
| RNF-001 | Seguridad | Toda comunicación productiva debe usar HTTPS. | No se acepta acceso productivo por HTTP sin redirección segura. |
| RNF-002 | Seguridad | Contraseñas almacenadas con hash robusto. | Ninguna contraseña puede consultarse en texto claro. |
| RNF-003 | Autorización | Los permisos deben validarse en servidor. | Una petición directa sin permiso es rechazada. |
| RNF-004 | Aislamiento | Los datos deben separarse por empresa/tenant. | Pruebas cruzadas no permiten consultar datos de otra empresa. |
| RNF-005 | Privacidad IA | Enviar a IA solo la información mínima necesaria. | Las solicitudes de IA usan indicadores/resúmenes y evitan datos personales innecesarios. |
| RNF-006 | Trazabilidad IA | Registrar tipo de función, modelo, prompt versionado y resultado importante. | El equipo puede identificar cómo se produjo una recomendación o reto. |
| RNF-007 | Rendimiento | Las vistas habituales deben responder en tiempo razonable. | Objetivo: 95 % de consultas comunes en 3 segundos o menos bajo carga del MVP, excluyendo tiempos de proveedores externos de IA. |
| RNF-008 | Asincronía | Procesos lentos de IA no deben bloquear toda la interfaz. | La interfaz muestra estado de procesamiento y permite continuar cuando sea posible. |
| RNF-009 | Disponibilidad | La base de datos debe tener respaldo periódico. | Existe evidencia de copia y procedimiento de restauración. |
| RNF-010 | Usabilidad | La interfaz debe ser comprensible para personas no técnicas. | Tareas principales pueden completarse con textos simples y sin manual técnico. |
| RNF-011 | Responsividad | Las pantallas principales deben funcionar en escritorio, tableta y teléfono. | No hay desbordamientos o controles inutilizables en resoluciones objetivo. |
| RNF-012 | Accesibilidad | Controles con etiquetas, contraste y navegación básica por teclado. | Formularios críticos son utilizables sin depender exclusivamente del ratón. |
| RNF-013 | Mantenibilidad | El código debe separar núcleo, módulos, educación e IA. | Agregar un módulo no requiere modificar funciones no relacionadas. |
| RNF-014 | Portabilidad IA | El acceso al proveedor de IA se realiza mediante interfaz/adaptador. | Se puede sustituir proveedor con cambios acotados. |
| RNF-015 | Configurabilidad | Prompts, umbrales y reglas variables no deben quedar dispersos en código. | Se gestionan en configuración/versionado controlado. |
| RNF-016 | Calidad de datos | Validar formatos, duplicados y campos obligatorios. | Datos inválidos se rechazan antes de confirmarse. |
| RNF-017 | Integridad | Operaciones relacionadas deben ser transaccionales cuando corresponda. | Un fallo no deja saldos o existencias parcialmente actualizados. |
| RNF-018 | Observabilidad | Errores técnicos deben registrarse sin exponer secretos al usuario. | Producción muestra mensajes simples y conserva detalle en logs protegidos. |
| RNF-019 | Fuentes | Cada pieza educativa generada debe mantener trazabilidad a sus fuentes. | Puede identificarse institución y documento utilizado. |
| RNF-020 | Contenido | La IA no debe presentar una fuente inexistente o no recuperada como si hubiera sido consultada. | Si no existe evidencia, la respuesta lo declara y no inventa referencia. |
| RNF-021 | Evaluación | Preguntas y retos deben conservar dificultad y criterio de corrección. | Una actividad puede revisarse o reproducirse con su configuración. |
| RNF-022 | Pruebas | Funciones críticas deben contar con pruebas automatizadas o escenarios reproducibles. | Autenticación, permisos, cálculos, flujo de venta/cobro y validadores de IA tienen evidencia. |
| RNF-023 | Despliegue | El prototipo debe poder desplegarse de forma reproducible. | Existe configuración de entorno y procedimiento de despliegue. |
| RNF-024 | Escalabilidad funcional | El diseño debe admitir nuevos módulos y nuevos tipos de fuentes educativas. | Un módulo nuevo puede registrarse sin rehacer las tablas principales. |
| RNF-025 | Costo IA | El consumo de IA debe controlarse. | Se registran llamadas/tokens/costo estimado o un equivalente por función. |
| RNF-026 | Experiencia IA | La IA debe responder en lenguaje sencillo y con acciones claras. | Las recomendaciones evitan jerga innecesaria y muestran un siguiente paso. |
| RNF-027 | Compatibilidad | La aplicación debe funcionar en navegadores modernos. | Pruebas en Chrome/Edge/Firefox no presentan errores bloqueantes. |
| RNF-028 | Legal/contenido | El sistema debe respetar condiciones de uso y derechos de los documentos fuente. | Una fuente no aprobada para almacenamiento se enlaza o usa según la política definida; no se redistribuye indebidamente. |

# 11. Reglas de negocio

| ID | Regla |
| --- | --- |
| RN-001 | Cada usuario debe operar dentro de una empresa y de los módulos que tenga habilitados. |
| RN-002 | Desactivar un módulo no elimina automáticamente la información histórica. |
| RN-003 | Los documentos financieros confirmados no se eliminan físicamente; se anulan o reversan. |
| RN-004 | Un cobro no puede superar el saldo por cobrar salvo que exista manejo explícito de anticipos. |
| RN-005 | Un pago no puede superar el saldo por pagar salvo política documentada. |
| RN-006 | Una salida de inventario no puede superar la existencia si la política no lo permite. |
| RN-007 | Toda modificación de saldo o existencia debe originarse en una operación trazable. |
| RN-008 | La IA no puede ejecutar por sí sola una operación financiera. |
| RN-009 | Toda recomendación financiera-educativa debe guardar la señal o indicador que la originó. |
| RN-010 | Una recomendación puede ser aceptada, pospuesta, descartada o completada por el usuario. |
| RN-011 | La IA no debe inventar documentos, universidades, títulos o referencias. |
| RN-012 | Toda respuesta educativa basada en fuentes debe poder relacionarse con al menos una fuente recuperada. |
| RN-013 | Si una fuente no tiene permiso claro para almacenamiento completo, el sistema conservará únicamente metadatos, fragmentos permitidos o enlace según corresponda. |
| RN-014 | Los contenidos generados por IA deben distinguirse del texto original de la fuente. |
| RN-015 | Un examen aprobado requiere cumplir la nota mínima configurada. |
| RN-016 | La XP no debe multiplicarse indefinidamente por repetir la misma actividad sin condición. |
| RN-017 | Los retos aplicados al negocio deben requerir consentimiento y no deben obligar al usuario a tomar una decisión financiera. |
| RN-018 | El nivel de dificultad de un reto debe estar dentro del rango definido para la ruta y el usuario. |
| RN-019 | Los prompts internos del sistema no son editables por usuarios finales. |
| RN-020 | Los cambios en prompts críticos deben versionarse. |
| RN-021 | La plataforma no presentará el aprendizaje como una certificación oficial de la universidad fuente salvo acuerdo explícito. |
| RN-022 | Los indicadores deben calcularse con datos confirmados y reglas conocidas antes de enviarse a IA. |
| RN-023 | La falta de datos suficientes debe producir “información insuficiente”, no una conclusión inventada. |
| RN-024 | Los permisos de reportes y documentos deben ser iguales o más restrictivos que los permisos de los datos originales. |

# 12. Casos de uso principales

| ID | Caso de uso | Actor principal | Resultado |
| --- | --- | --- | --- |
| CU-01 | Crear empresa y seleccionar módulos | Propietario | Queda configurado un espacio de trabajo con módulos activos. |
| CU-02 | Registrar una venta | Ventas/Administrador | Se genera documento, total y movimiento relacionado. |
| CU-03 | Registrar venta a crédito | Ventas | Se genera una cuenta por cobrar. |
| CU-04 | Registrar cobro | Caja/Finanzas | Se reduce la CxC y se registra entrada de dinero. |
| CU-05 | Registrar compra a crédito | Compras | Se genera cuenta por pagar y, si aplica, recepción de inventario. |
| CU-06 | Registrar pago a proveedor | Finanzas | Se reduce CxP y se registra salida. |
| CU-07 | Consultar inventario | Administrador/Bodega | Se visualizan existencias, mínimos y movimientos. |
| CU-08 | Consultar dashboard | Gerencia | Obtiene una lectura rápida del negocio. |
| CU-09 | Recibir recomendación educativa automática | Propietario/Administrador | Comprende la debilidad detectada y puede iniciar una ruta. |
| CU-10 | Buscar qué aprender por voluntad propia | Usuario | La IA orienta y muestra rutas/documentos disponibles. |
| CU-11 | Tomar una clase | Usuario aprendiz | Consulta contenido, fuentes y actividad. |
| CU-12 | Completar tarea | Usuario aprendiz | Entrega respuesta y recibe retroalimentación. |
| CU-13 | Realizar examen | Usuario aprendiz | Obtiene puntaje, errores y temas a repasar. |
| CU-14 | Generar reto con IA | Sistema/Usuario | Se crea reto controlado con dificultad, fuente y rúbrica. |
| CU-15 | Completar reto aplicado | Usuario | Registra evidencia y obtiene retroalimentación/XP cuando corresponde. |
| CU-16 | Preguntar al tutor | Usuario | Obtiene explicación basada en fuentes recuperadas. |
| CU-17 | Consultar documento fuente | Usuario | Abre o identifica el recurso original utilizado. |
| CU-18 | Publicar contenido educativo | Administrador de contenido | Una ruta pasa de borrador/revisión a publicada. |
| CU-19 | Cambiar prompt versionado | Administrador técnico | Nueva versión se usa sin borrar trazabilidad anterior. |
| CU-20 | Auditar una recomendación | Administrador autorizado | Puede ver señal, prompt/modelo y resultado asociado. |

## 12.1 Caso detallado: la IA detecta una debilidad y recomienda aprendizaje

| Elemento | Descripción |
| --- | --- |
| Actor principal | Propietario o administrador. |
| Precondiciones | Usuario autenticado; empresa con datos suficientes; módulo relacionado activo; reglas de diagnóstico disponibles. |
| Disparador | El dashboard actualiza indicadores o el usuario solicita “Analizar mi situación”. |
| Flujo principal | 1) El sistema calcula indicadores. 2) Una regla identifica una señal. 3) Se prepara un resumen sin datos innecesarios. 4) La IA explica la señal. 5) El recomendador busca cursos/unidades relacionados. 6) Se muestra motivo, prioridad y fuente. 7) El usuario decide iniciar, posponer o descartar. |
| Alternos | No hay datos suficientes; no existe curso relacionado; servicio de IA no disponible; recomendación duplicada. |
| Postcondición | La recomendación queda registrada con indicador origen, estado y versión de IA utilizada. |
| Éxito | El usuario comprende la situación y puede comenzar una ruta concreta sin que la IA modifique sus finanzas. |

## 12.2 Caso detallado: generar un reto educativo con IA

| Elemento | Descripción |
| --- | --- |
| Actor principal | Usuario aprendiz o sistema recomendador. |
| Precondiciones | Existe tema, nivel, clase/fuente aprobada y una plantilla de prompt activa. |
| Flujo principal | 1) Seleccionar tema y dificultad. 2) Recuperar fragmentos fuente. 3) Enviar prompt interno con esquema de salida. 4) Validar respuesta. 5) Guardar reto, rúbrica, fuente y versión. 6) Presentar al usuario. 7) Recibir respuesta. 8) Evaluar y retroalimentar. |
| Regla crítica | El reto debe ser resoluble con el contenido de la ruta o con un caso explícitamente marcado como simulado. |
| Postcondición | Se registra intento, resultado, retroalimentación y XP cuando corresponde. |
| Éxito | El reto exige razonamiento real y el usuario puede comprender por qué su respuesta fue correcta o incorrecta. |

# 13. Modelo conceptual de datos

Este apartado no define todavía todas las columnas de la base de datos. Define qué “cosas” importantes deberá reconocer el sistema y cómo se relacionan. Para el equipo no técnico, una entidad puede entenderse como un tipo de registro que la plataforma necesita guardar.

| Área | Entidad | Propósito |
| --- | --- | --- |
| Núcleo | Empresa | Negocio dueño de la información. |
| Núcleo | Usuario | Persona que accede. |
| Núcleo | Rol / Permiso | Define qué puede hacer. |
| Núcleo | Módulo | Capacidad activable. |
| Núcleo | EmpresaMódulo | Qué módulos tiene activa cada empresa. |
| Núcleo | Bitácora | Registro de acciones sensibles. |
| Finanzas | Cliente | Persona/empresa que compra. |
| Finanzas | Producto/Servicio | Artículo vendido o gestionado. |
| Finanzas | Factura/Venta | Documento de ingreso comercial. |
| Finanzas | DetalleVenta | Líneas de la venta. |
| Finanzas | CuentaPorCobrar | Saldo que un cliente debe. |
| Finanzas | Cobro | Pago recibido. |
| Finanzas | Proveedor | Persona/empresa proveedora. |
| Finanzas | Compra | Documento de adquisición. |
| Finanzas | CuentaPorPagar | Saldo que la empresa debe. |
| Finanzas | Pago | Dinero entregado al proveedor. |
| Finanzas | CuentaFinanciera | Caja, banco u otra cuenta operativa. |
| Finanzas | MovimientoFinanciero | Entrada/salida de dinero. |
| Finanzas | MovimientoInventario | Entrada/salida/ajuste de stock. |
| Educación | InstituciónFuente | Universidad u organización responsable de la fuente. |
| Educación | DocumentoFuente | PDF, guía, artículo o recurso oficial/publicado. |
| Educación | Tema | Clasificación: caja, crédito, inventario, administración, etc. |
| Educación | Curso | Ruta de aprendizaje. |
| Educación | Unidad | Bloque temático. |
| Educación | Clase | Contenido estudiable. |
| Educación | Tarea | Actividad práctica. |
| Educación | Examen | Evaluación formal de una unidad/curso. |
| Educación | Pregunta | Ítem evaluativo. |
| Educación | Intento | Respuesta y resultado del usuario. |
| Educación | Progreso | Avance por usuario. |
| Gamificación | PerfilGamificación | XP, nivel, racha. |
| Gamificación | Logro | Meta desbloqueable. |
| Gamificación | UsuarioLogro | Logros conseguidos. |
| Gamificación | Reto | Desafío educativo o aplicado. |
| Gamificación | UsuarioReto | Progreso y evidencia de un reto. |
| IA | IndicadorDiagnóstico | Métrica calculada que puede disparar análisis. |
| IA | RecomendaciónIA | Consejo educativo generado/explicado. |
| IA | ConversaciónIA | Sesión de tutoría. |
| IA | MensajeIA | Interacción del tutor. |
| IA | PlantillaPrompt | Prompt interno versionado. |
| IA | EjecuciónIA | Registro técnico de una llamada importante. |

## 13.1 Relaciones principales

- Una empresa tiene usuarios y módulos activos.

- Una venta puede originar una cuenta por cobrar y uno o varios cobros.

- Una compra puede originar una cuenta por pagar y uno o varios pagos.

- Las ventas y compras pueden generar movimientos de inventario cuando el módulo está activo.

- Los cobros y pagos pueden generar movimientos en cuentas financieras.

- Un documento fuente puede relacionarse con múltiples temas, clases, preguntas y retos.

- Un curso contiene unidades; cada unidad contiene clases, tareas y evaluaciones.

- Un usuario tiene progreso, XP, logros, intentos y retos.

- Un indicador de negocio puede originar una recomendación de IA.

- Una recomendación puede apuntar a un curso, unidad, clase o documento fuente.

- Una ejecución importante de IA debe relacionarse con la plantilla de prompt y su versión.

# 14. Diseño funcional de la inteligencia artificial

## 14.1 Qué debe hacer la IA

| Función | Entrada | Salida |
| --- | --- | --- |
| Explicador financiero | Indicadores ya calculados + contexto mínimo. | Explicación simple de qué significa la situación. |
| Recomendador educativo | Debilidad detectada + catálogo de aprendizaje. | Curso/unidad/documento recomendado y motivo. |
| Orientador voluntario | Objetivo del usuario + nivel + catálogo. | Ruta de aprendizaje sugerida. |
| Tutor con fuentes | Pregunta + fragmentos recuperados de fuentes aprobadas. | Respuesta explicada con referencias. |
| Generador de retos | Tema + fuente + dificultad + plantilla. | Reto, instrucciones, criterios, pistas opcionales. |
| Generador de evaluación | Clase/unidad + fuentes + objetivos. | Preguntas, respuesta esperada y explicación. |
| Evaluador asistido | Respuesta del usuario + rúbrica. | Puntaje sugerido, fortalezas, errores y retroalimentación. |

## 14.2 Qué NO debe hacer la IA

- Calcular saldos oficiales cuando esos cálculos pueden hacerse con reglas del sistema.

- Inventar movimientos, facturas, pagos, existencias o indicadores.

- Cambiar datos financieros directamente.

- Crear referencias académicas que no fueron recuperadas.

- Presentar una explicación generada como texto literal de una universidad.

- Garantizar resultados financieros o sustituir asesoría contable/legal profesional.

- Usar información de otra empresa o usuario para responder.

## 14.3 Pipeline recomendado

1. El sistema financiero calcula métricas con código normal.

1. El motor de reglas decide si existe una señal relevante.

1. Se prepara un objeto de contexto mínimo (porcentajes, tendencias, categoría, periodo).

1. Se buscan rutas educativas y fuentes relacionadas.

1. Se llama al servicio de IA con una plantilla específica.

1. La salida se valida contra un formato estructurado.

1. Se guarda la recomendación con origen, versión y estado.

1. El usuario decide si desea actuar o estudiar.

## 14.4 Prompts internos especializados

No se recomienda un solo “prompt gigante”. Cada función debe tener su propia plantilla. Las plantillas deben guardarse con nombre, propósito, versión, esquema de salida y fecha. El usuario final no ve ni modifica estas instrucciones.

| Prompt interno | Objetivo | Salida mínima |
| --- | --- | --- |
| financial_weakness_explainer | Explicar una debilidad ya detectada. | Resumen, por qué importa, nivel de prioridad, siguiente paso. |
| learning_path_recommender | Elegir la mejor ruta existente. | Curso/unidad, motivo, prerequisitos. |
| source_grounded_tutor | Responder únicamente con fuentes recuperadas. | Respuesta, referencias, límites/incertidumbre. |
| challenge_generator | Crear reto realista. | Contexto, consigna, dificultad, criterios, fuente, pistas. |
| quiz_generator | Crear preguntas de evaluación. | Pregunta, opciones si aplica, respuesta, explicación, fuente. |
| open_answer_grader | Evaluar una tarea abierta con rúbrica. | Puntaje, evidencia, fortalezas, errores, recomendación. |

## 14.5 Ejemplo de salida estructurada de una recomendación

> **Ejemplo conceptual<br>Indicador: 35 % de cartera vencida. Regla: cartera vencida > 25 %. Recomendación: “Tu negocio tiene una parte importante de cobros atrasados. Conviene revisar políticas de crédito y seguimiento.” Ruta sugerida: Gestión de cuentas por cobrar → Unidad 2: Crédito y cobranza. Prioridad: alta. Acción: Estudiar ahora / Ver fuente / Posponer.**

# 15. Diseño del aprendizaje y gamificación

## 15.1 Estructura de aprendizaje

| Elemento | Qué debe contener |
| --- | --- |
| Curso | Objetivo general, nivel, competencias, fuentes principales y requisitos. |
| Unidad | Objetivos concretos, clases obligatorias, tareas y evaluación. |
| Clase | Explicación sencilla, conceptos, ejemplos, fuente original y mini actividad. |
| Tarea | Caso o ejercicio que obliga a aplicar la teoría. |
| Examen | Preguntas variadas y casos, no solo memorización. |
| Reto | Problema más abierto que exige decidir, justificar o mejorar algo. |
| Retroalimentación | Qué hizo bien, qué falló, por qué y qué debe repasar. |

## 15.2 Dificultad real

La gamificación no debe convertir el curso en un juego superficial. El progreso debe exigir conocimiento. Se recomienda una curva: explicación → práctica guiada → práctica sin guía → examen → reto. La IA puede cambiar números y contexto de un caso para reducir respuestas memorizadas, pero siempre respetando la fuente y la dificultad.

## 15.3 Tipos de reto

| Tipo | Descripción | Ejemplo |
| --- | --- | --- |
| Conceptual | Explicar o comparar conceptos. | ¿Por qué utilidad no significa necesariamente efectivo disponible? |
| Cálculo | Resolver números sencillos o intermedios. | Calcular margen, plazo promedio o punto de equilibrio con un caso dado. |
| Decisión | Elegir entre alternativas y justificar. | Priorizar pago a proveedores con restricciones de caja. |
| Caso simulado | Administrar una empresa ficticia. | Reducir cartera vencida en un escenario con 6 clientes. |
| Aplicado opcional | Usar indicadores propios con consentimiento. | Crear un plan de cobro para la cartera real sin modificar datos automáticamente. |
| Reflexión | Explicar aprendizaje o error. | ¿Qué regla cambiarías en tu negocio después de esta unidad? |

## 15.4 Sistema de progreso

- XP: recompensa por completar acciones válidas.

- Nivel: resume avance general, pero no reemplaza notas o competencias.

- Racha: motiva constancia; no debe penalizar de forma agresiva.

- Logros: reconocen hitos específicos.

- Competencias: indican qué temas domina el usuario.

- Reintentos: permitidos según actividad, mostrando mejora entre intentos.

- Recomendación adaptativa: el usuario que falla repetidamente recibe repaso, no simplemente más XP.

# 16. Fuentes educativas y control de contenido

El objetivo es utilizar documentos oficiales y públicos relacionados con finanzas, administración, emprendimiento, contabilidad gerencial, operaciones u otros temas útiles. Pueden provenir de universidades reconocidas —por ejemplo Harvard, MIT, Yale, Stanford u otras— siempre que el recurso concreto sea público y su uso dentro de la plataforma sea compatible con sus condiciones de acceso y derechos.

## 16.1 Política de fuentes

1. Priorizar dominios y repositorios oficiales de la institución.

1. Guardar título, institución, URL, fecha, autor cuando exista y versión.

1. Registrar qué uso se permite: almacenar, enlazar, indexar, resumir o citar.

1. No asumir que “está en internet” significa que puede copiarse completo.

1. Mantener separada la fuente original del contenido explicado/generado por IA.

1. Mostrar referencias al usuario en clases, tutor y retos cuando corresponda.

1. Revisar manualmente las primeras rutas del MVP antes de publicarlas.

## 16.2 Flujo de incorporación de una fuente

1. Administrador encuentra un documento oficial público.

1. Registra institución, URL y metadatos.

1. Revisa condición de acceso/uso.

1. El sistema extrae o indexa el contenido permitido.

1. Se asignan temas y nivel.

1. La IA puede proponer resumen, objetivos, clases y preguntas.

1. Una revisión humana valida el material del catálogo.

1. Se publica con enlace a la fuente original.

# 17. Seguridad, privacidad y uso responsable de IA

## 17.1 Datos financieros

- Los datos de cada empresa deben permanecer aislados.

- No enviar nombres de clientes, correos, documentos o descripciones sensibles a la IA si no son necesarios.

- Preferir indicadores agregados: porcentajes, tendencias, categorías y periodos.

- No utilizar datos de una empresa para entrenar o responder a otra dentro del producto.

- Mantener secretos/API keys fuera del frontend y del repositorio.

## 17.2 Respuestas de IA

- Diferenciar claramente cálculo del sistema, explicación de IA y fuente académica.

- Permitir que la IA diga “no tengo suficiente información” o “no encontré una fuente aprobada”.

- Evitar lenguaje de garantía (“vas a ganar”, “esta es la decisión correcta”) en decisiones financieras.

- Mostrar que el contenido es educativo y no reemplaza asesoría contable, fiscal, legal o financiera profesional cuando el caso lo requiera.

- Registrar feedback para identificar respuestas poco útiles o problemáticas.

# 18. Plan de pruebas y criterios de aceptación

| Tipo | Qué se prueba | Evidencia esperada |
| --- | --- | --- |
| Unitarias | Cálculos de totales, saldos, inventario, XP y reglas. | Resultados esperados para casos conocidos. |
| Integración | Venta→CxC→Cobro→Caja; compra→CxP→Pago; venta/compra→inventario. | No existen registros parciales o dobles. |
| Permisos | Usuarios intentando acceder a módulos no habilitados. | Acceso rechazado. |
| Aislamiento | Empresa A intentando consultar datos de B. | No existe fuga de datos. |
| IA estructurada | Salida de recomendador, tutor, retos y quiz. | Cumple esquema o se rechaza/regenera. |
| Grounding | Tutor responde con y sin fuente disponible. | Cita fuente recuperada o declara falta de evidencia. |
| Gamificación | XP, niveles, logros, reintentos. | No existe duplicación indebida. |
| Usabilidad | Persona no técnica completa escenarios. | Completa flujo sin explicación técnica extensa. |
| Rendimiento | Dashboard y listados comunes. | Respuesta dentro del objetivo del MVP. |
| Demo integral | Escenario de principio a fin. | El jurado observa el ciclo finanzas→diagnóstico→aprendizaje→reto. |

## 18.1 Criterios de aceptación del MVP

- Una empresa puede crear su espacio y seleccionar módulos.

- Es posible registrar al menos una venta, una compra, inventario y cobros/pagos básicos.

- El dashboard muestra cifras coherentes con esas operaciones.

- Existe al menos una regla financiera que genere una señal real.

- La IA transforma esa señal en una explicación y recomienda una ruta educativa.

- El usuario puede abrir la ruta, completar una clase, tarea/examen y recibir progreso.

- La IA puede generar al menos un reto válido con fuente y dificultad.

- El tutor responde al menos un tema usando fuentes curadas y muestra su procedencia.

- XP, nivel o logro se actualiza al completar actividades.

- La IA no modifica datos financieros automáticamente.

- La demostración funciona con datos de prueba reproducibles.

# 19. Riesgos y respuestas

| ID | Riesgo | Prob. | Impacto | Respuesta |
| --- | --- | --- | --- | --- |
| R-01 | Querer construir demasiados módulos | Alta | Alto | Congelar MVP y priorizar el ciclo demostrable. |
| R-02 | IA inventa información o fuentes | Media | Muy alto | RAG con fuentes aprobadas, validación y regla de “sin evidencia, no afirmar”. |
| R-03 | Uso indebido de material universitario | Media | Alto | Registrar licencia/condición de uso; enlazar cuando no corresponda almacenar. |
| R-04 | Datos sensibles enviados al proveedor de IA | Media | Muy alto | Resumen agregado, minimización, filtros y secretos en backend. |
| R-05 | Recomendaciones financieras incorrectas | Media | Alto | Cálculos y señales por reglas; IA solo explica/recomienda aprendizaje. |
| R-06 | Retos demasiado fáciles o absurdos | Alta | Medio | Plantillas por dificultad, rúbricas, fuente obligatoria y pruebas previas. |
| R-07 | Costo o latencia de IA | Media | Medio | Cache, límites, modelos adecuados por función y llamadas asíncronas. |
| R-08 | Arquitectura demasiado acoplada al proveedor IA | Media | Alto | AIService + adapters y formatos internos. |
| R-09 | Interfaz compleja para PyMEs | Media | Alto | Activación modular, lenguaje simple, onboarding y pruebas con usuarios. |
| R-10 | Datos de demo insuficientes para detectar debilidades | Alta | Medio | Preparar dataset de demostración con escenarios diseñados. |
| R-11 | Gamificación distrae del aprendizaje | Media | Medio | XP ligado a evidencia y dificultad; exámenes/retos obligatorios para dominar temas. |
| R-12 | Fallas de integración entre módulos | Media | Alto | Eventos/servicios claros y pruebas de integración de flujos críticos. |

# 20. Roadmap después del hackathon

| Fase | Objetivo | Posibles ampliaciones |
| --- | --- | --- |
| MVP Hackathon | Probar la propuesta de valor completa. | Núcleo + módulos básicos + 3 rutas + IA puente. |
| Fase 2 | Profundizar gestión. | Cotizaciones, múltiples almacenes, conciliación, mejores reportes, presupuestos. |
| Fase 3 | Profundizar educación. | Más fuentes, competencias, rutas adaptativas, banco de preguntas, panel de progreso. |
| Fase 4 | Integraciones. | Bancos, facturación electrónica donde sea viable, importadores de datos, APIs. |
| Fase 5 | Inteligencia avanzada. | Análisis temporal, recomendaciones más personalizadas, evaluación longitudinal de mejora. |
| Fase 6 | Ecosistema. | Contadores/asesores, plantillas sectoriales, marketplace de módulos o contenidos permitidos. |

# 21. Beneficios esperados

| Beneficio | Descripción |
| --- | --- |
| Entrada gradual | Una microempresa puede iniciar con pocos módulos y crecer después. |
| Control operativo | Ventas, deudas, pagos e inventario dejan de estar dispersos. |
| Mejor lectura del negocio | Los indicadores se convierten en explicaciones y acciones. |
| Aprendizaje relevante | El usuario estudia lo que necesita o lo que le interesa, con contexto. |
| Fuentes confiables | Las explicaciones remiten a material oficial y público previamente curado. |
| Aprendizaje práctico | Tareas, exámenes y retos exigen aplicar conceptos. |
| IA mantenible | La IA queda detrás de servicios, prompts versionados y reglas claras. |
| Escalabilidad | Se pueden añadir módulos financieros y rutas educativas sin rehacer el producto. |
| Valor para el hackathon | El proyecto demuestra problema real, integración, IA útil, gamificación y un MVP defendible. |

# 22. Glosario para el equipo

| Término | Explicación sencilla |
| --- | --- |
| PyME / MIPyME | Pequeña, mediana o microempresa. |
| Módulo | Parte del sistema que puede activarse o no. |
| CxC | Cuentas por cobrar: dinero que los clientes todavía deben a la empresa. |
| CxP | Cuentas por pagar: dinero que la empresa todavía debe a proveedores. |
| Kardex | Historial de entradas, salidas y saldo de un producto. |
| Dashboard | Pantalla resumen con indicadores importantes. |
| Indicador | Número calculado que ayuda a entender una situación. |
| Regla determinística | Condición programada que siempre produce el mismo resultado con los mismos datos. |
| IA | Modelo que ayuda a interpretar, explicar o generar contenido, pero que puede equivocarse si no se controla. |
| Prompt | Instrucción que se envía a la IA. |
| Prompt interno | Instrucción controlada por el sistema y no por el usuario final. |
| RAG | Forma de hacer que la IA busque fragmentos de documentos aprobados antes de responder. |
| Grounding / respuesta fundamentada | Respuesta que puede relacionarse con evidencia o fuentes reales. |
| XP | Puntos de experiencia obtenidos por actividades válidas. |
| Racha | Cantidad de días consecutivos con actividad educativa válida. |
| Rúbrica | Criterios usados para calificar una tarea abierta. |
| MVP | Versión mínima que demuestra que la idea funciona. |
| MoSCoW | Prioridad: M imprescindible, S importante, C deseable, W pospuesto. |
| Tenant / multiempresa | Mecanismo para que varias empresas usen la misma plataforma manteniendo sus datos separados. |
| API | Forma controlada en que un sistema se comunica con otro. |

# 23. Cierre: qué debe entender todo el equipo

> **Mensaje para la presentación<br>El producto tiene dos enfoques, pero un solo propósito: ayudar a una PyME a tomar mejores decisiones. Primero organiza la realidad financiera del negocio. Después convierte esa realidad en aprendizaje. La IA es el puente que identifica qué conviene entender, guía al usuario hacia fuentes confiables y genera práctica suficientemente exigente para que el aprendizaje se convierta en mejora.**

Para el hackathon, la prioridad no es tener más pantallas ni más tablas. La prioridad es demostrar una historia completa y coherente. El jurado debe poder ver una empresa con una situación concreta, cómo el sistema la detecta, cómo la IA la explica, qué aprendizaje recomienda, cómo el usuario estudia y supera un reto, y cómo todo queda registrado como progreso.
