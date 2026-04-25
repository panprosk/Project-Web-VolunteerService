const qstatus = document.querySelector("#status");

const ctx = document.querySelector('#myChart');

const graph = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['New Requests', 'New Donations', 'Completed Requests', 'Completed Donations'],
        datasets: [{
            label: '# of Tasks',
            data: [0, 0, 0, 0],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function submitRange() {

    let sdate = document.querySelector("#sdate");
    let edate = document.querySelector("#edate");
    console.log(edate.value);
    fetch("statistics.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "sdate=" + sdate.value + "&edate=" + edate.value
    }).then(response => response.text())
        .then(text => {
            if (text !== "Fail") {
                source = JSON.parse(text);
                graph.data.datasets[0].data[0] = source['req_new'];
                graph.data.datasets[0].data[1] = source['don_new'];
                graph.data.datasets[0].data[2] = source['req_com'];
                graph.data.datasets[0].data[3] = source['don_com'];
                graph.update();
            }
        });
    
}