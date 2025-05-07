<?php
    $title = "Accueil";
    include __DIR__ . '/../../templates/header.php';
?>

<div class="m-3">
    <p class="text-xl my-3">Projects :</p>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Year</th>
                    <th scope="col" class="px-6 py-3">Duration</th>
                    <th scope="col" class="px-6 py-3">Creator</th>
                    <th scope="col" class="px-6 py-3">
                        <span class="sr-only">View</span>
                    </th>
                </tr>
            </thead>
            <tbody id="projects">
            </tbody>
        </table>
    </div>
</div>

<script src="/views/home/home.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>