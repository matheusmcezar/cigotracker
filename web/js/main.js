showLoading();

// load leaflet and open street map
var cigomap = L.map('divmap').setView([48, -98], 3);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; Layer by <a href="http://osm.org/copyright">OpenStreetMap</a>'
}).addTo(cigomap);

// when click in blank space, clean the selection
cigomap.on("click", function() {
    hilightOrder(0);
});

// -=-=-=-=-=-
// setup the icons
var Icon = L.Icon.extend({
    options: {
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    }
});
var peIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/057-stopwatch.png"});
var asIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/005-calendar.png"});
var roIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/028-express-delivery.png"});
var doIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/015-delivered.png"});
var caIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/016-delivery-failed.png"});


var orders;
var orderstatus = null;

var newMarker;
var newIcon = new Icon({iconUrl: $("#baseUrl").val() + "/icons/logistics/023-destination.png"});

// get all osrder status
getOrderStatus();

// -=-=-=-=-=-
// load the orders
$.ajax({
    url: $("#baseUrl").val() + "/?r=cigo/get-orders",
    dataType: "json",
    success: function(response) {
        orders = response;

        orders.forEach(function (order) {
            // create the select with the selected status
            var selectStatus = createSelectStatus(order);

            //verify status to insert delete icon
            var deleteIcon = '<i class="fa fa-times-circle bt-delete-';
            if (order.orderStatus.textid == "PE" || order.orderStatus.textid == "AS") {
                deleteIcon += 'on" data="' + order.id + '" onClick="modalDeleteOrder(this)" ';
            } else {
                deleteIcon += 'off" ';
            }
            deleteIcon += '/>';

            // add order to the table
            var tableRow = '<tr data="'+order.id+'" onClick="showInfo('+order.id+')">';
            tableRow += "<td>" + order.firstName + "</td>";
            tableRow += "<td>" + order.lastName + "</td>";
            tableRow += "<td>" + order.scheduledDate + "</td>";
            tableRow += "<td>" + selectStatus + '&nbsp' + deleteIcon + "</td>";
            $("#ordersTable").find('tbody').append(tableRow);

            // add order to the map
            var iconObj;
            switch (order.orderStatus.textid) {
                case "PE":
                    iconObj = peIcon;
                    break;
                case "AS":
                    iconObj = asIcon;
                    break;
                case "RO":
                    iconObj = roIcon;
                    break;
                case "DO":
                    iconObj = doIcon;
                    break;
                case "CA":
                    iconObj = caIcon;
                    break;
                default:
                    iconObj = peIcon;
            }
            if (order.latitude != null && order.longitude != null) {
                order.marker = L.marker([order.latitude, order.longitude], {
                    icon: iconObj
                });
                
                order.marker.orderid = order.id;

                order.marker.on("click", function() {
                    hilightOrder(this.orderid);
                });

                order.marker.bindPopup(
                    "<b>Order Type:</b> " + order.orderType.description + "<br />" +
                    "<b>Contact:</b><br />" +
                    order.orderType.phoneNumber + "<br />" +
                    order.orderType.email + "<br />" +
                    "<b>Order Value:</b> " + order.orderType.orderValue + "<br />"
                );

                order.marker.addTo(cigomap);
            }
        });

        $("#ordersTable").DataTable({
            paging: false,
            searching: false,
            info: false,

            "scrollY": "350px",
            "scrollCollapse": true,
        });
        hideLoading();
    }
});

// -=-=-=-=-=-
// setup the buttons

$("#cancelButton").bind("click", function() {
    $("#submitButton").attr("disabled", "disabled");
    removeNewMarker()
});

$(".address").bind("change", function() {
    $("#submitButton").attr("disabled", "disabled");
    removeNewMarker();
});

$(".address").bind("keyup", function() {
    $("#submitButton").attr("disabled", "disabled");
    removeNewMarker();
});