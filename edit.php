<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "myshop";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$email = "";
$phone = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: /myshop/index.php");
        exit;
    }

    $id = $_GET["id"];
    $sql = "SELECT * FROM clients WHERE id=$id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /myshop/index.php");
        exit;
    }

    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
    $address = $row["address"];
} 
else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    do {
        if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($address)) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql = "UPDATE clients 
                SET name = '$name', email = '$email', phone = '$phone', address = '$address' 
                WHERE id = $id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Client updated correctly";

        header("location: /myshop/index.php");
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
</head>
<body>
  <!-- Navbar with Theme Toggle -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
    <a class="navbar-brand" href="/myshop/index.php">My Shop</a>
    <div class="ms-auto">
      <button id="themeToggle" class="btn btn-outline-secondary">🌙 Dark Mode</button>
    </div>
  </nav>

  <div class="container my-5">
    <h2>Edit Client</h2>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?php echo $errorMessage; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="id" value="<?php echo $id; ?>">

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Name</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Email</label>
        <div class="col-sm-6">
          <input type="email" class="form-control" name="email" value="<?php echo $email; ?>">
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

  <!-- Theme Script + Styles -->
  <script>
    const body = document.body;
    const btn = document.getElementById("themeToggle");

    if (localStorage.getItem("theme") === "dark") {
      body.classList.add("dark-theme");
      btn.textContent = "☀️ Light Mode";
    }

    btn.addEventListener("click", () => {
      body.classList.toggle("dark-theme");
      if (body.classList.contains("dark-theme")) {
        localStorage.setItem("theme", "dark");
        btn.textContent = "☀️ Light Mode";
      } else {
        localStorage.setItem("theme", "light");
        btn.textContent = "🌙 Dark Mode";
      }
    });
  </script>

  <style>
    .dark-theme {
      background-color: #121212 !important;
      color: #e4e4e4 !important;
    }
    .dark-theme .navbar {
      background-color: #1f1f1f !important;
    }
    .dark-theme .form-control,
    .dark-theme .btn {
      background-color: #2a2a2a;
      color: #e4e4e4;
      border: 1px solid #555;
    }
    .dark-theme .alert {
      background-color: #333 !important;
      color: #fff !important;
    }
  </style>
</body>
</html>
