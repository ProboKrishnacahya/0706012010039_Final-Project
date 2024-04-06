function getLocation(valueAddress) {
    if (valueAddress == null) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;

                    // Initialize Leaflet map
                    map = L.map("map").setView([latitude, longitude], 13);

                    // Add OpenStreetMap tile layer
                    L.tileLayer(
                        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors",
                    }
                    ).addTo(map);

                    // Add marker at user's location
                    var marker = L.marker([latitude, longitude], {
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(
                            "Latitude: " +
                            latitude +
                            "<br>Longitude: " +
                            longitude
                        )
                        .openPopup();

                    // Event listener for marker drag end
                    marker.on("dragend", function (event) {
                        var marker = event.target;
                        var position = marker.getLatLng();
                        var newLatitude = position.lat;
                        var newLongitude = position.lng;

                        // Update latitude and longitude input fields
                        document.getElementById("latitude").value = newLatitude;
                        document.getElementById("longitude").value =
                            newLongitude;

                        // Get address for the new marker position
                        getAddress(newLatitude, newLongitude);
                    });

                    // Get address for the initial marker position
                    getAddress(latitude, longitude);
                },
                function (error) {
                    toastr.error("Error getting location:", error.message);
                }
            );
        } else {
            toastr.error("Geolocation is not supported by this browser.");

            alert("Geolocation is not supported by this browser.");
        }
    } else {
        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + valueAddress)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var result = data[0];
                    console.log(map, "map");
                    if (typeof map !== "undefined") {
                        map.remove();
                    }
                    map = L.map("map");

                    // Add OpenStreetMap tile layer
                    L.tileLayer(
                        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors",
                    }
                    ).addTo(map);

                    // Add marker at user's location
                    var marker = L.marker([data[0].lat, data[0].lon], {
                        draggable: true,
                    })
                        .addTo(map)
                        .bindPopup(
                            "Latitude: " +
                            data[0].lat +
                            "<br>Longitude: " +
                            data[0].lon
                        )
                        .openPopup();

                    // Set initial view
                    map.setView([data[0].lat, data[0].lon], 13);
                } else {
                    alert("Address not found");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

function getAddress(latitude, longitude) {
    fetch("https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" + latitude + "&lon=" + longitude)
        .then(response => response.json())
        .then(data => {
            var address = "";

            if (data.address) {
                console.log(data.address, "address");
                if (data.address.road) {
                    address += data.address.road + ", ";
                }
                if (data.address.state) {
                    address += data.address.state + ", ";
                }
                if (data.address.municipality) {
                    address += data.address.municipality + ", ";
                }
                if (data.address.city) {
                    address += data.address.city + ", ";
                }
                if (data.address.village) {
                    address += data.address.village + ", ";
                }
                if (data.address.postcode) {
                    address += data.address.postcode + ".";
                }
            }
            document.getElementById("address").innerHTML = address;
            document.getElementById("address_text").innerHTML = address;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Declare map and marker variables in the global scope
var map;
var marker;

// Function to initialize the map
// Event listener for search input
document.getElementById("searchInput").addEventListener("keyup", function () {
    var input = this.value;
    if (input.length >= 3) {
        // Fetch suggestions from geocoding service
        fetch("https://nominatim.openstreetmap.org/search?format=json&q=" + input)
            .then(response => response.json())
            .then(data => {
                var dropdown = document.getElementById("addressDropdown");
                dropdown.innerHTML = ""; // Clear previous suggestions
                if (data && data.length > 0) {
                    data.forEach(function (item) {
                        // Create dropdown items
                        var option = document.createElement("a");
                        option.classList.add("dropdown-item");
                        option.textContent = item.display_name;
                        option.href = "#";
                        option.onclick = function () {
                            // Remove active class from previously active item
                            var activeItem = dropdown.querySelector(
                                ".dropdown-item.active");
                            if (activeItem) {
                                activeItem.classList.remove("active");
                            }
                            // Set selected address to input field
                            document.getElementById("searchInput").value = item
                                .display_name;
                            document.getElementById("selectedAddress").value = item
                                .display_name;
                            // Set current item as active
                            option.classList.add("active");
                            dropdown.style.display = "none"; // Hide dropdown
                            return false; // Prevent page from reloading
                        };
                        dropdown.appendChild(option);
                    });
                    dropdown.style.display = "block"; // Show dropdown
                } else {
                    dropdown.style.display = "none"; // Hide dropdown if no suggestions
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        document.getElementById("addressDropdown").style.display =
            "none"; // Hide dropdown if input is less than 3 characters
    }
});

// Close dropdown when clicking outside
document.addEventListener("click", function (event) {
    if (!event.target.matches("#searchInput")) {
        var dropdown = document.getElementById("addressDropdown");
        var selectedAddress = dropdown.querySelector(".dropdown-item.active");
        if (selectedAddress) {
            var address = selectedAddress.textContent;
            getLocation(address);
            // Fetch the coordinates of the selected address
        } else {
            console.log("No active dropdown item selected");
        }
        dropdown.style.display = "none";
    }
});

function searchAddress() {
    var address = document.getElementById("searchInput").value;

    // Perform geocoding to get latitude and longitude
    $.get(
        "https://nominatim.openstreetmap.org/search?format=json&q=" + address,
        function (data) {
            if (data && data.length > 0) {
                var result = data[0];
                var formattedAddress = result.display_name;

                // Update selected address input field
                document.getElementById("selectedAddress").value =
                    formattedAddress;

                // Update search input field with selected address
                document.getElementById("searchInput").value = formattedAddress;
            } else {
                alert("Address not found");
            }
        }
    );
}