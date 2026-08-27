# LiteView PHP

<div align="center">
  <img src="public/assets/logo.png" alt="LiteView Logo" width="250">
</div>

A lightweight, custom-built PHP web application for dynamically viewing SQLite databases using a simple MVC (Model-View-Controller) architecture.

## Features
- **Dynamic Routing:** Routes all requests through a single `public/index.php` entry point.
- **Dynamic Table Viewer:** A single, reusable controller and view that can render any table in the database dynamically without writing new HTML/SQL for every table.
- **Advanced Data Filtering:** Instant frontend filtering supporting `=, !=, >, <, >=, <=, LIKE, IN, BETWEEN, IS NULL, IS NOT NULL` without needing to refresh the page.
- **Dark Mode Support:** Built-in theme toggling (Light/Night mode) that saves your preference.
- **Row Selection & Copy:** Select individual rows using checkboxes and easily copy the formatted data to your clipboard.
- **Data Inspection Modal:** Double-click on any cell to view and copy the full contents (perfect for long JSON strings or text blocks).
- **Home Dashboard:** Automatically reads the database schema and generates a menu of all available tables.
- **Security:** Whitelists allowed tables to prevent arbitrary database access.

## Project Structure
```text
liteview-php/
├── public/
│   └── index.php                 # The Router (Entry point for the application)
├── src/
│   ├── Database.php              # Handles the PDO SQLite connection
│   ├── Controllers/
│   │   ├── HomeController.php    # Fetches a list of all tables for the homepage
│   │   └── TableViewerController.php # Dynamically fetches data for any allowed table
│   └── view/
│       ├── home.php              # The HTML template for the homepage
│       └── generic_table.php     # The dynamic HTML template that builds tables from array keys
└── database/
    ├── setup.sql                 # The database schema and dummy data script
    └── example.sqlite            # The generated SQLite database file
```

## How to Run

### Using a Local Server (XAMPP / MAMP)
1. Place the `liteview-php` folder in your `htdocs` (XAMPP) or `htdocs` (MAMP) directory.
2. Open your browser and navigate to:
   `http://localhost/liteview-php/public/index.php`

### Using PHP's Built-in Server
1. Open your terminal and navigate to the project root directory.
2. Start the built-in server targeting the `public` folder:
   ```bash
   php -S localhost:8000 -t public
   ```
3. Open your browser and navigate to `http://localhost:8000`

## Architecture Concepts Learned
This project demonstrates the transition from spaghetti PHP (mixing SQL, HTML, and routing in one file) to an organized MVC approach:
- **Router:** Parses the URL (`PATH_INFO`) to decide which Controller to load.
- **Controllers:** Handle the business logic (querying the database) independently of the HTML.
- **Views:** Strictly handle presentation (HTML/CSS) by looping over variables provided by the Controllers.
