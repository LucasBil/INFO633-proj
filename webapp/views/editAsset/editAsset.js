const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

const form = document.querySelector("form");

form.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.querySelector('#name');
    const state = document.querySelector('#state');
    const numSerie = document.querySelector('#numSerie');

    api.put(`asset/${id}`, {
        name : name.value,
        state : state.value,
        numSerie : numSerie.value,
    })
    .then(asset => {
        window.location.href = `/views/asset/asset.php?id=${asset['id']}`;
    });
});

api.get(`asset/${id}`)
.then(asset => {
    console.log(asset);
    const name = document.querySelector('#name');
    const state = document.querySelector('#state');
    const numSerie = document.querySelector('#numSerie');

    name.value = asset['name'];
    state.value = asset['state'];
    numSerie.value = asset['numSerie'];
}
);
