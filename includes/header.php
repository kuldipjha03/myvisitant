<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap" style="position: relative;">
                <form class="form-header" name="search" action="search-visitor.php" method="post">
                    <input class="au-input au-input--xl" type="text" name="searchdata" id="searchdata"
                        placeholder="Search Visitor by names &amp; mobile number..." autocomplete="off" />
                    <button class="au-btn--submit" type="submit" name="search">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                </form>
                <div id="suggestion-box"></div>

                <div class="header-button">
                    <div class="noti-wrap">
                        <?php
                            $adminid = $_SESSION['avmsaid'];
                            $ret = mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adminid'");
                            $row = mysqli_fetch_array($ret);
                            $name = $row['AdminName'];
                        ?>   
                    </div>
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu">
                            <div class="content">
                                <a class="js-acc-btn" href="admin-profile.php"><?php echo $name; ?></a>
                            </div>
                            <div class="account-dropdown js-dropdown">
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="admin-profile.php">
                                            <i class="zmdi zmdi-account"></i> Profile</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="change-password.php">
                                            <i class="zmdi zmdi-settings"></i>Change Password</a>
                                    </div>
                                </div>
                                <div class="account-dropdown__footer">
                                    <a href="logout.php">
                                        <i class="zmdi zmdi-power"></i>Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX Search Script -->
<script>
$(document).ready(function () {
    $("#searchdata").keyup(function () {
        var query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: "livesearch.php",
                method: "POST",
                data: { searchdata: query },
                success: function (data) {
                    $("#suggestion-box").fadeIn().html(data);
                }
            });
        } else {
            $("#suggestion-box").fadeOut();
        }
    });

    $(document).on("click", ".suggestion", function () {
        $("#searchdata").val($(this).text());
        $("#suggestion-box").fadeOut();
    });
});
</script>

<!-- Styles -->
<style>
#suggestion-box {
    position: absolute;
    background-color: white;
    z-index: 9999;
    width: 100%;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
}

#suggestion-box .list-group-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

#suggestion-box .list-group-item:hover {
    background-color: #f0f0f0;
}
</style>
