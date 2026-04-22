# Grupo79_ISII

Este proyecto, desarrollado para la cátedra de Ingeniería de Software II de la Universidad Nacional del Nordeste (UNNE) en el ciclo lectivo 2026, consiste en una plataforma integral para la gestión de alquiler de vehículos en la ciudad de Corrientes. El sistema, denominado CorrientesRent, ha sido diseñada utilizando el framework Laravel 12 para el backend y Bootstrap para una interfaz de usuario responsiva y profesional. El objetivo principal es optimizar la administración de la flota, el control de sucursales y la experiencia de reserva para los clientes.

🚀 Funcionalidad Desarrollada: Gestión de Flota Administrativa
Se ha implementado un módulo robusto de administración de vehículos que permite a los operadores gestionar el catálogo de la empresa de manera eficiente. Las características clave incluyen:

Registro Detallado de Vehículos: Alta de unidades mediante un formulario dinámico que valida datos críticos como patente (PK), marca, modelo, año, kilometraje y capacidad de combustible.

Edición Dinámica con Modales: Sistema de actualización de datos en tiempo real que permite modificar precios, estados (Disponible/Alquilado/Mantenimiento) y descripciones sin recargar la página.

Integración de Eliminación Lógica (Soft Deletes): Implementación de una capa de seguridad para la integridad de los datos, donde los vehículos "eliminados" no se borran físicamente de la base de datos, permitiendo mantener el historial de auditoría y la posibilidad de restauración inmediata.


Framework: Laravel 12.

Frontend: Bootstrap 5

Lenguaje: PHP 8.2.12

Base de Datos: MySQL