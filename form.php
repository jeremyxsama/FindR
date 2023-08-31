<?php
$conn = mysqli_connect('localhost', 'root', '', 'findr');

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $about = $_POST['about'];
    $website = $_POST['website'];
    $image_url = $_POST['image_url'];

    $sql = "INSERT INTO `restaurants`(`name`, `address`, `city`, `about`, `website`, `image_url`)
VALUES ('$name', '$address', '$city', '$about', '$website', '$image_url');";

    $alert = mysqli_query($conn, $sql);

    if ($alert) {
        header('Location: success.html');
    } else {
        header('Location: wrong.html');
    }
}