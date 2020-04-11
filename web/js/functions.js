// all functions

// loader
function showLoading() {
    $("#loadingScreen").css("display", "block");
}

function hideLoading() {
    $("#loadingScreen").css("display", "none");
}

// new marker (preview and delete)
function removeNewMarker() {
    cigomap.removeLayer(newMarker);
}

function previewMarker() {
    showLoading();

    var address = $("#order-streetaddress").val();
    var city = $("#order-city").val();
    var state = $("#order-state").val();
    var country = $("#order-city").val();
    var postalcode = $("#order-postalcode").val();

    $.ajax({
        url: $("#baseUrl").val() + "/?r=cigo/get-latlng&address=" + address +
                                                    "&city=" + city +
                                                    "&state=" + state +
                                                    "&country=" + country +
                                                    "&postalcode=" + postalcode,
        dataType: "json",
        async: false,
        success: function(response) {
            if (response.latitude != null && response.longitude != null) {
                newMarker = L.marker([response.latitude, response.longitude], {
                    icon: newIcon
                });
                newMarker.addTo(cigomap);
                cigomap.setView(new L.LatLng(response.latitude, response.longitude), 14);

                $("#submitButton").removeAttr("disabled");
            } else {
                $("#modalNotFound").modal();
            }
            hideLoading();
        }
    });
}

// delete orders in the table
function modalDeleteOrder(order) {
    $("#deleteButton").attr("data", order.getAttribute("data"));
    $("#modalDelete").modal();
}

function deleteOrder(order) {
    window.location.href = $("#baseUrl").val() + "/?r=cigo/delete-order&id=" + order.getAttribute("data");
}


// create dropdown to change status
function createSelectStatus(order) {
    var bgcolor;

    switch (order.orderStatus.textid) {
        case "PE":
            bgcolor = "light";
            break;
        case "AS":
            bgcolor = "primary";
            break;
        case "RO":
            bgcolor = "warning";
            break;
        case "CA":
            bgcolor = "danger";
            break;
        case "DO":
            bgcolor = "success";
            break;
        default:
            bgcolor = "light";
    }

    var select = '<div class="btn-group" style="width: 80% !important">';
    select += '<button type="button" class="btn btn-' + bgcolor + ' status-select">';
    select += order.orderStatus.description;
    select += '</button>';
    select += '<button type="button" class="btn btn-' + bgcolor + ' dropdown-toggle dropdown-toggle-split  status-select-split" data-toggle="dropdown">';
    select += '<span class="caret"></span></button>';
    select += '<div class="dropdown-menu" >';
    orderstatus.forEach(function(status) {
        if (status.textid != order.orderStatus.textid) {
            select += '<li class="dropdown-item"><a data="' + status.id + '" href="#" onClick="updateOrderStatus(' + order.id +', ' + status.id + ')">' + status.description + '</a></li>';
        }        
    });
    select += "</div></div>";

    return select;
}

// get all order status
function getOrderStatus() {
    $.ajax({
        url: $("#baseUrl").val() + "/?r=cigo/get-order-status",
        dataType: "json",
        async: false,
        success: function(response) {
            orderstatus = response;
        }
    });
}

// update order status
function updateOrderStatus(orderid, statusid) {
    window.location.href = $("#baseUrl").val() + "/?r=cigo/update-order-status&orderid=" + orderid + "&statusid=" + statusid;
}

// show infos of an order
function showInfo(orderid) {
    orders.forEach(function (o) {
        if (o.id == orderid) {
            cigomap.setView(new L.LatLng(o.latitude, o.longitude), 14);
            o.marker.openPopup();
        }
    });
}

// highlight order on the table
function hilightOrder(orderid) {
    $("#ordersTable tr").each(function() {
        if (orderid == this.getAttribute("data")) {
            this.setAttribute("style", "background-color: #d9edf7");
        } else {
            this.removeAttribute("style");
        }
    });
}