<?php
// app/views/issue/index_embedded.php
// This file contains ONLY the table structure for embedding.

// Success/Error Messages (Check for messages redirected from the return action)
if (isset($_GET['success']) && $_GET['success'] == 'returned'): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p class="font-bold">Success!</p>
        <p>Book marked as returned successfully.</p>
    </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'return_failed'): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Error!</p>
        <p>Error processing return.</p>
    </div>
<?php endif; ?>

<div class="bg-white p-8 rounded-lg shadow-lg">
    <?php if (!empty($issued_books)): ?>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($issued_books as $issue): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= htmlspecialchars($issue->book_title) ?> 
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($issue->first_name . ' ' . $issue->last_name) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= date('Y-m-d', strtotime($issue->issue_date)) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold 
                        <?php 
                            $isOverdue = (strtotime($issue->due_date) < time());
                            echo $isOverdue ? 'text-red-600' : 'text-gray-900'; 
                        ?>">
                        <?= date('Y-m-d', strtotime($issue->due_date)) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= $isOverdue ? 'Overdue' : 'Issued' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?= URL_ROOT ?>/index.php?url=issue/returnBook/<?= $issue->issue_id ?>" 
                           class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
                           onclick="return confirm('Are you sure you want to mark the book \'<?= htmlspecialchars($issue->book_title) ?>\' as returned?');">
                            <i class="fas fa-undo-alt mr-1"></i> Return
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-6 text-center border border-gray-200 rounded-md">
            <p class="text-lg text-gray-500 font-semibold mb-2">No Books Currently Borrowed</p>
            <p class="text-gray-400">All books are accounted for, or you haven't issued any yet.</p>
        </div>
    <?php endif; ?>
</div>