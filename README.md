# SGC - Sistema de Gestion Clinica

Sistema de gestion clinica medica construido con Laravel 11, MySQL, Blade Templates y Livewire.

## Stack Tecnologico

- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js, Livewire
- **Base de Datos:** MySQL (XAMPP)
- **Autenticacion:** Laravel Breeze
- **Roles y Permisos:** Spatie Laravel-Permission
- **PDF:** Barryvdh Laravel-DomPDF
- **Build:** Vite

## Funcionalidades (12 Pantallas)

### Sprint 1
- **Pantalla 1:** Landing page publica con formulario de contacto
- **Pantalla 2:** Autenticacion con redireccion por rol (Admin, Doctor, Enfermero, Paciente)
- **Pantalla 12:** CRUD completo de usuarios con asignacion de roles

### Sprint 2
- **Pantalla 3:** Perfil de paciente con historial clinico, alergias, condiciones cronicas y signos vitales
- **Pantalla 4:** Gestion de citas (paciente solicita, doctor gestiona, admin supervisa)
- **Pantalla 5:** Facturacion (crear facturas, registrar pagos, descargar PDF)

### Sprint 3
- **Pantalla 6:** Historial clinico (registros medicos, notas, recetas)
- **Pantalla 7:** Gestion de horarios medicos y tiempo libre
- **Pantalla 8:** Reportes con estadisticas e indicadores

### Sprint 4
- **Pantalla 9:** Panel de enfermeria con cola de pacientes
- **Pantalla 10:** Triaje con registro de signos vitales
- **Pantalla 11:** Notificaciones por email y WhatsApp
- **Pantalla 12:** Admin dashboard mejorado

## Roles

| Rol | Permisos |
|-----|----------|
| Admin | Gestionar usuarios, citas, facturacion, reportes |
| Doctor | Ver citas, crear registros medicos, recetas, horarios |
| Enfermero | Triaje, signos vitales, cola de pacientes |
| Paciente | Agendar citas, ver perfil, ver facturas |

## Instalacion

### Requisitos
- PHP 8.2+
- Composer 2.x
- MySQL 8.x
- Node.js 18+
- XAMPP (opcional)

### Pasos

```bash
# Clonar el repositorio
git clone https://github.com/carlosioterodev/ClinicaMedica.git
cd ClinicaMedica

# Instalar dependencias PHP
composer install

# Instalar dependencias JS
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# DB_DATABASE=clinica_medica
# DB_USERNAME=root
# DB_PASSWORD=

# Crear base de datos y migrar
php artisan migrate:fresh --seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

### Usuarios de Prueba

| Email | Password | Rol |
|-------|----------|-----|
| admin@clinica.com | password | Admin |
| doctor@clinica.com | password | Doctor |
| nurse@clinica.com | password | Enfermero |
| paciente@clinica.com | password | Paciente |

## Estructura del Proyecto

```
app/
├── Enums/                    # Estados de citas, facturas, etc.
├── Exceptions/               # Excepciones personalizadas
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            # Dashboard, Usuarios, Citas, Reportes
│   │   ├── Auth/             # Autenticacion Breeze
│   │   ├── Billing/          # Facturacion
│   │   ├── Doctor/           # Citas, Horarios, Historial Clinico
│   │   ├── Nurse/            # Triaje, Signos Vitales
│   │   └── Patient/          # Citas, Perfil, Facturas
│   ├── Middleware/            # Roles
│   └── Requests/             # Form Requests
├── Mail/                     # Mailables
├── Models/                   # 20+ modelos Eloquent
├── Observers/                # Appointment, Invoice observers
└── Services/
    ├── Appointment/          # Logica de citas, disponibilidad
    ├── Billing/              # PDF generation
    └── Notification/         # Email, WhatsApp

resources/views/
├── admin/                    # Dashboard, usuarios, citas, reportes
├── auth/                     # Login, registro
├── billing/                  # Facturas
├── doctor/                   # Citas, horarios, historial clinico
├── emails/                   # Templates de email
├── layouts/                  # App, guest, navigation, public
├── nurse/                    # Dashboard, triaje
├── patient/                  # Dashboard, citas, perfil, facturas
└── pdf/                      # PDF templates
```

## Arquitectura

- **Service Layer:** Logica de negocio en servicios inyectados via interfaces
- **Form Requests:** Validacion centralizada
- **Observers:** Eventos automaticos en modelos
- **Middleware:** Control de acceso por rol
- **Mailables:** Notificaciones por email
- **API WhatsApp:** Notificaciones via Meta Cloud API

## Licencia

Proyecto privado - Uso academico
