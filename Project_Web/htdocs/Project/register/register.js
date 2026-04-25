var map;
var source;
var citizenLocation;

function registerSubmit() {
    document.querySelector("#failedregister").textContent = "";
    var username = document.querySelector("#username").value;
    var pwd = document.querySelector("#pwd").value;
    var fname = document.querySelector("#fname").value;
    var lname = document.querySelector("#lname").value;
    var phone = document.querySelector("#phone").value;
    var lat = citizenLocation.getLatLng().lat;
    var lng = citizenLocation.getLatLng().lng;
    fetch("register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: ("username=" + username + "&pswd=" + pwd + "&fname=" + fname + "&lname=" + lname + "&phone=" + phone + "&lat=" + lat + "&lng=" + lng)
    }).then(response => response.text())
        .then(text => {
            if (text !== "Success") {
                document.querySelector("#failedregister").textContent = "This username has already been taken";
            }
            else location.href = "../home";
        });
}

fetch("baselocation.php", {
    method: "POST"
}).then(response => response.text())
    .then(text => {
        source = JSON.parse(text);

        map = L.map('map').setView([source['ba_latitude'], source['ba_longitude']], 13);
        citizenLocation = L.marker([0, 0], { draggable: true, autoPan: true });

        map.on('click', onMapClick);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        //document.querySelector("#btn-register").onclick=registerSubmit;
    });
function onMapClick(e) {
    citizenLocation.setLatLng(e.latlng);
    if (!map.hasLayer(citizenLocation)) {
        citizenLocation.addTo(map);
        document.querySelector("#btn-register").classList.remove("disabled");
    }
}