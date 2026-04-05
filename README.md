# Incredible India Tours

A full-stack tour and travel booking website built with Vue.js (Frontend) and PHP (Backend).

## Tech Stack
- **Frontend**: Vue 3, Vite, Tailwind CSS, Pinia, Vue Router, Axios
- **Backend**: PHP 8+ (Vanilla MVC), PDO, MySQL
- **Database**: MySQL

## Prerequisites
- Node.js & npm
- PHP 8.0+
- MySQL Server

## Setup Instructions

### 1. Database Setup
1. Create a new MySQL database named `incredible_india_tours`.
2. Import the schema: `database/schema.sql`.
3. Import sample data: `database/seed.sql`.

### 2. Backend Setup
1. Configure database credentials in `backend/config/Database.php` if you are not using default root/empty password.
2. Start the PHP built-in server (or use Apache/Nginx):
   ```bash
   cd backend/public
   php -S localhost:8000
   ```
   *Note: The API will be available at `http://localhost:8000/api`.*
   *Note: If you change the port, update `frontend/src/services/api.js`.*

### 3. Frontend Setup
1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```
4. Open the URL shown (usually `http://localhost:5173`) in your browser.

## Features
- Browse Destinations and Tours
- Search Tours
- View Tour Details (Itinerary, Price)
- User Registration & Login
- Book Tours (Mock payment)
- User Dashboard (View Bookings - logic implemented, API pending)
- Admin Dashboard (Analytics view)

## Folder Structure
- `backend/`: PHP API code
- `frontend/`: Vue.js Application
- `database/`: SQL scripts
