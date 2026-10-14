# Driving School Campus Management System

A Laravel 12 and SQLite web app for managing students, instructors, vehicles,
courses, and scheduled lessons.

## Setup

Requirements: PHP 8.2+, Composer, and the PHP SQLite extension.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

The seeded SQLite database contains 10 students, 5 instructors, 6 vehicles,
5 courses, and 25 lessons. The `.env` file is local and excluded from Git.
Sessions and cache use the file driver, and queues use the synchronous driver;
these settings avoid requiring additional application tables.

## Main areas

- `/` — dashboard counts and upcoming scheduled lessons
- `/students`, `/instructors`, `/vehicles`, `/courses`, `/lessons` — paginated CRUD

All five resources use `Route::resource` routes and share the responsive
Bootstrap layout and reusable list, form, and detail Blade views.

## Database relationships

The four parent tables—`students`, `instructors`, `vehicles`, and `courses`—
have no foreign keys. `lessons` belongs to one student, instructor, and course,
and may belong to one vehicle. A theory lesson may omit its vehicle. All four
lesson foreign keys use restricted deletion so lesson history cannot be
silently removed. The Laravel `migrations` bookkeeping table is separate from
the five application tables.

The Eloquent models expose `hasMany` from each parent to its lessons and
`belongsTo` from a lesson to each parent. Lesson lists and dashboard data eager
load their related records.

Lesson validation checks that referenced records exist, requires a vehicle for
practical courses, enforces an end time later than the start time, and rejects
overlaps for a student, instructor, or vehicle on the same date. Cancelled
lessons do not block scheduling.

## Verification

```powershell
php artisan test
vendor\bin\pint.bat --test app\Http\Controllers app\Models database\factories database\migrations database\seeders routes tests
```

Web requests resolve a named route in `routes/web.php`, run its controller,
validate and read/write Eloquent models, then render a Blade view. For example,
creating a lesson posts to `lessons.store`, validates its foreign keys and
schedule, saves it through `Lesson`, and redirects to its detail page with a
flash message.
