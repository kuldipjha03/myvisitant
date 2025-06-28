<?php
include('includes/dbconnection.php');

if (isset($_POST['searchdata'])) {
    $search = mysqli_real_escape_string($con, $_POST['searchdata']);
    
    $query = "SELECT DISTINCT VisitorName, MobileNumber FROM tblvisitor 
              WHERE VisitorName LIKE '%$search%' OR MobileNumber LIKE '%$search%' 
              LIMIT 5";
    
    $result = mysqli_query($con, $query);

    // ✅ Check for query errors
    if (!$result) {
        echo "Query Error: " . mysqli_error($con);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        echo '<ul class="list-group">';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li class="list-group-item suggestion">' . htmlspecialchars($row['VisitorName']) . ' - ' . htmlspecialchars($row['MobileNumber']) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<div class="list-group-item">No match found</div>';
    }
}
?>
