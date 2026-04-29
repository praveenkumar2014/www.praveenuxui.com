<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $to = "praveenkumar.kanneganti@gmail.com";
    $subject = "New Contact Form Submission: " . strip_tags($_POST['subject']);
    
    $name = strip_tags($_POST['name']);
    $email = strip_tags($_POST['email']);
    $budget = strip_tags($_POST['budget']);
    $message = strip_tags($_POST['message']);
    
    $body = "
    <h2>New Inquiry Received</h2>
    <p><strong>Name:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Subject:</strong> {$_POST['subject']}</p>
    <p><strong>Budget:</strong> {$budget}</p>
    <p><strong>Message:</strong><br>{$message}</p>
    ";
    
    // Set content-type header for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // Additional headers
    $headers .= "From: {$name} <{$email}>" . "\r\n";
    $headers .= "Reply-To: {$email}" . "\r\n";
    
    if(mail($to, $subject, $body, $headers)){
        // Redirect to thank you page using relative path
        header("Location: /thank-you");
        exit();
    } else {
        echo "Something went wrong. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
