<?php
// app/views/dashboard/books.php

// Include the structural parts
require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="flex justify-between items-center pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $page_title ?? 'Manage Books' ?></h1>
        
        <a href="<?= URL_ROOT ?>/index.php?url=book/add" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
            <i class="fas fa-plus-circle mr-2"></i> Add New Book
        </a>
    </header>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'added'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success!</p>
            <p>The new book has been added to the library inventory.</p>
        </div>
    <?php endif; ?>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Book Inventory</h2>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Title</th>
                        <th class="py-3 px-6 text-left">Author</th>
                        <th class="py-3 px-6 text-left">Category</th>
                        <th class="py-3 px-6 text-center">ISBN</th>
                        <th class="py-3 px-6 text-center">Copies (Avail/Total)</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap font-medium">
                                    <?= htmlspecialchars($book->title) ?>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <?= htmlspecialchars($book->author) ?>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full text-xs">
                                        <?= htmlspecialchars($book->category_name) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <?= htmlspecialchars($book->isbn) ?>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="font-bold text-green-600"><?= $book->available_copies ?></span> / 
                                    <?= $book->total_copies ?>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        <a href="<?= URL_ROOT ?>/index.php?url=book/edit/<?= $book->id ?>" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= URL_ROOT ?>/index.php?url=book/delete/<?= $book->id ?>" class="w-4 mr-2 transform hover:text-red-500 hover:scale-110" 
                                           onclick="return confirm('Are you sure you want to delete this book?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">
                                No books found in the inventory. Click "Add New Book" to start.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
    
    <hr class="my-8">

    <section>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex justify-between items-center">
            Currently Borrowed Books
            <a href="<?= URL_ROOT ?>/index.php?url=issue/borrow" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md text-sm">
                <i class="fas fa-arrow-right mr-2"></i> Borrow Book
            </a>
        </h2>
        
        <?php 
        // We include the table content from the embedded view file.
        // The $issued_books variable MUST be provided by the BookController's index method.
        require APP_ROOT . '/app/views/issue/index_embedded.php'; 
        ?>
    </section>

</div>

<?php 
require_once APP_ROOT . '/app/views/includes/footer.php';
?>