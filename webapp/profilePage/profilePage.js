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


// TOKEN AUTHENTICATION
const API_BASE = 'http://127.0.0.1:5000/user/';
const token    = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJmcmVzaCI6ZmFsc2UsImlhdCI6MTc0NjM0NjM2NywianRpIjoiZmE0YjViNGEtZjEwMy00YTA1LTkzMDYtMTc3MWM4MGMwMjI1IiwidHlwZSI6ImFjY2VzcyIsInN1YiI6InRlc3RAZ21haWwuY29tIiwibmJmIjoxNzQ2MzQ2MzY3LCJjc3JmIjoiY2ZhY2NlZTMtZTcxNi00NGY4LWE0ZjMtYTA2MmVjMTc0ZjY4IiwiZXhwIjoxNzQ2MzQ5OTY3LCJpZCI6MTQsInJvbGVzIjpbInN0dWRlbnQiXX0.qaNmRfzlyjvq595-PpNaIeXCg8JasIzYuJFbMibzl4o";

// Charger les informations de l'utilisateur au démarrage
window.addEventListener('DOMContentLoaded', loadProfile);

async function loadProfile() {
  const token = localStorage.getItem('jwt_token');
  if (!token) return;

  try {
    // On récupère l'email à partir du token décodé (ou le stocker dans localStorage si pas possible)
    const tokenPayload = JSON.parse(atob(token.split('.')[1]));
    const email = tokenPayload.sub; // ou tokenPayload.email selon ton payload

    const res = await axios.get(`${API_BASE}?email=${email}`, {
      headers: { Authorization: `Bearer ${token}` }
    });

    const user = res.data[0];
    if (!user) return;

    // Remplir les champs
    document.getElementById('email').value = user.email || '';
    document.getElementById('first_name').value = user.first_name || '';
    document.getElementById('last_name').value = user.last_name || '';

  } catch (err) {
    console.error("Erreur de chargement du profil :", err.response?.data || err);
    alert("Impossible de charger le profil.");
  }
}

// Vérifier si l'utilisateur est connecté
if (!token) {
  alert('Non autorisé. Veuillez vous connecter.');
  window.location.href = '/auth/login';
}

// -- UPDATE PROFILE --
document.getElementById('update-form').addEventListener('submit', (e) => {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const first_name = document.getElementById('first_name').value.trim();
  const last_name = document.getElementById('last_name').value.trim();
  const password = document.getElementById('password').value.trim();

  if (!email || !password) {
    alert("Please enter both email and password to save.");
    return;
  }

  // 1. Fetch the user ID
  axios.get(`http://127.0.0.1:5000/user/?email=${email}`, {
    headers: { Authorization: `Bearer ${token}`},
  })
  .then((userResponse) => {
    if (!userResponse.data || userResponse.data.length === 0) {
      throw new Error("User not found.");
    }

    const userId = userResponse.data[0].id;
    console.log("User ID:", userId);

    // 2. PUT request
    return axios.put(
      `${API_BASE}${userId}`,
      { email, password, first_name, last_name },
      { headers: { Authorization: `Bearer ${token}` } }
    );
  })
  .then(() => {
    alert("Profile updated successfully!");
  })
  .catch((error) => {
    console.error("Update error:", error.response || error.message);
    alert(`Error: ${error.response?.data?.message || error.message}`);
  });
});


//-- DELETE ACCOUNT --
document.getElementById('delete-btn').addEventListener('click', async () => {
  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();

  if (!email || !password) {
    alert("Veuillez saisir l'email et le mot de passe pour supprimer le compte.");
    return;
  }

  if (!confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) return;

  try {
    const response = await axios.delete(`${API_BASE}${userId}`, {
      headers: { Authorization: `Bearer ${token}` },
      data: { email, password }
    });

    alert("Compte supprimé avec succès.");
    localStorage.clear();
    window.location.href = "/auth/login";
  } catch (err) {
    console.error(err.response?.data || err);
    alert(err.response?.data?.message || "Erreur lors de la suppression du compte.");
  }
});
