<?php
class UploadController {
    public function upload() {
        // Check if file was uploaded
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(["message" => "No file uploaded or upload error."]);
            return;
        }

        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        // Validate File Type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($fileType, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed."]);
            return;
        }

        // Validate File Size (e.g., max 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(["message" => "File size too large. Max 5MB."]);
            return;
        }

        // Generate unique filename/path
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('img_', true) . '.' . $extension;
        $uploadDir = __DIR__ . '/../public/uploads/';
        
        // Ensure upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $destination)) {
            // Return the public URL
            // Assuming the server is running on localhost:8000 and served from public/
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $domainName = $_SERVER['HTTP_HOST'];
            
            // Adjust this based on how the server is actually serving files
            // For php -S localhost:8000, the root is usually where the command is run. 
            // If running from backend root: localhost:8000/public/uploads/...
            // If running from backend/public: localhost:8000/uploads/...
            
            // Since we are running php -S localhost:8000 in d:\workshop\tour\backend, DO WE?
            // User info says: php -S localhost:8000 (in d:\workshop\tour\backend)
            // So http://localhost:8000/public/uploads/filename.jpg
            
            $publicUrl = $protocol . $domainName . '/public/uploads/' . $newFileName;

            http_response_code(200);
            echo json_encode([
                "message" => "File uploaded successfully.",
                "url" => $publicUrl
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Failed to move uploaded file."]);
        }
    }
}
?>
