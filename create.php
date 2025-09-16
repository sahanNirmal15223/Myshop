<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "myshop";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$name    = "";
$Email   = "";
$phone   = "";
$address = "";

$errorMessage   = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST["name"]);
    $Email   = trim($_POST["Email"]);
    $phone   = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    do {
        if (empty($name) || empty($Email) || empty($phone) || empty($address)) {
            $errorMessage = "All fields are required";
            break;
        }

        $check = $connection->prepare("SELECT id FROM clients WHERE email = ?");
        $check->bind_param("s", $Email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errorMessage = "This email is already registered!";
            break;
        }

        $stmt = $connection->prepare(
            "INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $name, $Email, $phone, $address);

        if (!$stmt->execute()) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $name    = "";
        $Email   = "";
        $phone   = "";
        $address = "";

        $successMessage = "Client added correctly";

        header("Location: /myshop/index.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        body.dark-mode {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
        }
        .dark-mode .form-control {
            background-color: #1e1e1e !important;
            color: #fff !important;
            border-color: #444 !important;
        }
        .dark-mode .btn-primary {
            background-color: #0d6efd !important;
        }
        .dark-mode .btn-outline-primary {
            border-color: #0d6efd !important;
            color: #0d6efd !important;
        }
        .dark-mode .alert {
            background-color: #333 !important;
            color: #fff !important;
        }
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <!-- Theme Toggle Button -->
    <button class="btn btn-secondary theme-toggle" id="themeToggle">🌙 Dark Mode</button>

    <div class="container my-5">
        <h2>New Client</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" name="Email" value="<?php echo $Email; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-6">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $successMessage; ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/myshop/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>

    <!-- ✅ JavaScript for theme toggle -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("themeToggle");

            // Load saved theme from localStorage
            if (localStorage.getItem("theme") === "dark") {
                document.body.classList.add("dark-mode");
                toggleBtn.textContent = "☀️ Light Mode";
            }

            toggleBtn.addEventListener("click", () => {
                document.body.classList.toggle("dark-mode");
                if (document.body.classList.contains("dark-mode")) {
                    toggleBtn.textContent = "☀️ Light Mode";
                    localStorage.setItem("theme", "dark");
                } else {
                    toggleBtn.textContent = "🌙 Dark Mode";
                    localStorage.setItem("theme", "light");
                }
            });
        });
    </script>
</body>
</html>
