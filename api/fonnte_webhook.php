<?php

include "../koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);

/* simpan log untuk debug */

file_put_contents(
"webhook_log.txt",
date("Y-m-d H:i:s")." | ".json_encode($data).PHP_EOL,
FILE_APPEND
);

$phone = $data['sender'] ?? '';
$message = $data['message'] ?? '';

if(!$phone || !$message){
exit;
}

$stmt = $conn->prepare("
INSERT INTO chats(phone,message,sender)
VALUES(?,?, 'customer')
");

$stmt->bind_param("ss",$phone,$message);
$stmt->execute();
$stmt->close();

echo "OK";