# 🚀 HireHub API — Professional Freelancing Ecosystem

HireHub is a robust, scalable API built with **Laravel 12**. Inspired by platforms like Upwork and Freelancer, it connects Arab clients with verified freelancers, with a strong focus on architectural integrity and performance.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Sanctum](https://img.shields.io/badge/Auth-Sanctum-FF2D20)
![Tests](https://img.shields.io/badge/Tests-PHPUnit-3C9CD7)

---

## 🌐 Live demo

**API base URL:** https://hirehub-api-dy61.onrender.com

- Try the public endpoints: [`GET /api/projects`](https://hirehub-api-dy61.onrender.com/api/projects) and [`GET /api/freelancers`](https://hirehub-api-dy61.onrender.com/api/freelancers)
- Admin demo account (from `AdminSeeder`): `admin@hirehub.com` / `Example@Pass2026`
- Full request collection: [docs/HireHub.postman_collection.json](docs/HireHub.postman_collection.json)
- Hosted on Render's free tier with SQLite: the first request after a period of inactivity can take about a minute, and the demo data resets on every restart.

## ✨ Features

- **Three roles** — client, freelancer and admin, each with its own permissions.
- **Projects** — clients post, update and delete projects; anyone can browse them.
- **Proposals** — verified freelancers submit and manage proposals on projects.
- **Freelancer profiles** — public profiles with skills that freelancers manage themselves.
- **Reviews & attachments** — shared across entities through polymorphic relations.
- **Admin statistics** — cached platform analytics.
- **API performance logging** — every request's duration and metadata are stored for auditing.

## 🛠 Tech Stack

| Layer | Technology |
| :--- | :--- |
| Language | PHP 8.2+ |
| Framework | Laravel 12 |
| Authentication | Laravel Sanctum + Fortify |
| Database | MySQL (SQLite for local testing) |
| Testing | PHPUnit (unit + feature tests) |
| CI | GitHub Actions (lint & tests) |

---

## 🏗 Architecture & Design Patterns

### 1. Repository Pattern & Service Layer
- **Decoupling:** data access (`ProjectRepositoryInterface`, `ProposalRepositoryInterface`) is separated from business logic (`ProjectService`, `ProposalService`, …).
- **Testability:** services can be unit-tested with mocks, without a real database (see `tests/Unit/ProposalServiceTest.php`).
- **Interchangeability:** switching from Eloquent to Redis or an external API doesn't touch the controllers.

### 2. Event-Driven Architecture (SOLID — Open/Closed)
- When a proposal is accepted, a `ProposalAccepted` event is dispatched and handled by `SendProposalAcceptedNotification`.
- New side effects (SMS, email, push notifications) can be added as listeners without modifying `ProposalService`.

### 3. Advanced Database Design
- **Polymorphic relationships** for attachments and reviews — one table serves projects, proposals and users.
- **`HasOneThrough`** (user → city → country) for cross-entity lookups with fewer queries.
- **Normalized location data** with dedicated `countries` and `cities` tables.

---

## ⚡ Performance Optimization

- **No N+1 queries:** eager loading (`with(['user', 'tags'])`) on every listing endpoint.
- **Aggregations:** `withCount()` for proposal tallies and `sum()` for financial analytics to keep memory usage low.
- **Caching:** admin statistics are cached for 10 minutes (`Cache::remember`).

## 🛡 Security & Business Integrity

- **Role-based access control:** `CheckRole` (`role:client`, `role:freelancer`, `role:admin`) and `EnsureFreelancerIsVerified` middleware.
- **Policies** for projects, proposals, profiles and users.
- **Form Requests** encapsulate validation and authorization, keeping controllers lean; a custom `CleanContent` rule filters user content.
- **Database transactions:** proposal submission runs inside `DB::transaction()` so it is saved completely or not at all.
- **API analytics:** `ApiPerformanceLogger` uses the middleware `terminate()` hook to log each request's duration after the response is sent.

---

## 🚀 Getting Started

### Prerequisites

- PHP **8.2+**
- Composer
- MySQL (or SQLite)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/hasan123dayoub-del/HireHub.git
cd HireHub

# 2. Install dependencies
composer install

# 3. Create the environment file
cp .env.example .env
php artisan key:generate
```

Configure the database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hirehub
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Run migrations and seed demo data (countries, cities, users, projects, proposals…)
php artisan migrate --seed

# 5. Start the server
php artisan serve
```

The API is available at **http://127.0.0.1:8000/api**.

### Running Tests

```bash
php artisan test
```

---

## 📡 API Endpoints

### Authentication

| Method | Endpoint | Description | Auth |
| :--- | :--- | :--- | :---: |
| `POST` | `/api/register` | Create an account | — |
| `POST` | `/api/login` | Log in and receive a token | — |
| `DELETE` | `/api/logout` | Revoke the current token | ✅ |
| `DELETE` | `/api/destroyaccount` | Delete the account | ✅ |
| `GET` | `/api/user` | Get the authenticated user | ✅ |

### Public

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/projects` | List projects |
| `GET` | `/api/projects/{id}` | Show a project |
| `GET` | `/api/freelancers` | List freelancers |
| `GET` | `/api/freelancers/{id}` | Show a freelancer profile |

### Client (`role:client`)

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/projects` | Create a project |
| `PUT` | `/api/projects/{project}` | Update a project |
| `DELETE` | `/api/projects/{project}` | Delete a project |

### Freelancer (`role:freelancer`)

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `POST` | `/api/proposals` | Submit a proposal |
| `GET` | `/api/proposals/{id}` | Show a proposal |
| `PUT` | `/api/proposals/{proposal}` | Update a proposal |
| `GET` | `/api/profile` | Get my profile |
| `PUT` | `/api/profile` | Update my profile |
| `POST` | `/api/profile/skills` | Add a skill |
| `PUT` | `/api/profile/skills/{skillId}` | Update a skill |
| `DELETE` | `/api/profile/skills/{skillId}` | Remove a skill |

### Admin (`role:admin`)

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/api/admin/stats` | Platform statistics (cached) |

> All client, freelancer and admin routes require a verified account and a `Bearer` token.

---

## 🧪 Postman Collection

📥 **[Download the API collection](./docs/HireHub.postman_collection.json)** and import it into Postman to try every endpoint.
