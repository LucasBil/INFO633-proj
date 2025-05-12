<?php
    $title = "Deliverable";
    include __DIR__ . '/../../templates/header.php';
    if (!isset($user) || !isset($_GET['id']))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3">
    <div>
        <div>
            <h1 class="text-xl">Project : <span class="underline" id="name"></span><span id="tag"></span></h1>
            <p>NumSérie : <span id="numSerie"></span></p>
        </div>
        <div>
            <?php if (roleisGranded($user, ['technician'])) { ?>
                <!-- EDIT BUTTON 2 -->
                <a href="/views/editAsset/editAsset.php?id=<?= $_GET['id'] ?>" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent">
                    Edit Asset
                    </span>
                </a>
            <?php } ?>
        </div>
    </div>   
</main>

<script src="/views/asset/asset.js"></script>
<?php include __DIR__ . '/../../templates/footer.php'; ?>