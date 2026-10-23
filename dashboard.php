<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['cpmsaid']==0)) {
  header('location:logout.php');
  exit();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Curfew Pass Management System | Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Nunito', sans-serif;
        }
        .dashboard-wrapper {
            padding: 40px 20px;
        }
        .card {
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            color: #fff;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        }
        /* Gradient Backgrounds */
        .card-success { background: linear-gradient(45deg, #00c851, #007e33); }
        .card-info    { background: linear-gradient(45deg, #33b5e5, #0099cc); }
        .card-warning { background: linear-gradient(45deg, #ffbb33, #ff8800); }
        .card-danger  { background: linear-gradient(45deg, #ff4444, #cc0000); }
        .card-default { background: linear-gradient(45deg, #6c757d, #343a40); }

        .label-title {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
            text-transform: uppercase;
            opacity: 0.9;
        }
        .card h4 {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
        }
        .icon-box i {
            font-size: 32px;
            color: rgba(255,255,255,0.9);
        }
        a.card-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }
        a.card-link:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 p-4 bg-light shadow-sm">
            <h5 class="mb-4">Navigation</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-category.php">Manage Category</a></li>
                <li class="nav-item"><a class="nav-link" href="manage-pass.php">Manage Pass</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Dashboard -->
        <div class="col-lg-10 mx-auto dashboard-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-primary">Dashboard</h3>
            </div>

            <?php
                // Category count
                $sql ="SELECT ID from tblcategory";
                $query = $dbh->prepare($sql);
                $query->execute();
                $totalcat = $query->rowCount();

                // Total Pass count
                $sql ="SELECT ID from tblpass";
                $query = $dbh->prepare($sql);
                $query->execute();
                $totalpass = $query->rowCount();

                // Today Passes
                $sql ="SELECT ID from tblpass where date(PasscreationDate)=CURDATE()";
                $query = $dbh->prepare($sql);
                $query->execute();
                $today_pass = $query->rowCount();

                // Yesterday Passes
                $sql ="SELECT ID from tblpass where date(PasscreationDate)=CURDATE()-1";
                $query = $dbh->prepare($sql);
                $query->execute();
                $yes_pass = $query->rowCount();

                // Last 7 Days Passes
                $sql ="SELECT ID from tblpass where date(PasscreationDate)>=(DATE(NOW()) - INTERVAL 7 DAY)";
                $query = $dbh->prepare($sql);
                $query->execute();
                $seven_pass = $query->rowCount();

                $cards = [
                    ['title' => 'Total Category', 'value' => $totalcat, 'icon' => 'fa-calendar', 'link' => 'manage-category.php', 'class' => 'card-danger'],
                    ['title' => 'Total Pass', 'value' => $totalpass, 'icon' => 'fa-beer', 'link' => 'manage-pass.php', 'class' => 'card-success'],
                    ['title' => 'Pass Created Today', 'value' => $today_pass, 'icon' => 'fa-pencil-square-o', 'link' => 'todays-pass.php', 'class' => 'card-info'],
                    ['title' => 'Pass Created Yesterday', 'value' => $yes_pass, 'icon' => 'fa-pencil', 'link' => 'yesterdays-pass.php', 'class' => 'card-warning'],
                    ['title' => 'Pass Created in Last 7 Days', 'value' => $seven_pass, 'icon' => 'fa-pencil', 'link' => 'last-7days-pass.php', 'class' => 'card-default'],
                ];
            ?>

            <!-- Cards Section -->
            <div class="row">
                <?php foreach ($cards as $card): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <?php if ($card['link']): ?><a href="<?php echo $card['link']; ?>" class="card-link"><?php endif; ?>
                            <div class="card <?php echo $card['class']; ?>">
                                <div class="label-title"><?php echo $card['title']; ?></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4><?php echo $card['value']; ?></h4>
                                    <div class="icon-box">
                                        <i class="fa <?php echo $card['icon']; ?>"></i>
                                    </div>
                                </div>
                            </div>
                        <?php if ($card['link']): ?></a><?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Footer -->
            <footer class="text-center mt-5">
                <p class="text-muted">&copy; <?php echo date('Y'); ?> Curfew Pass Management System</p>
            </footer>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
