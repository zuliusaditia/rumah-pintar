<?php
session_start();

$id = $_POST['id'];
$qty = $_POST['qty'];

if(!isset($_SESSION['cart'])){
$_SESSION['cart'] = [];
}

if(isset($_SESSION['cart'][$id])){

$_SESSION['cart'][$id] += $qty;

}else{

$_SESSION['cart'][$id] = $qty;

}

echo "ok";