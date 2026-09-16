<?php

class UploadController {
    
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sqlite_file'])) {
            $file = $_FILES['sqlite_file'];
            
            // Check for upload errors
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Generate a unique filename to avoid overwriting
                $filename = uniqid('db_') . '_' . basename($file['name']);
                $uploadPath = __DIR__ . '/../../database/uploads/' . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // Store the absolute path in session
                    $_SESSION['active_db'] = $uploadPath;
                }
            }
        }
        
        // Redirect back to home
        $basePath = htmlspecialchars($_SERVER['SCRIPT_NAME']);
        header("Location: $basePath");
        exit;
    }

    public function remove() {
        if (isset($_SESSION['active_db'])) {
            $dbPath = $_SESSION['active_db'];
            // Check if it's in the uploads directory for safety before deleting
            if (file_exists($dbPath) && strpos($dbPath, '/database/uploads/') !== false) {
                unlink($dbPath);
            }
            unset($_SESSION['active_db']);
        }
        
        $basePath = htmlspecialchars($_SERVER['SCRIPT_NAME']);
        header("Location: $basePath");
        exit;
    }
}
