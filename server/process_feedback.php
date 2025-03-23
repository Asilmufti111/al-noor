
// use Dotenv\Dotenv;

// // Load .env variables
// $dotenv = Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// $databaseUrl = $_ENV['DATABASE_URL'] ?? '';

// if (!$databaseUrl) {
//     die("Database configuration missing.");
// }
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST request received!";
} else {
    echo "Try sending a POST request.";
}

$databaseUrl = "DATABASE_URL=postgresql://postgres:IslamicWebsite2025@db.kulertixdkuybhheownb.supabase.co:5432/postgres";

// Parse DATABASE_URL
preg_match('/^postgresql:\/\/([^:]+):([^@]+)@([^:]+):(\d+)\/(.+)$/', $databaseUrl, $matches);

if (count($matches) !== 6) {
    die("Invalid database URL format.");
}

$db_user = $matches[1];
$db_password = $matches[2];
$db_host = $matches[3];
$db_port = $matches[4];
$db_name = $matches[5];

try {
    // Establish database connection
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;";
    $pdo = new PDO($dsn, $db_user, $db_password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $rating = $_POST['rating'] ?? '';
        $services = isset($_POST['services']) ? implode(", ", $_POST['services']) : '';
        $preferences = $_POST['preferences'] ?? '';
        $message = trim($_POST['message']);

        // Validate input
        if (empty($name) || empty($email) || empty($message)) {
            die("All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format.");
        }

        // Prepare and execute SQL statement
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, rating, services, preferences, message) VALUES (:name, :email, :rating, :services, :preferences, :message)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':rating' => $rating,
            ':services' => $services,
            ':preferences' => $preferences,
            ':message' => $message
        ]);

        echo "Feedback submitted successfully!";
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
