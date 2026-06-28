# Tourism Web Application

A full-stack, single-page web portal designed to promote tourism in Jaffna, Northern Sri Lanka. This application allows users to explore beautiful local destinations, create accounts, log in securely, choose tour packages, handle mock payments, and instantly generate printable receipt invoices.

## Features
* **Interactive SPA Architecture:** Smooth navigation between Home, About, and Attraction locations without reloading the page.
* **Secure Authentication:** Full registration and sign-in engine utilizing PHP PDO matching against hashed passwords (`PASSWORD_BCRYPT`).
* **Interactive Booking Engine:** Dynamic calculating engine configured to process travel dates, tourist headcounts, and variable location package prices.
* **Mock Payment Portal:** Simulates credit card validation logic layout.
* **Invoice Receipt Generator:** Instantly builds a unique booking transaction reference ID upon successful checkout.

---

## Tech Stack
* **Frontend:** HTML5, CSS3, Vanilla JavaScript (ES6)
* **Backend:** PHP 8.x (REST API Architecture)
* **Database:** MySQL (Relational Schema storage)

---

## Installation & Setup Instructions

Follow these steps to run the project locally on your machine using **XAMPP**:

### 1. Project Cloning / Placement
Move or download the source code files and place them into your local server root directory:
```bash
# Path for XAMPP users
C:/xampp/htdocs/Tourism/
