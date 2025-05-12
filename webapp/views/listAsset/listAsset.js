const user = JSON.parse(cookieManager.getCookie('user'));
const tbody = document.querySelector('tbody#assets');


if (user) {
    api.get('assets')
    .then(assets => {
        console.log(assets);
        assets.forEach(asset => {
            tbody.insertAdjacentHTML('beforeend',`
                <tr>
                    <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${asset['name']}
                    </td>
                    <td>${asset['state']}</td>
                    <td>${asset['numSerie']}</td>
                    <td class="hover:underline">
                        <a href="/views/asset/asset.php?id=${asset['id']}">View</a>
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