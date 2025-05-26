# Laboratory Management System

A comprehensive web-based system for managing laboratory rooms, equipment, and maintenance.

## Features

- Room Management
  - Add, edit, and view rooms
  - Track room capacity and equipment
  - Room status monitoring

- Equipment Management
  - Equipment inventory tracking
  - Equipment status monitoring
  - Maintenance scheduling

- User Management
  - Role-based access control
  - User authentication
  - Activity logging

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP (recommended for local development)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/lab_management_.git
```

2. Set up your web server (XAMPP):
   - Place the project in your `htdocs` directory
   - Start Apache and MySQL services

3. Create a database:
   - Open phpMyAdmin
   - Import the database schema from `sql/database.sql`
   - Import the database schema from `sql/update_audit_logs.sql`
   - Import the database schema from `sql/password_resets.sql`

4. Access the application:
   - Open your browser
   - Navigate to `http://localhost/lab_management_`

## Default Login

- Admin:
  - Username: admin
  - Password: admin123

## Security

- Change default passwords after installation
- Keep your configuration files secure
- Regularly update dependencies
- Follow security best practices

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please open an issue in the GitHub repository or contact the development team. 

## Reset Password Problem

Ibahin niyo nalang yung mismong ip address na nakalagay sa `config/config.php`

- Punta kayo sa Command Prompt at i-type niyo lang ay "ipconfig"
- Hanapin niyo ang may IPv4 at i-copy at paste niyo dun sa mismong `config/config.php` line 10. Tapos subukan niyo ulit irun pag hindi gumana bahala na si batman
