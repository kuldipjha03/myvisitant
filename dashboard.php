<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['avmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 12px;
        }
        .card h4 {
            font-size: 1.8rem;
            margin: 0;
        }
        .card small {
            font-size: 0.875rem;
            color: #666;
        }
    </style>
</head>
<body class="animsition">
<div class="page-wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="page-container">
        <?php include_once('includes/header.php'); ?>

        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">

                        <?php
                        // Visitor Metrics
                        $today = mysqli_num_rows(mysqli_query($con, "SELECT ID FROM tblvisitor WHERE date(EnterDate)=CURDATE()"));
                        $yesterday = mysqli_num_rows(mysqli_query($con, "SELECT ID FROM tblvisitor WHERE date(EnterDate)=CURDATE()-1"));
                        $last7 = mysqli_num_rows(mysqli_query($con, "SELECT ID FROM tblvisitor WHERE date(EnterDate)>=CURDATE() - INTERVAL 7 DAY"));
                        $total = mysqli_num_rows(mysqli_query($con, "SELECT ID FROM tblvisitor"));
                        $categories = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblcategory"));
                        $totalpass = mysqli_num_rows(mysqli_query($con, "SELECT ID FROM tblvisitorpass"));

                        // Room Metrics
                        $total_rooms = mysqli_num_rows(mysqli_query($con, "SELECT id FROM rooms"));
                        $todayDate = date('Y-m-d');
                        $available_today_query = "
                            SELECT r.id FROM rooms r
                            WHERE NOT EXISTS (
                                SELECT 1 FROM room_bookings b
                                WHERE b.room_id = r.id
                                AND (
                                    (b.booking_date <= '$todayDate' AND b.till_date IS NULL)
                                    OR ('$todayDate' BETWEEN b.booking_date AND b.till_date)
                                )
                            )
                        ";
                        $available_rooms_today = mysqli_num_rows(mysqli_query($con, $available_today_query));
                        ?>

                        <!-- Visitor Stats -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-account-o text-primary fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $today; ?></h4>
                                        <small>Today's Visitors</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-account-o text-success fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $yesterday; ?></h4>
                                        <small>Yesterday's Visitors</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-account-o text-info fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $last7; ?></h4>
                                        <small>Last 7 Days Visitors</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-accounts-alt text-danger fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $total; ?></h4>
                                        <small>Total Visitors</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-file-text text-warning fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $categories; ?></h4>
                                        <small>Listed Categories</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-ticket-star text-secondary fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $totalpass; ?></h4>
                                        <small>Total Pass Created</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Room Stats -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-home text-primary fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $total_rooms; ?></h4>
                                        <small>Total Rooms</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm p-3">
                                <div class="d-flex align-items-center">
                                    <i class="zmdi zmdi-hotel text-success fa-2x mr-3"></i>
                                    <div>
                                        <h4><?php echo $available_rooms_today; ?></h4>
                                        <small>Available Rooms Today</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Visitor Trend (Last 7 Days)</strong>
                                </div>
                                <div class="card-body">
                                    <canvas id="visitorChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include_once('includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/popper.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Visitor Chart -->
<script>
    <?php
    $labels = [];
    $values = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('D', strtotime($day));
        $result = mysqli_query($con, "SELECT COUNT(*) AS count FROM tblvisitor WHERE DATE(EnterDate) = '$day'");
        $row = mysqli_fetch_assoc($result);
        $values[] = (int)$row['count'];
    }
    ?>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const visitorChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Visitors',
                data: <?php echo json_encode($values); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: '#36A2EB',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>
<?php } ?>
