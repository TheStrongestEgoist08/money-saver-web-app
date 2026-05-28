# PesoFlow

## Author
**Bigal, Carl Michael C.**

## Program
**BSIT 4-1**

---

# Project Description
PesoFlow is a web-based financial management platform designed to help users monitor, organize, and manage their personal finances efficiently. The system provides an intuitive interface where users can record expenses, manage wallets, set financial goals, and generate detailed financial reports. With built-in security features and a user-friendly dashboard, PesoFlow aims to make budgeting and expense tracking simple, secure, and accessible for everyday users.

The system also includes a **premium subscription feature powered by PayMongo**, allowing users to securely upgrade their accounts using **GCash, PayMaya, and credit/debit cards**. Premium users gain access to an **AI Financial Assistant** and a completely **ad-free experience**, improving usability and productivity.

---

# Features

## User Registration and Authentication
Secure account system that allows users to register, log in, and safely access their financial data.

## Personalized Dashboard
Provides an overview of financial activities including expenses, wallet balances, and spending summaries.

## Expense Management
Allows users to add, edit, categorize, and track daily expenses for better financial monitoring.

## Wallet Management
Users can manage multiple wallets and track balances in real time.

## Goals Management
Enables users to set financial goals and track progress toward savings targets.

## Report Generation
Generates detailed financial reports to analyze spending patterns and financial performance.

## Premium Subscription (PayMongo Integration)
Secure payment gateway integration using PayMongo supporting:
- GCash  
- PayMaya  
- Credit / Debit Card  

Premium plans unlock additional system features.

## AI Financial Assistant
An intelligent assistant that provides financial insights, suggestions, and personalized guidance.

## Ad-Free Experience
Premium users enjoy a clean, distraction-free interface with no advertisements.

## Security Features
Implements authentication, authorization, and data protection to secure user accounts and financial records.

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
Extract the project ZIP file.

## Step 2
Open the project folder in your preferred IDE.

## Step 3
Configure the `.env` file and set your database credentials.

## Step 4
Run the following command:

```bash
php artisan migrate

npm install

php artisan storage:link

composer update

php artisan serve
npm run dev
php artisan schedule:work
