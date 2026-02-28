<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin - Rumah Pintar</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }

.sidebar {
    height:100vh;
    background:#111827;
    color:white;
}

.sidebar a {
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px 20px;
    color:#9ca3af;
    text-decoration:none;
    font-size:14px;
}

.sidebar a.active,
.sidebar a:hover {
    background:#1f2937;
    color:white;
}

.card-modern {
    border:none;
    border-radius:14px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
}
</style>
</head>
<body>