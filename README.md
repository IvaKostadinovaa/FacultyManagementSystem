# Faculty Management System

A web-based faculty management system built with **Laravel 11** and **Filament v3**, designed to manage students, subjects, semesters, enrollments, and grading across a university faculty.

## Tech Stack

- **Backend:** Laravel 11
- **Admin Panel:** Filament v3
- **Database:** MySQL
- **Notifications:** Laravel Mail + Database notifications

## Features

### Role-Based Access

Three user roles with different panel access:

| Role | Access                                                                                        |
|------|-----------------------------------------------------------------------------------------------|
| `admin` | Full access — manage users, faculties, semesters, subjects, students, enrollments, audit logs |
| `professor` | Can view and grade enrolled students in their subjects                                        |
| `assistant` | Limited read-only access                                                                      |

### Core Modules

- **Students** — student records with index number, faculty, current semester, enrollment year, and status (`active` / `inactive` / `graduated`)
- **Subjects** — courses with ECTS credits, assigned professor and assistant, linked to faculty and semester
- **Semesters** — academic periods; students and subjects are tied to a semester
- **Faculties** — organizational units; users, students, and subjects belong to a faculty
- **Enrollments** — link students to subjects, track status (`pending` / `approved` / `rejected`) and grade (5–10)
- **Users** — admin accounts with role and faculty assignment

### Grading Workflow

Professors access the **Grade Students** page to view approved enrollments for their subjects, filter by subject/semester/ungraded status, and submit grades via a modal. Saving a grade fires an enrollment update event.

### Notifications

When an enrollment's **status** or **grade** changes, an `EnrollmentStatus` event is fired. A listener sends both an email and a database notification to the student.

### Audit Log

All model mutations (create, update, delete) are automatically recorded via the `LogsActivity` trait. Each log entry captures the user, action, model type/ID, old values, new values, and IP address. Admins can view the full audit log in the panel (read-only).

### Dashboard Widgets

The dashboard displays role-specific stats and charts:

- **Admin:** student stats overview, students by faculty (pie chart), subjects by faculty (pie chart), subject pass/fail chart, recent enrollments
- **Professor:** professor-specific stats
- **Assistant:** assistant-specific stats

## Project Structure

```
app/
├── Filament/
│   ├── Pages/           # Dashboard, GradeStudents
│   ├── Resources/       # Filament CRUD resources 
│   └── Widgets/         # Dashboard charts and stat cards
├── Models/              # Eloquent models
├── Traits/
│   └── LogsActivity.php # Auto audit logging for all models
├── Events/              # EnrollmentStatus event
├── Listeners/           # SendEnrollmentStatusNotification
├── Mail/                # EnrollmentStatusMail
└── Notifications/       # EnrollmentUpdatedNotification
```

## Installation

```bash
git clone https://github.com/IvaKostadinovaa/FacultyManagementSystem.git
cd FacultyManagementSystem

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`, then:

```bash
php artisan migrate
php artisan db:seed       
php artisan serve
```

Access the admin panel at `/admin`.

## User Roles

Create users with one of the following `role` values:

- `admin`
- `professor`
- `assistant`

All three roles can log into `/admin`; access to specific resources and pages is enforced per role inline on each Filament resource.
