<div align="center">

# ⭐ Starhotel

### Full-stack hotel booking platform

A hotel booking web application with room discovery, reservations, user accounts and a dedicated backend API.

Built as a full-stack project using **SvelteKit, PHP, MySQL and Docker**.

</div>

---

## 🏨 About Starhotel

**Starhotel** is a full-stack hotel booking platform created as part of a Creative Learning Experience within the **Creative Media & Game Technologies** programme.

The project explores the development of a complete web application rather than only a static hotel website.

Users can browse hotel rooms, interact with the reservation system and manage parts of their experience through a personal account.

The application is split into a dedicated frontend, backend and database environment.

---

## ✨ Features

### 🛏️ Hotel & rooms

Users can:

- Browse available hotel rooms
- View room information
- Navigate through the hotel website
- Learn more about Starhotel
- Contact the hotel

### 📅 Reservations

The application includes functionality for handling hotel reservations.

The reservation system connects the frontend to the PHP backend and database.

### 👤 User accounts

Starhotel includes authentication and account-related functionality such as:

- User registration and authentication
- Logged-in and logged-out application states
- User profiles
- Protected account functionality

### 📬 Communication

The backend also contains functionality related to contact requests and reservation confirmation.

---

## 🧱 Architecture

Starhotel is split into three main services:

### Frontend

The user-facing application is built with **SvelteKit**.

### Backend

A **PHP** backend handles application logic, authentication, rooms, reservations and user-related functionality.

### Database

A **MySQL** database stores the application's persistent data.

The complete environment can be run using **Docker Compose**.

---

## 💻 Tech stack

### Frontend

![Svelte](https://img.shields.io/badge/Svelte-FF3E00?style=for-the-badge&logo=svelte&logoColor=white)
![SvelteKit](https://img.shields.io/badge/SvelteKit-FF3E00?style=for-the-badge&logo=svelte&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![SCSS](https://img.shields.io/badge/SCSS-CC6699?style=for-the-badge&logo=sass&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)

### Backend

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

### Infrastructure

![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Docker Compose](https://img.shields.io/badge/Docker_Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)

---

## 📁 Repository structure

<pre>
starhotel-booking-platform/
│
├── frontend/
│   ├── src/
│   │   ├── lib/
│   │   ├── routes/
│   │   └── scss/
│   ├── static/
│   └── Dockerfile
│
├── backend/
│   ├── public/
│   ├── src/
│   │   ├── config/
│   │   └── controllers/
│   └── Dockerfile
│
├── docker-compose.yml
└── LICENSE
</pre>

---

## 🐳 Running with Docker

The project contains a Docker Compose configuration for running the complete application.

The environment consists of:

- MySQL database
- PHP backend
- SvelteKit frontend

Clone the repository and start the containers:

    docker compose up --build

The default development services are configured as:

| Service | Address |
| --- | --- |
| Frontend | `http://localhost:5173` |
| Backend | `http://localhost:8080` |
| MySQL | `localhost:3306` |

To stop the environment:

    docker compose down

---

## 🏗️ Application structure

The frontend uses SvelteKit's routing system to separate public and authenticated parts of the application.

Application areas include:

- Homepage
- Rooms
- About
- Contact
- Authentication
- User profile
- Reservation functionality

The PHP backend contains dedicated controllers for areas such as:

- Authentication
- Rooms
- Reservations
- Users
- Contact
- Confirmation

---

## 🎨 Hotel concept

Starhotel was designed as a fictional luxury hotel experience.

The interface focuses on an elegant hospitality aesthetic with features including:

- Luxury rooms
- Hotel information
- Wellness and spa presentation
- Dining experiences
- Reservation calls-to-action
- User account functionality

The goal was to combine the presentation of a hotel website with the technical requirements of a full-stack booking application.

---

## 🎓 Project context

Starhotel was created as an educational team project within **Creative Media & Game Technologies**.

The project provided experience with:

- Full-stack development
- Frontend architecture
- Backend development
- Authentication
- Database integration
- REST-style application communication
- SvelteKit
- PHP
- MySQL
- Docker
- Git collaboration
- Team development

---

## 👥 Contributors

Starhotel was developed as a team project by:

- **Toon van Berkel**
- **Vince**
- **S.P.G. Wolters**
- **Casper van Gameren**

---

## 📦 Project status

This repository contains the completed educational Starhotel project.

It is preserved as part of the development history of the project and is not intended to operate as a real hotel booking service.

---

## 📄 License

Copyright © 2026 Starhotel project contributors. All rights reserved.

This project is publicly available for **educational, portfolio and reference purposes only**.

Copying, redistribution, modification, deployment or reuse is not permitted without prior written permission from the copyright holders.

See [`LICENSE`](LICENSE) for the full terms.

---

<div align="center">

# ⭐ Starhotel

**Full-stack hotel booking platform**

*Built with SvelteKit, PHP, MySQL and Docker.*

</div>
