<?php
// app/views/dashboard/categories.php

// Include the structural parts using absolute path
require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="flex justify-between items-center pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $title ?? 'Manage Categories' ?></h1>
        
        <a href="<?= URL_ROOT ?>/index.php?url=category/add" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
            <i class="fas fa-plus-circle mr-2"></i> Add New Category
        </a>
    </header>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'added'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success!</p>
            <p>The new category has been added.</p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Category Name</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
                                <?= htmlspecialchars($category->name) ?>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <a href="<?= URL_ROOT ?>/index.php?url=category/edit/<?= $category->id ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= URL_ROOT ?>/index.php?url=category/delete/<?= $category->id ?>" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" 
                                       onclick="return confirm('Are you sure you want to delete this category?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="py-6 text-center text-gray-500">
                            No categories found. Click "Add New Category" to start.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php 
// --- Use APP_ROOT for footer ---
require_once APP_ROOT . '/app/views/includes/footer.php';
?>