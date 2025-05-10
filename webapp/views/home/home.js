const user = JSON.parse(cookieManager.getCookie('user'));
const tbody = document.querySelector('tbody#projects');

function tagColor(value) {
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
    return color;
}


if (user) {
    let url = 'projects';
    if (user['roles'].length <= 1 && user['roles'].includes('student')) {
        url = `project/user/${user['id']}`;
    }
    api.get(url)
    .then(projects => {
        projects.forEach(project => {
            const color = tagColor(project['status'])
            tbody.insertAdjacentHTML('beforeend',`
                <tr>
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${project['name']}
                        <span class="bg-${color}-100 text-${color}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-${color}-700 dark:text-${color}-300 mx-2">${project['status']}</span>
                    </td>
                    <td>${project['year']}</td>
                    <td>${project['duration']}</td>
                    <td>${project['creator']['first_name']} ${project['creator']['last_name']}</td>
                    <td class="hover:underline">
                        <a href="/views/project/project.php?id=${project['id']}">View</a>
                    </td>
                </tr>
            `);
        });

        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#search-table", {
                    searchable: true,
                    sortable: false
            });
        }
    })
}