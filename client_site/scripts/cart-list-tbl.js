$(document).ready(function () {
  // Get all product remove buttons
  let removeButtons = $(".remove-cart-a");

  removeButtons.each(function () {
    $(this).click(function (event) {
      event.preventDefault();

      let productId = $(this).data("productId");

      $.ajax({
        url: "./controller/cart_controller.php",
        method: "POST",
        data: "remove_product_id=" + encodeURIComponent(productId),
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
