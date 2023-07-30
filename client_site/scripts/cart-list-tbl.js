$(document).ready(function () {
  // Get all product remove buttons
  let removeButtons = $(".remove-cart-a");

  removeButtons.each(function () {
    $(this).click(function (event) {
      event.preventDefault();

      let itemId = $(this).data("itemId");

      $.ajax({
        url: "./controller/cart_controller.php",
        method: "POST",
        data: "remove_item_id=" + encodeURIComponent(itemId),
        beforeSend: function (xhr) {
          xhr.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
          );
        },
        success: function (response) {
          location.reload();
        },
      });
    });
  });

 
});