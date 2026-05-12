<?php

function getSettings(mysqli $conn): array {

$result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");

return mysqli_fetch_assoc($result) ?: [];

}