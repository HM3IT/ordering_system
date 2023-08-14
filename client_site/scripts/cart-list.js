document.addEventListener("DOMContentLoaded", function () {

  const limitQuanitty = 10;
   
  let windowWidth = $(window).width();
  $(window).resize(function () {
    windowWidth = $(window).width();
  });
  // cart-list add remove animation
  const cartListOpenBtn =
    document.getElementsByClassName("card-list-open-btn")[0];
  const cartListCloseBtn = document.getElementsByClassName(
    "card-list-close-btn"
  )[0];

  const cartList = document.getElementsByClassName("card-list")[0];

  if (cartListOpenBtn != null) {
    cartListOpenBtn.addEventListener("click", function () {
      if (windowWidth < 480) {
        cartList.style.right = "0px";
      } else {
        cartList.style.right = "20px";
      }
    });

    cartListCloseBtn.addEventListener("click", () => {
      cartList.style.right = "-600px";
    });
  }

  let quantityOverlay = document.getElementById("quantity-limit-overlay");
  let quantityAlertBox = document.getElementById("quantity-limit-alert-box");

  let quantityButtons = document.querySelectorAll(
    ".product-quantity-wrapper .minus, .product-quantity-wrapper .plus"
  );
  quantityButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      let quantityElement = button.parentNode.querySelector(".quantity");
      let itemIndex = quantityElement.dataset.itemIndex;
      let quantity = parseInt(quantityElement.textContent.trim());

      // to compatiable with both cart-list & cart-list table
      let itemPriceElement;
      if(quantityElement.closest(".card-list-box") == null){
        itemPriceElement = quantityElement.closest(".card-list-row").querySelector(".item-price");
      }else{
         itemPriceElement = quantityElement.closest(".card-list-box").querySelector(".item-price");
       }

      let itemID = quantityElement.parentNode.querySelector(".cart-id").dataset.itemId;
    
      let basePrice = parseFloat(itemPriceElement.dataset.basePrice);
 
      if (button.classList.contains("minus")) {
        quantity = Math.max(quantity - 1, 1);
        if (quantity <= 0) {
          quantity == 1;
        }
      } else if (button.classList.contains("plus")) {
        if (quantity >= limitQuanitty) {
          quantityOverlay.style.display = "block";
          quantityAlertBox.style.display = "block";
        } else {
          quantity += 1;
        }
      }
    // Update the item quantity and price display
    
    let newPrice = (basePrice * quantity).toFixed(2);
    
    itemPriceElement.textContent = newPrice + " Ks";
    quantityElement.textContent = quantity;

      itemStateHandler(quantity, newPrice, itemIndex, itemID);
    
    });
  });

  let timer;
  let waitTimer = 1000;
  let updatedItemStateAry = [];

  function itemStateHandler(
    newQuantity,
    newPrice,
    itemIndex,
    itemID
  ) {
    clearTimeout(timer);
    updatedItemStateAry.push({
      itemIndex: itemIndex,
      itemID: itemID,
      quantity: newQuantity,
      price:newPrice
    });

    timer = setTimeout(function () {
      $.ajax({
        url: "controller/cart_controller.php",
        method: "POST",
        data: JSON.stringify(updatedItemStateAry),
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Content-Type", "application/json");
        },
        success: function (response) {
       
          if (response.out_of_stock) {
            // Handle out of stock scenario
            let quantityOverlay = $("#quantity-limit-overlay")[0];
            let outOfStockBox = $("#out-of-stock-box")[0];
            let instockInfo = $(outOfStockBox).find("span");

            $(quantityOverlay).css("display", "block");
            $(outOfStockBox).css("display", "block");
            $(instockInfo).text(response.data);
            // $(quantityElement).text(response.data);
          }
          location.reload();
        },
        error: function () {
          console.log("Failed to update quantity.");
          alert(
            "There was an error updating the quantity of the product. Please report this to the admin!"
          );
        },
      });

      // Reset the array after sending the data
      updatedItemStateAry = [];
  
    }, waitTimer);
  }
});