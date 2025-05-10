const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');


api.get(`deliverable/${id}`)
.then(deliverable => {
    let name = document.querySelector("#name");
    let date_creation = document.querySelector("#date_creation");
    let date_closure = document.querySelector("#date_closure");
    let description = document.querySelector("#description");

    name.textContent = deliverable['name'];
    date_creation.textContent = deliverable['date_creation'];
    date_closure.textContent = deliverable['date_closure'];
    description.innerHTML = marked.parse(deliverable['description'] ?? '');
})
.catch( _  => {
    window.location.href = '/views/404/404.php';
})
.then( _ => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const name = form.querySelector('#name-ressource');
            const url = form.querySelector('#url')
            const file = form.querySelector('#file_input')

            const formData = new FormData();
            formData.append('id_deliverable', id);
            formData.append('name', name.value);
            if (url)
                formData.append('url', url.value ?? '')
            if (file)
                formData.append('file', file.files[0]);
            api.post('document', formData)
            .then(_document => {
                window.location.reload();
            })
        });
    })

    const downloads = document.querySelector('#downloads');
    api.get(`document/download/deliverable/${id}`)
    .then(zip => {
        const blob = new Blob([zip]);
        const url = URL.createObjectURL(blob);
        downloads.href = url;
        downloads.download = `deliverable.zip`;
    });

    api.get(`document/deliverable/${id}`)
    .then(documents => {
        const tbody = document.querySelector("#documents");
        documents.forEach(_document => {
            createDocumentLine(_document['id'], _document['name'], _document['file_type'], _document['date_deposition'],  `${_document['user']['first_name']} ${_document['user']['last_name']}`, _document['data'], tbody)
            const DOM_download = document.querySelector(`a[id*='download_${_document['id']}']`);
            const DOM_delete = document.querySelector(`a[id*='delete_${_document['id']}']`);
            if (DOM_download)
                download(DOM_download, _document);
            _delete(DOM_delete, _document);
        });
    })
});

function createDocumentLine(id, filename, type, date, user, data, parent) {
    return parent.insertAdjacentHTML('beforeend',`
        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                ${type == null ?
                    `<a href="${data}" target="_blank" class="underline">${filename}</a>` :
                    `${filename}`
                }
            </th>
            <td class="px-6 py-4">
                ${type ?? ''}
            </td>
            <td class="px-6 py-4">
                ${date}
            </td>
            <td class="px-6 py-4">
                ${user}
            </td>
            <td class="px-6 py-4 flex justify-between">
                ${type != null ?
                    `<a id="download_${id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Download</a>` :
                    ``
                }
                <a id="delete_${id}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
            </td>
        </tr>
    `);
}

function download(a, _document) {
    const id = a.id.split('_')[1];
    api.get(`document/download/${id}`)
    .then(response => {
        const data = JSON.stringify(response, null, 2);
        const blob = new Blob([data], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        a.href = url;
        a.download = `${_document['name']}.${_document['file_type']}`;
    })
}

function _delete(a, _document) {
    a.addEventListener('click', e => {
        const id = a.id.split('_')[1];
        api.delete(`document/${id}`)
        .then(response => {
            window.location.reload();
        })
    })
}