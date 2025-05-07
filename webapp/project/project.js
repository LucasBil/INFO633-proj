const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');
const api = new API();

api.get(`project/${id}`)
.then(project => {
    Object.keys(project).forEach(key => {
        let e = document.querySelector(`#${key}`);
        if (e) {
            e.innerHTML = `${project[key]}`;
        }
        if (key == 'creator') {
            let detail = document.querySelector('#detail');
            let a = document.createElement('a');
            a.href = ''

        }
    })
    console.log(project);
})