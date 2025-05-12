const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

api.get(`asset/${id}`)
.then(asset => {
    console.log(asset);
})