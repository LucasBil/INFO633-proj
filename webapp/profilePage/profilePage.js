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


const API_BASE = 'http://localhost:5000/user/';
const token    = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0NjE3NjczNywianRpIjoiMDgwMDJjZmMtNzYzNS00YWQ4LWIxMGEtMTg2OWY0OGNkZjFjIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6InRlc3RAZ21haWwuY29tIiwibmJmIjoxNzQ2MTc2NzM3LCJjc3JmIjoiMmQzZjczMDYtYTExMi00OWI0LWFhOWYtNTgwMjk3MzI3OeyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0NjE3NjczNywianRpIjoiMDgwMDJjZmMtNzYzNS00YWQ4LWIxMGEtMTg2OWY0OGNkZjFjIiwidHlwZSI6ImFjY2VzcyIsInN1YiI6InRlc3RAZ21haWwuY29tIiwibmJmIjoxNzQ2MTc2NzM3LCJjc3JmIjoiMmQzZjczMDYtYTExMi00OWI0LWFhOWYtNTgwMjk3MzI3OTQxIiwiZXhwIjoxNzQ2MTgwMzM3LCJpZCI6MTQsInJvbGVzIjpbInN0dWRlbnQiXX0.Sn65eBeZgbzKPTqSkVUEbezKNiZZwmIgaAMqhm8MHRwTQxIiwiZXhwIjoxNzQ2MTgwMzM3LCJpZCI6MTQsInJvbGVzIjpbInN0dWRlbnQiXX0.Sn65eBeZgbzKPTqSkVUEbezKNiZZwmIgaAMqhm8MHRw";

if (!token) {
  alert('Non autorisé. Veuillez vous connecter.');
  window.location.href = '/auth/login';
}

// -- UPDATE PROFILE --
document.getElementById('update-form').addEventListener('submit', async e => {
  e.preventDefault();

  const email      = document.getElementById('email').value.trim();
  axios.get(`http://localhost:5000/user/?email=${email}`, {
      headers: { Authorization: `Bearer ${token}` }
  })
  .then(response => {
    let userId = response.data[0].id;
    console.log("1");
    //const first_name = document.getElementById('first_name').value.trim();
    console.log("2");
    //const last_name  = document.getElementById('last_name').value.trim();
    console.log("3");
    //const password   = document.getElementById('password').value.trim();
    console.log("4");
    // if (!email || !password) {
    //   alert("Veuillez saisir l'email et le mot de passe pour sauvegarder.");
    //   return;
    // }
    // axios.put(`${API_BASE}${userId}`, {
    //     email: email,
    //     password: password,
    //     first_name: first_name,
    //     last_name: last_name
    // }, {
    //     headers: { Authorization: `Bearer ${token}` }
    // })
    // .then(response => {
    //     alert("Profil mis à jour avec succès.");
    //     window.location.href = "/auth/login"; // Redirect to login page after update
    // })
    // .catch(error => {
    //     console.error('Error updating profile:', error.response);
    //     alert("Erreur lors de la mise à jour du profil.");
    // });
  })
  .catch(error => {
      console.error('Error fetching user data:', error.response);
  });
});
// -- DELETE ACCOUNT --
// document.getElementById('delete-btn').addEventListener('click', async () => {
//   const email    = document.getElementById('email').value.trim();
//   const password = document.getElementById('password').value.trim();

//   if (!email || !password) {
//     alert("Veuillez saisir l'email et le mot de passe pour supprimer le compte.");
//     return;
//   }

//   if (!confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) return;

//   try {
//     const response = await axios.delete(`${API_BASE}${userId}`, {
//       headers: { Authorization: `Bearer ${token}` },
//       data: { email, password }
//     });

//     alert("Compte supprimé avec succès.");
//     localStorage.clear();
//     window.location.href = "/auth/login";
//   } catch (err) {
//     console.error(err.response?.data || err);
//     alert(err.response?.data?.message || "Erreur lors de la suppression du compte.");
//   }
// });
