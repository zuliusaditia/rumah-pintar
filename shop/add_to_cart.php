<?php
session_start();

if(!isset($_POST['id'])){
    echo "error";
    exit;
}

$id = (int) $_POST['id'];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])){
    $_SESSION['cart'][$id]++;
}else{
    $_SESSION['cart'][$id] = 1;
}

echo "ok";