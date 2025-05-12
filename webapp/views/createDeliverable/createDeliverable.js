const form = document.querySelector("form");

form.addEventListener('submit', e => {
    e.preventDefault();
    let promises = [];
    const name = document.querySelector('#name');
    const description = document.querySelector('#description');
    const creation = document.querySelector('#creation');
    const closure = document.querySelector('#closure');
    const closure_time = document.querySelector('#time');
    const projects = document.querySelectorAll("input[id*='checkbox-item-']:checked");
    projects.forEach(project => {
        const projectId = project.id.split('-').pop();
        promise = api.post('deliverable', {
            name : name.value,
            description : description.value,
            date_creation : formatDateTime(`${creation.value} 00:00:00`),
            id_project : projectId,
            date_closure : closure.value ? formatDateTime(`${closure.value} ${closure_time.value}`) : null
        })
        promises.push(promise);
    });
    Promise.all(promises)
    .then( _ => {
        window.location.href = '/views/home/home.php';
    })
})

api.get('projects')
.then(projects => {
    const ul = document.querySelector('#projects');
    projects.forEach(project => {
        ul.insertAdjacentHTML('beforeend',`
            <li>
                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                    <input id="checkbox-item-${project['id']}" type="checkbox" value="${project['name']}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="checkbox-item-${project['id']}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">${project['name']}</label>
                </div>
            </li>
        `);
    });
    const search = document.querySelector('#input-group-search');
    const lis = document.querySelectorAll("li:has(> div > input[id*='checkbox-item-'])");
    search.addEventListener('input', e => {
        lis.forEach(li => {
            let input = li.querySelector("input[id*='checkbox-item-']");
            if (search.value.length == 0 || input.value.includes(search.value))
                li.classList.remove('hidden')
            else
                li.classList.add('hidden')
        })
    });
});

function formatDateTime(inputDateTime) {
  const [datePart, timePart] = inputDateTime.split(' '); // Split date & time
  const [month, day, year] = datePart.split('/'); // Split MM/DD/YYYY
  return `${year}-${month}-${day} ${timePart}`;
}