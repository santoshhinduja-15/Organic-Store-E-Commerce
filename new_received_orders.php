<?php
session_start();
if(isset($_SESSION['alogin'])){
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
                 AND tbl_customer_order.status = 'order placed'
                 AND tbl_customer_order.email = tbl_registration.email 
                 LIMIT $offset, $no_of_records_per_page";
    $d_seller_prod = mysqli_query($con, $viewprod);
    
    $disp = "SELECT tbl_login.user_type_id, tbl_registration.email, tbl_registration.fname, tbl_registration.lname, tbl_registration.phone_no 
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
/* CSS styling here */
</style>
</head>
<body>
    <!-- Your HTML content here -->
</body>
</html>

<?php
}
else {
    header('Location:login.php');
}
?>
