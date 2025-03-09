<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "All fields are required.";
        exit;
    }

    // Email settings (replace with your email)
    $to = "misritha24@example.com"; // Admin email or your email
    $headers = "From: " . $email;

    // Send email
    $mail_status = mail($to, $subject, $message, $headers);

    if ($mail_status) {
        echo "<script>alert('Your message has been sent successfully.'); window.location.href = 'contact-us.html';</script>";
    } else {
        echo "<script>alert('There was an error sending your message. Please try again later.'); window.location.href = 'contact-us.html';</script>";
    }
}
?>
