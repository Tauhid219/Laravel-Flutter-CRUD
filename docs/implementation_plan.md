# Laravel-Flutter CRUD Task Manager - Implementation Plan

This document outlines the step-by-step development process of the Task Manager CRUD application. We follow a modular, phase-by-phase execution strategy.

## Progress Overview
* **Current Phase**: **Phase 6: Laravel API Controllers & Endpoints (Sanctum Authenticated)**
* **Status Markers**:
  * `[ ]` Pending
  * `[/]` In Progress
  * `[x]` Completed

---

## Phase-by-Phase Plan

### Phase 1: Environment & Directory Setup
* [x] Create the `docs/` folder in the project root.
* [x] Create and initialize `docs/implementation_plan.md`, `docs/changelog.md`, and `docs/walkthrough.md`.
* [x] Configure the Laravel database connection in `backend/.env` (Switch to SQLite or configure MySQL).

### Phase 2: Laravel Authentication & API Setup (Breeze & Sanctum)
* [x] Install Laravel Breeze in the backend: `composer require laravel/breeze --dev`.
* [x] Install Laravel API routing and Sanctum: `php artisan install:api`.
* [x] Configure `bootstrap/app.php` to load API routes:
  ```php
  web: __DIR__.'/../routes/web.php',
  api: __DIR__.'/../routes/api.php',
  ```

### Phase 3: Laravel Task Model & Database Migration
* [x] Create the `Task` model and migration: `php artisan make:model Task -m`.
* [x] Update the `create_tasks_table` migration with fields:
  * `id` (Primary Key)
  * `user_id` (Foreign Key linked to `users` table)
  * `title` (String)
  * `description` (Text, nullable)
  * `category` (String, default: 'General')
  * `priority` (String, default: 'Medium' - Low, Medium, High)
  * `due_date` (Date, nullable)
  * `is_completed` (Boolean, default: false)
  * `timestamps`
* [x] Update the `Task` model class with `$fillable`, user relationship (`belongsTo`), and casts.
* [x] Update the `User` model class with task relationship (`hasMany`).
* [x] Run migrations: `php artisan migrate`.

### Phase 4: Laravel Admin Panel Asset Integration (AdminLTE)
* [x] Copy the `dist/` and `plugins/` directories from `C:\Reza\Tauhid\Templates\AdminLTE-3.1.0` into Laravel's `backend/public/` folder.
* [x] Create a master layout view `resources/views/layouts/adminlte.blade.php` based on AdminLTE's `starter.html`.
* [x] Re-style Laravel Breeze login and registration views to match the AdminLTE design.

### Phase 5: Laravel Web Dashboard (CRUD & Panel)
* [x] Create `TaskWebController` to handle CRUD operations on the web interface.
* [x] Build the web dashboard UI in `resources/views/tasks/index.blade.php` containing:
  * Stats cards (Total, Completed, Pending tasks).
  * Task creation form / modal.
  * Task list table with category badges, priority colors, and action buttons (Edit, Delete, Complete checkbox).
* [x] Define web routes in `routes/web.php` for index, store, update, toggle, and destroy.
* [x] Implement session feedback / toast alerts for CRUD success.

### Phase 6: Laravel API Controllers & Endpoints (Sanctum Authenticated)
* [ ] Create `AuthApiController` for register, login, and logout API endpoints (returning Sanctum Bearer tokens).
* [ ] Create `TaskApiController` to handle API CRUD requests.
* [ ] Protect task routes in `routes/api.php` using the `auth:sanctum` middleware so users only access their own tasks.
* [ ] Create custom Form Request validation (`StoreTaskRequest`, `UpdateTaskRequest`) for API safety.
* [ ] Use Eloquent API Resources (`TaskResource`) to return consistent JSON formats.

### Phase 7: Flutter Project Initialization & Dependencies
* [ ] Create the Flutter project in `frontend/`: `flutter create --project-name flutter_practice .`.
* [ ] Add dependencies in `pubspec.yaml`:
  * `http: ^1.2.0` (REST API client)
  * `provider: ^6.1.2` (State management)
  * `shared_preferences: ^2.2.3` (Local persistent storage for token/user details)
  * `intl: ^0.19.0` (Date formatting)
* [ ] Define target design system based on `kider-1.0.0` themes in Flutter (`ThemeData` configuration):
  * Primary color: `#FE5D37` (Vibrant Coral/Orange)
  * Light background: `#FFF5F3` (Soft Peach/Light Pink)
  * Dark text/primary: `#103741` (Deep Teal)
  * Headings: Playful styling mimicking 'Lobster Two'.
* [ ] Structure the project directories: `lib/models`, `lib/services`, `lib/providers`, `lib/views`, `lib/widgets`.

### Phase 8: Flutter Models & API Services
* [ ] Create the `User` and `Task` Dart models with `fromJson` and `toJson` serialization.
* [ ] Create `ApiService` to make REST requests (Login, Register, Get Tasks, Add Task, Edit Task, Delete Task). Make sure the base URL is easily configurable.
* [ ] Create `AuthService` to store and load token and user details from local storage using `shared_preferences`.

### Phase 9: Flutter State Management (Provider)
* [ ] Create `AuthProvider` to manage login, registration, logout, and startup auth token checks.
* [ ] Create `TaskProvider` to handle task list loading state, error catching, dynamic statistics calculation, and calling the CRUD APIs.

### Phase 10: Flutter Authentication Screens
* [ ] Build a beautiful login screen using the `kider-1.0.0` design guidelines.
* [ ] Build a registration screen with form validation.
* [ ] Add automated navigation checks: redirects users to home if authenticated on app launch, otherwise to login.

### Phase 11: Flutter Dashboard & Stats Screens
* [ ] Build the main Task Dashboard.
* [ ] Create Kider-inspired top status banner displaying Task summary statistics (Total, Completed, Pending) dynamically.
* [ ] Add Pull-to-Refresh functionality.

### Phase 12: Flutter Task CRUD Operations (List & Actions)
* [ ] Create custom `TaskCard` widget with swipe-to-delete (`Dismissible`), priority color indicator, category tag, and completion checkbox toggle.
* [ ] Create `AddEditTaskSheet` bottom-sheet modal with fields for title, description, category chip selection, priority dropdown, and due date calendar picker.
* [ ] Integrate loading dialogs and error alerts via SnackBars.

### Phase 13: End-to-End Local Network Testing
* [ ] Serve Laravel locally on all network interfaces: `php artisan serve --host=0.0.0.0 --port=8000`.
* [ ] Configure Flutter to connect to the computer's local IP address (`192.168.0.240:8000`).
* [ ] Perform manual validation on both web dashboard and mobile interface to confirm real-time data sync.

### Phase 14: APK Release Compilation
* [ ] Configure internet network permission in `android/app/src/main/AndroidManifest.xml`.
* [ ] Update the Android application icon and display name.
* [ ] Run compilation command: `flutter build apk --release`.
