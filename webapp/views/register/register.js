const form = document.querySelector('form');

form.addEventListener('submit', e => {
    e.preventDefault();
    const email = document.querySelector('#email').value;
    const first_name = document.querySelector('#first_name').value;
    const last_name = document.querySelector('#last_name').value;
    const password = document.querySelector('#password').value;

    api.post('user', {
        email : email,
        first_name : first_name,
        last_name : last_name,
        password : password
    }).then(user => {
        window.location.href = '/views/login/login.php';
    });
});