// Show password toggle functionality
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");

togglePassword.addEventListener("click", function () {
    // Toggle the type attribute
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);

    // Toggle the eye icon
    this.querySelector('i').classList.toggle('fa-eye');
    this.querySelector('i').classList.toggle('fa-eye-slash');
});


// Form
const api = new API();
const cookiesManager = new CookiesManager();

const form = document.querySelector('form');
form.addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent the default form submission
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    api.post('login', {
        email: email,
        password: password
    }).then(response => {
        cookiesManager.setCookie('token', response['token']);
        window.location.href = "../home/home.html";
    })
});
 
