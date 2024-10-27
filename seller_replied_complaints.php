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

    // Get the seller ID based on the email
    $reg = "SELECT rid FROM tbl_registration WHERE email='$mail'";
    $regid = mysqli_query($con, $reg);
    $rid_row1 = mysqli_fetch_array($regid);
    $rid = $rid_row1['rid'];

    // Fetching products for the seller
    $viewbrand = "SELECT * FROM tbl_product WHERE rid='$rid'";
    $d_seller_brand = mysqli_query($con, $viewbrand);

    // Fetching complaints that have been replied to
    $viewprod = "SELECT * FROM tbl_complaints, tbl_customer_order, tbl_product, tbl_registration 
                 WHERE tbl_complaints.customer_order = tbl_customer_order.customer_order_id 
                 AND tbl_complaints.stock_product_id = tbl_product.pid 
                 AND tbl_product.rid = '$rid' 
                 AND tbl_complaints.status = 'REPLIED' 
                 AND tbl_customer_order.email = tbl_registration.email 
                 LIMIT $offset, $no_of_records_per_page";
    $d_seller_prod = mysqli_query($con, $viewprod);

    // Fetching seller information
    $disp = "SELECT tbl_login.user_type_id, tbl_registration.email, tbl_registration.fname, 
                    tbl_registration.lname, tbl_registration.phone_no 
             FROM tbl_login 
             INNER JOIN tbl_registration ON tbl_login.email = tbl_registration.email 
             WHERE tbl_registration.email='$mail'";
    $disp_result = mysqli_query($con, $disp);

    // Get total count of replied complaints for pagination
    $total_complaints_query = "SELECT COUNT(*) FROM tbl_complaints 
                                WHERE status = 'REPLIED' 
                                AND stock_product_id IN (SELECT pid FROM tbl_product WHERE rid='$rid')";
    $total_complaints_result = mysqli_query($con, $total_complaints_query);
    $total_complaints_row = mysqli_fetch_array($total_complaints_result);
    $total_complaints = $total_complaints_row[0];

    $total_pages = ceil($total_complaints / $no_of_records_per_page);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer | Home</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script type="application/x-javascript"> 
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); 
        function hideURLbar(){ window.scrollTo(0,1); } 
    </script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
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

        .space {
            margin: 8px 8px 0;
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
                <h3>Welcome to the organic world<br><br></h3>
            </div>	
            <div class="login-left"></div>
            <div class="clearfix"></div>
        </div>
        <div class="sub-cate">
            <div class="top-nav rsidebar span_1_of_left">
                <h3 class="cate">MENU</h3>
                <ul class="menu">
                    <ul class="kid-menu">
                        <li><a href="seller_home.php">Home</a></li>
                    </ul>
                    <li class="item2"><a href="#">Profile<img class="arrow-img" src="images/arrow1.png" alt=""/></a>
                        <ul class="cute">
                            <li class="subitem1"><a href="seller_profile.php">View Profile</a></li>
                            <li class="subitem1"><a href="seller_update.php">Change Password</a></li>
                        </ul>
                    </li>
                    <ul class="kid-menu">
                        <li><a href="product.php">Add Product</a></li>
                        <li><a href="viewproduct.php">View Products</a></li>
                    </ul>
                    <li class="item2"><a href="#">Orders<img class="arrow-img" src="images/arrow1.png" alt=""/></a>
                        <ul class="cute">
                            <li class="subitem1"><a href="new_received_orders.php">View New Orders</a></li>
                            <li class="subitem1"><a href="seller_delivered_orders.php">Dispatched Orders</a></li>
                        </ul>
                    </li>
                    <li class="item2"><a href="#">Complaints<img class="arrow-img" src="images/arrow1.png" alt=""/></a>
                        <ul class="cute">
                            <li class="subitem1"><a href="seller_complaint.php">New Complaints</a></li>
                            <li class="subitem1"><a href="seller_replied_complaints.php">Replied Complaints</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="outer-w3-agile mt-3">
                <h4 class="tittle-w3-agileits mb-4">Replied Complaints</h4>
            </div>
            <div class="outer-w3-agile col-xl mt-3" style="overflow: auto">
                <table id="recordListing" class="table table-bordered table-striped">
                    <col width="70">
                    <col width="190">
                    <?php 
                    $count = 0;

                    while ($prod = mysqli_fetch_array($d_seller_prod)) {
                        $count++;
                    ?>
                    <tr id="<?php echo $prod['stock_product_id'] ?>">
                        <th>Sl.No</th>
                        <td><?php echo $count; ?></td>
                        <th>Complaint ID</th>
                        <td><?php echo $prod['complaint_id']; ?></td>
                        <th>Product ID</th>
                        <td><?php echo $prod['stock_product_id']; ?></td>
                        <th>Reply</th>
                        <td><?php echo $prod['reply']; ?></td>
                        <th>Customer Name</th>
                        <td><?php echo $prod['fname'] . " " . $prod['lname']; ?></td>
                        <th>Customer Phone</th>
                        <td><?php echo $prod['phone_no']; ?></td>
                        <th>Action</th>
                        <td><a href="delete_complaint.php?cid=<?php echo $prod['complaint_id']; ?>" onclick="return confirm('Are you sure you want to delete?');">Delete</a></td>
                    </tr>
                    <?php } ?>
                </table>
                <div class="pagination">
                    <ul class="pagination">
                        <li><a href="?pageno=1">First</a></li>
                        <li class="<?php if ($pageno <= 1) { echo 'disabled'; } ?>">
                            <a href="<?php if ($pageno <= 1) { echo '#'; } else { echo "?pageno=" . ($pageno - 1); } ?>">Prev</a>
                        </li>
                        <li class="<?php if ($pageno >= $total_pages) { echo 'disabled'; } ?>">
                            <a href="<?php if ($pageno >= $total_pages) { echo '#'; } else { echo "?pageno=" . ($pageno + 1); } ?>">Next</a>
                        </li>
                        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container">
            <div class="footer-info">
                <p>&copy; 2024 Organic Shopping. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    header('location:index.php');
}
?>
	