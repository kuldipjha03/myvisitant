<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['avmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $catname = $_POST['category'];
        $visname = $_POST['visname'];
        $mobnumber = $_POST['mobilenumber'];
        $add = $_POST['address'];
        $apart = $_POST['apartment'];
        $floor = $_POST['floor'];
        $whomtomeet = $_POST['whomtomeet'];
        $reasontomeet = $_POST['reasontomeet'];

        $query = mysqli_query($con, "INSERT INTO tblvisitor(categoryName, VisitorName, MobileNumber, Address, WhomtoMeet, ReasontoMeet, Apartment, Floor) 
            VALUES ('$catname', '$visname', '$mobnumber', '$add', '$whomtomeet', '$reasontomeet', '$apart', '$floor')");

        if ($query) {
            $last_insert_id = mysqli_insert_id($con);
            $booked = false;

            if (!empty($_POST['room_id']) && !empty($_POST['booking_date'])) {
                $room_id = $_POST['room_id'];
                $booking_date = $_POST['booking_date'];
                $till_date = !empty($_POST['till_date']) ? $_POST['till_date'] : NULL;

                mysqli_query($con, "UPDATE rooms SET status='booked' WHERE id=$room_id");

                $stmt = $con->prepare("INSERT INTO room_bookings (visitor_id, room_id, booking_date, till_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $last_insert_id, $room_id, $booking_date, $till_date);
                $booked = $stmt->execute();
            }

            echo "<script>alert('Visitor added " . ($booked ? "and room booked." : "successfully.") . "');</script>";
            echo "<script>window.location.href = 'visitors-form.php'</script>";
            exit;
        } else {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AVSM Visitors Forms</title>
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
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
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Add</strong> New Visitor
                                </div>
                                <div class="card-body card-block">
                                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                        <!-- Visitor Fields -->
                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Category</label>
                                            <div class="col-md-9">
                                                <select name="category" class="form-control" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    $ret = mysqli_query($con, "SELECT * FROM tblcategory ORDER BY categoryName");
                                                    while ($row = mysqli_fetch_array($ret)) {
                                                        echo "<option value='" . $row['categoryName'] . "'>" . $row['categoryName'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Visitor Name</label>
                                            <div class="col-md-9">
                                                <input type="text" name="visname" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Phone Number</label>
                                            <div class="col-md-9">
                                                <input type="text" name="mobilenumber" class="form-control" maxlength="10" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Address</label>
                                            <div class="col-md-9">
                                                <textarea name="address" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Apartment</label>
                                            <div class="col-md-9">
                                                <input type="text" name="apartment" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Floor/Wing</label>
                                            <div class="col-md-9">
                                                <input type="text" name="floor" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Whom to Meet</label>
                                            <div class="col-md-9">
                                                <input type="text" name="whomtomeet" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Reason to Meet</label>
                                            <div class="col-md-9">
                                                <input type="text" name="reasontomeet" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Room Booking -->
                                        <hr>
                                        <h5 class="text-success text-center">Optional Room Booking</h5>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Booking Date</label>
                                            <div class="col-md-9">
                                                <input type="date" name="booking_date" class="form-control" min="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label">Till Date (optional)</label>
                                            <div class="col-md-9">
                                                <input type="date" name="till_date" class="form-control" min="<?= date('Y-m-d') ?>">
                                            </div>
                                        </div>

                                        <?php
                                        $today = date('Y-m-d');
                                        $room_q = mysqli_query($con, "SELECT r.id, r.room_number, r.room_type FROM rooms r 
                                            WHERE r.status='available' OR r.id NOT IN (
                                                SELECT room_id FROM room_bookings 
                                                WHERE ('$today' BETWEEN booking_date AND IFNULL(till_date, '9999-12-31'))
                                            )");
                                        if (mysqli_num_rows($room_q) > 0) {
                                        ?>
                                            <div class="form-group row">
                                                <label class="col-md-3 form-control-label">Select Room</label>
                                                <div class="col-md-9">
                                                    <select name="room_id" class="form-control">
                                                        <option value="">Select Room</option>
                                                        <?php while ($room = mysqli_fetch_assoc($room_q)) {
                                                            echo "<option value='{$room['id']}'>{$room['room_number']} - {$room['room_type']}</option>";
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-group row">
                                                <div class="col-md-12 text-center">
                                                    <p class="text-danger"><i class="fas fa-triangle-exclamation"></i> No rooms available for today</p>
                                                    <a href="book-future.php" class="text-success">Book Future Date</a>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="form-group">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Add Visitor</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
