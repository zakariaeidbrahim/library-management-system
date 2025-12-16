<?php
// app/views/login.php
// $data array variables (like $error, $username) are available here due to extract()

// Use CDN links for quick setup. In production, download files.
$bootstrap_css = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
$tailwind_css = 'https://cdn.tailwindcss.com'; // Use a compiled version for production!
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Manager Login</title>
    
    <script src="<?= $tailwind_css ?>"></script>
    
    <link href="<?= $bootstrap_css ?>" rel="stylesheet">
    
    <style>
        /* Custom Tailwind/Bootstrap integration style */
        .login-card {
            background-color: #fff; /* White background */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Tailwind shadow-lg */
            border-radius: 0.5rem; /* Tailwind rounded-lg */
            padding: 2rem;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-card">
                <h2 class="text-3xl font-bold text-center mb-4 text-gray-800">Library Manager</h2>
                <p class="text-center text-gray-600 mb-6">Responsible Login</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="<?= URL_ROOT ?>/index.php?url=login" method="POST">                    
                    <div class="mb-3">
                        <label for="username" class="form-label text-gray-700 font-semibold">Username</label>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               class="form-control focus:border-indigo-500 focus:ring-indigo-500" 
                               value="<?= isset($username) ? htmlspecialchars($username) : ''; ?>"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-gray-700 font-semibold">Password</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control focus:border-indigo-500 focus:ring-indigo-500" 
                               required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" 
                                class="btn btn-primary w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md transition duration-300">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>