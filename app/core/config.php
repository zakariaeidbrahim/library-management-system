<?php
// /app/core/config.php

// Application Root Path (Used for requiring files from the root)
define('APP_ROOT', dirname(dirname(dirname(__FILE__)))); // Go up three levels to the root directory

// URL Root (Used for links and redirects)
// IMPORTANT: Change 'library-management-system' to your project's folder name if different.
// The URL should point to the /public folder if you are using the .htaccess rewrite.
define('URL_ROOT', 'http://localhost/library-management-system'); 

// Database Parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');     // <-- CHANGE THIS TO YOUR DATABASE USERNAME
define('DB_PASS', '');         // <-- CHANGE THIS TO YOUR DATABASE PASSWORD
define('DB_NAME', 'library_db'); // The database name we created earlier