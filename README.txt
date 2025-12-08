# Wellness Booking System

---

## 📌 Features

### ✔ User Features
- Account registration & login  
- Browse available wellness services  
- Book appointments  
- View booking history  
- Cancel bookings  
- Edit profile  

### ✔ Admin Features
- Manage services (add/edit/delete)  
- Manage bookings  
- Manage users  
- Dashboard overview  
- Approve/decline appointments  

---

## 📦 Requirements

- **XAMPP 8.0+** (Apache + MySQL + PHP)
- Web Browser (Chrome recommended)
- PHP extensions:
  - PDO
  - mysqli

---

## 🔧 Installation & Setup

### 1. Download or Clone the Project
Copy the project folder into: C:\xampp\htdocs\cit17final
### 2. Configure the Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create a new database:jamespogi
3. Import the SQL file (if provided):
- Click the **jamespogi** database
- Go to **Import**
- Select the `.sql` file
- Click **Go**

### 3. Update the Configuration File
Open:cit17final/config.php
Set:

```php
define('DB_NAME','jamespogi');
define('BASE_URL','http://localhost/cit17final');

4. Start XAMPP Services

Open XAMPP Control Panel → Start:

Apache

MySQL

5. Run the System

Open in your browser:http://localhost/cit17final/

#User Manual
## 1. Accessing the System

Open your browser and visit:http://localhost/cit17final/
## 2. Creating an Account

1. Click **Register**
2. Fill in your details:
   - Full name
   - Email
   - Password
3. Click **Create Account**
4. You will be redirected to the login page.

---

## 3. Logging In

1. Enter your email and password
2. Click **Login**
3. You will enter the user dashboard

---

## 4. Browsing Services

1. Navigate to **Services**
2. View available wellness services:
   - Massage
   - Facial treatment
   - Spa sessions (Examples)
3. Click **Book Now** to proceed.

---

## 5. Booking an Appointment

1. Choose a service  
2. Select a date and time  
3. Confirm the booking  
4. The booking will appear in your **My Appointments** page

---

## 6. Viewing Appointment History

1. Go to **My Appointments**
2. View:
   - Pending appointments
   - Approved appointments
   - Completed appointments
   - Cancelled appointments

---

## 7. Cancelling a Booking

1. Open **My Appointments**
2. Click **Cancel** on the booking you want to remove
3. Confirm cancellation

---

## 8. Editing Your Profile

1. Go to **Profile**
2. Update personal info such as:
   - Name
   - Email
   - Contact number
3. Click **Save Changes**

---

## 9. Logging Out

1. Click **Logout** (top-right)
2. You will be redirected to the homepage

-----------------------------------------------------------------------------------------
#Admin Guide
## 1. Logging in as Admin

Open:http://localhost/cit17final/admin/

Enter the admin credentials.

---

## 2. Admin Dashboard

The dashboard shows:
- Total Users
- Total Appointments
- Pending Bookings
- Services count

---

## 3. Managing Services

### Add Service
1. Go to **Services**
2. Click **Add New**
3. Fill in service name, description, and price
4. Click **Save**

### Edit Service
1. Open **Services**
2. Click **Edit** on selected service
3. Update information
4. Save changes

### Delete Service
1. Click **Delete**
2. Confirm action

---

## 4. Managing Appointments

Go to **Appointments** to:

- Approve bookings  
- Decline bookings  
- Mark as completed  
- View booking details  

### Approve a Booking
Click **Approve** on pending appointments.

### Decline a Booking
Click **Decline** and optionally add a reason.

---

## 5. Managing Users

1. Navigate to **Users**
2. You can:
   - View user profiles
   - Deactivate abusive accounts (if available)
   - Reset passwords (manual or system-based)


