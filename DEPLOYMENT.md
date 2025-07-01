# AmyoParks - Hostinger Deployment Guide

## 🚀 Deploying to Hostinger Shared Server

### Prerequisites
- Hostinger hosting account with PHP support
- Database access (MySQL/MariaDB)
- File Manager or FTP access

## 📋 Step-by-Step Deployment

### Step 1: Download from GitHub
1. Go to: https://github.com/deez-nutter/amyoparks
2. Click the green "Code" button
3. Click "Download ZIP"
4. Extract the ZIP file

### Step 2: Database Setup
1. **Login to Hostinger hPanel**
2. **Go to MySQL Databases**
3. **Create a new database** (e.g., `u123456_parks_db`)
4. **Create a database user** and assign to the database
5. **Note down:**
   - Database name
   - Database username 
   - Database password
   - Database host (usually `localhost`)

### Step 3: Import Database Structure
1. **Open phpMyAdmin** from hPanel
2. **Select your database**
3. **Go to Import tab**
4. **Import this file first:** `docs/parks_db (1).sql`
5. **Then import:** `docs/add_scraping_to_existing_db.sql`

### Step 4: Update Database Configuration
1. **Edit:** `includes/db.php`
2. **Update the database credentials:**
```php
<?php
$host = 'localhost';  // Usually localhost for Hostinger
$dbname = 'u123456_parks_db';  // Your actual database name
$username = 'u123456_dbuser';  // Your database username
$password = 'your_db_password';  // Your database password
```

### Step 5: Upload Files
**Option A: File Manager (Recommended)**
1. Login to hPanel
2. Go to File Manager
3. Navigate to `public_html` (or your domain folder)
4. Upload all files from the extracted ZIP
5. Extract if needed

**Option B: FTP**
1. Use FTP client (FileZilla, etc.)
2. Connect using your FTP credentials
3. Upload all files to the web root directory

### Step 6: Set Permissions
Make sure these directories are writable (755 or 777):
- `admin/` (and all subdirectories)
- `uploads/` (if exists)
- `cache/` (if exists)

### Step 7: Update Paths (if needed)
If your site is in a subfolder, update these files:
- `includes/config.php` (if exists)
- Admin navigation links in `admin/includes/header.php`

### Step 8: Test the Installation
1. **Visit your website:** `https://yourdomain.com`
2. **Test admin login:** `https://yourdomain.com/admin/`
   - Username: `admin`
   - Password: `password` (change this immediately!)
3. **Test scraping portal:** `https://yourdomain.com/admin/scraping/`

## 🔧 Post-Deployment Checklist

### Security Updates
1. **Change admin password immediately**
2. **Update database credentials** if using default ones
3. **Remove or secure test files:**
   - `debug_db.php`
   - `debug_scraping.php` 
   - `test_scraping.php`

### Performance Optimization
1. **Enable gzip compression** in .htaccess
2. **Set up caching** if available
3. **Optimize images** if any

### Functionality Tests
- [ ] Homepage loads correctly
- [ ] Parks listing works
- [ ] Admin login works
- [ ] Admin dashboard shows statistics
- [ ] Admin CRUD operations work (parks, amenities, categories)
- [ ] Scraping portal loads
- [ ] Test connection works
- [ ] Mock scraping works

## 🛠️ Troubleshooting

### Common Issues

**Database Connection Errors:**
- Check database credentials in `includes/db.php`
- Verify database name and user permissions
- Check if database exists

**File Permission Errors:**
- Set directory permissions to 755
- Set file permissions to 644
- Check .htaccess file

**Scraping Portal Errors:**
- Run the SQL script: `docs/add_scraping_to_existing_db.sql`
- Check if `scraping_logs` table exists
- Verify website data in `websites` table

**Admin Access Issues:**
- Check admin_users table has data
- Verify session configuration
- Check file paths in navigation

### Contact Support
If you encounter issues:
1. Check Hostinger documentation
2. Contact Hostinger support
3. Check the GitHub repository for updates

## 📝 Important Files

### Configuration Files
- `includes/db.php` - Database configuration
- `admin/includes/header.php` - Admin navigation
- `.htaccess` - URL rewriting (if exists)

### Database Files  
- `docs/parks_db (1).sql` - Main database structure
- `docs/add_scraping_to_existing_db.sql` - Scraping functionality

### Key Features
- Public park directory
- Admin dashboard with statistics
- CRUD operations for parks, amenities, categories
- Scraping portal for importing park data
- Mobile-responsive design

## 🎯 Next Steps After Deployment

1. **Customize the design** to match your brand
2. **Add real park data** through the admin panel
3. **Configure scraping** for real websites
4. **Set up automated backups**
5. **Monitor performance** and optimize as needed

**Your AmyoParks site should now be live and fully functional!**
