const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

api.get(`project/${id}`)
.then(project => {
    let name = document .querySelector("#name");
    let creator = document .querySelector("#creator");
    let description = document .querySelector("#description");
    let tag = document .querySelector("#tag");
    let color = null;
    switch (project['status']) {
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

    name.textContent = project['name'];
    creator.textContent = `${project['creator']['first_name']} ${project['creator']['last_name']}`;
    description.innerHTML = marked.parse(project['description'] ?? '');
    tag.textContent = project['status'];
    tag.className = `bg-${color}-100 text-${color}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-${color}-700 dark:text-${color}-300 mx-2`;
});

api.get(`deliverable/project/${id}`)
.then(deliverables => {
    const ol = document.querySelector('#deliverables');
    deliverables.sort((a, b) => {
        return b["date_closure"] ? new Date(b["date_closure"]) - new Date(a["date_closure"]) : 1;
    });
    deliverables.forEach(deliverable => {
        createProgressDeliverable(deliverable['id'], deliverable['name'], deliverable['description'], deliverable['date_closure'], ol);
    });
})

api.get(`work/project/${id}`)
.then(works => {
    const users = document.querySelector("#users")
    works.forEach(work => {
        user = work['user'];
        shortname = user['first_name'].substring(0,1).toUpperCase() + user['last_name'].substring(0,1).toUpperCase();
        users.insertAdjacentHTML('beforeend',`
            <div class="flex items-center gap-4">
                <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                    <span class="font-medium text-gray-600 dark:text-gray-300">${shortname}</span>
                </div>
                <div class="font-medium dark:text-white">
                    <div>${user['first_name']} ${user['last_name']}</div>
                </div>
            </div>
        `);
    });
})

api.get(`compose/project/${id}`)
.then(composes => {
    const tbody = document.querySelector('tbody')
    composes.forEach(compose => {
        const asset = compose['asset'];
        tbody.insertAdjacentHTML('beforeend',`
            <tr>
                <td>${asset['name']}</td>
                <td>${asset['numSerie']}</td>
                <td><a href="/views/asset/asset.php?id=${asset['id']}">Views</a></td>
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

function createProgressDeliverable(id, title, description, timeline, parent) {
    const end = timeline ? new Date(timeline) : null;
    const now = new Date();
    let _description = marked.parse(description ?? 'No description');

    let svg = end < now && end ?
    `<span class="absolute flex items-center justify-center w-8 h-8 bg-green-200 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-green-900">
        <svg class="w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
        </svg>
    </span>` :
    `<span class="absolute flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-gray-700">
        <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
            <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
        </svg>
    </span>`;
    parent.insertAdjacentHTML('beforeend',`
        <li class="mb-10 ms-6">            
            ${svg}
            <h3 class="font-medium leading-tight underline">
                <a href="/views/deliverable/deliverable.php?id=${id}">${title} ${timeline ? `(${timeline})` : '(unlimited)'}</a>
            </h3>
            <p class="text-sm">${_description}</p>
        </li>
    `);   
}