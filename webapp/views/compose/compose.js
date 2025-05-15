const urlParams = new URLSearchParams(window.location.search);
const project_id = urlParams.get('project');
const asset_id = urlParams.get('asset');

const form = document.querySelector("form");
form.addEventListener('submit', e => {
    e.preventDefault();

    const condition = document.querySelector("#condition");
    const comment = document.querySelector("#comment");
    api.put(`compose/project/${project_id}/asset/${asset_id}`, {
        condition : condition.value ?? null,
        comment : comment.value ?? ""
    }).then(compose => {
        console.log(compose)
        window.location.href = `/views/asset/asset.php?id=${compose['asset']['id']}`;
    })
})