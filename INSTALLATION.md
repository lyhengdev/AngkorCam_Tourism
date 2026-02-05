no# 🚀 AngkorCam Tourism - Installation Guide

## ⚡ Quick Start (5 Minutes)

### Step 1: Database Setup
1. Open **phpMyAdmin** or your MySQL client
2. Create a new database: `angkorcam_pro`
3. Import the file: **database.sql**

```sql
CREATE DATABASE angkorcam_pro;
```

### Step 2: Configuration
Edit `config/config.php` if your database credentials are different:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'angkorcam_pro');
```

### Step 3: Deploy
1. Extract the folder to your web server
   - **XAMPP**: `C:\xampp\htdocs\angkorcam-pro\`
   - **WAMP**: `C:\wamp\www\angkorcam-pro\`
   - **MAMP**: `/Applications/MAMP/htdocs/angkorcam-pro/`

2. Start Apache and MySQL

3. Open your browser:
   ```
   http://localhost/angkorcam-pro/public/
   ```

### Step 4: Login

**Admin Account:**
- Email: `admin@angkorcam.com`
- Password: `admin123`

**Customer Account:**
- Email: `john@example.com`
- Password: `admin123`

---

## 🎯 Features

### Public Features
✅ Beautiful homepage with gradient hero
✅ Tour listing with animations
✅ Detailed tour pages
✅ User registration & login
✅ Booking system
✅ User dashboard
✅ Booking history

### Admin Features
✅ Admin dashboard with statistics
✅ Tour management (Add/Edit)
✅ Booking management
✅ Status updates
✅ Revenue tracking

### Design Features
✅ Modern gradient UI
✅ Glass morphism effects
✅ Smooth animations
✅ Fully responsive
✅ Professional typography

---

## 📂 Project Structure

```
angkorcam-pro/
├── app/
│   └── Models/              # Database models
│       ├── User.php         # User management
│       ├── Tour.php         # Tour operations
│       └── Booking.php      # Booking system
├── config/
│   └── config.php           # Configuration & helpers
├── public/
│   ├── index.php            # Front controller
│   ├── .htaccess            # URL rewriting
│   └── css/
│       └── style.css        # Modern UI styles
├── views/
│   ├── layouts/
│   │   └── main.php         # Main layout
│   └── pages/
│       ├── public/          # Public pages
│       │   ├── home.php
│       │   ├── tours.php
│       │   ├── tour-detail.php
│       │   ├── login.php
│       │   ├── register.php
│       │   ├── booking.php
│       │   ├── process-booking.php
│       │   ├── dashboard.php
│       │   └── my-bookings.php
│       └── admin/           # Admin pages
│           ├── dashboard.php
│           ├── tours.php
│           ├── bookings.php
│           ├── add-tour.php
│           └── edit-tour.php
└── storage/
    └── logs/                # Error logs

```

---

## 🛠️ Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.3
- **Icons**: Bootstrap Icons
- **Fonts**: Google Fonts (Inter)

---

## 🎨 Customization

### Change Colors
Edit `public/css/style.css`:

```css
:root {
    --primary: #6366f1;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Add New Pages
1. Create file in `views/pages/public/` or `views/pages/admin/`
2. Add route in `public/index.php`

### Modify Database
1. Update `database.sql`
2. Update corresponding Model in `app/Models/`

---

## 🐛 Troubleshooting

### Issue: Blank Page
**Solution**: Check PHP error log, ensure PHP 7.4+ installed

### Issue: Database Connection Failed
**Solution**: Verify credentials in `config/config.php`

### Issue: 404 Errors
**Solution**: Ensure `.htaccess` is in `/public/` folder

### Issue: Styles Not Loading
**Solution**: Check `css/style.css` path, verify file exists

---

## 📱 Browser Support

✅ Chrome (latest)
✅ Firefox (latest)
✅ Safari (latest)
✅ Edge (latest)
✅ Mobile browsers

---

## 🔒 Security Features

✅ Password hashing (bcrypt)
✅ SQL injection prevention (PDO)
✅ XSS protection (htmlspecialchars)
✅ Session management
✅ Input sanitization

---

## 📞 Support

If you need help:
1. Check this documentation
2. Review the code comments
3. Check PHP error logs

---

## 📝 License

Free to use for personal and commercial projects.

---

## 🎉 Enjoy!

You now have a professional tourism website! 

Start customizing it to make it your own!
