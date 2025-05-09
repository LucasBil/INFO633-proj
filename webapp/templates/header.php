<?php
    $user = null;
    if (isset($_COOKIE['user']))
        $user = json_decode($_COOKIE['user'],true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title><?= $title ?? 'Unknown' ?></title>
</head>
<body class="min-h-screen flex flex-col">
    <header>
        <nav class="bg-white border-gray-200 dark:bg-gray-900">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="/assets/Logo_Polytech_Annecy_Chambery.svg" class="h-8" alt="Flowbite Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">INFO633</span>
                </a>
                <div id="links" class="flex items-center space-x-6 rtl:space-x-reverse">
                    <?php if (!isset($user)) { ?>
                        <a href="/views/login/login.php" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">Login</a>
                        <a href="/views/register/register.php" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">Register</a>
                    <?php } else { ?>
                        <?php foreach ($user['roles'] as $role) { ?>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300"><?= ucfirst($role) ?></span>
                        <?php } ?>
                        <a href="/views/account/account.php" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline"><?= ucfirst($user['first_name']) . ' ' . strtoupper($user['last_name']) ?></a>
                        <a href="/views/logout/logout.php" class="text-sm  text-blue-600 dark:text-blue-500 hover:underline">Logout</a>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </header>
    <!--#region script utils -->
    <script src="/utils/cookies.js"></script>
    <script src="/utils/api.js"></script>
    <script src="/templates/header.js"></script>
    <?php if (isset($user)) { ?>
        <script src="/templates/logout.js"></script>
    <?php } ?>
    <!--#endregion -->