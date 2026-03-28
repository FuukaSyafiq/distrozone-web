# Distro Zone

A Laravel-based e-commerce platform for t-shirt (distro) products. Manage products, transactions, payments, and more with a powerful admin panel built with Filament.

## Tech Stack

- **Framework:** Laravel 10
- **Admin Panel:** Filament 3
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS, Vite
- **Database:** PostgreSQL
- **Storage:** MinIO (S3-compatible)
- **Container:** Docker & Docker Compose

## Features

### Admin Panel (Filament)
- **Dashboard** - Sales overview with charts and statistics
- **Product Management** - Manage t-shirts (kaos) with variants, colors, sizes, and brands
- **Transaction Management** - Track orders and transaction details
- **Payment Management** - Handle payment methods and payment records
- **Shipping Cost (Ongkir)** - Configure shipping rates by city/region
- **City Management** - Manage cities and regions for shipping
- **User Management** - Admin and cashier user management with roles
- **Operating Hours** - Set store operating hours
- **Revenue Reports** - View income reports and analytics
- **PDF Export** - Export documents to PDF

### Frontend (Public Store)
- Product catalog with filtering
- Shopping cart
- Checkout process
- Order history

## Requirements

- PHP 8.1+
- Node.js 18+
- PostgreSQL 14+
- Docker & Docker Compose
- Composer 2+

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/FuukaSyafiq/distrozone-web.git
cd distrozone-web
```

### 2. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Update the `.env` file with your configuration:

```env
APP_NAME="Distro Zone"
APP_ENV=local
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=distrozone
DB_USERNAME=postgres
DB_PASSWORD=your_password

AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_BUCKET=distrozone
AWS_ENDPOINT=http://localhost:9000
```

### 3. Using Docker (Recommended)

Start the development environment:

```bash
make dev        # Start development containers
# or
make run        # Start required containers only
```

Or manually with Docker Compose:

```bash
docker compose -f compose.dev.yml up -d
```

This will start:
- **Web Server** (Nginx) - Port 8000
- **PHP-FPM** - Application runtime
- **Workspace** - Vite dev server on port 5173
- **PostgreSQL** - Database on port 5432
- **MinIO** - S3 storage on ports 9000/9001

### 4. Install Dependencies

```bash
# Inside workspace container or locally
make install
# or
composer install
npm install
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations

```bash
make migrate-up
# or
php artisan migrate
```

### 7. Seed Initial Data (Optional)

```bash
make seed-up
# or
php artisan db:seed --class=InitSeeder
```

### 8. Create Storage Link

```bash
make link
# or
php artisan storage:link
```

### 9. Build Assets

```bash
npm run dev    # Development with hot reload
# or
npm run build  # Production build
```

## Accessing the Application

| Service | URL |
|---------|-----|
| Application | http://localhost:8000 |
| Admin Panel | http://localhost:8000/admin |
| Kasir Panel | http://localhost:8000/kasir |
| MinIO Console | http://localhost:9001 |
| Mailpit | http://localhost:8025 |
| Vite Dev Server | http://localhost:5173 |

### Default Admin Credentials

```
Email: admin@distrozone.com
Password: password
```

## Available Make Commands

```bash
make install       # Install dependencies
make run           # Start required containers
make dev           # Start development containers
make dev-down      # Stop development containers
make prod          # Start production containers
make prod-down     # Stop production containers
make migrate-up    # Run migrations
make migrate-down  # Rollback migrations
make seed-up       # Seed database
make seed-down     # Unseed database
make clear         # Clear Laravel caches
make link          # Create storage symlink
```

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | Distro Zone |
| `APP_ENV` | Environment | local |
| `APP_DEBUG` | Debug mode | true |
| `APP_URL` | Application URL | http://localhost |
| `DB_CONNECTION` | Database driver | pgsql |
| `DB_HOST` | Database host | postgres |
| `DB_PORT` | Database port | 5432 |
| `DB_DATABASE` | Database name | distrozone |
| `DB_USERNAME` | Database user | postgres |
| `DB_PASSWORD` | Database password | root |
| `AWS_ACCESS_KEY_ID` | S3/MinIO access key | minio |
| `AWS_SECRET_ACCESS_KEY` | S3/MinIO secret key | changeme321 |
| `AWS_BUCKET` | S3/MinIO bucket | distrozone |
| `AWS_ENDPOINT` | S3/MinIO endpoint | http://minio:9000 |
| `MAIL_MAILER` | Mail driver | smtp |
| `MAIL_HOST` | SMTP host | mailpit |
| `MAIL_PORT` | SMTP port | 1025 |
| `VITE_PORT` | Vite dev server port | 5173 |

## Project Structure

```
distrozone/
├── app/
│   ├── Filament/           # Filament admin resources
│   │   ├── Pages/          # Dashboard, Settings
│   │   ├── Resources/      # CRUD resources
│   │   └── Widgets/        # Dashboard widgets
│   ├── Helpers/            # Helper functions
│   └── Models/             # Eloquent models
├── bootstrap/              # Laravel bootstrap
├── config/                 # Configuration files
├── database/               # Migrations, seeders
├── docker/                 # Docker configurations
├── public/                 # Public assets
├── resources/              # Views, CSS, JS
├── routes/                 # Application routes
├── storage/                # Storage (logs, uploads)
├── tests/                  # PHPUnit tests
├── compose.dev.yml         # Development compose
├── compose.prod.yml        # Production compose
├── Makefile                # Development commands
├── composer.json           # PHP dependencies
├── package.json            # Node dependencies
├── vite.config.js          # Vite configuration
└── tailwind.config.js      # Tailwind configuration
```

## Common Tasks

### Clear All Caches

```bash
make clear
```

### Reset Database

```bash
php artisan migrate:fresh --seed
```

### Create New Admin User

```bash
php artisan make:filament-user
```

### Build for Production

```bash
npm run build
php artisan optimize
```

### Stop Containers

```bash
make dev-down
# or
docker compose -f compose.dev.yml down
```

### Adding New Products

1. Go to Admin Panel > Kaos
2. Click "New Kaos"
3. Fill in product details (name, price, description)
4. Add variants (size, color)
5. Upload product images
6. Set stock quantities

### Managing Shipping Rates

1. Go to Admin Panel > Ongkir
2. Add shipping rates by city
3. Configure base rates and per-kg rates

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
