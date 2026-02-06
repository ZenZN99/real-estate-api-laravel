# 🏡 Real Estate API – Laravel Backend System

## 🚀 Project Overview
**Real Estate API** is a professional back-end application built with **Laravel** for managing real estate platforms.  
It provides a complete system for users, properties, images, profiles, and role-based access control.

The project is designed to be:
- Secure 🔐
- Scalable ⚙️
- Ready for real-world production
- Easy to integrate with Front-End applications (React / Next.js)

---

## 🧩 Features

### 👤 Authentication & Users
- User registration & login using JWT
- Get authenticated user (Me)
- Update user profile:
  - Avatar image (Local Storage)
  - Cover image (Local Storage)
  - Bio
- User roles:
  - CUSTOMER
  - AGENT
  - ADMIN
- Secure logout
- Token-based authentication

---

### 🏠 Properties Management
- Create a real estate property
- Upload multiple property images (Local Storage)
- Update property details
- Delete property
- Get all properties
- Get single property by ID

🔒 Authorization:
- Only the property owner or Admin can update or delete a property

🖼 Images are stored locally using Laravel Storage

---

### 🖼 Images Handling
- Upload images using Laravel Filesystem
- Store images in:
  - `storage/app/public`
- Auto-generate image paths
- Validate image types & sizes
- Support multiple images per property

---

### 🧑‍💼 Profile System
- Update avatar & cover images and bio
- Store user media locally
- Automatically replace old images on update
- Keep profile data clean and optimized

---

## 🛡 Security
- JWT Authentication
- Role-based authorization
- Request validation using Laravel Form Requests
- Protected routes with middleware
- Prevent unauthorized access
- Secure file uploads

---

## 🛠 Tech Stack

### Back-End
- Laravel
- PHP
- MySQL
- JWT Authentication
- Laravel Storage (Local)
- Eloquent ORM
- Middleware & Policies

---

## 📂 Project Structure
app/
├─ Http/
│ ├─ Controllers/
│ ├─ Middleware/
├─ Models/
routes/
├─ api.php
storage/
database/

yaml
Copy code

---

## 🔗 API Endpoints

### Auth
- POST `/api/auth/signup`
- POST `/api/auth/login`
- GET  `/api/me`

---

### Users
- PUT `/api/user/profile/update`
- GET `/api/user/{id}`
- GET `/api/users`

---

### Properties
- POST   `/api/properties`
- PUT    `/api/properties/{id}`
- DELETE `/api/properties/{id}`
- GET    `/api/properties`
- GET    `/api/properties/{id}`

---

## ⚙️ Installation & Running

### Prerequisites
- PHP 8+
- Composer
- MySQL
- Git

---

### Steps

1. Clone the repository
```bash
git clone https://github.com/ZenZN99/real-estate-api-laravel
cd real-estate-api-laravel
Install dependencies

bash
Copy code
composer install
Environment variables

bash
Copy code
cp .env.example .env
Generate app key

bash
Copy code
php artisan key:generate
Run migrations

bash
Copy code
php artisan migrate
Create storage link

bash
Copy code
php artisan storage:link
Run the server

bash
Copy code
php artisan serve
Server will run on:

arduino
Copy code
http://localhost:8000
🎯 Future Enhancements
Property categories

Favorites system

Advanced search & filters

Booking & contact system

Admin dashboard

Cloudinary integration (optional)

👨‍💻 Author
Zen Allaham – Full-Stack / Backend Developer
Laravel • Node.js • NestJS

📜 License
MIT License © 2026 Zen Allaham
