<?php
// app/views/dashboard/books_add.php

// Include the structural parts
// --- FIX 1: Use APP_ROOT for header ---
require_once APP_ROOT . '/app/views/includes/header.php';
// --- FIX 2: Use APP_ROOT for sidebar ---
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="flex justify-between items-center pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $page_title ?? 'Add New Book' ?></h1>
        
        <a href="<?= URL_ROOT ?>/index.php?url=book/index" 
           class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </header>

    <?php if (isset($message)) echo $message; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg">
        
        <form action="<?= URL_ROOT ?>/index.php?url=book/add" method="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Author <span class="text-red-500">*</span></label>
                    <input type="text" name="author" id="author" value="<?= htmlspecialchars($author) ?>" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="<?= htmlspecialchars($isbn) ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-700">Publisher</label>
                    <input type="text" name="publisher" id="publisher" value="<?= htmlspecialchars($publisher) ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Publication Year</label>
                    <input type="number" name="year" id="year" value="<?= htmlspecialchars($year) ?>"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a category</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->id ?>" 
                                        <?= ($category->id == $category_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category->name) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="total_copies" class="block text-sm font-medium text-gray-700">Total Copies <span class="text-red-500">*</span></label>
                    <input type="number" name="total_copies" id="total_copies" value="<?= htmlspecialchars($total_copies) ?>" required min="1"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

            </div>
            
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($description) ?></textarea>
            </div>
            
            <div class="mt-8">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 shadow-md">
                    <i class="fas fa-save mr-2"></i> Save Book
                </button>
            </div>
        </form>
    </div>

</div>

<?php 
// --- FIX 3: Use APP_ROOT for footer ---
require_once APP_ROOT . '/app/views/includes/footer.php';
?>