<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $to = "praveenkumar.kanneganti@gmail.com";
    
    $name = strip_tags($_POST['name']);
    $email = strip_tags($_POST['email']);
    $subject_input = isset($_POST['subject']) ? strip_tags($_POST['subject']) : 'General Inquiry';
    $budget = isset($_POST['budget']) ? strip_tags($_POST['budget']) : 'Not Specified';
    $message = strip_tags($_POST['message']);
    
    $subject = "New Contact Form Submission: " . $subject_input;
    
    $body = "
    <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
        <h2 style='color: #4770FF;'>New Inquiry Received</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Subject:</strong> {$subject_input}</p>
        <p><strong>Budget:</strong> {$budget}</p>
        <hr style='border: 0; border-top: 1px solid #eee;'>
        <p><strong>Message:</strong><br>{$message}</p>
    </div>
    ";
    
    // Set content-type header for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Praveen Portfolio <noreply@pranuuxui.com>" . "\r\n";
    $headers .= "Reply-To: {$email}" . "\r\n";
    
    if(mail($to, $subject, $body, $headers)){
        // If it's an AJAX request (from modern.js)
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' || isset($_GET['ajax'])){
            echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
        } else {
            // Standard redirect for non-JS users
            header("Location: ../thank-you.php?status=success");
        }
        exit();
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Mail delivery failed"]);
    }
} else {
    http_response_code(403);
    echo "Invalid request.";
}
?>
