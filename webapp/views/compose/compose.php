<?php
    $title = "Compose";
    include __DIR__ . '/../../templates/header.php';
    if (!isset($user))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3 flex flex-col items-center gap-3">
    <form class="w-[90%] md:w-[50%] flex flex-col gap-3">
        <div>
            <label for="condition" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an condition</label>
            <select id="condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                <option value="" selected>Not defined</option>
                <option value="nine">Nine</option>
                <option value="dommage">Dommage</option>
                <option value="broken">Broken</option>
            </select>
        </div>
        <div>
            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your comment</label>
            <textarea id="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </div>
    </form>

    <div class="fixed bottom-[125px] right-0 z-10">
        <a href="/views/asset/asset.php?id=<?= $_GET['asset'] ?>" class="flex items-center gap-3 rounded-l-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium text-sm px-3 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 group">
            <svg class="flex-shrink-0" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="18px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"></path>
            </svg>
            <span class="max-w-0 overflow-hidden group-hover:max-w-xs transition-all duration-300 whitespace-nowrap">
                Back to asset
            </span>
        </a>
    </div>
</main>

<script src="/views/compose/compose.js"></script>
<?php include __DIR__ . '/../../templates/footer.php'; ?>