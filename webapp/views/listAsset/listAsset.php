<?php
    $title = "All assets";
    include __DIR__ . '/../../templates/header.php';

    if (!isset($user))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3 flex flex-col gap-3">
    <table id="search-table">
        <thead>
            <th><span class="flex items-center">Name</span></th>
            <th><span class="flex items-center">State</span></th>
            <th><span class="flex items-center">Serial number</span></th>
            <th><span class="flex items-center">Action</span></th>
        </thead>
        <tbody id="assets"></tbody>
    </table>
</main>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="/views/listAsset/listAsset.js"></script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>