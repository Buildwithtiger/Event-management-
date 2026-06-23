# Event-management-


```markdown
# 🎉 Event Management System – Full Stack Web Application

A complete event management/planning website with an **admin dashboard** to manage services, gallery, pricing plans, client reviews, and contact messages. Built with PHP, MySQL, HTML, CSS, and JavaScript.

## 📌 Features

### Frontend (Public Side)
- ✅ Responsive, modern UI with **Swiper** sliders
- ✅ Service listing with icons
- ✅ Image gallery with hover effects
- ✅ Pricing plans with feature lists
- ✅ Client testimonials (reviews)
- ✅ Contact form (stores messages in database)
- ✅ Color theme switcher (live demo)
- ✅ Smooth scrolling and mobile-friendly navigation

### Admin Panel (Backend)
- ✅ Secure admin login (password hashed with `password_hash()`)
- ✅ **CRUD** operations for:
  - Services (icon, title, description)
  - Gallery (image path, title)
  - Pricing plans (title, amount, features)
  - Reviews (name, role, image, review text)
- ✅ View all contact messages
- ✅ Simple logout functionality
- ✅ Default admin credentials: `admin` / `admin123`

### Optional Features (included in SQL)
- ✅ User registration/login (frontend not implemented, but tables are ready)
- ✅ Event creation and registration system (tables: `events`, `registrations`, `venues`)
- ✅ Can be extended for full event booking workflow

---

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **PHP** (7.4+) | Backend logic, authentication, CRUD |
| **MySQL** | Database storage |
| **HTML5 + CSS3** | Frontend structure and styling |
| **JavaScript** | Interactivity, theme toggle, sliders |
| **Swiper.js** | Touch‑friendly carousels |
| **Font Awesome** | Icons |
| **PDO** | Secure database access |

---

## 📁 Project Structure (Key Files)

```
/
├── index.php                # Frontend homepage (not included but expected)
├── admin.php                # Admin dashboard (login + CRUD)
├── config.php               # Database configuration & session start
├── logout.php               # Logout script
├── reset_password.php       # Utility to reset admin password (admin/admin123)
├── style.css                # Global styles (frontend)
├── script.js                # Frontend JavaScript (sliders, theme, menu)
└── sql/
    └── event_organizer.sql  # Full database schema with sample data
```

---

## 🚀 Installation Guide

### 1. Clone the repository
```bash
git clone https://github.com/yourusername/event-management.git
cd event-management
```

### 2. Database Setup
- Create a MySQL database (e.g., `event_organizer`).
- Import the SQL file provided in `/sql/event_organizer.sql`:
```bash
mysql -u root -p event_organizer < sql/event_organizer.sql
```
- This will create all tables and insert demo data.

### 3. Configuration
Open `config.php` and update database credentials if needed:
```php
$host = '127.0.0.1';
$port = 3306;
$dbname = 'event_organizer';
$username = 'root';
$password = 'root1234';
```

### 4. Run the Application
Place the project folder in your web server root (e.g., `htdocs` for XAMPP) and access:
- Frontend: `http://localhost/event-management/index.php`
- Admin Panel: `http://localhost/event-management/admin.php`

### 5. Login Credentials (Admin)
- **Username:** `admin`
- **Password:** `admin123`

To reset the admin password, run `reset_password.php` from your browser (then delete the file for security).

---

## 📸 Screenshots (Add your own)
> *Insert actual screenshots of your homepage and admin panel here.*

---

## 📝 Usage Instructions

### Frontend
- Browse services, gallery, pricing, and reviews.
- Use the contact form to send messages (stored in `messages` table).
- Click the color palette icon (bottom‑right) to change the theme color.

### Admin Panel
- Log in using the credentials above.
- From the dashboard, you can:
  - Add/delete services.
  - Add/delete gallery images.
  - Add/delete pricing plans.
  - Add/delete client reviews.
  - View all contact messages.
- Use the **Logout** button to end your session.

---

## 🔧 Customization Tips

- **Theme Colors**: Edit the CSS variables in `style.css` (`--main-color`).
- **Add More Fields**: Modify the respective tables and update `admin.php` forms.
- **Extend to Full Event Booking**: Use the `events`, `registrations`, and `users` tables already included – you just need to build the frontend booking forms.

---

## 🤝 Contributing

Contributions are welcome! Please fork the repository and create a pull request with your improvements.

1. Fork it
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License

This project is open‑source and available under the **MIT License**.

---

## 📧 Contact

For any queries, feel free to reach out:

- **Your Name** – [your.email@example.com](mailto:your.email@example.com)
- Project Link: [https://github.com/yourusername/event-management](https://github.com/yourusername/event-management)

---

⭐ **If you find this project useful, please give it a star!** ⭐
