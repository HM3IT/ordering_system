$(document).ready(function () {
  $("#empTable").DataTable({
    processing: true,
    serverSide: true,
    serverMethod: "post",
    ajax: {
      url: "./controller/table_controller.php",
    },
    columns: [
      {
        data: "order_id",
      },
      {
        data: "customer_name",
      },
      {
        data: "order_date",
      },
      {
        data: "ship_address",
      },
      {
        data: "total_price",
      },
      {
        data: "order_approval",
      },
      {
        data: "delivery_status",
      },
      {
        data: "action",
      },
    ],
  });
});
function updateOrderStatus(event, orderId) {
  event.preventDefault();

  const row = $(event.target).closest("tr");
  const approvalStatus = row.find(".order-approval").val();
  const deliveryStatus = row.find(".delivery-status").val();

  const payload = {
    order_id: orderId,
    order_approval: approvalStatus,
    delivery_status: deliveryStatus,
  };

  $("#confirm-btn").click(function () {
    $("#confirm-status-overlay").hide();
    $("#confirm-status-box").hide();

    $.ajax({
      url: "./controller/order_status_controller.php",
      type: "POST",
      dataType: "json",
      contentType: "application/json",
      data: JSON.stringify(payload),
      success: function (response) {
        console.log("Success");
        location.reload();
      },
    });
  });
}

let overlay = $("#confirm-status-overlay");
let confirmForm = $("#confirm-status-box");

function confirmChanges(event, orderId) {
  overlay.show();
  confirmForm.show();

  $("#confirm-btn").click(function () {
    updateOrderStatus(event, orderId);
  });
}

function closeConfirmForm() {
  overlay.hide();
  confirmForm.hide();
}
