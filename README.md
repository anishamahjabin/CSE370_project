# CSE370_project
# 🅿️ Campus Parking Slot Finder

A web-based database-driven application that helps users find available parking slots on campus in real time.


## 📌 Project Overview

The **Campus Parking Slot Finder** is designed to solve the daily problem of finding available parking on a university campus. The system allows users to check real-time slot availability, reducing time spent searching for parking and improving overall campus traffic flow.

---

## ✨ Features

- 🔍 Real-time parking slot availability lookup
- 🗺️ Campus-wide parking zone overview
- 📊 Database-backed slot tracking
- 🖥️ User-friendly frontend interface
- 🔐 Secure backend architecture

---

## 🛠️ Tech Stack

| Layer      | Technology                  |
|------------|-----------------------------|
| Frontend   | HTML / CSS / JavaScript     |
| Backend    | (Node.js / PHP / Python, .) |
| Database   | MySQL  |
| Others     | (e.g., XAMPP, phpMyAdmin)   |


## 🗄️ Database Design

- **EER Diagram** – Entity-relationship model covering all campus parking entities
- **Relational Schema** – Normalized tables derived from the EER diagram
- **Key Entities:** Parking Zones, Slots, Users, Reservations, Vehicles

---

## 🚀 Getting Started

### Prerequisites

- A running database server (MySQL / PostgreSQL)
- A web server or local development environment (e.g., XAMPP, WAMP, or Node.js)

### Installation

1. **Clone the repository**
   - Open **GitHub Desktop**
   - Go to `File > Clone Repository`
   - Select the repo and choose a local path, then click **Clone**

2. **Set up the database**
   - Import the provided `.sql` file into your database server:
     ```bash
     mysql -u root -p your_database_name < database/schema.sql
     ```

3. **Configure the connection**
   - Update the database credentials in the config file:
     ```
     DB_HOST=localhost
     DB_USER=your_username
     DB_PASSWORD=your_password
     DB_NAME=campus_parking
     ```

4. **Run the application**
   - Start your local server and navigate to `http://localhost/campus-parking-slot-finder`

---

## 📁 Project Structure

```
campus-parking-slot-finder/
│
├── frontend/           # UI files (HTML, CSS, JS)
├── backend/            # Server-side logic
├── database/
│   ├── schema.sql      # Table definitions
│   └── eer_diagram/    # EER diagram files
├── docs/               # Project report and documentation
└── README.md
```

---
## 📄 License

This project was developed for academic purposes as part of the CSE370 Database Systems course.

---

## 🙏 Acknowledgements

Special thanks to our course instructor Aabrar Islam sir and Farzana Reefat Raha mam for their guidance throughout this project.
