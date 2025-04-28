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
const form = document.querySelector('form');
        form.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent the default form submission

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            fetch('http://localhost:5000/auth/login', {
                method: 'POST',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json', // Important for POST requests
                    'Accept': 'application/json',
                    'Bearer': 'token'
                },
                body: JSON.stringify({ // You need to stringify your body
                        email: email,
                        password: password
                    })
            }).then(response => {
                response.json().then(data => {
                    if (response.ok) {
                        // Handle successful login, e.g., redirect to another page
                        console.log('Login successful:', data);
                        // window.location.href = 'http://localhost:5000/dashboard'; // Redirect to dashboard or home page
                    } else {
                        // Handle error response, e.g., show an error message
                        console.error('Login failed:', data.message);
                        alert('Login failed: ' + data.message); // Show error message to user
                    }
                });
            }).catch(error => {
                console.error('Error:', error);
            });
        });
 
