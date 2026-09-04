# 09 — Pruebas y criterios de aceptación

## Estrategia

Usar Pest. Priorizar pruebas de reglas y flujos, no perseguir cobertura porcentual vacía.

## Unitarias

- cálculo de subtotal/total;
- saldo de factura;
- aplicación de cobro;
- saldo CxC/CxP;
- stock después de movimiento;
- ratio cartera vencida;
- cobertura de caja;
- XP/nivel;
- evaluación de reglas diagnósticas.

## Feature / integración

### Flujo venta a crédito

```text
crear cliente
→ emitir venta crédito C$10,000
→ crear CxC C$10,000
→ cobrar C$4,000
→ CxC queda C$6,000
→ caja aumenta C$4,000
```

### Venta + inventario

```text
stock 20
→ vender 3
→ stock 17
→ kardex registra salida vinculada
```

### Compra a crédito

```text
compra C$8,000
→ CxP C$8,000
→ pagar C$3,000
→ saldo CxP C$5,000
→ caja disminuye C$3,000
```

### Multiempresa

- A no ve clientes B;
- A no ve facturas B;
- A no puede forzar `company_id=B`;
- exportes/dashboard también aislados.

### Módulos

- ruta protegida falla si módulo inactivo;
- activar crea visibilidad/configuración;
- desactivar no elimina historial;
- dependencias inválidas se rechazan.

## IA

Mockear `AiProvider` en pruebas normales.

Probar:

- schema válido se acepta;
- schema inválido se rechaza/reintenta según política;
- tutor sin evidencia devuelve falta de fuente;
- referencias deben pertenecer a fuentes recuperadas;
- recomendador solo elige targets permitidos;
- falla del proveedor no revierte transacción financiera.

## Educación

- no completar unidad si falta actividad obligatoria;
- examen calcula score;
- reintento respeta reglas;
- XP no se duplica indebidamente;
- logro se concede una sola vez;
- racha se calcula por actividad educativa válida.

## Criterios del MVP

Checklist final:

- [ ] empresa y usuario creados;
- [ ] módulos activables;
- [ ] venta, compra, inventario, cobro y pago funcionales;
- [ ] dashboard coherente;
- [ ] regla financiera real dispara señal;
- [ ] IA explica señal;
- [ ] IA recomienda ruta existente;
- [ ] usuario abre fuente;
- [ ] completa clase;
- [ ] realiza tarea/examen;
- [ ] recibe XP/progreso;
- [ ] challenge generator produce reto válido;
- [ ] tutor responde con referencias;
- [ ] sin fuente, tutor reconoce limitación;
- [ ] IA nunca modifica datos financieros;
- [ ] demo seed reproducible;
- [ ] pruebas críticas pasan.

## Comandos mínimos antes de cerrar una fase

```bash
php artisan test
php artisan route:list
php artisan migrate:fresh --seed   # solo en entorno de desarrollo/demo
npm run build
```

Si existen linters/formatters configurados, también deben ejecutarse.
