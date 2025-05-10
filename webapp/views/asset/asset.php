<?php
    $title = "Deliverable";
    include __DIR__ . '/../../templates/header.php';
    if (!isset($user) || !isset($_GET['id']))
        header("Location: /views/home/home.php");
?>

<main class="grow-1 p-3">
</main>

<script src="/views/asset/asset.js"></script>
<?php include __DIR__ . '/../../templates/footer.php'; ?>