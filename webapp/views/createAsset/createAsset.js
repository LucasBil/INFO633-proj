const form = document.querySelector("form");

form.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.querySelector("#name");
    const state = document.querySelector("#state");
    const numSerie = document.querySelector("#numSerie");
    api.post('asset', {
        name : name.value,
        state : state.value,
        numSerie : numSerie.value
    }).then(asset => {
        window.location.href = `/views/asset/asset.php?id=${asset['id']}`;
    })
})