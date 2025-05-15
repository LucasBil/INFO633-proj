<?php
    $title = "Project";
    include __DIR__ . '/../../templates/header.php';

    if (!isset($user) || !isset($_GET['id']))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3 flex gap-3">
    <div class="grow-1 flex flex-col gap-3">
        <div class="flex">
            <div class="grow-1 flex flex-col gap-3">
                <h1 class="text-xl">Project : <span class="underline" id="name"></span><span id="tag"></span></h1>
                <p>Creator : <span id="creator"></span></p>
                <div id="description"></div>
            </div>
            <div class="flex flex-col gap-2 block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm" id="users"></div>
        </div>

        <hr class="w-48 h-1 mx-auto my-2 bg-gray-100 border-0 rounded-sm">
        
        <table id="search-table">
            <thead>
                <tr>
                    <th><span class="flex items-center">Name</span></th>
                    <th><span class="flex items-center">NumSerie</span></th>
                    <th><span class="flex items-center">Actions</span></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="mx-4 w-[15vw]">
        <ol class="relative text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400" id="deliverables"></ol>
    </div>

    <?php if (roleisGranded($user, ['teacher', 'admin'])) { ?>
        <div class="fixed bottom-[175px] right-0 z-10">
            <a href="/views/createDeliverable/createDeliverable.php" class="flex items-center gap-3 rounded-l-lg focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium text-sm px-3 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900 group">
                <svg class="flex-shrink-0" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="18px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                </svg>
                <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 whitespace-nowrap">
                    Create deliverable
                </span>
            </a>
        </div>

        <div class="fixed bottom-[125px] right-0 z-10">
            <a href="/views/editProject/editProject.php?id=<?= $_GET['id'] ?>" class="flex items-center gap-3 rounded-l-lg focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-3 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 group">
                <svg class="flex-shrink-0" data-slot="icon" fill="currentColor" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"  width="18px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008Z"></path>
                </svg>
                <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 whitespace-nowrap">
                    Edit project
                </span>
            </a>
        </div>
    <?php } ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="/views/project/project.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>