function showPaymentFields() {
  $(".kPayFields").css("display", "block");
  $("#transaction-img").prop("required", true);
}

function hidePaymentFields() {
  $(".kPayFields").css("display", "none");
  $("#transaction-img").prop("required", false);
}
