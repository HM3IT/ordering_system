let productId = $("#product-id").val();
$(document).ready(function () {
  $("#ask-login").click(function () {
    $("#overlay").show();
    $("#ask-login-form").show();
  });

  $("#add_review").click(function () {
    $("#review-box").show();
    $("#overlay").show();
  });
  $("#close-review-box").click(function () {
    $("#review-box").hide();
    $("#overlay").hide();
  });
  

  $("#noti-review-already").click(function () {
    $("#noti-review-already-form").show();
    $("#overlay").show();
  });
  $("#close-noti-review-already-box").click(function () {
    $("#noti-review-already-form").hide();
    $("#overlay").hide();
  });

  // show all reviews btn
  $("#view-all-reviews-btn").click(function () {
    $("#review_content").css("height", "unset").css("max-height", "unset");
    $(this).hide();
    $("#show-less-reviews-btn").css("display", "block");
  });
  // show less reviews btn
  $("#show-less-reviews-btn").click(function () {
    $("#review_content").css("height", "450px");
    $(this).hide();
    $("#view-all-reviews-btn").show();
  });

  $("#save-review").click(function () {
    let star_rating = $("input[name='star']:checked").val();

    let product_id = $("#product_id").val();
    let customer_id = $("#customer_id").val();
    let customer_review = $("#customer_review").val();

    if (customer_review == "") {
      alert("Please Fill the review Field");
      return false;
    } else {
      $.ajax({
        url: "./controller/review_controller.php",
        method: "POST",
        data: {
          star_rating: star_rating,
          customer_id: customer_id,
          product_id: product_id,
          customer_review: customer_review,
        },
        success: function (data) {
          $("#review-box").hide();
          $("#overlay").hide();

          load_rating_data(productId);
        },
      });
    }
  });

  // First time loading
  load_rating_data(productId);
  function load_rating_data(product_id) {
    $.ajax({
      url: "./controller/review_controller.php",
      method: "POST",
      data: { action: "load_data", product_id: product_id },
      dataType: "JSON",
      success: function (data) {
        $("#avg-rating").text(data.average_rating);
        $("#total_review").text(data.total_review);

        let count_star = 0;

        $(".main_star").each(function () {
          count_star++;
          if (Math.ceil(data.average_rating) >= count_star) {
            $(this).addClass("warning");
          } else {
            $(this).addClass("disable-text");
          }
        });

        $("#total_five_star_review").text("(" + data.five_star_review + ")");
        $("#total_four_star_review").text("(" + data.four_star_review + ")");
        $("#total_three_star_review").text("(" + data.three_star_review + ")");
        $("#total_two_star_review").text("(" + data.two_star_review + ")");
        $("#total_one_star_review").text("(" + data.one_star_review + ")");

        if (data.total_review > 0) {
          $("#five_star_progress").css(
            "width",
            (data.five_star_review / data.total_review) * 100 + "%"
          );

          $("#four_star_progress").css(
            "width",
            (data.four_star_review / data.total_review) * 100 + "%"
          );

          $("#three_star_progress").css(
            "width",
            (data.three_star_review / data.total_review) * 100 + "%"
          );

          $("#two_star_progress").css(
            "width",
            (data.two_star_review / data.total_review) * 100 + "%"
          );

          $("#one_star_progress").css(
            "width",
            (data.one_star_review / data.total_review) * 100 + "%"
          );
        } else {
          $(".progress-bar").css("width", "0");
          $(".progress").css("box-shadow", ".5px 1px 2px grey");
        }
        let num_of_reviews = data.review_data.length;
        if (num_of_reviews > 0) {
          if (num_of_reviews > 2) {
            $("#show-btns").show();
          }
          let html = "";

          for (let count = 0; count < num_of_reviews; count++) {
            html += '<div class="customer-reviews">';
            html += '<div class="customer-img">';

            html +=
              '<img src="../images/User/' +
              data.review_data[count].image +
              '" alt="image">';
            html += "</div>";
            html += '<div class="reivew-div">';
            html += '<div class="customer-review-header">';

            html +=
              '<h3 class="customer-name">' +
              data.review_data[count].customer_name +
              "</h3>";
            html += "</div>";
            html += '<div class="card-body">';
            // Star ratings loop
            for (let star = 1; star <= 5; star++) {
              let class_name = "";
              if (data.review_data[count].rating >= star) {
                class_name = "warning";
              } else {
                class_name = "disable-text";
              }
              html += '<i class="fas fa-star ' + class_name + ' "></i>';
            }
            html += '<div class="customer-review">';
            html += "<p>" + data.review_data[count].customer_review + "</p>";
            html += "</div>";
            html += "</div>";

            html += '<div class="card-footer">';
            html += "On " + data.review_data[count].datetime;
            html += "</div>";
            html += "</div>";
            html += "</div>";
          }

          $("#review_content").html(html);
        }
      },
    });
  }
});
