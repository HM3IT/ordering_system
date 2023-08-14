compareDates();
function formatNumberWithCommas(number) {
  const numberString = number.toString();

  if (numberString.length <= 3) {
    return numberString; // No formatting needed
  }

  const firstGroup = numberString.slice(0, -3);
  const remainingDigits = numberString.slice(-3);

  const formattedNumber = `${firstGroup}, ${remainingDigits}`;
  return formattedNumber;
}

function textHighlight(elementName, percentage) {
  if (percentage > 0) {
    $(elementName)
      .text("+ " + percentage + "%")
      .css("color", "green");
  } else if (percentage < 0) {
    $(elementName)
      .text(percentage + "%")
      .css("color", "red");
  } else {
    $(elementName)
      .text(percentage + "%")
      .css("color", "orange");
  }
}

function compareDates() {
  let dateInput = $("#dateInput").val();

  if (dateInput === "") {
    // Get today's date in the format 'YYYY-MM-DD'
    let today = new Date().toISOString().split("T")[0];
    dateInput = today;
  }

  let dateObject = new Date(dateInput);

  // Format the date as 'YYYY-MM-DD HH:MM:SS'
  let formattedDate = dateObject.toISOString().slice(0, 19).replace("T", " ");

  $.ajax({
    url: "./controller/insight_data_controller.php",
    type: "POST",
    data: {
      date: formattedDate,
    },
    dataType: "json",
    success: function (response) {
      const target_revnue_KPI = 2_000_000;

      const selectedYear = response.selected_year;
      let thisDaySales =
        response.this_day_sales <= 0 ? 0 : response.this_day_sales;
      let yesterdaySales =
        response.yesterday_sales <= 0 ? 0 : response.yesterday_sales;

      let thisMonthSales =
        response.this_month_sales <= 0 ? 0 : response.this_month_sales;
      let lastMonthSales =
        response.last_month_sales <= 0 ? 0 : response.last_month_sales;

      let thisDayOrders =
        response.this_day_orders <= 0 ? 0 : response.this_day_orders;
      let yesterdayOrders =
        response.yesterday_orders <= 0 ? 0 : response.yesterday_orders;

      let thisYearRevenue =
        response.this_year_revenue <= 0 ? 0 : response.this_year_revenue;

      const selectedDaySalesFormat =
        formatNumberWithCommas(thisDaySales) + " ks";
      const selectedMonthlySalesFormat =
        formatNumberWithCommas(thisMonthSales) + " ks";

      $("#daily-sales").text(selectedDaySalesFormat);
      $("#monthly-sales").text(selectedMonthlySalesFormat);
      $("#total-order").text(thisDayOrders);

      let sales_percentage = 100;
      if (thisDaySales == 0 && yesterdaySales == 0) {
        sales_percentage = 0;
      } else if (yesterdaySales > 0) {
        sales_percentage =
          ((thisDaySales - yesterdaySales) / yesterdaySales) * 100;
      }
      sales_percentage = sales_percentage.toFixed(1);

      let order_percentage = 100;
      if (yesterdayOrders == 0 && thisDayOrders == 0) {
        order_percentage = 0;
      } else if (yesterdayOrders > 0 && thisDayOrders > 0) {
        order_percentage =
          ((thisDayOrders - yesterdayOrders) / yesterdayOrders) * 100;
      }
      order_percentage = order_percentage.toFixed(1);

      let monthly_sales_percentage = 100;

      if (thisMonthSales == 0 && lastMonthSales == 0) {
        monthly_sales_percentage = 0;
      } else if (thisMonthSales > 0 && lastMonthSales > 0) {
        monthly_sales_percentage =
          ((thisMonthSales - lastMonthSales) / lastMonthSales) * 100;
        monthly_sales_percentage = monthly_sales_percentage.toFixed(1);
      }

      textHighlight("#daily-sales-percent", sales_percentage);
      textHighlight("#total-order-percent", order_percentage);
      textHighlight("#monthly-sales-percent", monthly_sales_percentage);

      let yearly_sales_kpi_percentage = 0;
      if (thisYearRevenue > 0) {
        yearly_sales_kpi_percentage =
          (thisYearRevenue / target_revnue_KPI) * 100;
        yearly_sales_kpi_percentage = Math.round(yearly_sales_kpi_percentage);
      }

      $("#kpi-year").text(selectedYear + " Revenue KPI");
      $("#yearly-kpi-sales").text(thisYearRevenue + " MMK");
      $("#target-kpi-sales").text("Target KPI: " + target_revnue_KPI + " MMK");
      $("#yearly-kpi-sales-percent").text(yearly_sales_kpi_percentage + "%");

      // Calculate the stroke dash offset value

      let strokeDashOffset_kpi = 200 - yearly_sales_kpi_percentage * 2;

      // Update the SVG graphic based on the calculated percentage
      let svgYearlyKPICircle = $("#yearly-sales-kpi-card svg circle");

      svgYearlyKPICircle.css({
        "stroke-dashoffset": strokeDashOffset_kpi,
        "stroke-dasharray": "200",
      });
    },

    error: function (response) {
      console.log("fail");
    },
  });
}
