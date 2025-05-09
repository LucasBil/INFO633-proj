const expr = new Date(parseInt(cookieManager.getCookie('expr'))*1000);
const now = new Date();
if (expr <= now) {
    cookieManager.deleteCookie('token');
    cookieManager.deleteCookie('user');
    cookieManager.deleteCookie('expr');
    window.location.href = '/views/home/home.php';
}