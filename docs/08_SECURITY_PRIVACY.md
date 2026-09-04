# 08 — Seguridad, privacidad y uso responsable

## Modelo de amenaza mínimo

Activos importantes:

- credenciales;
- datos de empresa;
- clientes/proveedores;
- facturas/compras;
- saldos CxC/CxP;
- historial educativo;
- API keys;
- fuentes y prompts internos.

Riesgos prioritarios:

1. fuga entre empresas;
2. elevación de permisos;
3. exposición de secrets;
4. datos sensibles enviados a IA;
5. prompt injection desde documentos;
6. IA inventando fuentes;
7. modificación indebida de operaciones confirmadas.

## Autenticación

- hash robusto gestionado por Laravel;
- sesiones seguras;
- regenerar sesión al autenticar;
- rate limiting en login/recuperación;
- verificación de email opcional para MVP, recomendada antes de producción.

## Autorización

Toda acción sensible usa Policy/Gate/Action authorization.

Validar:

- empresa activa;
- pertenencia;
- módulo activo;
- permiso de acción;
- estado del registro.

## Aislamiento

Prueba obligatoria:

> Usuario de Empresa A intenta consultar por URL/ID un registro existente de Empresa B y recibe 404/403 sin revelar datos.

## Auditoría

Registrar:

- login sensible si se desea;
- cambios de roles/permisos;
- activación/desactivación de módulos;
- emisión/anulación de facturas;
- ajustes de inventario;
- cobros/pagos;
- cambios de configuración crítica;
- publicación de fuentes;
- ejecuciones IA relevantes.

No registrar contraseñas, tokens ni prompts con datos sensibles completos.

## IA y minimización de datos

Antes de llamar al proveedor:

1. determinar si la llamada es necesaria;
2. construir contexto mínimo;
3. remover PII no necesaria;
4. preferir agregados;
5. aplicar timeout/rate limit;
6. validar respuesta.

## Prompt injection desde fuentes

Los documentos recuperados son **datos**, no instrucciones. El prompt del tutor debe decir explícitamente que ignore cualquier instrucción encontrada dentro de la fuente y use los fragmentos solo como evidencia académica.

## Fuentes

Guardar condición de uso por recurso. Opciones:

- `link_only`
- `excerpt_allowed`
- `stored_allowed`
- `review_required`

Si no existe claridad, usar `link_only` y mostrar enlace original.

## Exportaciones

- respetan permisos;
- usan empresa activa;
- no exponen campos internos;
- archivos temporales tienen expiración/ubicación protegida.

## Producción

- HTTPS obligatorio;
- `APP_DEBUG=false`;
- secrets fuera del repo;
- backups de DB;
- logs protegidos;
- política de retención;
- healthcheck;
- workers supervisados si hay colas.
