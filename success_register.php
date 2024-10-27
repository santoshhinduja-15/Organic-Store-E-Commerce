<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "organic_shop_db") or die("Couldn't connect");

if (isset($_POST["submit1"])) {
    $mail = $_POST["mail"];
    $user_type_id = $_POST["user_type_id"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $phonem = $_POST["phonem"];
    $place = $_POST["place"];
    $password = $_POST["password"];
    $status = "ACTIVE";
    $flag = 0;

    $q2 = "SELECT * FROM tbl_login";
    $q3 = "SELECT * FROM tbl_registration";

    $disp_result = mysqli_query($con, $q2);
    $reg_result = mysqli_query($con, $q3);

    while ($row = mysqli_fetch_array($disp_result)) {
        $email = $row['email'];

        if (strcmp($email, $mail) == 0) {
            $flag++;
            echo "<script type='text/javascript'>alert('The email id you entered is already enrolled'); window.location='register.php';</script>";
            break;
        }
    }

    while ($row = mysqli_fetch_array($reg_result)) {
        $email = $row['email'];

        if (strcmp($email, $mail) == 0) {
            $flag++;
            echo "<script type='text/javascript'>alert('The email id is already enrolled'); window.location='register.php';</script>";
            break;
        }
    }

    if ($flag == 0) {
        $allowedExtsp = array("jpg");
        $images = $_FILES["images"]["name"];
        $tempp = explode(".", $_FILES["images"]["name"]);
        $extensionp = end($tempp);

        // Check if the uploaded file has the correct extension before uploading
        if (in_array($extensionp, $allowedExtsp)) {
            move_uploaded_file($_FILES["images"]["tmp_name"], "uploads/profile/" . $images);

            $q_login = "INSERT INTO tbl_login(email, user_type_id, password, status) VALUES ('$mail', $user_type_id, '$password', '$status')";
            $q1 = "INSERT INTO tbl_registration(fname, lname, phone_no, email, place, images) VALUES ('$firstname', '$lastname', '$phonem', '$mail', '$place', '$images')";

            $ins_login = mysqli_query($con, $q_login);
            $ins = mysqli_query($con, $q1);

            if ($ins && $ins_login) {
                $_SESSION['alogin'] = $mail;

                if ($user_type_id == 1) {
                    header('Location:admin_home.php');
                } elseif ($user_type_id == 2) {
                    header('Location:customer_home.php');
                } elseif ($user_type_id == 3) {
                    header('Location:seller_home.php');
                }
            } else {
                echo "<script type='text/javascript'>alert('Something went wrong, please try again later'); window.location='register.php';</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Only JPG images are allowed'); window.location='register.php';</script>";
        }
    }
}
?>
