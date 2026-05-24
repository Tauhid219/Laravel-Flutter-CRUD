# cPanel Hosting & GitHub Actions CI/CD Deployment Plan

This document outlines the detailed steps to host the Laravel backend on your cPanel server under the subdomain `test.rezatauhid.top` using GitHub Actions for continuous deployment (CI/CD).

## Overview
- **Subdomain URL**: `https://test.rezatauhid.top`
- **Subdomain Directory**: `/home/rezatauh/test.rezatauhid.top` (to be symlinked)
- **Repository Directory on Server**: `/home/rezatauh/Laravel-Flutter-CRUD`
- **Repository URL**: `https://github.com/Tauhid219/Laravel-Flutter-CRUD.git`

---

## Action Items & Steps

### 1. Preparation on cPanel Server (SSH Terminal)
First, log in to your cPanel Terminal or connect via SSH and run:

1. **Backup/Rename the current subdomain folder**:
   ```bash
   mv ~/test.rezatauhid.top ~/test.rezatauhid.top_backup
   ```
   *(This frees up the path `~/test.rezatauhid.top` so we can make it a symlink).*

2. **Clone the GitHub Repository**:
   If the repository is public:
   ```bash
   git clone https://github.com/Tauhid219/Laravel-Flutter-CRUD.git ~/Laravel-Flutter-CRUD
   ```
   *(If the repository is private, you will need to generate an SSH key on cPanel using `ssh-keygen` and add the public key to GitHub as a deploy key, then clone using `git@github.com:Tauhid219/Laravel-Flutter-CRUD.git`).*

3. **Establish the Symlink**:
   Create a symbolic link from the subdomain directory to the Laravel `public` directory:
   ```bash
   ln -s ~/Laravel-Flutter-CRUD/backend/public ~/test.rezatauhid.top
   ```

4. **Initialize `.env` File**:
   Copy the example environment file inside `~/Laravel-Flutter-CRUD/backend`:
   ```bash
   cd ~/Laravel-Flutter-CRUD/backend
   cp .env.example .env
   ```
   Open `~/Laravel-Flutter-CRUD/backend/.env` in the cPanel File Manager or via nano, and configure:
   - Create a MySQL Database and Database User in cPanel, grant privileges, and update `DB_*` settings.
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Set `APP_URL=https://test.rezatauhid.top`
   - Run `php artisan key:generate` to generate the app key.

---

### 2. Configure GitHub Secrets
Add the following secrets under **Settings > Secrets and variables > Actions** in your GitHub repository `Tauhid219/Laravel-Flutter-CRUD`:

| Secret Name | Value Description |
|---|---|
| `SSH_HOST` | Your server domain (e.g., `test.rezatauhid.top` or server IP address) |
| `SSH_USERNAME` | Your cPanel SSH username (`rezatauh`) |
| `SSH_PRIVATE_KEY` | The SSH Private Key that can log into the server without a password |
| `SSH_PORT` | SSH port (default is `22`, but verify if your host uses a custom port like `22002`) |

*(To set up the SSH Private Key, generate an SSH Key pair on your PC or cPanel. Authorize the public key in cPanel's SSH Access, and copy the private key to the GitHub Secret).*

---

### 3. GitHub Actions Workflow

We will create a GitHub Action file at `.github/workflows/deploy.yml` in the project:

```yaml
name: Deploy to cPanel

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout Code
        uses: actions/checkout@v4

      - name: Execute Deployment Commands via SSH
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.SSH_HOST }}
          username: ${{ secrets.SSH_USERNAME }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          port: ${{ secrets.SSH_PORT }}
          script: |
            echo "Starting deployment..."
            
            # Navigate to repo directory
            cd ~/Laravel-Flutter-CRUD
            
            # Fetch and reset hard to match main branch
            git fetch origin
            git reset --hard origin/main
            
            # Navigate to Laravel backend
            cd backend
            
            # Install composer dependencies
            composer install --no-dev --optimize-autoloader
            
            # Run migrations
            php artisan migrate --force
            
            # Cache config, routes, and views
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            
            echo "Deployment finished successfully!"
```

---

## Verification Plan

1. **Check Symlink**: Running `ls -la ~/test.rezatauhid.top` in the server terminal should display it pointing to `~/Laravel-Flutter-CRUD/backend/public`.
2. **Access Web URL**: Load `https://test.rezatauhid.top` in a browser. It should show the application dashboard (or login page).
3. **Verify API**: Test `/api/tasks` or check the health via mobile client.
