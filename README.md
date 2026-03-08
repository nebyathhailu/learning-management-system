# 🎓 Mini LMS — Laravel Learning Management System

A full-featured Learning Management System REST API built with Laravel, following clean architecture principles.

---

## ✨ Features

- **Role-based Auth** — Student, Instructor, Admin via Laravel Sanctum
- **Course Hierarchy** — Courses → Modules → Lessons
- **Enrollments** — Students enroll/unenroll; progress tracked per lesson
- **Assignments** — Instructors post; students submit files; instructors grade
- **Progress Tracking** — Per-lesson completion with course percentage
- **Comments** — Threaded discussion on lessons
- **Notifications** — Email + database notifications on enrollment and grading
- **Search & Filter** — Courses filterable by category, level, and keyword
- **API Pagination** — All list endpoints paginated

---

## 🏗️ Architecture

```
Controllers → Services → Repositories → Eloquent Models
                 ↓
            Policies (Authorization)
                 ↓
            API Resources (JSON output)
```

| Layer | Responsibility |
|---|---|
| Controller | HTTP input/output only |
| Service | Business logic |
| Repository | Database queries |
| Policy | Authorization rules |
| Resource | JSON transformation |

---

## 🗄️ Database Schema

```
users           (id, name, email, password, role)
courses         (id, instructor_id, title, slug, status, category, level)
modules         (id, course_id, title, sort_order)
lessons         (id, module_id, title, content, video_url, is_free_preview)
enrollments     (id, user_id, course_id, status, enrolled_at)
lesson_completions (id, user_id, lesson_id, completed_at)
assignments     (id, lesson_id, title, description, due_date, max_score)
submissions     (id, assignment_id, user_id, file_path, score, status)
comments        (id, lesson_id, user_id, parent_id, body)
notifications   (Laravel default)
```

---

## 🚀 Setup

**Requirements:** PHP 8.2+, Composer, MySQL 8+, Node.js 18+

```bash
# 1. Clone and install
composer create-project laravel/laravel mini-lms
cd mini-lms

# 2. Install Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 3. Configure .env
DB_DATABASE=mini_lms
DB_USERNAME=root
DB_PASSWORD=your_password

# 4. Run migrations & seed
php artisan migrate --seed

# 5. Link storage (for file uploads)
php artisan storage:link

# 6. Start server
php artisan serve

# 7. Start queue worker (for notifications)
php artisan queue:work
```

Seed creates three test accounts (password: `password`):

| Email | Role |
|---|---|
| admin@lms.com | Admin |
| instructor@lms.com | Instructor |
| student@lms.com | Student |

---

## 📡 API Endpoints

### Auth
| Method | Endpoint | Access |
|---|---|---|
| POST | `/api/register` | Public |
| POST | `/api/login` | Public |
| POST | `/api/logout` | Auth |
| GET | `/api/me` | Auth |

### Courses
| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/courses` | Public |
| GET | `/api/courses/{slug}` | Public |
| POST | `/api/courses` | Instructor |
| PUT | `/api/courses/{id}` | Instructor (owner) |
| DELETE | `/api/courses/{id}` | Instructor (owner) |

### Enrollments & Progress
| Method | Endpoint | Access |
|---|---|---|
| POST | `/api/courses/{id}/enroll` | Student |
| DELETE | `/api/courses/{id}/enroll` | Student |
| GET | `/api/my-enrollments` | Student |
| POST | `/api/lessons/{id}/complete` | Student |
| GET | `/api/courses/{id}/progress` | Student |

### Assignments & Submissions
| Method | Endpoint | Access |
|---|---|---|
| POST | `/api/lessons/{id}/assignments` | Instructor |
| POST | `/api/assignments/{id}/submit` | Student |
| POST | `/api/submissions/{id}/grade` | Instructor |

### Comments
| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/lessons/{id}/comments` | Auth |
| POST | `/api/lessons/{id}/comments` | Auth |
| DELETE | `/api/comments/{id}` | Owner/Admin |

### Admin
| Method | Endpoint | Access |
|---|---|---|
| GET | `/api/admin/stats` | Admin |
| GET | `/api/admin/users` | Admin |
| PATCH | `/api/admin/users/{id}/role` | Admin |

---

## 🔐 Authorization

Routes are protected by two layers:

1. **`auth:sanctum`** — requires a valid Bearer token
2. **`role:instructor,admin`** middleware — checks `users.role`
3. **Policies** — fine-grained ownership checks (e.g. only course owner can edit)

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/    # Thin controllers
│   ├── Middleware/         # RoleMiddleware
│   └── Resources/          # API transformers
├── Interfaces/             # Repository contracts
├── Models/                 # Eloquent models
├── Notifications/          # Email + DB notifications
├── Policies/               # Authorization policies
├── Providers/              # DI bindings
├── Repositories/           # DB query layer
└── Services/               # Business logic
database/
├── migrations/
└── seeders/
routes/
└── api.php
```

---

## 📦 Key Packages

- `laravel/sanctum` — API token authentication
- Laravel Notifications — email + database channels

---

## 📄 License

MIT
