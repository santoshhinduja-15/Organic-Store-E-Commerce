<?php
session_start();
if (isset($_SESSION['alogin'])) {
    $mail = $_SESSION['alogin'];
    
    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }

    $no_of_records_per_page = 10;
    $offset = ($pageno - 1) * $no_of_records_per_page;

    $con = mysqli_connect("localhost", "root", "", "organic_shop_db") or die("Couldn't connect");

    // Get the `rid` for the logged-in user
    $reg = "SELECT rid FROM tbl_registration WHERE email='$mail'";
    $regid = mysqli_query($con, $reg);
    $rid_row1 = mysqli_fetch_array($regid);
    $rid = $rid_row1['rid'];

    // Adjusted query to retrieve complaints related to the specific `rid`
    $viewprod = "SELECT tbl_complaints.*, tbl_product.name, tbl_registration.email
                 FROM tbl_complaints
                 INNER JOIN tbl_customer_order ON tbl_complaints.customer_order = tbl_customer_order.customer_order_id
                 INNER JOIN tbl_product ON tbl_complaints.stock_product_id = tbl_product.pid
                 INNER JOIN tbl_registration ON tbl_customer_order.email = tbl_registration.email
                 WHERE tbl_product.rid='$rid' AND tbl_complaints.status='new'
                 LIMIT $offset, $no_of_records_per_page";

    $d_seller_prod = mysqli_query($con, $viewprod);

    // For pagination, get the total number of complaints
    $total_pages_sql = "SELECT COUNT(*) FROM tbl_complaints
                        INNER JOIN tbl_product ON tbl_complaints.stock_product_id = tbl_product.pid
                        WHERE tbl_product.rid='$rid' AND tbl_complaints.status='new'";
    $result = mysqli_query($con, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer | Home</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="js/jquery.min.js"></script>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <div class="header">
        <div class="bottom-header" style="background-color:palegreen;">
            <div class="container">
                <div class="header-bottom-left">
                    <div class="logo">
                        <font size="4"><strong><i><b><p style="color:red;">ORGANICSHOPPING</p></b></i></strong></font>
                    </div>
                    <div class="clearfix"> </div>
                </div>
                <div class="header-bottom-right">
                    <div class="account"><a href="seller_profile.php"><span></span><?php echo $mail ?></a></div>
                    <ul class="login">
                        <li><a href="logout.php"><span></span>LOGOUT</a></li>
                    </ul>
                    <div class="cart"><a href="cart_view.php"><span></span>CART</a></div>
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="account_grid">
            <div class="login-right">
                <h3>Welcome to the organic world</h3>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="outer-w3-agile mt-3">
            <h4 class="tittle-w3-agileits mb-4">New Complaints</h4>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="outer-w3-agile col-xl mt-3" style="overflow: auto">
                    <table id="recordListing" class="table table-bordered table-striped" style="overflow: auto">
                        <col width="70">
                        <col width="190">
                        <?php while ($prod = mysqli_fetch_array($d_seller_prod)) { ?>
                            <form action="complaints_replay_action.php?complaintid=<?php echo $prod['complaints_id']; ?>" method="POST">
                                <tr><th>Product Name</th><td><?php echo $prod['name']; ?></td></tr>
                                <tr><th>Posted by</th><td><?php echo $prod['email']; ?></td></tr>
                                <tr><th>Complaint message</th><td><?php echo $prod['complaint_message']; ?></td></tr>
                                <tr><th>Reply message</th>
                                    <td><input type="text" name="replay_comments" id="replay_comments" placeholder="Enter response message"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <center>
                                            <input type="submit" name="send_reply" id="send_reply" value="Send Response" class="btn btn-danger btn-sm" style="background-color:green;color:black;font-weight:bold">
                                        </center>
                                    </td>
                                </tr>
                            </form>
                        <?php } ?>
                    </table>

                    <ul class="pagination">
                        <li><a>«</a></li>
                        <li><a href="?pageno=1">First</a></li>
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>"><<</a>
                        </li>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">>></a>
                        </li>
                        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                        <li><a>»</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
} else {
    header('Location: login.php');
}
?>
