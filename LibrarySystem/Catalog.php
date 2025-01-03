<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Include database connection
include 'db_connect.php'; 



$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 4px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            background: var(--secondary-color);
            color: white !important;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            text-align: center;
        }

        .search-bar {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table thead {
            background: var(--primary-color);
            color: white;
        }

        .table th {
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        .book-quantity {
            background: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        footer {
            background: var(--primary-color);
            color: white;
            padding: 1rem 0;
            margin-top: auto;
        }

        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stats-card i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="Homepage.php">
                <i class="fas fa-book-reader me-2"></i>Library 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="Catalog.php">
                            <i class="fas fa-books me-1"></i>Catalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="UserAccount.php">
                            <i class="fas fa-user me-1"></i>Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="BookDetails.php">
                            <i class="fas fa-info-circle me-1"></i>Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="BorrowReservation.php">
                            <i class="fas fa-bookmark me-1"></i>Borrow
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container">
            <h1 class="display-4">Book Catalog</h1>
            <p class="lead">Discover our collection of books</p>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-books"></i>
                    <h3>Total Books</h3>
                    <p class="h4"><?php echo $result->num_rows; ?></p>
                </div>
            </div>
            <div class="col-md-4">
    <div class="stats-card">
        <i class="fas fa-users"></i>
        <h3>Active Users</h3>
        <p class="h4">
            <?php
            // Query to count active users (if you have a specific condition for active users, adjust the WHERE clause)
            $userSql = "SELECT COUNT(*) AS active_users FROM users WHERE role = 'user'";
            $userResult = $conn->query($userSql);

            if ($userResult && $userResult->num_rows > 0) {
                $userRow = $userResult->fetch_assoc();
                echo "+" . $userRow['active_users'];
            } else {
                echo "0"; // Default if no users are found
            }
            ?>
        </p>
    </div>
</div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-clock"></i>
                    <h3>Library Hours</h3>
                    <p class="h4">9 AM - 9 PM</p>
                </div>
            </div>
        </div>

        <div class="search-bar">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="Search for books by title, author, or ISBN...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['book_id']; ?></td>
                                <td>
                                    <strong><?php echo $row['title']; ?></strong>
                                </td>
                                <td><?php echo $row['author'] ?? 'N/A'; ?></td>
                                <td><?php echo $row['isbn'] ?? 'N/A'; ?></td>
                                <td>
                                    <span class="book-quantity"><?php echo $row['quantity']; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No books available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Library Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>