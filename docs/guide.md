# Website Development Plan for amyoparks.com

Claude, this is a detailed plan to build a website for amyoparks.com using a Laragon testing environment with PHP/MySQL, based on the provided database schema. The site will list parks and their amenities, with a front-facing public interface and a password-protected admin area for CRUD operations. The site will be developed locally on Laragon and later deployed to a Hostinger shared server. Follow this plan precisely to ensure a robust, secure, and user-friendly website.

## Project Overview

**Purpose:** Create a website at amyoparks.com to display parks, their amenities, and detailed attributes (e.g., pool depth, field fence length), with search and filter functionality.

**Environment:**
- Local: Laragon (Windows) with PHP 8.2, MySQL 8.0, Apache/Nginx.
- Production: Hostinger shared server (Linux, PHP 8.2, MySQL 8.0).

**Database:** Use the MySQL schema provided (parks_db with tables: states, cities, zip_codes, parks, amenity_categories, attribute_types, amenities, amenity_attributes).

**Framework:** Use plain PHP with PDO for database interactions, Tailwind CSS for styling, and vanilla JavaScript for interactivity. No frameworks (e.g., Laravel) to keep it lightweight for Hostinger.

**Security:** Implement CSRF protection, input validation, prepared statements, and password hashing (bcrypt) for the admin area.

**Deployment:** Develop locally, test thoroughly, then upload to Hostinger via FTP or Git.

## Setup Laragon Environment

### Install Laragon:
- Download and install Laragon Full (includes PHP, MySQL, Apache/Nginx) from laragon.org.
- Ensure PHP 8.2 and MySQL 8.0 are selected.
- Start Laragon and verify the server is running (http://localhost).

### Create Project Directory:
Place the project in C:\laragon\www\amyoparks.

**Structure:**
```
amyoparks/
├── css/
│   └── styles.css
├── js/
│   └── scripts.js
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── admin/
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── parks/
│   ├── amenities/
│   ├── categories/
│   ├── attributes/
│   └── locations/
├── index.php
├── parks.php
├── park-details.php
├── search.php
├── about.php
├── contact.php
└── .htaccess
```

### Database Setup:
- Open Laragon's Terminal (or HeidiSQL) and create the database:
  ```sql
  CREATE DATABASE parks_db;
  ```
- Import the provided schema (parks_database_optimized.sql) and category population (populate_amenity_categories.sql) from the previous responses.
- Create a MySQL user:
  ```sql
  CREATE USER 'parks_user'@'localhost' IDENTIFIED BY 'SecureP@ssw0rd123';
  GRANT ALL PRIVILEGES ON parks_db.* TO 'parks_user'@'localhost';
  FLUSH PRIVILEGES;
  ```
- In includes/db.php, set up PDO connection:
  ```php
  <?php
  try {
      $pdo = new PDO('mysql:host=localhost;dbname=parks_db', 'parks_user', 'SecureP@ssw0rd123');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      die('Connection failed: ' . $e->getMessage());
  }
  ?>
  ```

### Configure .htaccess:
Enable clean URLs and security headers:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "DENY"
Header set X-XSS-Protection "1; mode=block"
```

## Front-Facing Pages

Create the following public pages, all responsive, using Tailwind CSS (CDN: https://cdn.tailwindcss.com) and a consistent header/footer. Use includes/header.php and includes/footer.php for navigation and layout. All pages should be accessible, with ARIA labels and keyboard navigation.

### index.php (Homepage):
**Purpose:** Welcome users, highlight featured parks, and provide a search bar.

**Content:**
- Hero section with a call-to-action: "Find Your Perfect Park".
- Search form (POST to search.php) with fields: city, state, amenity category (dropdown populated from amenity_categories).
- Featured parks section (display 3 random parks from parks with name, city, and image placeholder).
- Brief about section linking to about.php.

**Query:**
```sql
SELECT p.park_id, p.name, c.name AS city, s.code AS state
FROM parks p
JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
JOIN cities c ON z.city_id = c.city_id
JOIN states s ON c.state_id = s.state_id
ORDER BY RAND() LIMIT 3;
```

### parks.php (Parks List):
**Purpose:** Display all parks with pagination and filters.

**Content:**
- Filter form (GET): state, city, zip code, amenity category (checkboxes from amenity_categories).
- Paginated table: park name, city, state, number of amenities (count from amenities).
- Links to park-details.php?park_id=X for each park.
- Pagination (10 parks per page).

**Query:**
```sql
SELECT p.park_id, p.name, c.name AS city, s.code AS state, COUNT(a.amenity_id) AS amenity_count
FROM parks p
LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
LEFT JOIN cities c ON z.city_id = c.city_id
LEFT JOIN states s ON c.state_id = s.state_id
LEFT JOIN amenities a ON p.park_id = a.park_id
WHERE (c.name = :city OR :city IS NULL)
AND (s.code = :state OR :state IS NULL)
AND (z.code = :zip OR :zip IS NULL)
AND (a.category_id IN (:categories) OR :categories IS NULL)
GROUP BY p.park_id
LIMIT 10 OFFSET :offset;
```

### park-details.php (Park Details):
**Purpose:** Show detailed information for a single park.

**Content:**
- Park name, address, city, state, zip code, hours, coordinates.
- List of amenities (from amenities) with instance names (e.g., "Smith Field") and attributes (from amenity_attributes joined with attribute_types).
- Map placeholder (use Leaflet.js CDN for future integration).

**Query:**
```sql
SELECT p.*, c.name AS city, s.code AS state, z.code AS zip_code
FROM parks p
LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
LEFT JOIN cities c ON z.city_id = c.city_id
LEFT JOIN states s ON c.state_id = s.state_id
WHERE p.park_id = :park_id;

SELECT a.amenity_id, a.instance_name, ac.name AS category, at.name AS attribute_name, aa.attribute_value
FROM amenities a
JOIN amenity_categories ac ON a.category_id = ac.category_id
LEFT JOIN amenity_attributes aa ON a.amenity_id = aa.amenity_id
LEFT JOIN attribute_types at ON aa.attribute_type_id = at.attribute_type_id
WHERE a.park_id = :park_id
ORDER BY ac.name, a.instance_name;
```

### search.php (Search Results):
**Purpose:** Display parks matching search criteria.

**Content:**
- Form mirroring index.php search, pre-filled with user input.
- Results table: park name, city, state, matching amenities (from amenities).
- Links to park-details.php.

**Query:**
```sql
SELECT DISTINCT p.park_id, p.name, c.name AS city, s.code AS state
FROM parks p
LEFT JOIN zip_codes z ON p.zip_code_id = z.zip_code_id
LEFT JOIN cities c ON z.city_id = c.city_id
LEFT JOIN states s ON c.state_id = s.state_id
LEFT JOIN amenities a ON p.park_id = a.park_id
WHERE (c.name LIKE :city OR :city IS NULL)
AND (s.code = :state OR :state IS NULL)
AND (a.category_id = :category OR :category IS NULL)
LIMIT 10 OFFSET :offset;
```

### about.php (About Page):
**Purpose:** Provide information about the website's purpose.

**Content:**
- Static text: "AmyOParks helps you find parks with amenities like pools, fields, and trails."
- List of available amenity categories (from amenity_categories).

**Query:**
```sql
SELECT name, description FROM amenity_categories ORDER BY name;
```

### contact.php (Contact Page):
**Purpose:** Allow users to send inquiries.

**Content:**
- Form (POST): name, email, message. Store submissions in a new contacts table.
- Display success message on submission (no email setup for local testing).

**Table Creation:**
```sql
CREATE TABLE contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Admin Area (CRUD)

Create a password-protected admin area in the admin/ directory. Use sessions for authentication, bcrypt for password hashing, and CSRF tokens for form security. All CRUD operations should validate input and use prepared statements.

### Database Setup for Admins:
Create an admins table:
```sql
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Insert a default admin (username: admin, password: Admin123!):
```sql
INSERT INTO admins (username, password_hash)
VALUES ('admin', '$2y$10$exampleHashForAdmin123!'); -- Replace with bcrypt hash
```

Generate the password hash in PHP:
```php
echo password_hash('Admin123!', PASSWORD_BCRYPT);
```

### admin/login.php:
**Purpose:** Authenticate admins.

**Content:**
- Form: username, password.
- Validate credentials using password_verify().
- Set session ($_SESSION['admin_id']) on success, redirect to dashboard.php.
- Display error for invalid credentials.

**Query:**
```sql
SELECT admin_id, password_hash FROM admins WHERE username = :username;
```

### admin/logout.php:
**Purpose:** End admin session.

**Content:**
```php
session_start();
session_destroy();
header('Location: login.php');
exit;
```

### admin/index.php:
Redirect to dashboard.php if logged in, else to login.php.

### admin/dashboard.php:
**Purpose:** Admin homepage.

**Content:**
- Links to CRUD sections: Parks, Amenities, Categories, Attributes, Locations (States/Cities/Zip Codes).
- Summary: total parks, amenities, categories.

**Query:**
```sql
SELECT (SELECT COUNT(*) FROM parks) AS park_count,
       (SELECT COUNT(*) FROM amenities) AS amenity_count,
       (SELECT COUNT(*) FROM amenity_categories) AS category_count;
```

### admin/parks/ (CRUD for Parks):
**Files:**
- index.php: List parks with edit/delete links.
- create.php: Form to add a park.
- edit.php: Form to update a park.
- delete.php: Confirm and delete a park.

**Fields:** name, address, zip_code_id (dropdown from zip_codes), latitude, longitude, hours_open, hours_close.

**Queries:**
- List: `SELECT p.park_id, p.name, c.name AS city, s.code AS state FROM parks p ...`
- Create: `INSERT INTO parks (name, address, zip_code_id, latitude, longitude, hours_open, hours_close) VALUES (:name, :address, :zip_code_id, :latitude, :longitude, :hours_open, :hours_close);`
- Update: `UPDATE parks SET name = :name, ... WHERE park_id = :park_id;`
- Delete: `DELETE FROM parks WHERE park_id = :park_id;`

### admin/amenities/ (CRUD for Amenities):
**Files:** Same as parks (index, create, edit, delete).

**Fields:** park_id (dropdown from parks), category_id (dropdown from amenity_categories), instance_name, attributes (dynamic form to add attribute_type_id and attribute_value).

**Queries:**
- List: `SELECT a.amenity_id, a.instance_name, p.name AS park, ac.name AS category FROM amenities a ...`
- Create: `INSERT INTO amenities (park_id, category_id, instance_name) VALUES (:park_id, :category_id, :instance_name);`
- Attributes: `INSERT INTO amenity_attributes (amenity_id, attribute_type_id, attribute_value) VALUES (:amenity_id, :attribute_type_id, :attribute_value);`

### admin/categories/ (CRUD for Amenity Categories):
**Files:** Same as parks.

**Fields:** name, description.

**Queries:**
- List: `SELECT category_id, name, description FROM amenity_categories;`
- Create: `INSERT INTO amenity_categories (name, description) VALUES (:name, :description);`

### admin/attributes/ (CRUD for Attribute Types):
**Files:** Same as parks.

**Fields:** name, description.

**Queries:**
- List: `SELECT attribute_type_id, name, description FROM attribute_types;`
- Create: `INSERT INTO attribute_types (name, description) VALUES (:name, :description);`

### admin/locations/ (CRUD for States, Cities, Zip Codes):
**Subdirectories:** states/, cities/, zip_codes/, each with index, create, edit, delete.

**Fields:**
- States: name, code.
- Cities: name, state_id (dropdown).
- Zip Codes: code, city_id (dropdown).

**Queries:**
- States: `INSERT INTO states (name, code) VALUES (:name, :code);`
- Cities: `INSERT INTO cities (name, state_id) VALUES (:name, :state_id);`
- Zip Codes: `INSERT INTO zip_codes (code, city_id) VALUES (:code, :city_id);`

## Implementation Details

### Styling:
- Use Tailwind CSS via CDN in header.php.
- Apply a clean, modern design: sans-serif fonts, green/blue color scheme (#10B981, #3B82F6), rounded buttons, and card layouts.
- Ensure mobile responsiveness with Tailwind's utility classes (e.g., sm:, md:).

### JavaScript:
In js/scripts.js, add:
- Dynamic search filtering (debounced input for search.php).
- Form validation (client-side for required fields).
- Admin form handling (e.g., dynamic attribute fields in amenities/create.php).
- Use vanilla JS, no jQuery.

### Security:
- **CSRF:** Generate tokens in forms:
  ```php
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
  ```
- Validate on submission:
  ```php
  if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      die('CSRF token validation failed');
  }
  ```
- **Input Validation:** Sanitize all inputs (e.g., filter_var(), htmlspecialchars()).
- **Prepared Statements:** Use PDO with named parameters for all queries.
- **Admin Access:** Check $_SESSION['admin_id'] on all admin pages; redirect to login.php if not set.

### includes/functions.php:
Add helper functions:
- `is_logged_in()`: Check admin session.
- `sanitize_input($input)`: Clean user input.
- `get_amenity_categories($pdo)`: Fetch categories for dropdowns.
- `get_attribute_types($pdo)`: Fetch attribute types for admin forms.

## Testing

### Local Testing:
- Test all pages in Laragon (http://localhost/amyoparks).
- Verify:
  - Search and filter functionality (e.g., find parks by city or amenity).
  - Admin CRUD operations (add/edit/delete parks, amenities, etc.).
  - Responsiveness on mobile and desktop (use browser dev tools).
  - Security (CSRF, SQL injection attempts, XSS prevention).
- Use Laragon's PHPMyAdmin to inspect database changes.

### Performance:
- Ensure queries are fast (test with 1000+ parks using a script to insert dummy data).
- Check page load times (<2s) using browser dev tools.

## Deployment to Hostinger

### Prepare Files:
- Update db.php with Hostinger's MySQL credentials (from control panel).
- Export local database (parks_db) using PHPMyAdmin and import on Hostinger.

### Upload:
- Use FileZilla or Hostinger's File Manager to upload amyoparks/ to /public_html.
- Verify .htaccess works (mod_rewrite enabled on Hostinger).

### Test Production:
- Access amyoparks.com and test all functionality.
- Check error logs in Hostinger's control panel if issues arise.
- Update DNS if domain is registered elsewhere (point to Hostinger's nameservers).

## Maintenance Plan

- **Backups:** Schedule weekly database backups via Hostinger's control panel.
- **Updates:** Monitor PHP/MySQL updates on Hostinger; test locally before applying.
- **SEO:** Add meta tags in header.php:
  ```html
  <meta name="description" content="Find parks with amenities like pools, fields, and trails.">
  <meta name="keywords" content="parks, amenities, outdoor, recreation">
  ```
- **Analytics:** Add Google Analytics via <script> in header.php.

## Notes

- Ensure all forms have CSRF tokens and validation.
- Use prepared statements for all database interactions.
- Test edge cases (e.g., empty search results, invalid park IDs).
- Keep code modular and commented for future maintenance.
- If issues arise, log errors to a file (e.g., error_log() in db.php).

Claude, execute this plan exactly as outlined. Create all files, implement the queries, and test thoroughly. The result should be a fully functional, secure, and responsive website ready for deployment to Hostinger. Let me know if you need clarification on any step.

## Current Implementation Status

### ✅ Completed:
1. **Project Structure** - All directories and core files created
2. **Core Infrastructure:**
   - Database connection (includes/db.php) - configured for root user with blank password
   - Security headers and URL rewriting (.htaccess)
   - Helper functions (includes/functions.php)
   - Header and footer templates with responsive navigation
   - Custom CSS with Tailwind integration (css/styles.css)
   - JavaScript functionality (js/scripts.js)

3. **Front-Facing Pages:**
   - Homepage (index.php) - Hero section, search form, featured parks
   - Parks listing (parks.php) - Filterable, paginated park list
   - Park details (park-details.php) - Individual park information with amenities
   - Search page (search.php) - Advanced search with AJAX support
   - About page (about.php) - Information about the website and available amenities
   - Contact page (contact.php) - Contact form with database storage

4. **Security Features:**
   - CSRF token generation and validation
   - Input sanitization functions
   - Prepared statements for all database queries
   - XSS and injection protection

5. **Responsive Design:**
   - Mobile-first responsive layout
   - Tailwind CSS integration
   - Accessible navigation and forms
   - Print-friendly styles

### 🔄 Next Steps (Admin Area):
The admin area still needs to be created. This will include:
- Admin authentication system
- CRUD interfaces for parks, amenities, categories, attributes, and locations
- Dashboard with statistics
- Session management and security

### 🗄️ Database Requirements:
The website expects the following database structure to be present:
- `parks_db` database
- Tables: `states`, `cities`, `zip_codes`, `parks`, `amenity_categories`, `attribute_types`, `amenities`, `amenity_attributes`
- `admins` table (will be created by admin system)
- `contacts` table (created automatically by contact form)

The public-facing website is now fully functional and ready for testing at `http://localhost/amyoparks` once the database is populated with some sample data.
