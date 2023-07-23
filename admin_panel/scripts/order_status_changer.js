const setStatusColor = () => {
  let deliveryStatusSelects = $(".delivery-status");
  let orderStatusSelects = $(".order-approval");

  for (let i = 0; i < deliveryStatusSelects.length; i++) {
    let deliveryStatusSelect = deliveryStatusSelects[i];
    let orderStatusSelect = orderStatusSelects[i];
    setOrderTextColor();
    setDeliveryTextColor();
    orderStatusSelect.addEventListener("change", function () {
      setOrderTextColor();
    });
    deliveryStatusSelect.addEventListener("change", function () {
      setDeliveryTextColor();
    });

    function setOrderTextColor() {
      let selectedOption = $(orderStatusSelect).find("option:selected");
      let selectedValue = selectedOption.val();

      if (selectedValue === "YES") {
        $(orderStatusSelect).addClass("text-green").removeClass("text-red");
      } else {
        $(orderStatusSelect).addClass("text-red").removeClass("text-green");
      }
    }

    function setDeliveryTextColor() {
      let selectedOption = $(deliveryStatusSelect).find("option:selected");
      let selectedValue = selectedOption.val();

      if (selectedValue === "RECEIVED") {
        $(deliveryStatusSelect).addClass("text-green").removeClass("text-orange");
      } else {
        $(deliveryStatusSelect).addClass("text-orange").removeClass("text-green");
      }
    }
  }
};

$(document).ajaxSuccess(function() {
  setStatusColor();
});

let dataLoading = 1000;
setTimeout(setStatusColor, dataLoading);
