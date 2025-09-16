<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        /* Dark mode styling */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        .dark-mode .table {
            color: #fff;
        }
        .dark-mode .btn-primary {
            background-color: #0d6efd;
        }
        .dark-mode .btn-danger {
            background-color: #dc3545;
        }
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <!-- Theme Toggle Button -->
        <button class="btn btn-secondary theme-toggle" id="themeToggle">🌙 Dark Mode</button>

        <h2>List Of Clients</h2>
        <a class="btn btn-primary mb-3" href="/myshop/create.php" role="button">New Client</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username   = "root";
                $password   = "";
                $database   = "myshop"; 

                $connection = new mysqli($servername, $username, $password, $database);

                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                $sql = "SELECT * FROM clients"; 
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/myshop/edit.php?id={$row['id']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/myshop/delete.php?id={$row['id']}'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        const toggleBtn = document.getElementById("themeToggle");
        toggleBtn.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");
            if (document.body.classList.contains("dark-mode")) {
                toggleBtn.textContent = "☀️ Light Mode";
            } else {
                toggleBtn.textContent = "🌙 Dark Mode";
            }
        });
    </script>
</body>
</html>
