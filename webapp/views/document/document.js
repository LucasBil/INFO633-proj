const urlParams = new URLSearchParams(window.location.search);
const asset_id = urlParams.get('asset');
const deliverable_id = urlParams.get('deliverable');

const form = document.querySelector("form");
form.addEventListener('submit', e => {
    e.preventDefault();
    const active_data_type = form.querySelector("button[aria-selected='true']");
    const name = form.querySelector('#name');
    const url = form.querySelector('#url');
    const file = form.querySelector('#file_input');

    const formData = new FormData();
    formData.append('name', name.value);
    if (asset_id)
        formData.append('id_asset', asset_id);
    else
        formData.append('id_deliverable', deliverable_id);
    
    if (active_data_type.id.includes('url'))
        formData.append('url', url.value ?? '')
    else
        formData.append('file', file.files[0]);

    api.post('document', formData)
    .then(_document => {
        if (asset_id)
            window.location.href = `/views/asset/asset.php?id=${asset_id}`;
        else
            window.location.href = `/views/deliverable/deliverable.php?id=${deliverable_id}`;
    })
})