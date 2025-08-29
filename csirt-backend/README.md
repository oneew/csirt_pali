# CSIRT PALI - Complete Backend System

A comprehensive Laravel backend system for Computer Security Incident Response Team (CSIRT) management, integrated with your existing HTML/CSS frontend templates.

## 🚀 Features

### Core Functionality
- **User Management**: Role-based access control (Admin, Operator, Analyst, Viewer)
- **Incident Management**: Complete incident lifecycle with severity tracking, assignments, and status updates
- **News & Updates**: Publishing workflow with categories, priorities, and featured articles
- **Gallery Management**: Image and media management with categories
- **Contact Management**: Handle inquiries and emergency contacts
- **Services Management**: Manage CSIRT services and offerings
- **Settings**: Configurable system settings and organization information

### Security Features
- **Authentication**: Login, registration, email verification
- **Authorization**: Role and permission-based access control
- **Activity Logging**: Complete audit trail of all user actions
- **File Upload Security**: Secure file handling with type and size restrictions
- **CSRF Protection**: Built-in Laravel CSRF protection
- **Input Validation**: Comprehensive request validation

### Dashboard & Analytics
- **Real-time Statistics**: Users, incidents, news metrics
- **Charts & Trends**: Visual analytics for incidents and user activity
- **Notifications**: System-wide notification system
- **Activity Feeds**: Recent activity monitoring

## 📋 Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM (for frontend assets)

## 🛠️ Installation

### 1. Database Setup
Create a MySQL database named `csirt_pali`:
```sql
CREATE DATABASE csirt_pali CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Environment Configuration
The `.env` file has been pre-configured with CSIRT PALI settings. Update the database credentials:
```env
DB_DATABASE=csirt_pali
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 3. Install Dependencies
```bash
cd csirt-backend
composer install
```

### 4. Run Database Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 5. Generate Application Key (if needed)
```bash
php artisan key:generate
```

### 6. Create Storage Symlink
```bash
php artisan storage:link
```

### 7. Start the Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 👥 Default User Accounts

After running the seeders, you can login with these accounts:

### Super Admin
- **Email**: admin@csirtpali.org
- **Password**: password123
- **Role**: Admin (Full access)

### Operator
- **Email**: operator@csirtpali.org
- **Password**: password123
- **Role**: Operator (Management access)

### Analyst
- **Email**: analyst@csirtpali.org
- **Password**: password123
- **Role**: Analyst (Limited management access)

### Viewer
- **Email**: viewer@csirtpali.org
- **Password**: password123
- **Role**: Viewer (Read-only access)

## 🗂️ Project Structure

```
csirt-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── Frontend/       # Public frontend controllers
│   │   └── Middleware/         # Custom middleware
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/               # Database seeders
├── public/
│   └── frontend/              # Your original HTML/CSS/JS files
├── resources/
│   └── views/
│       └── frontend/          # Your original HTML templates
└── routes/
    ├── web.php                # Web routes
    └── api.php                # API routes
```

## 🌐 Routes Overview

### Public Routes
- `/` - Homepage
- `/profile` - Organization profile
- `/services` - Services listing
- `/gallery` - Image gallery
- `/contact` - Contact form
- `/news` - News articles
- `/login` - User login
- `/register` - User registration

### Admin Routes (Protected)
- `/admin/dashboard` - Admin dashboard
- `/admin/incidents` - Incident management
- `/admin/news` - News management
- `/admin/users` - User management (Admin only)
- `/admin/gallery` - Gallery management
- `/admin/contacts` - Contact management
- `/admin/services` - Services management
- `/admin/settings` - System settings

### API Routes
- `/api/dashboard/*` - Dashboard APIs
- `/api/admin/*` - Admin panel APIs
- `/api/notifications/*` - Notification APIs
- `/api/public/*` - Public APIs (no auth required)

## 🔧 Configuration

### System Settings
The application includes configurable settings accessible through the admin panel:
- Organization information
- Security settings
- Email configuration
- Display preferences
- Feature toggles

### File Uploads
- **Max Upload Size**: 10MB (configurable)
- **Allowed Types**: JPG, PNG, GIF, PDF, DOC, DOCX, TXT, ZIP
- **Storage**: `storage/app/public/`

### Notifications
The system supports multiple notification types:
- **Incident notifications**: For new/updated incidents
- **News notifications**: For published articles
- **System notifications**: For account changes
- **Security notifications**: For critical alerts

## 🎨 Frontend Integration

Your original HTML templates have been copied to:
- **Assets**: `public/frontend/` (CSS, JS, Images)
- **Templates**: `resources/views/frontend/` (HTML files)

The backend serves your frontend templates while providing:
- Dynamic data from the database
- User authentication
- Form processing
- API endpoints for AJAX interactions

## 🔒 Security Features

### Role-Based Access Control
- **Admin**: Full system access
- **Operator**: Management functions, limited user management
- **Analyst**: Create/edit incidents and news, view users
- **Viewer**: Read-only access to incidents and news

### Permissions System
Fine-grained permissions for specific actions:
- `incidents.view`, `incidents.create`, `incidents.edit`, `incidents.delete`
- `news.view`, `news.create`, `news.edit`, `news.publish`
- `users.view`, `users.create`, `users.edit`, `users.delete`
- `settings.view`, `settings.edit`
- `reports.view`, `reports.export`

### Activity Logging
All user actions are logged with:
- User ID and name
- Action performed
- IP address and user agent
- Timestamp
- Before/after data for changes

## 🔄 API Usage

### Authentication Required
Most API endpoints require authentication. Include the session cookie or use Laravel Sanctum tokens.

### Example API Calls

#### Get Dashboard Statistics
```javascript
fetch('/api/dashboard/stats')
  .then(response => response.json())
  .then(data => console.log(data));
```

#### Create New Incident
```javascript
fetch('/api/admin/incidents', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  },
  body: JSON.stringify({
    title: 'Security Incident',
    description: 'Description of the incident',
    severity: 'high',
    category: 'malware'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 🚨 Emergency Procedures

### Critical Incident Response
1. Login to admin panel: `/admin/dashboard`
2. Navigate to: `/admin/incidents/create`
3. Set severity to "Critical"
4. Assign to appropriate team member
5. System will automatically notify all admins/operators

### Emergency Contacts
- **General**: contact@csirtpali.org
- **Emergency**: emergency@csirtpali.org
- **Phone**: +1-555-CSIRT-1

## 📊 Monitoring & Maintenance

### Logs
- **Application Logs**: `storage/logs/laravel.log`
- **Activity Logs**: Database table `activity_logs`
- **Web Server Logs**: Check your web server configuration

### Backup Recommendations
1. **Database**: Regular MySQL dumps
2. **Files**: Backup `storage/app/public/` directory
3. **Configuration**: Backup `.env` file

### Performance Tips
1. Enable Laravel caching: `php artisan config:cache`
2. Optimize autoloader: `composer install --optimize-autoloader`
3. Use database indexing for large datasets
4. Consider Redis for session/cache storage in production

## 🤝 Support

For technical support or questions:
- **Email**: admin@csirtpali.org
- **Documentation**: Available in the admin panel
- **Issue Reporting**: Contact your system administrator

## 📄 License

This system is proprietary software developed for CSIRT PALI operations.

---

**Note**: Remember to change default passwords in production and configure proper email settings for notifications to work correctly.