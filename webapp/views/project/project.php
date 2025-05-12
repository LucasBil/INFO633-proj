<?php
    $title = "Project";
    include __DIR__ . '/../../templates/header.php';

    if (!isset($user) || !isset($_GET['id']))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3 flex flex-col gap-3">
    <div class="flex gap-2">
        <div class="flex flex-col gap-3 grow-1">
            <h1 class="text-xl">Project : <span class="underline" id="name"></span><span id="tag"></span></h1>
            <p>Creator : <span id="creator"></span></p>
            <div id="description"></div>
        </div>
        <div>
            <?php if (roleisGranded($user, ['teacher', 'admin'])) { ?>
                <!-- EDIT BUTTON 2 -->
                <a href="/views/editProject/editProject.php?id=<?= $_GET['id'] ?>" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent">
                    Edit Project
                    </span>
                </a>
            <?php } ?>
        </div>
    </div>
    
    
    <div id="accordion-collapse" data-accordion="collapse">
        <!-- ACCORDION 1 : Deliverable -->
        <h2 id="accordion-collapse-heading-1">
            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
            <span>Deliverable</span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
            <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                <ol class="relative text-gray-500 border-s border-gray-200 dark:border-gray-700 dark:text-gray-400" id="deliverables">                  </ol>
            </div>
        </div>
        <!-- ACCORDION 2 : Asset -->
        <h2 id="accordion-collapse-heading-2">
            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-collapse-body-3" aria-expanded="false" aria-controls="accordion-collapse-body-3">
            <span>Assets</span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-collapse-body-3" class="hidden" aria-labelledby="accordion-collapse-heading-2">
            <div class="p-5 border border-t-0 border-gray-200 dark:border-gray-700 flex flex-wrap gap-3" id="assets">
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="/views/project/project.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>