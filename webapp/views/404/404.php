<?php
    $title = "404 Not Found";
    include __DIR__ . '/../../templates/header.php';
?>

<main class="grow-1 p-3 px-6 flex flex-col justify-center items-center">
    <div>
        <h2 class="text-4xl font-extrabold dark:text-white">Houston, on a un problème <span class="text-blue-600">404</span> !</h2>
        <p class="my-4 text-lg text-gray-500">
            La page que vous recherchez s'est envolée vers une autre galaxie.
        </p>
        <p class="mb-4 text-lg font-normal text-gray-500 dark:text-gray-400">
            Pas de souci : notre <span class="underline text-blue-500">accueil</span> est un bon point de départ pour repartir à l'aventure !
        </p>
        <a href="/" class="inline-flex items-center text-lg text-blue-600 dark:text-blue-500 hover:underline">
            Home
            <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>