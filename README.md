# MoneyTrackr

## Author
**Bigal, Carl Michael C.**  

## Program
**BSIT 4-1**

---

# Project Description
MoneyTrackr is a web-based financial management platform designed to help users monitor, organize, and manage their personal finances efficiently. The system provides an intuitive interface where users can record expenses, manage wallets, set financial goals, and generate detailed financial reports. With built-in security features and a user-friendly dashboard, MoneyTrackr aims to make budgeting and expense tracking simple, secure, and accessible for everyday users.

---

# Features

## User Registration and Authentication
Provides a secure registration and login system that allows users to create personal accounts and safely access their financial data.

## Personalized Dashboard
Displays an organized overview of the user’s financial information, including recent expenses, wallet balances, and spending summaries.

## Expense Management
Allows users to create, edit, categorize, and track daily expenses to help monitor spending habits and financial activities.

## Wallet Management
Enables users to manage multiple wallets or accounts, track balances, and monitor available funds in real time.

## Goals Management
Helps users set financial goals, monitor progress, and stay motivated in achieving savings or budgeting targets.

## Report Generation
Generates detailed financial reports and summaries that provide insights into expenses, budgeting patterns, and overall financial performance.

## Security Features
Implements authentication, access control, and data protection measures to ensure user information and financial records remain secure.

---

# Technologies Used

| Category | Technology |
|----------|-------------|
| Backend | PHP |
| Frontend | HTML, CSS, JavaScript, Blade |
| Database | MySQL |
| Framework | Laravel 13 |
| Tools | Visual Studio Code, Laragon |

---

# Installation Guide

## Step 1
Extract the ZIP file.

## Step 2
Open the extracted project folder in your preferred IDE.

## Step 3
Configure the `.env` file and set the database password based on your MySQL root account password.

## Step 4
Create the database and run migrations using the following command:

```bash
php artisan migrate
```

Alternatively, you may manually create the database using the provided SQL statements.

## Step 5
Run the Laravel development server:

```bash
php artisan serve
```

## Step 6
Open a new terminal and run the frontend development server:

```bash
npm run dev
```

After that, open the provided local URL:

```txt
http://127.0.0.1:8000
```

---
