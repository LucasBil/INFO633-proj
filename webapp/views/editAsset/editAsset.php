<?php
    $title = "Edit asset";
    include __DIR__ . '/../../templates/header.php';
?>

<main class="grow-1 p-3 flex flex-col items-center gap-3">
    <form class="w-[90%] md:w-[50%]">
    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asset name</label>
        <input type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Asset name" required />
    </div>

    <div class="mb-5">
      <label for="state" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
        <select id="state" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>
    </div>

    <div class="mb-5">
        <label for="numSerie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Serial number</label>
        <input type="text" id="numSerie" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Asset serial number" required />
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
    </form>

    <!-- Back to page button : project -->
    <a href="/views/asset/asset.php?id=<?= $_GET['id'] ?>" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
        <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent">
        Back to asset
        </span>
    </a>

</main>
<script src="/views/editAsset/editAsset.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>