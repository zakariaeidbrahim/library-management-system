<?php
// app/views/includes/sidebar.php

$nav_items = [
    // FIX: Use query string ?url= for all links
    ['icon' => 'fas fa-chart-line', 'text' => 'Overview', 'link' => URL_ROOT . '/index.php?url=dashboard'],
    ['icon' => 'fas fa-book', 'text' => 'Manage Books', 'link' => URL_ROOT . '/index.php?url=book/index'],
    ['icon' => 'fas fa-layer-group', 'text' => 'Categories', 'link' => URL_ROOT . '/index.php?url=category/index'],
    ['icon' => 'fas fa-sign-out-alt', 'text' => 'Logout', 'link' => URL_ROOT . '/index.php?url=logout'],
];
?>

<div class="sidebar bg-gray-800 text-white h-screen fixed">
    <div class="p-5 text-2xl font-bold border-b border-gray-700">
        LMS Admin
    </div>
    <nav class="pt-4">
        <?php foreach ($nav_items as $item): ?>
            <a href="<?= $item['link'] ?>" 
               class="flex items-center py-3 px-5 hover:bg-gray-700 transition duration-150 <?= (strpos($_SERVER['REQUEST_URI'], $item['link']) !== false && $item['link'] != URL_ROOT . '/index.php/logout') ? 'bg-indigo-600' : '' ?>">
                <i class="<?= $item['icon'] ?> mr-3 w-5"></i>
                <span class="text-sm"><?= $item['text'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</div>