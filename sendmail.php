<?php
$secretKey = "TWOJ_SECRET_KEY";
$to = "masterxrambo@gmail.com";
$subject = "Nowa wiadomość z formularza ALKOMANDO";

// Sprawdź, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Zbieranie danych
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Walidacja
    if (empty($name) || empty($email) || empty($message)) {
        exit("<h2 style='color:red;'>Wszystkie pola są wymagane.</h2>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit("<h2 style='color:red;'>Nieprawidłowy adres e-mail.</h2>");
    }

    // Weryfikacja reCAPTCHA
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
    $responseData = json_decode($response);

    if (!$responseData->success) {
        exit("<h2 style='color:red;'>Nieprawidłowa weryfikacja reCAPTCHA.</h2>");
    }

    // Treść wiadomości
    $email_body = "Imię: $name\nEmail: $email\n\nWiadomość:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $email_body, $headers)) {
        echo "<h2 style='color:green;'>Wiadomość wysłana. Dzięki za kontakt!</h2>";
    } else {
        echo "<h2 style='color:red;'>Błąd podczas wysyłania. Spróbuj ponownie.</h2>";
    }
}
?>
