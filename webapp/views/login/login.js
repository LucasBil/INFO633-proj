const form = document.querySelector('form');

form.addEventListener('submit', e => {
    e.preventDefault();
    const email = document.querySelector('#email').value;
    const password = document.querySelector('#password').value;

    api.post('login', {
        email : email,
        password : password
    }).then(response => {
        cookieManager.setCookie('token', response['token']);
        cookieManager.setCookie('expr', response['expr']);
    }).then( _ => {
        api.refresh();
        api.get('user')
        .then(user => {
            cookieManager.setCookie('user', JSON.stringify(user));
            window.location.href = '/views/home/home.php';
        })
    });
});