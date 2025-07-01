# AmyoParks Deployment Guide for Hostinger

## 🚀 Deployment Steps

### Option 1: Direct Download from GitHub
1. Go to your Hostinger file manager or cPanel
2. Navigate to your domain's public_html folder
3. Download the project as ZIP from: https://github.com/deez-nutter/amyoparks/archive/refs/heads/main.zip
4. Extract the ZIP file in public_html
5. Rename the folder from `amyoparks-main` to `amyoparks` (or whatever you prefer)

### Option 2: Git Clone (if Hostinger supports SSH/Git)
```bash
cd public_html
git clone https://github.com/deez-nutter/amyoparks.git
```

## 🗄️ Database Setup on Hostinger

### Step 1: Create Database
1. In cPanel, go to "MySQL Databases"
2. Create a new database (e.g., `yourusername_parks_db`)
3. Create a database user with password
4. Assign the user to the database with ALL privileges

### Step 2: Import Database Structure
1. Go to phpMyAdmin in cPanel
2. Select your new database
3. Go to "Import" tab
4. Upload and run: `docs/parks_db (1).sql`
5. Then run: `docs/add_scraping_to_existing_db.sql`

### Step 3: Update Database Configuration
Edit `includes/db.php`:
```php
<?php
$host = 'localhost';
$dbname = 'yourusername_parks_db';  // Your actual database name
$username = 'yourusername_dbuser';  // Your database username
$password = 'your_db_password';     // Your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
```

## 🔧 Configuration Changes for Production

### Update File Paths
If your site is in a subdirectory, update paths in:
- `includes/header.php` - navigation links
- `admin/includes/header.php` - admin navigation
- CSS/JS file references

### Security Considerations
1. **Remove test files**: Delete `test_scraping.php`, `debug_scraping.php`, `debug_db.php`
2. **Secure admin area**: Consider adding IP restrictions
3. **Update admin password**: Change default admin password
4. **Enable HTTPS**: Ensure SSL is enabled in Hostinger

## 📁 File Structure on Server
```
public_html/
├── amyoparks/
│   ├── admin/           # Admin panel
│   ├── css/            # Stylesheets
│   ├── includes/       # Database & shared files
│   ├── js/             # JavaScript files
│   ├── docs/           # Documentation & SQL files
│   ├── index.php       # Main website
│   └── about.php       # About page
```

## 🔍 Testing After Deployment

### Test URLs:
- Main site: `https://amyoparks.com/`
- Admin panel: `https://amyoparks.com/admin/`
- Scraping portal: `https://amyoparks.com/admin/scraping/`

### Default Admin Login:
- Username: `admin`
- Password: `password` (change this immediately!)

## 🛠️ Troubleshooting

### Common Issues:
1. **Database connection errors**: Check db.php credentials
2. **File permissions**: Ensure PHP files are executable (644)
3. **Missing data**: Import both SQL files in correct order
4. **Scraping not working**: Run the scraping database update script

### Log Files:
Check Hostinger error logs in cPanel for PHP errors.

## 📞 Support
If you encounter issues, check:
1. Hostinger's PHP version (should be 7.4+)
2. MySQL version compatibility
3. File permissions and ownership

## 🎯 Post-Deployment Checklist
- [ ] Database imported and accessible
- [ ] Admin login works
- [ ] Main website displays correctly
- [ ] Scraping portal loads without errors
- [ ] Test scraping functionality
- [ ] Update admin password
- [ ] Remove test/debug files
- [ ] Enable SSL/HTTPS
