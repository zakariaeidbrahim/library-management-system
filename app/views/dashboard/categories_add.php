<?php
// app/views/dashboard/categories_add.php

// Include the structural parts using absolute path
require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="flex justify-between items-center pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $page_title ?? 'Add New Category' ?></h1>
        
        <a href="<?= URL_ROOT ?>/index.php?url=category/index" 
           class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
            <i class="fas fa-arrow-left mr-2"></i> Back to Categories
        </a>
    </header>

    <?php if (isset($message)) echo $message; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg">
        
        <form action="<?= URL_ROOT ?>/index.php?url=category/add" method="POST">
            
            <div class="max-w-md">
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="e.g., Fiction, Science, History">
                </div>

            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 shadow-md">
                    <i class="fas fa-save mr-2"></i> Save Category
                </button>
            </div>
        </form>
    </div>

</div>

<?php 
// Include the structural footer part
require_once APP_ROOT . '/app/views/includes/footer.php';
?>