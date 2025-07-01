# AmyoParks - Park Database & Management System

A comprehensive park database and management system for Kansas City area parks, featuring both public browsing and administrative management capabilities.

## Features

### Public Interface
- 🏞️ **Park Directory** - Browse and search parks by location, amenities, and categories
- 🔍 **Advanced Search** - Filter parks by city, amenities, and other criteria  
- 📍 **Park Details** - Detailed information including amenities, hours, contact info
- 📱 **Responsive Design** - Mobile-friendly interface

### Admin Dashboard
- 👥 **Admin Authentication** - Secure login system for administrators
- 🏞️ **Park Management** - Full CRUD operations for parks
- 🎯 **Amenity Management** - Manage park amenities and categories
- 🏷️ **Category Management** - Organize parks and amenities by categories
- 📊 **Dashboard Analytics** - Statistics and insights about the park system
- 🌐 **Scraping Portal** - Import park data from external websites

### Scraping Portal
- 🔗 **Website Integration** - Connect to external park websites
- ⚙️ **Configurable Scraping** - JSON-based CSS selector configuration
- 📈 **Progress Tracking** - Real-time scraping progress with detailed logs
- 🗂️ **Activity Logging** - Track all scraping operations
- 🎛️ **Website Management** - Configure and manage scraping targets

## Technology Stack

- **Backend**: PHP 8+, MySQL
- **Frontend**: HTML5, CSS3, JavaScript, Tailwind CSS
- **Database**: MySQL with optimized schema
- **Architecture**: MVC-inspired structure with modular components

## Installation

1. Clone the repository
2. Configure database connection in `includes/db.php`
3. Import the database schema from `docs/parks_db.sql`
4. Set up web server (Apache/Nginx) with PHP 8+
5. Configure admin user credentials

## Database Setup

### Main Database
```sql
-- Import the main database structure
source docs/parks_db.sql
```

### Scraping Portal (Optional)
```sql  
-- Add scraping functionality
source docs/add_scraping_to_existing_db.sql
```

## Configuration

### Database Connection
Edit `includes/db.php` with your database credentials:
```php
$host = 'your_host';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';
```

### Admin Access
Default admin credentials can be configured in the admin setup.

## Project Structure

```
amyoparks/
├── admin/              # Admin dashboard and management
│   ├── amenities/      # Amenity management
│   ├── categories/     # Category management  
│   ├── parks/          # Park management
│   ├── scraping/       # Scraping portal
│   └── includes/       # Admin shared components
├── includes/           # Shared PHP components
├── css/               # Stylesheets
├── js/                # JavaScript files
├── docs/              # Documentation and SQL files
└── [public pages]     # Main website pages
```

## Features in Detail

### Park Management
- Complete CRUD operations for park records
- Support for park details including location, hours, contact info
- Amenity association and management
- Category assignment and organization

### Scraping System
- Import parks from external websites automatically
- Configurable CSS selectors for different website structures
- Progress tracking with real-time updates
- Error handling and logging
- Support for multiple website configurations

### Search & Browse
- Multi-criteria search functionality
- Filter by location, amenities, categories
- Responsive grid and list views
- Detailed park information pages

## Contributing

This is a custom park management system. For modifications or enhancements, please ensure:
- Database changes are documented
- Admin functionality is tested
- Public interface remains user-friendly
- Security best practices are followed

## License

Custom application for AmyoParks. All rights reserved.

## Support

For technical support or questions about the system, please contact the development team.

---

**Version**: 1.0  
**Last Updated**: July 2025  
**Compatibility**: PHP 8+, MySQL 8+
