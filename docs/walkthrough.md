# Project Walkthrough - Laravel-Flutter CRUD Task Manager

This document provides a walkthrough of the architecture, folder structure, design systems, and commands used to run and build the application.

---

## 1. Project Architecture

This is a mono-repo structure containing:
*   `backend/` - Laravel 12 API & Admin Panel.
*   `frontend/` - Flutter mobile client.
*   `docs/` - Project documentation, plan, and changelogs.

---

## 2. Directory Structure

```text
Laravel-Flutter-CRUD/
├── backend/                  # Laravel 12 Backend
│   ├── app/
│   │   ├── Models/
│   │   └── Http/Controllers/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/               # Asset root (AdminLTE assets)
│   ├── resources/views/      # Admin Panel Blade views
│   └── routes/               # Routes (web.php, api.php)
├── frontend/                 # Flutter Mobile Frontend
│   ├── lib/
│   │   ├── models/
│   │   ├── services/
│   │   ├── providers/
│   │   ├── views/
│   │   └── widgets/
│   └── pubspec.yaml
└── docs/                     # Project Documentation
    ├── implementation_plan.md
    ├── changelog.md
    └── walkthrough.md
```

---

## 3. Design Guidelines

### Backend Layout (AdminLTE 3.1.0)
*   **Template Source**: `C:\Reza\Tauhid\Templates\AdminLTE-3.1.0`
*   **Visual Style**: Sleek Bootstrap-based admin layout with sidebar navigation, user profile panel, dashboard header, and statistics cards.

### Frontend Layout (Kider 1.0.0)
*   **Template Source**: `C:\Reza\Tauhid\Templates\kider-1.0.0`
*   **Color Palette**:
    *   Primary: `#FE5D37` (Coral/Orange)
    *   Light Accent: `#FFF5F3`
    *   Dark Accent: `#103741`
*   **Aesthetics**: Rounded curves, friendly cards, and playful typography inspired by a modern preschool website theme.

---

## 4. Run & Test Commands

### Running Backend
1. Start XAMPP (Apache & MySQL).
2. Inside `backend/`, start Laravel server:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### Running Frontend
1. Inside `frontend/`, run Flutter in debug mode:
   ```bash
   flutter run -d <device-id>
   ```

---

## 5. Profile & Settings Dashboard (AdminLTE Layout)

The Profile page has been redesigned to integrate with the AdminLTE layout. The page consists of:
1. **User Profile Card (Left)**: Renders the user's name, email, account registration date, and dynamic counts of their tasks (Total, Completed, Pending).
2. **Forms Panel (Right)**: Contains Bootstrap 4 tabbed panels:
   - **Profile Info**: Modifies the user's Name and Email.
   - **Change Password**: Safely updates current password.
   - **Delete Account**: Danger zone to permanently delete the account (requires password confirmation).
3. **Dynamic UX**: Automatically switches tabs to active fields on validation errors or successful status messages using jQuery.

---

## 6. cPanel Production Deployment

The backend application is hosted on cPanel at `https://test.rezatauhid.top`. 

### Production Directory Map
*   **Subdomain Web Root**: `/home/rezatauh/test.rezatauhid.top` (Symlinked to the public directory of the repo).
*   **Repository Location**: `/home/rezatauh/Laravel-Flutter-CRUD/`
*   **Laravel Public Folder**: `/home/rezatauh/Laravel-Flutter-CRUD/backend/public/`

### Manual Deployment Steps
Whenever you make updates to the codebase (such as updating the Flutter frontend APIs or Laravel backend routes):
1.  Push your changes from your local machine to GitHub:
    ```bash
    git add .
    git commit -m "Your changes"
    git push origin main
    ```
2.  Log into your cPanel Terminal or connect via SSH, and run:
    ```bash
    cd ~/Laravel-Flutter-CRUD
    git pull
    cd backend
    php -d allow_url_fopen=On ~/composer.phar install --no-dev --optimize-autoloader
    php artisan migrate --force
    php artisan optimize
    ```
