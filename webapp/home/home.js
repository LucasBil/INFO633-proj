const ul = document.querySelector('ul#projects');
const api = new API();

api.get('projects')
.then(projects => {
    projects.forEach(project => {
        let li = document.createElement('li');
        let a = document.createElement('a');
        a.innerHTML = `${project.name}`;
        a.href = `../project/project.html?id=${project.id}`;
        li.appendChild(a);
        ul.appendChild(li);
    });
})