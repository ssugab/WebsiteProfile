<?php

include 'config.php';

if (isset($_POST['submit'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = "uploaded_img/".$image;

    $message_f = "Query failed";

    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'") or die($message_f);

    if(mysqli_num_rows($select) > 0) {
        $message[] = "User already exist";
    }else{
        if($password != $cpassword){
            $message[] = "Password not match";
        }elseif($image_size > 2000000){
            $message[] = "Image size should be less than 2MB";
        }else{
            $insert = mysqli_query($conn, "INSERT INTO `user_form`(name, email, password, image) VALUES('$name', '$email', '$password', '$image')") or die('query failed');
        
            if($insert){
                move_uploaded_file($tempname, $folder);
                $message[] = "Registration successfull";
                header('location:login.php');
            }else {
                $message[] = "Registration failed";
            }

        }
    }


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

    <!-- <a href="main.php"> </a><i class="fa-solid fa-arrow-left"> </i> -->

    <div class="form-container">
    <h3 class="back"><a href="main.php">Kembali</a></h3>

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Register</h3>

            <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo '<div class="message">'.$message.'</div>';
                    }
                }
            ?>

            <input type="text" name="name" placeholder="enter username" class="box" required>
            <input type="email" name="email" placeholder="enter email" class="box" required>
            <input type="password" name="password" placeholder="enter password" class="box" required>
            <input type="password" name="cpassword" placeholder="confirm password" class="box" required>
            
            <h2 class="prof" style="font-size: 18px; margin-right: 260px; color: white;"> Masukkan Foto Profil</h2>
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
            <input type="submit" name="submit" value="register now" class="btn">
            <p>already have an account? <a href="login.php">login now</a></p>
        </form>
    </div>
    
</body>
</html>