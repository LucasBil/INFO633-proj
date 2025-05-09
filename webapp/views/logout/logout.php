<?php
    $title = "Logout";
    include __DIR__ . '/../../templates/header.php';
?>

<main class="grow-1"></main>

<script>
    cookieManager.deleteCookie('token');
    cookieManager.deleteCookie('user');
    window.location.href = '/views/home/home.php';
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>