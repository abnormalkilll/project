<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $attachment = $_FILES['attachment'];

    // Your Gmail address
    $to = "your-email@gmail.com";
    $subject = "Contact Form Submission from $name";

    // Create Email Body
    $emailBody = "Name: $name\n";
    $emailBody .= "Email: $email\n";
    $emailBody .= "Message:\n$message\n";

    // Create Email Headers
    $headers = "From: $email\r\n";

    // Handle Attachment
    if (!empty($attachment['name'])) {
        $fileTmpPath = $attachment['tmp_name'];
        $fileName = $attachment['name'];
        $fileType = $attachment['type'];

        // Read the file content into a variable
        $fileData = file_get_contents($fileTmpPath);
        $fileData = chunk_split(base64_encode($fileData));

        // Unique boundary
        $boundary = md5(time());

        // Update headers for file attachment
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        // Email Body with Attachment
        $emailBody = "--$boundary\r\n";
        $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $emailBody .= "Content-Transfer-Encoding: 7bit\r\n";
        $emailBody .= "\r\n$emailBody\r\n";
        $emailBody .= "--$boundary\r\n";
        $emailBody .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
        $emailBody .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
        $emailBody .= "Content-Transfer-Encoding: base64\r\n";
        $emailBody .= "\r\n$fileData\r\n";
        $emailBody .= "--$boundary--";
    } else {
        // Simple Email without attachment
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    }

    // Send the Email
    if (mail($to, $subject, $emailBody, $headers)) {
        echo "<h2>Message Sent Successfully!</h2>";
        echo "<a href='contact.html'>Go Back</a>";
    } else {
        echo "<h2>Message Sending Failed!</h2>";
        echo "<a href='contact.html'>Try Again</a>";
    }
} else {
    echo "<h2>Invalid Request!</h2>";
}
?>
