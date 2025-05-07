const user = JSON.parse(cookieManager.getCookie('user'));
const tbody = document.querySelector('tbody#projects');

function createTH(value, parent) {
    let th = document.createElement('th');
    th.scope = "row"
    th.className = "px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white";
    th.textContent = value;
    parent.appendChild(th)
    return th;
}

function createTag(value, parent) {
    let span = document.createElement('span');
    let color = null;
    switch (value) {
        case 'in_progress':
            color = 'blue';
            break;
        case 'completed':
            color = 'green';
            break;
        case 'dismantled':
            color = 'red';
            break;
        default:
            color = 'gray';
    }
    span.className = `bg-${color}-100 text-${color}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-${color}-700 dark:text-${color}-300 mx-2`;
    span.textContent = value;
    parent.appendChild(span);
}


if (user) {
    let url = 'works';
    if (user['roles'].length <= 1 && user['roles'].includes('student')) {
        url = `work/user/${user['id']}`;
    }
    api.get(url)
    .then(works => {
        works.forEach(work => {
            const project = work['project'];
            let tr = document.createElement('tr');
            tr.className = "bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600";
            let name = createTH(project['name'], tr);
            createTag(project['status'], name);
            createTH(project['year'], tr);
            createTH(project['duration'], tr);
            createTH(`${project['creator']['first_name']} ${project['creator']['last_name']}`, tr);
            tbody.appendChild(tr);
    
            let td = document.createElement('td');
            td.className = "px-6 py-4 text-right";
            let a = document.createElement('a');
            a.className = "font-medium text-blue-600 dark:text-blue-500 hover:underline";
            a.href = `/views/project/project.php?id=${project['id']}`
            a.textContent = "View"
            td.appendChild(a)
            tr.appendChild(td)
        });
    })
}