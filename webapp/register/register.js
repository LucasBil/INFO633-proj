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
            const first_name = document.getElementById('first_name').value;
            const last_name = document.getElementById('last_name').value;

            fetch('http://localhost:5000/user', {
                method: 'POST',
                mode: 'cors',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                        email: email,
                        password: password,
                        first_name: first_name,
                        last_name: last_name
                    })
            }).then(response => {
                response.json().then(data => {
                    if (response.ok) {
                        // Handle successful login, e.g., redirect to another page
                        console.log('Account created:', data);
                        // window.location.href = 'http://localhost:5000/dashboard'; // Redirect to dashboard or home page
                    } else {
                        // Handle error response, e.g., show an error message
                        console.error('Failed to create an account:', data.message);
                        alert('Failed to create an account: ' + data.message); // Show error message to user
                    }
                });
            }).catch(error => {
                console.error('Error:', error);
            });
        });
 
