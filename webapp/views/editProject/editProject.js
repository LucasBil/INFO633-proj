const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

const form = document.querySelector("form");

form.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.querySelector('#name');
    const description = document.querySelector('#description');
    const status = document.querySelector('#status');
    const year = document.querySelector('#year');
    const duration = document.querySelector('#duration');
    api.put(`project/${id}`, {
        name : name.value,
        description : description.value,
        status : status.value,
        year : year.value,
        duration : `${duration.value}:00`,
    })
    .then(project => {
        //window.location.href = `/views/project/project.php?id=${project['id']}`;
    });
});

api.get(`project/${id}`)
.then(project => {
    console.log(project);
    const name = document.querySelector('#name');
    const description = document.querySelector('#description');
    const status = document.querySelector('#status');
    const year = document.querySelector('#year');
    const duration = document.querySelector('#duration');

    name.value = project['name'];
    description.value = project['description'];
    status.value = project['status'];
    year.value = project['year'];
    duration.value = project['duration'].split(':')[0];
});