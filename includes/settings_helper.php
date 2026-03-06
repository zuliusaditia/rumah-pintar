<?php
function getSettings($conn) {
    $result = mysqli_query($conn,"SELECT * FROM settings LIMIT 1");
    return mysqli_fetch_assoc($result);
}