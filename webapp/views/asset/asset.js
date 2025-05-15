const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

api.get(`asset/${id}`)
.then(asset => {
    const name = document.querySelector('#name');
    const numSerie = document.querySelector('#numSerie');

    name.textContent = asset['name'];
    numSerie.textContent = asset['numSerie'];
});

api.get(`compose/asset/${id}`)
.then(composes => {
    const ol = document.querySelector('#composes');
    composes.sort((a, b) => {
        return b["project"]["year"] - a["project"]["year"];
    });
    composes.forEach(compose => {
        let project = compose['project'];
        let asset = compose['asset'];
        let action = roleGranted(['admin', 'technician']) ? `
            <a href="/views/compose/compose.php?project=${project['id']}&asset=${asset['id']}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                Edit
                <svg class="w-3 h-3 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        ` : ''
        ol.insertAdjacentHTML('afterbegin', `
            <li class="mb-10 ms-4">
                <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">${project["year"]}</time>
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <a href="/views/project/project.php?id=${project['id']}">${project["name"]}</a>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">${compose["condition"] ?? 'Not define'}</span>
                </h3>
                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">${compose["comment"] ?? 'No comment'}</p>
                ${action}
            </li>
        `);
    });
});

api.get(`document/asset/${id}`)
.then(documents => {
    const tbody = document.querySelector("tbody");
    let promises = [];
    documents.forEach(_document => {
        let promise = api.get(`document/download/${id}`)
        .then(blob => {
            const url = window.URL.createObjectURL(new Blob([blob], {type: "application/octet-stream"}));

            let action = roleGranted(['admin', 'technician']) ? `
                <a href="#" class="grow-1 flex justify-center" id="delete_${_document['id']}">
                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"></path>
                    </svg>
                </a>
                <span>|</span>
            ` : ''
            tbody.insertAdjacentHTML('beforeend',`
                <tr>
                    <td class="font-medium text-gray-900 whitespace-nowrap">${_document['name']}</td>
                    <td>${_document['file_type']}</td>
                    <td>${_document['date_deposition']}</td>
                    <td>${_document['user']['first_name']} ${_document['user']['last_name']}</td>
                    <td class="flex gap-2 justify-between">
                        ${action}
                        <a class="grow-1 flex justify-center" id="download_${_document['id']}" href="${url}" download="${_document['name']}.${_document['file_type']}">
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            `);

            tbody.addEventListener('click', e => {
                const deleteButton = e.target.closest('a[id^="delete_"]');
                if (deleteButton) {
                    const document_id = deleteButton.id.split('_')[1];
                    api.delete(`document/${document_id}`)
                    .then(document => {
                        window.location.reload();
                    })
                }
            });
        })
        promises.push(promise);
    });

    Promise.all(promises)
    .then( _ => {
        if (document.getElementById("filter-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#filter-table", {
                tableRender: (_data, table, type) => {
                    if (type === "print") {
                        return table
                    }
                    const tHead = table.childNodes[0]
                    const filterHeaders = {
                        nodeName: "TR",
                        attributes: {
                            class: "search-filtering-row"
                        },
                        childNodes: tHead.childNodes[0].childNodes.map(
                            (_th, index) => ({nodeName: "TH",
                                childNodes: [
                                    {
                                        nodeName: "INPUT",
                                        attributes: {
                                            class: "datatable-input",
                                            type: "search",
                                            "data-columns": "[" + index + "]"
                                        }
                                    }
                                ]})
                        )
                    }
                    tHead.childNodes.push(filterHeaders)
                    return table
                }
            });
        }
    })
});


api.get(`document/download/asset/${id}`)
.then(blob => {
    const a = document.querySelector("#downloads");
    const url = window.URL.createObjectURL(new Blob([blob], {type: "application/zip"}));
    a.href = url;
    a.download = "asset.zip";
});