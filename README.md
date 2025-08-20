# L.P.S.T Bookings System

A comprehensive hotel and halls booking management system built with PHP and MySQL.

## Features

### Authentication & Security
- Secure login system for Owner and Admin roles
- Password hashing with bcrypt
- CSRF protection on all forms
- Session-based authentication
- Emergency owner login bypass

### Admin Features
- Real-time room/hall status dashboard
- Booking management (create, extend, checkout)
- Advanced booking system for future dates
- Payment tracking with UPI integration
- Personal profile with daily activity statistics
- Password change functionality

### Owner Features
- Complete system overview and analytics
- Admin management (add, remove, reset passwords)
- Detailed reports with CSV export
- System settings configuration
- Username change without password verification
- Revenue tracking and statistics

### Booking Management
- 26 rooms + 2 halls (Small & Big Party Hall)
- Real-time status updates (VACANT, BOOKED, PENDING)
- Automatic status transitions after 24 hours
- Live counters showing elapsed time
- Advanced bookings for future dates
- Payment integration with UPI

## Installation

### Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Database Configuration
The system automatically detects database configuration from:
1. Environment variables: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
2. Server variables (for shared hosting)
3. Default values: localhost, lpst_bookings, root, (empty password)

### Setup Steps

1. **Upload Files**
   ```
   Upload all files to your web server directory
   ```

2. **Database Setup**
   ```
   Visit: yoursite.com/setup_database.php
   ```
   This will automatically:
   - Create the database if it doesn't exist
   - Create all required tables
   - Insert default users and resources
   - Configure initial settings

3. **Login**
   - Access the system at: `yoursite.com/login.php`
   - Default credentials are set during database setup

4. **Emergency Access**
   - Owner emergency login: `yoursite.com/emergency_login.php`
   - Only requires username (no password)

## System Structure

### Database Tables
- `users` - Authentication (Owner & Admin accounts)
- `resources` - Rooms and halls configuration
- `bookings` - All booking records with status tracking
- `payments` - Payment tracking and UPI integration
- `settings` - System configuration

### File Structure
```
/
├── assets/           # CSS, JS, and static files
├── admin/           # Admin-specific pages
├── owner/           # Owner dashboard and management
├── includes/        # Shared functions and utilities
├── config/          # Database configuration
├── api/            # API endpoints for real-time updates
├── cron/           # Background tasks
└── supabase/       # Database migrations
```

## Key Features Explained

### Real-time Updates
- Auto-refresh every 30 seconds
- Live counters for booking duration
- Automatic status transitions
- Visual indicators for urgent actions

### Status Management
- **VACANT**: Available for booking
- **BOOKED**: Currently occupied
- **PENDING**: Over 24 hours, requires action
- **ADVANCED_BOOKED**: Future booking confirmed

### Payment System
- UPI integration for instant payments
- Payment tracking and confirmation
- Revenue reports and analytics
- Unpaid booking indicators

### Security Features
- CSRF token protection
- Password hashing (bcrypt)
- Session management
- Role-based access control
- Emergency access for owners

## Configuration

### Environment Variables (Optional)
```
DB_HOST=localhost
DB_NAME=lpst_bookings
DB_USER=your_username
DB_PASS=your_password
```

### Hosting Compatibility
- Tested on shared hosting (Hostinger, cPanel)
- Works with any PHP/MySQL hosting provider
- No special server requirements
- Automatic database detection

## Updates & Changes

### Recent Improvements
1. **Fixed Login Issues**
   - Resolved admin and owner login problems
   - Improved session handling
   - Better error messages

2. **Admin Profile System**
   - Personal dashboard with daily statistics
   - Password change functionality
   - Activity tracking and history

3. **Emergency Access**
   - Owner bypass login for emergencies
   - Username-only authentication
   - Direct access to owner dashboard

4. **Enhanced Security**
   - Removed default credentials from login page
   - Improved password policies
   - Better session management

5. **Database Flexibility**
   - Auto-detection of hosting environment
   - Compatible with shared hosting
   - No hardcoded database credentials

## Support

For technical support or customization requests, please refer to the system documentation or contact the development team.

## License

This system is proprietary software developed for L.P.S.T Bookings management.