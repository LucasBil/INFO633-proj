<?php
    $title = "Deliverable";
    include __DIR__ . '/../../templates/header.php';
    if (!isset($user) || !isset($_GET['id']))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 flex gap-3 p-3">
    <div class="grow-1">
        <div class="my-3">
            <h1 class="text-xl">Project : <span class="underline" id="name"></span><span id="tag"></span></h1>
            <p>NumSérie : <span id="numSerie"></span></p>
        </div>
        
        <table id="filter-table">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            Filename
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Type
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Date
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            User
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Action
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="flex gap-2 justify-between">
                        <?php if (roleisGranded($user, ['technician', 'admin'])) { ?>
                            <a class="grow-1 flex justify-center" href="/views/document/document.php?asset=<?= $_GET['id'] ?>">
                                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                            </a>
                            <span>|</span>
                        <?php } ?>
                        <a class="grow-1 flex justify-center" href="" id="downloads">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="w-[50vw]">
        <ol class="relative border-s border-gray-200" id="composes"></ol>
    </div>

    <?php if (roleisGranded($user, ['technician', 'admin'])) { ?>
        <div class="fixed bottom-[125px] right-0 z-10">
            <a href="/views/editAsset/editAsset.php?id=<?= $_GET['id'] ?>" class="flex items-center gap-3 rounded-l-lg focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-3 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 group">
                <svg class="flex-shrink-0" data-slot="icon" fill="currentColor" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"  width="18px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 0 1-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 1 1-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 0 1 6.336-4.486l-3.276 3.276a3.004 3.004 0 0 0 2.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852Z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008Z"></path>
                </svg>
                <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 whitespace-nowrap">
                    Edit asset
                </span>
            </a>
        </div>
    <?php } ?>

</main>

<script src="/views/asset/asset.js"></script>
<?php include __DIR__ . '/../../templates/footer.php'; ?>