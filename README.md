# FMRR Document Management System

A comprehensive document management system built with Laravel for managing regulations, compliance documents, and related content. This system provides a centralized platform for storing, organizing, and accessing important regulatory documents with advanced search and filtering capabilities, role-based access control, and payment integration for document access.

## Table of Contents
- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database](#database)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Development](#development)
- [Testing](#testing)
- [Deployment](#deployment)
- [Session Management](#session-management)
- [Contributing](#contributing)
- [License](#license)

## About

The FMRR Document Management System is designed to efficiently manage regulatory documents, track compliance requirements, and facilitate document workflows within organizations. It provides a centralized platform for storing, organizing, and accessing important regulatory documents with advanced search and filtering capabilities.

## Features

- **Document Management**: Upload, categorize, and organize regulatory documents
- **Compliance Tracking**: Monitor document status and compliance requirements
- **Advanced Search**: Powerful search functionality across document content and metadata
- **User Roles & Permissions**: Role-based access control for different user types using Spatie Laravel Permission
- **Document Relationships**: Link related documents and track document lineage
- **Version Control**: Track document versions and changes over time
- **Notifications**: Email notifications for document updates and approvals
- **Reporting**: Generate compliance reports and document analytics
- **Responsive Design**: Mobile-friendly interface that works on all devices
- **Session Management**: Configurable session timeouts with automatic extension based on user activity
- **Payment Integration**: Paystack and Flutterwave integration for document access payments
- **PDF Processing**: Text extraction and preview capabilities for PDF documents
- **Subscription Management**: User subscription plans for document access
- **Activity Logging**: Track user activities and document access
- **News & Alerts**: Internal communication system for announcements

## Tech Stack

### Backend
- **PHP 8.0+**
- **Laravel 8.x**
- **MySQL 5.7+**

### Frontend
- **Bootstrap 5**
- **jQuery**
- **JavaScript (ES6+)**
- **Sass/SCSS**
- **NPM** for asset management

### Key Libraries & Packages
- **Laravel Sanctum** for API authentication
- **Spatie Laravel Permission** for roles and permissions
- **Smalot PDF Parser** for PDF text extraction
- **Paystack & Flutterwave** for payment processing
- **Laravel Collective HTML** for form building
- **Guzzle HTTP Client** for API requests

### Development Tools
- **Composer** for PHP dependency management
- **NPM** for frontend asset management
- **Laravel Mix** for asset compilation
- **PHPUnit** for testing

## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher (or SQL Server for specific connections)
- Composer
- Node.js and NPM
- Git

### Steps

1. **Clone the Repository**
```bash
git clone https://github.com/your-username/fmrr-dev.git
cd fmrr-dev
```

2. **Install PHP Dependencies**
```bash
composer install
```

3. **Install Node Dependencies**
```bash
npm install
```

4. **Copy and Configure Environment File**
```bash
cp .env.example .env
```

5. **Generate Application Key**
```bash
php artisan key:generate
```

6. **Configure Database**
Update the `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. **Run Database Migrations**
```bash
php artisan migrate
```

8. **Seed the Database (Optional)**
```bash
php artisan db:seed
```

9. **Compile Assets**
```bash
npm run dev
# Or for production
npm run prod
```

## Configuration

### Environment Variables
Key environment variables to configure:

```env
APP_NAME="FMRR Document Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fmrr_dev
DB_USERNAME=root
DB_PASSWORD=

# Session Configuration
SESSION_LIFETIME=120

# Payment Gateways
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
FLUTTERWAVE_PUBLIC_KEY=your_flutterwave_public_key
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage
The application uses Laravel's file storage system. By default, documents are stored in:
- Local storage: `storage/app/public/documents/`

To make files publicly accessible:
```bash
php artisan storage:link
```

## Database

### Migrations
The database schema is managed through Laravel migrations. Key tables include:
- `users` - User accounts and profiles
- `regulations` - Regulatory documents
- `categories` - Document categories
- `subcategories` - Document subcategories
- `entities` - Document entities
- `groups` - Document groups
- `documents_relationships` - Document relationships
- `roles` & `permissions` - Access control (Spatie Laravel Permission)
- `sessions` - User sessions
- `payments` - Payment records
- `subscriptions` - User subscriptions
- `news` - News and alerts
- `downloads` - Document download tracking
- `session_settings` - Configurable session timeout settings

### Seeding
Default data can be seeded using:
```bash
php artisan db:seed
```

## Usage

### Starting the Development Server
```bash
php artisan serve
```
The application will be available at `http://localhost:8000`

### Compiling Assets
During development:
```bash
npm run watch
```

For production:
```bash
npm run prod
```

### Key Functionalities

1. **Document Management**
   - Upload and categorize documents
   - Set document status (Approved, Pending, Rejected)
   - Link related documents
   - Track document versions
   - Manage ceased documents
   - Categorize by entity, group, and year

2. **User Management**
   - Role-based access control using Spatie permissions
   - User registration and authentication
   - Profile management
   - External user support
   - Deactivated user management

3. **Compliance Tracking**
   - Monitor document compliance status
   - Set document expiry dates
   - Track approval workflows
   - Document relationship management

4. **Payment & Subscription**
   - Paystack and Flutterwave integration
   - Subscription plan management
   - Document purchase tracking
   
5. **Communication**
   - News and alert system
   - Feedback collection
   - User contact forms
   
6. **Session Management**
   - Configurable timeout settings
   - Automatic session extension based on activity
   - Session timeout warnings
   - AJAX-based session refresh

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/register` - User registration
- `POST /api/logout` - User logout

### Documents
- `GET /api/documents` - List all documents
- `POST /api/documents` - Create new document
- `GET /api/documents/{id}` - Get document details
- `PUT /api/documents/{id}` - Update document
- `DELETE /api/documents/{id}` - Delete document

### Categories
- `GET /api/categories` - List all categories
- `POST /api/categories` - Create new category

### Session Management
- `POST /session/refresh` - Refresh user session
- `GET /session/check` - Check session status
- `GET /debug-session-timeout` - Debug session timeout values

## Development

### Code Structure
```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Providers/
├── Services/
├── Mail/
├── Jobs/
└── Helpers/
config/
database/
├── migrations/
├── seeds/
└── factories/
resources/
├── views/
├── js/
└── sass/
routes/
tests/
```

### Adding New Features

1. Create new controller:
```bash
php artisan make:controller FeatureController
```

2. Create new model:
```bash
php artisan make:model Feature
```

3. Create migration:
```bash
php artisan make:migration create_features_table
```

4. Create new mail class (for notifications):
```bash
php artisan make:mail FeatureNotification
```

5. Clear caches after changes:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=FeatureTest
```

### Test Types
- **Unit Tests**: Located in `tests/Unit/`
- **Feature Tests**: Located in `tests/Feature/`

### Test Coverage
The application includes tests for:
- Authentication flows
- Document management operations
- Payment processing
- User role and permission checks
- Session management functionality

## Deployment

### Production Deployment Steps

1. **Set Environment to Production**
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize Autoload**
```bash
composer install --optimize-autoloader --no-dev
```

3. **Cache Configuration**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

4. **Set Proper File Permissions**
```bash
chmod -R 755 storage bootstrap/cache
```

5. **Configure Web Server**
Ensure your web server (Apache/Nginx) points to the `public` directory.

6. **Run Migrations**
```bash
php artisan migrate --force
```

7. **Link Storage**
```bash
php artisan storage:link
```

### Server Requirements
- PHP >= 8.0
- MySQL >= 5.7 (or SQL Server)
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

### Session Management in Production
In production environments, ensure that:
- The `session_settings` table is properly configured with appropriate timeout values
- Session driver is set appropriately (database recommended for production)
- Regular cleanup of expired sessions is configured

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a pull request

### Coding Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Include tests for new features
- Update documentation as needed

### Branch Naming Convention
- `feature/feature-name` for new features
- `bugfix/issue-name` for bug fixes
- `hotfix/hotfix-name` for urgent fixes
- `release/version-number` for releases

### Pull Request Process
1. Ensure all tests pass before submitting
2. Update README.md with details of changes if applicable
3. Increase version number in any changed files according to semantic versioning
4. Squash commits before merging

## Session Management

The FMRR Document Management System includes an advanced session management system that automatically extends user sessions based on activity, preventing unexpected logouts during active usage.

### How It Works
- Sessions are tracked both client-side and server-side
- User activity (navigation, form submissions, AJAX requests) automatically extends the session
- A warning modal appears 1 minute before session expiration
- Users can extend their session with the "Continue Session" button
- Sessions automatically expire after the configured timeout period of inactivity

### Configuration
Session timeout values are configurable through the admin panel and stored in the `session_settings` database table. The default timeout is 120 minutes, but administrators can adjust this value as needed.

### Verification
To verify session management is working correctly:

1. Navigate to the session management test page (requires authentication)
2. Perform various activities on the site
3. Observe that the session timeout warning appears before expiration
4. Confirm that sessions don't expire while you're active
5. Refer to `SESSION_MANAGEMENT_VERIFICATION.md` for detailed testing procedures

### Troubleshooting
If experiencing session issues:
1. Check browser console for JavaScript errors
2. Verify CSRF token is included in AJAX requests
3. Ensure session routes are properly registered
4. Check middleware is loaded in correct order
5. Verify database session settings are being applied

## License

This project is proprietary and confidential. Unauthorized copying, distribution, or modification is strictly prohibited.

## Support

For support, contact your system administrator or create an issue in the internal ticketing system.