compareDates() ;
function formatNumberWithCommas(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

  function compareDates() {

    let dateInput = $("#dateInput").val();

    if (dateInput === '') {
        // Get today's date in the format 'YYYY-MM-DD'
        let today = new Date().toISOString().split('T')[0];
        dateInput = today;
      }

    let dateObject = new Date(dateInput);


    // Format the date as 'YYYY-MM-DD HH:MM:SS'
    let formattedDate = dateObject.toISOString().slice(0, 19).replace('T', ' ');

    $.ajax({
      url: "./controller/performance_card_controller.php",
      type: "POST",
      data: {
        date: formattedDate
      },
      dataType: "json",
      success: function(response) {
        let daily_sales = response.daily_sales;
        let orderCount = response.count;
        let monthly_sales = response.monthly_sales;
        if (orderCount == null || daily_sales == null ||monthly_sales == null) {
          daily_sales = 0;
          orderCount = 0;
        }
       
        const daily_salesFormat = formatNumberWithCommas(daily_sales) + " ks";
        const monthly_salesFormat = formatNumberWithCommas(monthly_sales) + " ks";
        
        $("#daily-sales").text(daily_salesFormat);
        $("#total-order").text(orderCount);
        $("#monthly-sales-kpi").text(monthly_salesFormat);

        const target_sales = 32000000;
        const target_orders = 100;

      
       const target_orders_text = "Target order: " + target_orders.toLocaleString();
      const monthly_sales_text = monthly_sales.toLocaleString() + " ks";

 
        $("#target-order").text(target_orders_text);
        $("#monthly-sales-kpi").text(monthly_sales_text );
     

        let sales_percentage = (daily_sales / target_sales) * 100;
        sales_percentage = sales_percentage.toFixed(1);

        let order_percentage = (orderCount / target_orders) * 100;
        order_percentage = order_percentage.toFixed(1);

        let monthly_sales_percentage = (monthly_sales / target_sales) * 100;
        monthly_sales_percentage = monthly_sales_percentage.toFixed(1);


        $("#daily-sales-percent").text(sales_percentage + "%");
        $("#total-order-percent").text(order_percentage + "%");
        $("#monthly-sales-percent").text(monthly_sales_percentage + "%");

        // Update the SVG graphic based on the calculated percentage
        let svgSalesCircle = $(".insights .sales-card svg circle");
        let svgOrderCircle = $(".insights .order-card svg circle");
        let svgMonthlyKPICircle = $(".insights .montly-sales-card svg circle");
        // Calculate the stroke dash offset value
        let strokeDashOffset_sales = 200 - (sales_percentage * 2);
        let strokeDashOffset_orders = 200 - (order_percentage * 2);
        let strokeDashOffset_kpi = 200 - (monthly_sales_percentage * 2);


        // Set the new styles dynamically

        svgSalesCircle.css({
          "stroke-dashoffset": strokeDashOffset_sales,
          "stroke-dasharray": "200"
        });
        svgOrderCircle.css({
          "stroke-dashoffset": strokeDashOffset_orders,
          "stroke-dasharray": "200"
        });

        svgMonthlyKPICircle.css({
            "stroke-dashoffset": strokeDashOffset_kpi,
            "stroke-dasharray": "200"
          });

      }

    });
  }
