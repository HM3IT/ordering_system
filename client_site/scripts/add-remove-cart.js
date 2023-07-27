$(document).ready(function () {
  let addToCartForms = $(".cart-form");

  addToCartForms.each(function () {
    $(this).submit(function (e) {
      e.preventDefault();

      let formData = new FormData($(this)[0]);

      $.ajax({
        url: "./controller/cart_session_controller.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (data) {
          console.log(data.out_of_stock);
          if (data.out_of_stock) {
            $("#quantity-limit-overlay").css("display", "block");
            $("#out-of-stock-box").css("display", "block");
            $("#out-of-stock-box span").text(data.message);
          } else if (!data.exceed_quantity) {
            $("#popup-info-box").css("display", "block");
            setTimeout(function () {
              $("#popup-info-box").css("display", "none");
            }, 800);
            setTimeout(function () {
              location.reload();
            }, 1000);
          } else {
            $("#quantity-limit-overlay").css("display", "block");
            $("#quantity-limit-alert-box").css("display", "block");
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });
  });

  let removeButtons = $(".product-remove-btn");

  removeButtons.each(function () {
    $(this).click(function (event) {
      event.preventDefault();

      let productId = $(this).data("productId");
      let listItem = $(this).closest(".card-list-items");

      listItem.addClass("removing");
      setTimeout(function () {
        listItem.remove();
        listItem.css("display", "none");
      }, 2000);

      $.ajax({
        url: "./controller/cart_controller.php",
        method: "POST",
        data: "remove_product_id=" + encodeURIComponent(productId),
        success: function () {
          let cartList = $("#card-list-ul");
          let emptyCartItems = cartList.find(
            ".card-list-items:not(.removing)"
          );

          if (emptyCartItems.length === 0) {
            let emptyCartItem = $("<li>").addClass("card-list-items");
            let emptyCartBox = $("<div>").addClass("card-list-box");
            let emptyCartEmoji = $("<i>")
              .addClass("fa-regular fa-face-sad-cry")
              .attr("id", "cry-emoji");
            let emptyCartDescription = $("<div>").addClass(
              "card-list-box-description1"
            );
            let emptyCartHeading = $("<h3>")
              .css("text-align", "center")
              .text("Your order cart is now empty");

            emptyCartDescription.append(emptyCartHeading);
            emptyCartBox.append(emptyCartEmoji);
            emptyCartBox.append(emptyCartDescription);
            emptyCartItem.append(emptyCartBox);

            cartList.append(emptyCartItem);
          }
        },
      });
    });
  });
});
