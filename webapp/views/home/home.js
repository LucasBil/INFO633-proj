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
        createChart(projects, ["not_started", "in_progress", "completed", "dismantled"]);
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

function createChart(projects, labels) {
    let datas = [];
    labels.forEach(label => {
        let data = projects.filter(p => p['status'] == label);
        datas.push(
            Math.round((data.length / projects.length)*100)
        );
    });

    const getChartOptions = (datas, labels) => {
        return {
            series: datas,
            colors: ["#333037", "#2450B4", "#065F4B", "#991B61"],
            chart: {
                height: 420,
                width: "100%",
                type: "pie",
            },
            stroke: {
                colors: ["white"],
                lineCap: "",
            },
            plotOptions: {
                pie: {
                    labels: {
                        show: true,
                    },
                    size: "100%",
                    dataLabels: {
                        offset: -25
                    }
                },
            },
            labels: labels,
            dataLabels: {
                enabled: true,
                style: {
                    fontFamily: "Inter, sans-serif",
                },
            },
            legend: {
                position: "bottom",
                fontFamily: "Inter, sans-serif",
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value + "%"
                    },
                },
            },
            xaxis: {
                labels: {
                    formatter: function (value) {
                        return value  + "%"
                    },
                },
                axisTicks: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                },
            },
        }
    }

    if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions(datas, labels));
        chart.render();
    }
}