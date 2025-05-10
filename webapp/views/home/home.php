<?php
    $title = "Accueil";
    include __DIR__ . '/../../templates/header.php';
?>

<?php if (isset($user)) { ?>
    <main class="grow-1 p-3 flex flex-col gap-2">
        <table id="search-table">
            <thead>
                <tr>
                    <th><span class="flex items-center">Name</span></th>
                    <th><span class="flex items-center">Year</span></th>
                    <th><span class="flex items-center">Duration</span></th>
                    <th><span class="flex items-center">Creator</span></th>
                    <th><span class="flex items-center">Action</span></th>
                </tr>
            </thead>
            <tbody id="projects"></tbody>
        </table>
    </main>
<?php } else { ?>
    <main class="grow-1 flex flex-col justify-center p-3">
        <div>
            <h1 class="mb-4 text-4xl sm:px-16 xl:px-48 font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">Votre plateforme collaborative de projets pédagogiques</h1>
            <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">
                Un espace dédié où les élèves peuvent soumettre leurs travaux, 
                les enseignants évaluer les productions, 
                et les techniciens accompagner la concrétisation des idées.
                Ensemble, donnons vie aux projets éducatifs de demain.
            </p>
        </div>
        <div class="flex gap-3 sm:px-16 xl:px-48">
            <a href="/views/login/login.php" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                Login
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
            <a href="/views/register/register.php" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                Register
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>
    </main>
<?php } ?>

<script src="/views/home/home.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>