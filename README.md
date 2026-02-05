# 🌟 AngkorCam Tourism - Professional Edition

<div align="center">

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**Modern, Clean, and Fully Working Tourism Website**

[Features](#features) • [Installation](#installation) • [Documentation](#documentation) • [Screenshots](#screenshots)

</div>

---

## ✨ Features

### 🎨 Beautiful Modern UI
- **Gradient backgrounds** everywhere
- **Glass morphism effects** on cards
- **Smooth animations** and transitions
- **Fully responsive** design
- **Professional typography** with Inter font

### 🌍 Public Features
- ✅ Stunning homepage with hero section
- ✅ Tour listing with modern cards
- ✅ Detailed tour pages with highlights
- ✅ User registration & authentication
- ✅ Seamless booking system
- ✅ User dashboard
- ✅ Booking history & management

### 👨‍💼 Admin Features
- ✅ Comprehensive admin dashboard
- ✅ Tour management (Create/Edit/Delete)
- ✅ Booking management
- ✅ Status updates
- ✅ Revenue tracking
- ✅ Customer management

### 🔒 Security Features
- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS protection
- ✅ CSRF protection ready
- ✅ Secure session management

---

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server

### Installation (5 Minutes)

1. **Import Database**
   ```sql
   CREATE DATABASE angkorcam_pro;
   ```
   Import `database.sql` via phpMyAdmin

2. **Configure**
   Edit `config/config.php` if needed

3. **Deploy**
   Place in your web server directory

4. **Access**
   ```
   http://localhost/angkorcam-pro/public/
   ```

5. **Login**
   - Admin: `admin@angkorcam.com` / `admin123`
   - Customer: `john@example.com` / `admin123`

**See [INSTALLATION.md](INSTALLATION.md) for detailed instructions.**

---

## 📂 Project Structure

```
angkorcam-pro/
├── app/
│   └── Models/           # Business logic
│       ├── User.php
│       ├── Tour.php
│       └── Booking.php
├── config/
│   └── config.php        # Configuration & helpers
├── public/
│   ├── index.php         # Front controller
│   └── css/style.css     # Modern UI
├── views/
│   ├── layouts/          # Layout templates
│   └── pages/            # Page views
│       ├── public/       # Public pages (9 files)
│       └── admin/        # Admin pages (5 files)
└── storage/logs/         # Application logs
```

---

## 💻 Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 7.4+ | Backend logic |
| MySQL | 5.7+ | Database |
| Bootstrap | 5.3 | UI framework |
| Bootstrap Icons | 1.11 | Icons |
| Google Fonts | - | Typography (Inter) |

---

## 🎯 Use Cases

Perfect for:
- 🏢 **Tourism businesses** - Ready to deploy
- 📚 **Learning projects** - Clean MVC pattern
- 💼 **Portfolio** - Professional showcase
- 🎓 **Education** - Teaching PHP basics
- 🚀 **Startups** - Quick MVP launch

---

## 🎨 Customization

### Change Brand Colors
```css
/* In public/css/style.css */
:root {
    --primary: #6366f1;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Add New Tour Category
```php
// In database, or via admin panel
'category' => 'Historical' // Cultural, Adventure, Nature, Beach, Historical
```

### Modify Email Templates
```php
// In app/Models/Booking.php
// Add your email sending logic after booking creation
```

---

## 📱 Responsive Design

| Device | Status |
|--------|--------|
| 📱 Mobile | ✅ Optimized |
| 📱 Tablet | ✅ Optimized |
| 💻 Desktop | ✅ Optimized |
| 🖥️ Large screens | ✅ Optimized |

---

## 🐛 Troubleshooting

**Common Issues:**

| Issue | Solution |
|-------|----------|
| Blank page | Enable `display_errors` in php.ini |
| Database connection failed | Check credentials in `config/config.php` |
| 404 errors | Verify `.htaccess` file exists |
| Styles not loading | Check file paths are correct |

---

## 📊 Database Schema

**3 Main Tables:**
- `users` - User authentication & management
- `tours` - Tour information & details
- `bookings` - Booking records & status

**Sample Data Included:**
- 6 tours (various locations)
- 2 users (admin + customer)
- 2 sample bookings

---

## 🌟 Highlights

### What Makes This Special

1. **Zero Dependencies** - No Composer, no npm, just PHP
2. **Clean Code** - MVC pattern, well-documented
3. **Modern UI** - Not your typical PHP project
4. **Production Ready** - Security best practices
5. **Easy to Customize** - Well-structured code

### Code Quality

- ✅ Consistent naming conventions
- ✅ Comprehensive comments
- ✅ Error handling
- ✅ Input validation
- ✅ SQL injection prevention
- ✅ XSS protection

---

## 📸 Screenshots

### Homepage
Beautiful gradient hero with featured tours

### Tour Listing
Modern card design with hover effects

### Admin Dashboard
Professional admin interface with statistics

---

## 🎓 Learning Resources

This project demonstrates:
- MVC architecture
- PDO database interaction
- Session management
- Form validation
- User authentication
- CRUD operations
- Responsive design

---

## 📝 File Inventory

| Category | Count |
|----------|-------|
| Total files | 26 |
| PHP files | 21 |
| Model classes | 3 |
| Public pages | 9 |
| Admin pages | 5 |
| CSS files | 1 |

---

## 🤝 Contributing

Feel free to:
- Report bugs
- Suggest features
- Submit pull requests
- Share your improvements

---

## 📄 License

MIT License - Free for personal and commercial use

---

## 🎉 Get Started!

```bash
# 1. Download and extract
# 2. Import database.sql
# 3. Configure config/config.php
# 4. Access http://localhost/angkorcam-pro/public/
# 5. Login and explore!
```

---

<div align="center">

**Built with ❤️ for Tourism Businesses**

⭐ **Star this project if you find it useful!** ⭐

</div>
# AngkorCam_Tourism
# AngkorCam_Tourism
