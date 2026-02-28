<?php

function log_activity($conn, $admin_username, $action) {

    $stmt = $conn->prepare(
        "INSERT INTO admin_logs (admin_username, action) VALUES (?, ?)"
    );

    if ($stmt) {
        $stmt->bind_param("ss", $admin_username, $action);
        $stmt->execute();
        $stmt->close();
    }
}