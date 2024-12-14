# Crime Record Management System

## Overview
This project is an enhanced version of a Crime Record Management System, originally developed as a bachelor's degree final year project. The system has been significantly improved with robust security features to ensure safe deployment on the internet.

## Key Features
- Secure user authentication with bcrypt password hashing
- Role-based access control for admin, police staff, and public users
- CSRF protection to prevent cross-site request forgery attacks
- Comprehensive input sanitization against injection attacks and XSS
- Enhanced session management for secure user sessions
- Google reCAPTCHA integration to prevent automated attacks

## Technologies Used
- Backend: PHP
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript

## Security Implementations
- Password hashing using PHP's password_hash() function with bcrypt
- CSRF token generation and validation
- Input sanitization using htmlspecialchars()
- Secure cookie management with HTTPOnly and Secure flags
- Integration of Google reCAPTCHA v2

## Installation
1. Clone the repository
2. Set up a PHP environment (7.4+ recommended) with MySQL
3. Import the provided SQL file to set up the database
4. Configure the database connection in config.php
5. Set up Google reCAPTCHA and update the site key in the relevant files

## Usage
- Admin Module: Manage police stations, staff, crime categories, and view statistics
- Police Module: Handle criminal records, FIRs, charge sheets, and generate reports
- User Module: File FIRs online and track their status

## Testing
The system has undergone rigorous testing, including:
- Functional testing of security features
- Static Application Security Testing (SAST)
- Manual code review

## Future Improvements
- Implement password strength checker
- Add progressive delays for login attempts
- Implement device fingerprinting for additional session verification
