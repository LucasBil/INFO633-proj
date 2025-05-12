const form = document.querySelector("form");

form.addEventListener('submit', e => {
    e.preventDefault();
    const name = document.querySelector('#name');
    const description = document.querySelector('#description');
    const status = document.querySelector('#status');
    const year = document.querySelector('#year');
    const duration = document.querySelector('#duration');
    api.post('project', {
        name : name.value,
        description : description.value,
        status : status.value,
        year : year.value,
        duration : `${duration.value}:00`,
    })
    .then(project => {
        let promises = []
        const workers = document.querySelectorAll("input[id*='w-checkbox-item-']:checked");
        const assets = document.querySelectorAll("input[id*='a-checkbox-item-']:checked");

        workers.forEach(worker => {
            const userId = worker.id.split('-').pop();
            promise = api.post('work', {
                user_id : userId,
                project_id : project['id']
            });
            promises.push(promise);
        })
        assets.forEach(asset => {
            const asset_id = asset.id.split('-').pop();
            promise = api.post('compose', {
                id_project : project['id'],
                id_asset : asset_id
            });
            promises.push(promise);
        })
        Promise.all(promises)
        .then(_ => {
            window.location.href = `/views/project/project.php?id=${project['id']}`
        })
    })
});

api.get('users')
.then(users => {
    const ul = document.querySelector('#users');
    users.forEach(user => {
        ul.insertAdjacentHTML('beforeend',`
            <li>
                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                    <input id="w-checkbox-item-${user['id']}" type="checkbox" value="${user['first_name']} ${user['last_name']} ${user['email']}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="w-checkbox-item-${user['id']}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">${user['first_name']} ${user['last_name']} (${user['email']})</label>
                </div>
            </li>
        `);
    });
    const search = document.querySelector('#input-group-search-workers');
    const lis = document.querySelectorAll("li:has(> div > input[id*='w-checkbox-item-'])");
    search.addEventListener('input', e => {
        lis.forEach(li => {
            let input = li.querySelector("input[id*='w-checkbox-item-']");
            if (search.value.length == 0 || input.value.includes(search.value))
                li.classList.remove('hidden')
            else
                li.classList.add('hidden')
        })
    });
});


api.get('assets')
.then(assets => {
    const ul = document.querySelector('#assets');
    assets.forEach(asset => {
        ul.insertAdjacentHTML('beforeend',`
            <li>
                <div class="flex items-center p-2 rounded-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                    <input id="a-checkbox-item-${asset['id']}" type="checkbox" value="${asset['name']} ${asset['numSerie']}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                    <label for="a-checkbox-item-${asset['id']}" class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm dark:text-gray-300">${asset['name']} (${asset['numSerie']})</label>
                </div>
            </li>
        `);
    });
    const search = document.querySelector('#input-group-search-assets');
    const lis = document.querySelectorAll("li:has(> div > input[id*='a-checkbox-item-'])");
    search.addEventListener('input', e => {
        lis.forEach(li => {
            let input = li.querySelector("input[id*='a-checkbox-item-']");
            if (search.value.length == 0 || input.value.includes(search.value))
                li.classList.remove('hidden')
            else
                li.classList.add('hidden')
        })
    });
});