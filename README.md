# Muhammad Faizan - 701765
# EZmarket.com (Web Development- 1)

This platform is an online advertising system that leverages the following technologies:
- Docker
- NGINX webserver
- PHP 8.1
- Javascript
- HTML
- CSS
- MariaDB
- PHPMyAdmin


## Website Features:

**User Accessibility:**

- Accessible both with and without logging in.
- Homepage displays all available Ads to all users.

**Shopping Cart:**

- Add items to the cart directly from the homepage.
- Navigate between the cart and homepage seamlessly.
- Options in the cart: Continue shopping, delete items, and proceed to payment.

**My Ads page:**

- Requires login to access.
- View posted ads and post new ones.
- Manage posted ads: Mark as done, delete, or edit for corrections.

**My Purchases page:**

- Accessible with login.
- Displays a list of purchased products.

**User Registration:**

- Register from the login screen.
- Provide first name, last name, email address, and password.
- After registration, login directly to start buying or selling.

## Logics: 
- The application's homepage lists available advertisements, arranged by most recent dates, for users who are logged in and those who are not.
- The website use Bcrypt for password hashing and verifying.
- The project contains the SQL database script to populate the database.
- The application is designed to make it easy and user-friendly.
- The application stores the user's ID in the database as buyerID if they are logged in while purchasing something to show it in My purchases.

## Website Bugs:
I have 2 issues in the website:

- The logout is not working, I tried a lof different logics but could not make it work because of less time. To try different user we have too clode the browser and open again to login with different user or use different browser.
- The Search feature is not working on the Homepage.

## Run the Application
```bash
docker-compose up
```

## Test Users
User Credentials:
#### - First User- Muhammad
- email:
```bash
faizan@inholland.nl
```
- password:
```bash
Faizan123
```
---------------------------------------
#### - Second User- Ayaz
- email:
```bash
ayaz@such.nl
```
- password:
```bash
Ayazv1456
```
---------------------------------------
#### - Third User - Dawood
- email:
```bash
dawood@inholland.nl
```
- password:
```bash
Dawood456
```
