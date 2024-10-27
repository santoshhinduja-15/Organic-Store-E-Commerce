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

    $reg = "SELECT rid FROM tbl_registration WHERE email='$mail'";
    $regid = mysqli_query($con, $reg);
    $rid_row1 = mysqli_fetch_array($regid);
    $rid = $rid_row1['rid'];

    $viewbrand = "SELECT * FROM tbl_product WHERE rid='$rid'";
    $d_seller_brand = mysqli_query($con, $viewbrand);

    $viewprod = "SELECT * FROM tbl_customer_order, tbl_customer_delv_address, tbl_product, tbl_registration 
                 WHERE tbl_customer_order.delv_adres_id = tbl_customer_delv_address.delv_adres_id 
                 AND tbl_customer_order.stock_product_id = tbl_product.pid 
                 AND tbl_product.rid = '$rid' 
                 AND tbl_customer_order.status = 'DISPATCHED' 
                 AND tbl_customer_order.email = tbl_registration.email 
                 LIMIT $offset, $no_of_records_per_page";
    $d_seller_prod = mysqli_query($con, $viewprod);

    // Corrected SQL query
    $disp = "SELECT tbl_login.user_type_id, tbl_registration.email, tbl_registration.fname, 
             tbl_registration.lname, tbl_registration.phone_no 
             FROM tbl_login 
             INNER JOIN tbl_registration ON tbl_login.email = tbl_registration.email 
             WHERE tbl_registration.email = '$mail'";
    $disp_result = mysqli_query($con, $disp);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer|Home</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="js/jquery.min.js"></script>

    <style>
        input[type=text], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .div1 {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .inputbx {
            width: 90%;
            padding: 12px 20px;
            margin: 8px 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        ul.pagination {
            display: inline-block;
            padding: 0;
            margin: 0;
        }

        ul.pagination li {
            display: inline;
        }

        ul.pagination li a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }
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
                    <div class="clearfix"></div>
                </div>
                <div class="header-bottom-right">
                    <div class="account"><a href="seller_profile.php"><span></span><?php echo $mail ?></a></div>
                    <ul class="login">
                        <li><a href="logout.php"><span></span>LOGOUT</a></li>
                    </ul>
                    <div class="cart"><a href="cart_view.php"><span></span>CART</a></div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="account_grid">
            <div class="login-right">
                <h3>Welcome to organic world</h3><br><br>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="sub-cate">
            <div class="top-nav rsidebar span_1_of_left">
                <h3 class="cate">MENU</h3>
                <ul class="menu">
                    <li class="item2"><a href="#">Profile<img class="arrow-img" src="images/arrow1.png" alt=""/></a>
                        <ul class="cute">
                            <li class="subitem1"><a href="seller_profile.php">View Profile</a></li>
                            <li class="subitem1"><a href="seller_update.php">Change Password</a></li>
                        </ul>
                    </li>
                    <li class="item2"><a href="#">Orders<img class="arrow-img" src="images/arrow1.png" alt=""/></a>
                        <ul class="cute">
                            <li class="subitem1"><a href="new_received_orders.php">View new orders</a></li>
                            <li class="subitem1"><a href="seller_delivered_orders.php">Dispatched Orders</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="outer-w3-agile mt-3">
            <h4 class="tittle-w3-agileits mb-4">New Orders</h4>
        </div>

        <div class="outer-w3-agile col-xl mt-3" style="overflow: auto">
            <table id="recordListing" class="table table-bordered table-striped" style="overflow: auto">
                <col width="70">
                <col width="190">
                <?php 
                    $count = 0;
                    while ($prod = mysqli_fetch_array($d_seller_prod)) {
                        $count++;
                ?>
                <tr id="<?php echo $prod['stock_product_id'] ?>">
                    <tr><th>Sl.No</th><td><?php echo $count ?></td></tr>
                    <tr><th>Product Name</th><td><?php echo $prod['name'] ?></td></tr>
                    <tr><th>Ordered Quantity</th><td><?php echo $prod['purchase_qty'] ?></td></tr>
                    <tr><th>Payed Amount</th><td><?php echo $prod['purchase_price'] ?></td></tr>
                    <tr><th>Ordered date</th><td><?php echo $prod['order_date'] ?></td></tr>
                    <tr><th>Order by</th><td><?php echo $prod[1] ?></td></tr>
                    <tr><th>Delivery Address</th><td><?php echo $prod['fname'] ?><br><?php echo $prod['Mobile number'] ?><br><?php echo $prod['address_line'] ?><br><?php echo $prod['landmark'] ?><br><?php echo $prod['town_city'] ?><br><?php echo $prod['pin_code'] ?><br></td></tr>
                </tr>
                <?php } ?>
            </table>

            <ul class="pagination">
                <li><a>«</a></li>
                <li><a href="?pageno=1">First</a></li>
                <li class="<?php if($pageno <= 1) echo 'disabled'; ?>">
                    <a href="<?php if($pageno > 1) echo "?pageno=".($pageno - 1); ?>"><<</a>
                </li>
                <li class="<?php if($pageno >= $total_pages) echo 'disabled'; ?>">
                    <a href="<?php if($pageno < $total_pages) echo "?pageno=".($pageno + 1); ?>">>></a>
                </li>
                <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                <li><a>»</a></li>
            </ul>
        </div>
    </div>
    <div class="footer">
        <div class="footer-bottom">
        </div>
    </div>
</body>
</html>
<?php
} else {
    header('Location:login.php');
}
?>
