<?php
// app/views/member/add.php

require_once APP_ROOT . '/app/views/includes/header.php';
require_once APP_ROOT . '/app/views/includes/sidebar.php';
?>

<div class="ml-[250px] p-8">
    
    <header class="pb-6 border-b border-gray-300 mb-6">
        <h1 class="text-4xl font-light text-gray-800"><?= $page_title ?></h1>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Success!</p>
            <p>New member has been added successfully.</p>
        </div>
    <?php endif; ?>

    <?php if (isset($message)) echo $message; ?>

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-3xl">
        
        <form action="<?= URL_ROOT ?>/index.php?url=member/add" method="POST">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($first_name) ?>" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($last_name) ?>" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($phone) ?>"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea name="address" id="address" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($address) ?></textarea>
            </div>
            
            <div class="mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-300 shadow-md">
                    <i class="fas fa-user-plus mr-2"></i> Register Member
                </button>
            </div>
        </form>
    </div>

</div>

<?php 
require_once APP_ROOT . '/app/views/includes/footer.php';
?>