# AqylApp

**AqylApp** is an innovative educational platform for school students and teachers, featuring quiz management, student statistics, notifications, and AI-powered insights.

---

## Project Structure
- **Server:** Apache 2.4
- **Backend:** PHP 8.x (Latte templates), MariaDB 10.x
- **Frontend:** Vanilla JS, ApexCharts, tailwind
- **Directory structure:**  
  - `/html` – public web root  
  - `/src` – application logic  
  - `/views` – Latte views  
  - `/sql` – database migrations/scripts

---

## Branch logic
- **master** - Production branch
- **dev** - Development branch
- **ubuntu** - Deployment branch for our VPS

---

## Quick Start

### 1. Install project to Apache web directory

```sh
git clone https://github.com/RustiktamQ/study_quiz.git
cd study_quiz
composer i
```
Install composer if hasn't installed yet

### 2. Start Apache & Mysql services

```sh
sudo systemctl start apache2
sudo systemctl start mysql
```

### 3. Add .env

**/src/.env** - create environment file here and edit it with your own settings:
```env
APP_NAME = "Aqyl"
APP_ENV = "development"
ROOT_URL = "<domain>"
GOOGLE_CLIENT_ID = ""
GOOGLE_CLIENT_SECRET = ""
GOOGLE_REDIRECT_URI = "auth/callback"
DB_HOST = ""
DB_USER = ""
DB_DATABASE = ""
DB_PASS = ""
TIMEZONE = ""
```

### 4. Import database

Run script in /sql with
`mysql <paste here import script>`

### 5. Create sql user and give him all permisions

```sql
CREATE USER 'your_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON your_database.* TO 'your_user'@'localhost';
FLUSH PRIVILEGES;
```
