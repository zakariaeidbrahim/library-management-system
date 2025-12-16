<?php
// app/views/dashboard/index.php

// --- FIX: Use APP_ROOT for robust, absolute file system pathing ---
require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="flex justify-between items-center pb-6 border-b border-gray-300">
        <h1 class="text-4xl font-light text-gray-800">Dashboard Overview</h1>
        <div class="text-gray-600">
            Welcome, <span class="font-semibold text-indigo-600"><?= htmlspecialchars($username) ?></span>
        </div>
    </header>
    
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mt-8"> 
        
        <div class="bg-indigo-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Total Books</p>
                <p class="text-3xl font-bold mt-1"><?= $totalBooks ?? 0 ?></p>
            </div>
            <i class="fas fa-book text-4xl opacity-50"></i>
        </div>
        
        <div class="bg-green-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Available Copies</p>
                <?php $availableCopies = ($totalBooks ?? 0) - ($issuedBooks ?? 0); ?>
                <p class="text-3xl font-bold mt-1"><?= $availableCopies ?></p>
            </div>
            <i class="fas fa-check-circle text-4xl opacity-50"></i>
        </div>
        
        <div class="bg-yellow-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Borrowed Books</p>
                <p class="text-3xl font-bold mt-1"><?= $issuedBooks ?? 0 ?></p>
            </div>
            <i class="fas fa-hand-holding-box text-4xl opacity-50"></i>
        </div>
        
        <div class="bg-gray-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Total Categories</p>
                <p class="text-3xl font-bold mt-1"><?= $totalCategories ?? 0 ?></p>
            </div>
            <i class="fas fa-tags text-4xl opacity-50"></i>
        </div>

        <div class="bg-red-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Overdue Books</p>
                <p class="text-3xl font-bold mt-1"><?= $overdueCount ?? 0 ?></p>
            </div>
            <i class="fas fa-exclamation-triangle text-4xl opacity-50"></i>
        </div>
        <?php if (isset($totalMembers)): ?>
        <div class="bg-blue-600 p-6 rounded-lg shadow-lg flex items-center justify-between text-white">
            <div>
                <p class="text-sm font-light opacity-75">Total Members</p>
                <p class="text-3xl font-bold mt-1"><?= $totalMembers ?? 0 ?></p>
            </div>
            <i class="fas fa-users text-4xl opacity-50"></i>
        </div>
        <?php endif; ?>

    </section>
    
    <hr class="my-8">

    <section class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Quick Actions</h2>
        
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-wrap gap-4"> 
            
            <a href="<?= URL_ROOT ?>/index.php?url=issue/borrow" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
                <i class="fas fa-handshake mr-2"></i> **Issue / Borrow Book**
            </a>
            
            <a href="<?= URL_ROOT ?>/index.php?url=book/add" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
                <i class="fas fa-plus-circle mr-2"></i> Add New Book
            </a>
            <a href="<?= URL_ROOT ?>/index.php?url=book/index" 
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
                <i class="fas fa-search mr-2"></i> View All Books
            </a>
            
            <a href="<?= URL_ROOT ?>/index.php?url=member/add" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300 inline-flex items-center shadow-md">
                <i class="fas fa-user-plus mr-2"></i> Add New Member
            </a>
            
        </div>
    </section>

    <hr class="my-8">

    <section class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4 flex justify-between items-center">
            ⚠️ Overdue Books
            <a href="<?= URL_ROOT ?>/index.php?url=issue/index" 
               class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition duration-300">
                View All Issues <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </h2>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <?php if (!empty($overdueIssues)): ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach (array_slice($overdueIssues, 0, 5) as $issue): // Show top 5 overdue issues ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($issue->book_title) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($issue->first_name . ' ' . $issue->last_name) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                <?= date('Y-m-d', strtotime($issue->due_date)) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= URL_ROOT ?>/index.php?url=issue/returnBook/<?= $issue->issue_id ?>" 
                                   class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
                                   onclick="return confirm('Confirm book return for <?= htmlspecialchars($issue->book_title) ?>?');">
                                    <i class="fas fa-undo-alt mr-1"></i> Return
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-6 text-center border border-gray-200 rounded-md">
                    <p class="text-lg text-gray-500 font-semibold mb-2">No Overdue Books! 🎉</p>
                    <p class="text-gray-400">The library is in good standing.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php 
// --- FIX: Use APP_ROOT for robust, absolute file system pathing ---
require_once APP_ROOT . '/app/views/includes/footer.php';
?>