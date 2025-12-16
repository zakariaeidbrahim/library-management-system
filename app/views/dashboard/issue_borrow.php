<?php
// app/views/dashboard/issue_borrow.php

require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $page_title ?></h1>
    </header>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'issued'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success!</p>
            <p>The book has been successfully issued to the member.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($message)) echo $message; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl">
        
        <form action="<?= URL_ROOT ?>/index.php?url=issue/borrow" method="POST">
            
            <div class="mb-4">
                <label for="member_id" class="block text-sm font-medium text-gray-700">
                    Select Member <span class="text-red-500">*</span>
                </label>
                <select name="member_id" id="member_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choose Member --</option>
                    <?php foreach ($members as $member): ?>
                        <option value="<?= $member->id ?>" <?= ($member_id == $member->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($member->first_name . ' ' . $member->last_name . ' (' . $member->email . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="book_id" class="block text-sm font-medium text-gray-700">
                    Select Book <span class="text-red-500">*</span>
                </label>
                <select name="book_id" id="book_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choose Available Book --</option>
                    <?php foreach ($available_books as $book): ?>
                        <option value="<?= $book->id ?>" <?= ($book_id == $book->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($book->title . ' (ISBN: ' . $book->isbn . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="due_date" class="block text-sm font-medium text-gray-700">
                    Due Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="due_date" id="due_date" value="<?= htmlspecialchars($due_date) ?>" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 shadow-md">
                    <i class="fas fa-arrow-alt-circle-right mr-2"></i> Issue Book
                </button>
            </div>
        </form>
    </div>

</div>

<?php 
require_once APP_ROOT . '/app/views/includes/footer.php';
?>