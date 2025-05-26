# Lab Management System

A comprehensive laboratory management system for educational institutions to manage equipment, borrowing, maintenance, and user roles.

## Features

- User authentication and role-based access control
- Equipment management and tracking
- Borrowing system for lab equipment
- Maintenance scheduling and tracking
- Room management
- User profile management
- Email notifications

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/MateoRommel12/lab_management_.git
cd lab_management_
```

2. Install dependencies:
```bash
composer install
```

3. Create a database and import the schema:
```bash
mysql -u your_username -p your_database_name < sql/schema.sql
```

4. Configure the application:
   - Update the configuration values in `config/database.php`
   - Make sure to set the correct database credentials
   - Configure your email settings

5. Set up your web server:
   - Point your web server to the project directory
   - Ensure the web server has write permissions for uploads and logs

## Configuration

1. Database Configuration:
   - Update database credentials in `config/database.php`
   - Set appropriate database host, username, password, and database name

2. Email Configuration:
   - Configure SMTP settings in `config/database.php`
   - For Gmail, use App Password instead of regular password
   - Update SMTP_USER and SMTP_PASS with your email credentials

3. Application Settings:
   - Update `APP_URL` to match your server configuration
   - Modify other application settings as needed

## Security

- Never commit sensitive configuration files
- Keep your composer dependencies updated
- Use strong passwords for database and email accounts
- Regularly backup your database
- Keep your PHP version updated
- Use HTTPS in production

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 