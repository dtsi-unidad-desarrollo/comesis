---
description: Regla Global Principal para Agentes - Flujo de Trabajo y Contexto
---

# Directrices Globales para Agentes de IA

**ESTA ES LA REGLA PRINCIPAL QUE DEBE SEGUIR CUALQUIER AGENTE QUE INTERACTÚE CON ESTE PROYECTO.**

Como asistente de IA (Agente), antes de proponer código, ejecutar comandos o modificar la estructura del sistema, **DEBES** seguir el siguiente flujo de trabajo:

## 1. Revisión Obligatoria de Memoria
El proyecto contiene un directorio llamado `antigravity_memory` en la raíz, el cual sirve como nuestra fuente única de verdad para el contexto. Siempre debes revisar:
- `antigravity_memory/contexto_del_sistema.md`: Para entender el stack tecnológico, dependencias y arquitectura del proyecto.
- `antigravity_memory/plan_y_tareas.md`: Para saber qué tareas se están ejecutando actualmente, cuáles ya se completaron y evitar realizar trabajo duplicado o fuera de fase.
- `antigravity_memory/estructura_del_sistema.md`: Para conocer el esquema de la base de datos y la arquitectura técnica.

## 2. Cumplimiento Estricto de Patrones de Diseño
Debes aplicar y respetar los patrones y reglas de desarrollo específicos de Laravel que se definieron para este proyecto.
- Si no estás seguro de las reglas, **LEE INMEDIATAMENTE** el archivo `.agents/rules/laravel_rules.md`.
- No propongas patrones que contradigan las reglas establecidas (ej. no pongas lógica pesada en los controladores, usa Services/Actions, validaciones en FormRequests, y **nunca agregues letras blancas sobre fondo blanco**).

## 3. Actualización Continua de la Memoria
Tu deber también es mantener el sistema actualizado:
- Si tú o el usuario deciden cambiar el stack tecnológico, un patrón de diseño, o se completa una tarea importante, **debes actualizar** los archivos correspondientes dentro de `antigravity_memory`.
- Mantener la documentación al día es tan importante como escribir código.

---
**Nota para el Agente:** Al iniciar una nueva conversación o recibir una tarea compleja que involucre arquitectura o base de datos, siempre usa tu herramienta de lectura de archivos (`view_file`) para escanear `antigravity_memory/` antes de actuar.
