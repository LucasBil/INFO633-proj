<?php
    $user = null;
    if (isset($_COOKIE['user']))
        $user = json_decode($_COOKIE['user'],true);

    function roleisGranded($user, array $roles) : bool {
        foreach ($roles as $role) {
            if (in_array($role, $user['roles']))
                return true;
        }
        return false;
    }
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

    <?php if (isset($user) && roleisGranded($user, ['teacher', 'technician', 'admin'])) { ?>
        <!-- drawer init and toggle -->
        <div class="fixed bottom-[75px] right-0 z-10">
            <button type="button" class="flex items-center gap-3 rounded-l-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium text-sm px-3 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 group"
                data-drawer-target="drawer-disable-body-scrolling" 
                data-drawer-show="drawer-disable-body-scrolling" 
                data-drawer-body-scrolling="false" 
                aria-controls="drawer-disable-body-scrolling">
                <svg class="flex-shrink-0" data-slot="icon" fill="currentColor" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="18px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"></path>
                </svg>
                <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 whitespace-nowrap">
                    Special Action
                </span>
            </button>
        </div>

        <!-- drawer component -->
        <div id="drawer-disable-body-scrolling" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-64 dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-disable-body-scrolling-label">
            <h5 id="drawer-disable-body-scrolling-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">Action</h5>
            <button type="button" data-drawer-hide="drawer-disable-body-scrolling" aria-controls="drawer-disable-body-scrolling" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        <div class="py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <!-- ACTION : ADMIN -->
                 <?php if (roleisGranded($user, ['admin'])) { ?>
                    <li class="flex flex-col gap-1 p-2 text-gray-900">
                        <div class="flex items-center">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Admin</span> 
                        </div>
                        <ul class="pl-5 text-blue-600">
                            <li>
                                <a href="#" class="flex items-center p-2 rounded-lg group"> 
                                    Manage Users
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <!-- ACTION : TEACHER -->
                <?php if (roleisGranded($user, ['teacher', 'admin'])) { ?>
                    <li class="flex flex-col gap-1 p-2 text-gray-900">
                        <div class="flex items-center">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Teacher</span> 
                        </div>
                        <ul class="pl-5 text-blue-600">
                            <li>
                                <a href="/views/createProject/createProject.php" class="flex items-center p-2 rounded-lg group"> 
                                    Create Project
                                </a>
                            </li>
                            <li>
                                <a href="/views/createDeliverable/createDeliverable.php" class="flex items-center p-2 rounded-lg group"> 
                                    Create Deliverable
                                </a>
                            </li>
                            <li>
                                <a href="/views/updateProject/updateProject.php" class="flex items-center p-2 rounded-lg group"> 
                                    Update Project
                                </a>
                            </li>
                            <li>
                                <a href="/views/updateDeliverable/updateDeliverable.php" class="flex items-center p-2 rounded-lg group"> 
                                    Update Deliverable
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <!-- ACTION : TECHNICIAN -->
                <?php if (roleisGranded($user, ['technician', 'admin'])) { ?>
                    <li class="flex flex-col gap-1 p-2 text-gray-900">
                        <div class="flex items-center">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495"></path>
                            </svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Tehcnician</span> 
                        </div>
                        <ul class="pl-5 text-blue-600">
                            <li>
                                <a href="/views/createAsset/createAsset.php" class="flex items-center p-2 rounded-lg group"> 
                                    Create Asset
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
        </div>
    <?php } ?>


    <!--#region script utils -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script src="/utils/cookies.js"></script>
    <script src="/utils/api.js"></script>
    <script src="/templates/header.js"></script>
    <?php if (isset($user)) { ?>
        <script src="/templates/logout.js"></script>
    <?php } ?>
    <!--#endregion -->