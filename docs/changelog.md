# Changelog - Laravel-Flutter CRUD Task Manager

All notable changes to this project will be documented in this file.

## [Unreleased]
### Added
- Created `docs/implementation_plan.md` with a 14-phase development plan.
- Created `docs/changelog.md` to track code modifications.
- Created `docs/walkthrough.md` to document architecture and walk through changes.
- Verified MySQL database connection for "laravelfluttercrud" and configured `backend/.env` database parameters.
- Installed Laravel Breeze scaffolding for Blade authentication.
- Installed Laravel Sanctum API routing infrastructure and configured `bootstrap/app.php` routing.
- Integrated `HasApiTokens` trait into `App\Models\User` model.
- Created `Task` model and migrations with user association, category, priority, and due dates.
- Defined Eloquent relationships between `User` and `Task` models.
- Executed database migrations to create the `tasks` table.
- Copied AdminLTE 3.1.0 assets (`dist/`, `plugins/`) to Laravel's `public/` directory.
- Created `resources/views/layouts/adminlte.blade.php` master layout.
- Restyled `login.blade.php` and `register.blade.php` with AdminLTE's clean card designs.
- Created `TaskWebController` for handling web CRUD actions.
- Created `resources/views/tasks/index.blade.php` containing statistics cards, task list table, creation/editing modals, and status toggle checks.
- Defined routes in `routes/web.php` for index, store, update, toggle, and destroy actions.
- Verified test suite passes successfully with all 25 unit and feature tests.
- Created `AuthApiController` for register, login, and logout API endpoints with Sanctum bearer tokens.
- Created `TaskApiController` for tasks API CRUD operations.
- Implemented custom API request validation (`StoreTaskRequest`, `UpdateTaskRequest`).
- Created `TaskResource` for structured, type-safe API responses.
- Registered and protected API routes in `routes/api.php` under the `auth:sanctum` middleware.
- Developed and executed a new integration test suite `TaskApiTest` (all 29 backend tests now passing).
- Initialized a new Flutter project in `frontend/` folder.
- Configured dependencies (`http`, `provider`, `shared_preferences`, `intl`) in `pubspec.yaml`.
- Created custom theme config `lib/theme.dart` inspired by `kider-1.0.0` layout styling.
- Structured subdirectories: `lib/models`, `lib/services`, `lib/providers`, `lib/views`, and `lib/widgets`.
- Created Dart models `User` and `Task` in `lib/models/`.
- Implemented `ApiService` in `lib/services/` to manage REST API integrations (registration, login, logout, task CRUD).
- Implemented `AuthService` in `lib/services/` to store and manage authentication tokens and user profiles using `SharedPreferences`.
- Created `AuthProvider` in `lib/providers/` to manage login, registration, logout, and token auto-login checks.
- Created `TaskProvider` in `lib/providers/` to manage task list loading, dynamic stats, error catching, and API operations with optimistic updates for toggle and deletion.
- Integrated `MultiProvider` in `lib/main.dart` wrapping the root widget, initialized custom Kider themes, and set up a basic home screen.
- Developed `LoginView` in `lib/views/` using the Kider visual design guidelines, featuring complete form validation, error SnackBar feedback, and login logic.
- Developed `RegisterView` in `lib/views/` using the Kider visual design guidelines, featuring complete form validation (name, email, password matching) and registration logic.
- Implemented `AuthWrapper` in `lib/main.dart` to automatically handle local credential loading on app launch via `tryAutoLogin()`, dynamically routing to either the Login page or a temporary dashboard.
- Created `DashboardView` in `lib/views/dashboard_view.dart` featuring a beautiful header greeting, daily progress bar, and dynamic statistics cards (Total, Completed, Pending).
- Integrated `RefreshIndicator` into the dashboard to support Pull-to-Refresh task reloading.
- Refactored `main.dart` routing to render the full `DashboardView` upon successful session authentication.
- Developed `TaskCard` in `lib/widgets/task_card.dart` displaying task details with dynamic left priority color strips, category badges, due date calendar indicators, and swiping deletion behaviors.
- Developed `AddEditTaskSheet` in `lib/widgets/add_edit_task_sheet.dart` for modal task creation and update actions with ChoiceChip selectors and calendar datepickers.
- Updated `DashboardView` to use the `TaskCard` widget and added a `FloatingActionButton` to invoke the `AddEditTaskSheet`.
