const qstatus = document.querySelector("#status");

var optioncount;

var vehiclemarker;

const fastaccess = Object();

const map = L.map('map');
//map.setView([38.2904, 21.7951], 18);
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

// Icons
const dimensions = [50, 50];
const anchor = [25,49];
const requestred = L.icon({ iconUrl: '../icons/requestred.png', iconSize: dimensions, iconAnchor: anchor});
const requestgreen = L.icon({ iconUrl: '../icons/requestgreen.png', iconSize: dimensions, iconAnchor: anchor});
const donationred = L.icon({ iconUrl: '../icons/donationred.png', iconSize: dimensions, iconAnchor: anchor});
const donationgreen = L.icon({ iconUrl: '../icons/donationgreen.png', iconSize: dimensions, iconAnchor: anchor});
const rescuer = L.icon({ iconUrl: '../icons/rescuer.png', iconSize: dimensions, iconAnchor: anchor});
const baseblue = L.icon({ iconUrl: '../icons/baseblue.png', iconSize: dimensions, iconAnchor: anchor});

// Marker/Line Groups
const pendingRequests = L.layerGroup();
const acceptedRequests = L.layerGroup();
const pendingDonations = L.layerGroup();
const acceptedDonations = L.layerGroup();
const activeVehicles = L.layerGroup();
const inactiveVehicles = L.layerGroup();
const myVehicle = L.layerGroup();
const lines = L.layerGroup();

// myVehicle coordinates
var mycoordinates = L.latLng([0, 0]);

// Filters Interface
const layerControl = L.control.layers().addTo(map);

var source;

fetch("map.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=1"
}).then(response => response.text())
    .then(text => {
        console.log(text);
        source = JSON.parse(text);
        if (source['level'] === "2") {
            initializeMapRescuer();
        }
        else if (source['level'] === "3") {
            initializeMapAdmin();
        }
    });

function enableButtons(){
    document.querySelectorAll(".bcontrol").forEach(button => button.classList.remove("disabled"));
}

function disableButtons(){
    document.querySelectorAll(".bcontrol").forEach(button => button.classList.add("disabled"));
}

function taskSelect(){
    if(optioncount == 0) return;
    let id = document.querySelector("#taskselect").value;
    document.querySelectorAll("table").forEach(table => table.setAttribute("hidden", true));
    document.querySelector("#table" + id).removeAttribute("hidden");
}

function taskAccept(id){
    fetch("map.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=3&id=" + id
    }).then(response => response.text())
        .then(text => {
            if(text === "Success!"){
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                location.reload();
            }
            else{
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
            }
            qstatus.textContent = text;
        });
}

function taskComplete(){
    let id = document.querySelector("#taskselect").value;
    for(let request of source.requests){
        if(request['ta_id'] === id){
            if(mycoordinates.distanceTo([request['ci_latitude'], request['ci_longitude']]) > 50){
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
                qstatus.textContent = "Your distance must be 50 meters or less to complete the task. Current distance: " + mycoordinates.distanceTo([request['ci_latitude'], request['ci_longitude']]);
                return;
            }
        }
    }
    for(let donation of source.donations){
        if(donation['ta_id'] === id){
            console.log(mycoordinates.distanceTo([donation['ci_latitude'], donation['ci_longitude']]));
            if(mycoordinates.distanceTo([donation['ci_latitude'], donation['ci_longitude']]) > 50){
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
                qstatus.textContent = "Your distance must be 50 meters or less to complete the task. Current distance: " + mycoordinates.distanceTo([donation['ci_latitude'], donation['ci_longitude']]);
                return;
            }
        }
    }
    fetch("map.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=4&id=" + id
    }).then(response => response.text())
        .then(text => {
            if(text === "Success!"){
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                let delnum = Number(document.querySelector("#option" + id).textContent);
                document.querySelectorAll("option").forEach(option => {
                    let num = Number(option.textContent);
                    if(num > delnum) num--;
                    option.textContent = num;
                });
                document.querySelector("#option" + id).remove();
                document.querySelector("#table" + id).remove();
                if(--optioncount == 0) disableButtons();
                taskSelect();
                location.reload();
            }
            else{
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
            }
            qstatus.textContent = text;
        });
}

function taskRemove(){
    let id = document.querySelector("#taskselect").value;
    fetch("map.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=5&id=" + id
    }).then(response => response.text())
        .then(text => {
            if(text === "Success!"){
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                let delnum = Number(document.querySelector("#option" + id).textContent);
                document.querySelectorAll("option").forEach(option => {
                    let num = Number(option.textContent);
                    if(num > delnum) num--;
                    option.textContent = num;
                });
                document.querySelector("#option" + id).remove();
                document.querySelector("#table" + id).remove();
                if(--optioncount == 0) disableButtons();
                taskSelect();
                location.reload();
            }
            else{
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
            }
            qstatus.textContent = text;
        });
}

function unloadCargo(){
    if(mycoordinates.distanceTo([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']]) > 100){
        qstatus.classList.add("text-danger");
        qstatus.classList.remove("text-success");
        qstatus.textContent = "Your distance must be 100 meters or less to unload. Current distance: " + mycoordinates.distanceTo([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']]);
        return;
    }
    fetch("map.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=6"
    }).then(response => response.text())
        .then(text => {
            if(text === "Success!"){
                qstatus.classList.add("text-success");
                qstatus.classList.remove("text-danger");
                vehiclemarker.unbindPopup();
                for (let vehicle of source.vehicles) {
                    vehiclemarker.bindPopup('<strong>' +
                                'Username: ' + vehicle['us_name'] + '<br>' +
                                'Status: Inactive<br> Cargo [NAME (ID) - QUANTITY]:<br>' +
                                '<ul><li>Empty</li></ul>' +
                                '</strong>');
            
                }
            }
            else{
                qstatus.classList.add("text-danger");
                qstatus.classList.remove("text-success");
            }
            qstatus.textContent = text;
        });
}

function initializeMapRescuer() {
    document.querySelector("#paneldiv").removeAttribute("hidden");
    document.querySelector("#rescdistance").removeAttribute("hidden");

    map.setView([source.vehicles[0]['ve_latitude'], source.vehicles[0]['ve_longitude']], 18);

    // Add Filters
    layerControl.addOverlay(pendingRequests, 'Pending Requests');
    layerControl.addOverlay(acceptedRequests, 'Accepted Requests');
    layerControl.addOverlay(pendingDonations, 'Pending Donations');
    layerControl.addOverlay(acceptedDonations, 'Accepted Donations');
    layerControl.addOverlay(myVehicle, 'My Vehicle');
    layerControl.addOverlay(lines, 'Lines');


    // Base
    L.marker([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']], { icon: baseblue}).addTo(map);

    // Other markers plus side panel
    optioncount = 0;
    if(source.requests[0] != null)
    for (let request of source.requests) {
        if (request['ta_re_id'] === null)
            L.marker([request['ci_latitude'], request['ci_longitude']], { icon: requestred, draggable: true, autoPan: true }).addTo(pendingRequests)
                .bindPopup('<strong>' +
                    'First Name: ' + request['ci_fname'] + '<br>' +
                    'Last Name: ' + request['ci_lname'] + '<br>' +
                    'Phone: ' + request['ci_phone'] + '<br>' +
                    'Issue Date: ' + request['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + request['it_name'] + ' (' + request['it_id'] + ')' + '<br>' +
                    'Quantity: ' + request['ta_quantity'] + '<br><br>' +
                    '<a href = "javascript: taskAccept(' + request['ta_id'] + ')" class="btn btn-dark" style="color:white;">Accept</a>' +
                    '</strong>');
        else{
            L.marker([request['ci_latitude'], request['ci_longitude']], { icon: requestgreen, draggable: true, autoPan: true }).addTo(acceptedRequests)
                .bindPopup('<strong>' +
                    'First Name: ' + request['ci_fname'] + '<br>' +
                    'Last Name: ' + request['ci_lname'] + '<br>' +
                    'Phone: ' + request['ci_phone'] + '<br>' +
                    'Issue Date: ' + request['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + request['it_name'] + ' (' + request['it_id'] + ')' + '<br>' +
                    'Quantity: ' + request['ta_quantity'] + '<br>' +
                    'Accept Date: ' + request['ta_accept_date'] + '<br>' +
                    'Rescuer Username: ' + request['us_name'] + '<br>' +
                    '</strong>');
            fastaccess["isactive" + request['ta_re_id']] = "true";
            if (fastaccess["lines" + request['ta_re_id']] === undefined) fastaccess["lines" + request['ta_re_id']] = Array();
            fastaccess["lines" + request['ta_re_id']].push([request['ci_latitude'], request['ci_longitude']]);
            enableButtons();
            let newoption = document.createElement("option");
            optioncount++;
            newoption.setAttribute("value", request['ta_id']);
            newoption.id = "option" + request['ta_id'];
            newoption.append(optioncount);
            document.querySelector("#taskselect").append(newoption);
            let newpanelpage = document.createElement("table");
            newpanelpage.classList.add("table", "table-responsive", "table-bordered");
            newpanelpage.id = "table" + request['ta_id'];

            let newpanelrow = document.createElement("tr");
            let newpanelbody = document.createElement("th");
            newpanelbody.append("Full Name");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(request['ci_fname'] + " " + request['ci_lname']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Phone");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(request['ci_phone']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Issue Date");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(request['ta_issue_date']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Item (id)");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(request['it_name'] + " (" + request['it_id'] +")");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Quantity");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(request['ta_quantity']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Task type");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append("Request");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            if(optioncount > 1) newpanelpage.setAttribute("hidden", true);
            document.querySelector("#panel").prepend(newpanelpage);
        }
    }

    if(source.donations[0] != null)
    for (let donation of source.donations) {
        if (donation['ta_re_id'] === null)
            L.marker([donation['ci_latitude'], donation['ci_longitude']], { icon: donationred, draggable: true, autoPan: true }).addTo(pendingDonations)
                .bindPopup('<strong>' +
                    'First Name: ' + donation['ci_fname'] + '<br>' +
                    'Last Name: ' + donation['ci_lname'] + '<br>' +
                    'Phone: ' + donation['ci_phone'] + '<br>' +
                    'Issue Date: ' + donation['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + donation['it_name'] + ' (' + donation['it_id'] + ')' + '<br>' +
                    'Quantity: ' + donation['ta_quantity'] + '<br><br>' +
                    '<a href = "javascript: taskAccept(' + donation['ta_id'] + ')" class="btn btn-dark" style="color:white;">Accept</a>' +
                    '</strong>');
        else {
            L.marker([donation['ci_latitude'], donation['ci_longitude']], { icon: donationgreen, draggable: true, autoPan: true }).addTo(acceptedDonations)
                .bindPopup('<strong>' +
                    'First Name: ' + donation['ci_fname'] + '<br>' +
                    'Last Name: ' + donation['ci_lname'] + '<br>' +
                    'Phone: ' + donation['ci_phone'] + '<br>' +
                    'Issue Date: ' + donation['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + donation['it_name'] + ' (' + donation['it_id'] + ')' + '<br>' +
                    'Quantity: ' + donation['ta_quantity'] + '<br>' +
                    'Accept Date: ' + donation['ta_accept_date'] + '<br>' +
                    'Rescuer Username: ' + donation['us_name'] + '<br>' +
                    '</strong>');
            enableButtons();
            fastaccess["isactive" + donation['ta_re_id']] = "true";
            if (fastaccess["lines" + donation['ta_re_id']] === undefined) fastaccess["lines" + donation['ta_re_id']] = Array();
            fastaccess["lines" + donation['ta_re_id']].push([donation['ci_latitude'], donation['ci_longitude']]);
            let newoption = document.createElement("option");
            optioncount++;
            newoption.setAttribute("value", donation['ta_id']);
            newoption.id = "option" + donation['ta_id'];
            newoption.append(optioncount);
            document.querySelector("#taskselect").append(newoption);
            let newpanelpage = document.createElement("table");
            newpanelpage.classList.add("table", "table-responsive", "table-bordered");
            newpanelpage.id = "table" + donation['ta_id'];

            let newpanelrow = document.createElement("tr");
            let newpanelbody = document.createElement("th");
            newpanelbody.append("Full Name");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(donation['ci_fname'] + " " + donation['ci_lname']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Phone");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(donation['ci_phone']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Issue Date");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(donation['ta_issue_date']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Item (id)");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(donation['it_name'] + " (" + donation['it_id'] +")");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Quantity");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append(donation['ta_quantity']);
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("th");
            newpanelbody.append("Task type");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);
            
            newpanelrow = document.createElement("tr");
            newpanelbody = document.createElement("td");
            newpanelbody.append("Donation");
            newpanelrow.append(newpanelbody);
            newpanelpage.append(newpanelrow);

            if(optioncount > 1) newpanelpage.setAttribute("hidden", true);
            document.querySelector("#panel").prepend(newpanelpage);
        }
    }

    if(source.inventory[0] != null)
    for (let cargo of source.inventory) {
        if (fastaccess["cargo" + cargo['in_ve_id']] === undefined)
            fastaccess["cargo" + cargo['in_ve_id']] = "<ul>";
        fastaccess["cargo" + cargo['in_ve_id']] += "<li>" + cargo['it_name'] + " (" + cargo['it_id'] + ") -" + cargo['in_quantity'] + "</li>";
    }

    if(source.vehicles[0] != null)
    for (let vehicle of source.vehicles) {
        if (fastaccess["cargo" + vehicle['us_id']] === undefined)
            fastaccess["cargo" + vehicle['us_id']] = "<ul>Empty</ul>";
        if (fastaccess["isactive" + vehicle['us_id']] === undefined) {
            vehiclemarker = L.marker([vehicle['ve_latitude'], vehicle['ve_longitude']], { icon: rescuer, draggable: true, autoPan: true }).addTo(myVehicle)
                .bindPopup('<strong>' +
                    'Username: ' + vehicle['us_name'] + '<br>' +
                    'Status: Inactive<br> Cargo [NAME (ID) - QUANTITY]:<br>' +
                    fastaccess["cargo" + vehicle['us_id']] + '</ul>' +
                    '</strong>');
        }
        else {
            vehiclemarker = L.marker([vehicle['ve_latitude'], vehicle['ve_longitude']], { icon: rescuer, draggable: true, autoPan: true }).addTo(myVehicle)
                .bindPopup('<strong>' +
                    'Username: ' + vehicle['us_name'] + '<br>' +
                    'Status: Active<br> Cargo [NAME (ID) - QUANTITY]:<br>' +
                    fastaccess["cargo" + vehicle['us_id']] + '</ul>' +
                    '</strong>');
            for (coordinate of fastaccess["lines" + vehicle['us_id']]) {
                L.polyline([coordinate, [vehicle['ve_latitude'], vehicle['ve_longitude']]], { color: '#0E7CD3' }).addTo(lines);
            }
        }
        mycoordinates.lat = vehicle['ve_latitude'];
        mycoordinates.lng = vehicle['ve_longitude'];
        document.querySelector("#distance").textContent = mycoordinates.distanceTo([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']]);
        let tempcoords;
        vehiclemarker.on('dragstart', function (e) {
            tempcoords = [vehiclemarker.getLatLng().lat, vehiclemarker.getLatLng().lng];
        });
        vehiclemarker.on('dragend', function (e) {
            if (!confirm("Move?")) {
                vehiclemarker.setLatLng(tempcoords);
            } else {
                fetch("map.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "action=2&lat=" + vehiclemarker.getLatLng().lat + "&lng=" + vehiclemarker.getLatLng().lng
                }).then(response => response.text())
                .then(text => {
                    if(text === "Success"){
                        mycoordinates.lat = vehiclemarker.getLatLng().lat;
                        mycoordinates.lng = vehiclemarker.getLatLng().lng;
                        document.querySelector("#distance").textContent = mycoordinates.distanceTo([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']]);
                        qstatus.textContent = "";
                        location.reload();
                    }
                    else{
                        vehiclemarker.setLatLng(tempcoords);
                        qstatus.classList.add("text-danger");
                        qstatus.classList.remove("text-success");
                        qstatus.textContent = "Failed to move vehicle";
                    }
                });
            }
        });
    }
    pendingRequests.addTo(map);
    acceptedRequests.addTo(map);
    pendingDonations.addTo(map);
    acceptedDonations.addTo(map);
    myVehicle.addTo(map);
    lines.addTo(map);
}

function initializeMapAdmin() {
    map.setView([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']], 18);

    // Add Filters
    layerControl.addOverlay(pendingRequests, 'Pending Requests');
    layerControl.addOverlay(acceptedRequests, 'Accepted Requests');
    layerControl.addOverlay(pendingDonations, 'Pending Donations');
    layerControl.addOverlay(acceptedDonations, 'Accepted Donations');
    layerControl.addOverlay(activeVehicles, 'Active Vehicles');
    layerControl.addOverlay(inactiveVehicles, 'Inactive Vehicles');
    layerControl.addOverlay(lines, 'Lines');


    // Base
    let basemarker = L.marker([source.base[0]['ba_latitude'], source.base[0]['ba_longitude']], { icon: baseblue, draggable: true, autoPan: true }).addTo(map);
    let tempcoords;
    basemarker.on('dragstart', function (e) {
        tempcoords = [basemarker.getLatLng().lat, basemarker.getLatLng().lng];
    });
    basemarker.on('dragend', function (e) {
        if (!confirm("Move?")) {
            basemarker.setLatLng(tempcoords);
        } else {
            fetch("map.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "action=2&lat=" + basemarker.getLatLng().lat + "&lng=" + basemarker.getLatLng().lng
            });
        }
    });

    // Other markers
    if(source.requests[0] != null)
    for (let request of source.requests) {
        if (request['ta_re_id'] === null)
            L.marker([request['ci_latitude'], request['ci_longitude']], { icon: requestred, draggable: true, autoPan: true }).addTo(pendingRequests)
                .bindPopup('<strong>' +
                    'First Name: ' + request['ci_fname'] + '<br>' +
                    'Last Name: ' + request['ci_lname'] + '<br>' +
                    'Phone: ' + request['ci_phone'] + '<br>' +
                    'Issue Date: ' + request['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + request['it_name'] + ' (' + request['it_id'] + ')' + '<br>' +
                    'Quantity: ' + request['ta_quantity'] + '<br>' +
                    '</strong>');
        else{
            L.marker([request['ci_latitude'], request['ci_longitude']], { icon: requestgreen, draggable: true, autoPan: true }).addTo(acceptedRequests)
                .bindPopup('<strong>' +
                    'First Name: ' + request['ci_fname'] + '<br>' +
                    'Last Name: ' + request['ci_lname'] + '<br>' +
                    'Phone: ' + request['ci_phone'] + '<br>' +
                    'Issue Date: ' + request['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + request['it_name'] + ' (' + request['it_id'] + ')' + '<br>' +
                    'Quantity: ' + request['ta_quantity'] + '<br>' +
                    'Accept Date: ' + request['ta_accept_date'] + '<br>' +
                    'Rescuer Username: ' + request['us_name'] + '<br>' +
                    '</strong>');
            fastaccess["isactive" + request['ta_re_id']] = "true";
            if (fastaccess["lines" + request['ta_re_id']] === undefined) fastaccess["lines" + request['ta_re_id']] = Array();
            fastaccess["lines" + request['ta_re_id']].push([request['ci_latitude'], request['ci_longitude']]);
        }
    }

    if(source.donations[0] != null)
    for (let donation of source.donations) {
        if (donation['ta_re_id'] === null)
            L.marker([donation['ci_latitude'], donation['ci_longitude']], { icon: donationred, draggable: true, autoPan: true }).addTo(pendingDonations)
                .bindPopup('<strong>' +
                    'First Name: ' + donation['ci_fname'] + '<br>' +
                    'Last Name: ' + donation['ci_lname'] + '<br>' +
                    'Phone: ' + donation['ci_phone'] + '<br>' +
                    'Issue Date: ' + donation['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + donation['it_name'] + ' (' + donation['it_id'] + ')' + '<br>' +
                    'Quantity: ' + donation['ta_quantity'] + '<br>' +
                    '</strong>');
        else {
            L.marker([donation['ci_latitude'], donation['ci_longitude']], { icon: donationgreen, draggable: true, autoPan: true }).addTo(acceptedDonations)
                .bindPopup('<strong>' +
                    'First Name: ' + donation['ci_fname'] + '<br>' +
                    'Last Name: ' + donation['ci_lname'] + '<br>' +
                    'Phone: ' + donation['ci_phone'] + '<br>' +
                    'Issue Date: ' + donation['ta_issue_date'] + '<br>' +
                    'Item Name (id): ' + donation['it_name'] + ' (' + donation['it_id'] + ')' + '<br>' +
                    'Quantity: ' + donation['ta_quantity'] + '<br>' +
                    'Accept Date: ' + donation['ta_accept_date'] + '<br>' +
                    'Rescuer Username: ' + donation['us_name'] + '<br>' +
                    '</strong>');
            fastaccess["isactive" + donation['ta_re_id']] = "true";
            if (fastaccess["lines" + donation['ta_re_id']] === undefined) fastaccess["lines" + donation['ta_re_id']] = Array();
            fastaccess["lines" + donation['ta_re_id']].push([donation['ci_latitude'], donation['ci_longitude']]);
        }
    }

    if(source.inventory[0] != null)
    for (let cargo of source.inventory) {
        if (fastaccess["cargo" + cargo['in_ve_id']] === undefined)
            fastaccess["cargo" + cargo['in_ve_id']] = "<ul>";
        fastaccess["cargo" + cargo['in_ve_id']] += "<li>" + cargo['it_name'] + " (" + cargo['it_id'] + ") -" + cargo['in_quantity'] + "</li>";
    }

    if(source.vehicles[0] != null)
    for (let vehicle of source.vehicles) {
        if (fastaccess["cargo" + vehicle['us_id']] === undefined)
            fastaccess["cargo" + vehicle['us_id']] = "<ul>Empty</ul>";
        if (fastaccess["isactive" + vehicle['us_id']] === undefined)
            L.marker([vehicle['ve_latitude'], vehicle['ve_longitude']], { icon: rescuer, draggable: true, autoPan: true }).addTo(inactiveVehicles)
                .bindPopup('<strong>' +
                    'Username: ' + vehicle['us_name'] + '<br>' +
                    'Status: Inactive<br> Cargo [NAME (ID) - QUANTITY]:<br>' +
                    fastaccess["cargo" + vehicle['us_id']] + '</ul>' +
                    '</strong>');
        else {
            L.marker([vehicle['ve_latitude'], vehicle['ve_longitude']], { icon: rescuer, draggable: true, autoPan: true }).addTo(activeVehicles)
                .bindPopup('<strong>' +
                    'Username: ' + vehicle['us_name'] + '<br>' +
                    'Status: Active<br> Cargo [NAME (ID) - QUANTITY]:<br>' +
                    fastaccess["cargo" + vehicle['us_id']] + '</ul>' +
                    '</strong>');
            for (coordinate of fastaccess["lines" + vehicle['us_id']]) {
                L.polyline([coordinate, [vehicle['ve_latitude'], vehicle['ve_longitude']]], { color: '#0E7CD3' }).addTo(lines);
            }
        }
    }
    pendingRequests.addTo(map);
    acceptedRequests.addTo(map);
    pendingDonations.addTo(map);
    acceptedDonations.addTo(map);
    activeVehicles.addTo(map);
    inactiveVehicles.addTo(map);
    lines.addTo(map);
}