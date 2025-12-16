<?php
// app/views/includes/header.php
// Use CDN links for quick setup. In production, download and compile files.

$tailwind_css = 'https://cdn.tailwindcss.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Manager | <?= $title ?? 'Dashboard' ?></title>
    <script src="<?= $tailwind_css ?>"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Optional custom styles for the sidebar */
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }
    </style>
</head>
<body class="bg-gray-100"></body>