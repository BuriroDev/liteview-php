<?php

// Check if a database is currently active in the session
if (isset($_SESSION['active_db']) && file_exists($_SESSION['active_db'])) {
    $dbPath = $_SESSION['active_db'];
} else {
    // If no db is uploaded, we will leave $dbPath unset so controllers can handle it.
    $dbPath = null;
}

if ($dbPath) {
    try {
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        $db = null;
    }
} else {
    $db = null;
}