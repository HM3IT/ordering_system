$(document).ready(function () {
  const ctx = document.getElementById("chart-bars").getContext("2d");
  const ctx2 = document.getElementById("line-chart").getContext("2d");
  const ctx3 = document.getElementById("monthly-line-chart").getContext("2d");

  const horizontalAxis = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

  const daysOfMonth = [];
  for (let i = 1; i <= 31; i++) {
    daysOfMonth.push(i);
  }

  function hasOrder(array) {
    let result = false;
    array.forEach((val) => {
      if (val > 0) {
        result = true;
      }
    });
    return result;
  }

  $.ajax({
    url: "./controller/bar_chart_data_controller.php?chart-bar-data",
    type: "POST",
    dataType: "json",
    success: function (response) {
      let orderCountAry = Object.values(response.orderCountData);

      if (!hasOrder(orderCountAry)) {
        $("#no-order-info").show();
        $("#no-sales-info").show();
        $("#chart-bars").hide();
        $("#line-chart").hide();
        return;
      }

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: horizontalAxis,
          datasets: [
            {
              label: "Orders",
              tension: 0.4,
              borderWidth: 0,
              borderRadius: 4,
              borderSkipped: false,
              backgroundColor: "rgba(255, 255, 255, .8)",
              data: orderCountAry,
              maxBarThickness: 20,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: "index",
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: "rgba(255, 255, 255, .2)",
              },
              ticks: {
                suggestedMin: 0,
                suggestedMax: 500,
                beginAtZero: true,
                padding: 10,
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
                color: "#fff",
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: "rgba(255, 255, 255, .2)",
              },
              ticks: {
                display: true,
                color: "#f8f9fa",
                padding: 10,
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
          },
        },
      });

      let totalSaleAry = Object.values(response.weeklySales);
      new Chart(ctx2, {
        type: "line",
        data: {
          labels: horizontalAxis,
          datasets: [
            {
              label: "Total sales (MMK)",
              tension: 0,
              borderWidth: 0,
              pointRadius: 5,
              pointBackgroundColor: "rgba(255, 255, 255, .8)",
              pointBorderColor: "transparent",
              borderColor: "rgba(255, 255, 255, .8)",
              borderWidth: 4,
              backgroundColor: "transparent",
              fill: true,
              data: totalSaleAry,
              maxBarThickness: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: "index",
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: "rgba(255, 255, 255, .2)",
              },
              ticks: {
                display: true,
                padding: 10,
                color: "#f8f9fa",
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5],
              },
              ticks: {
                display: true,
                color: "#f8f9fa",
                padding: 10,
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
          },
        },
      });

      let lastMonthSaleAry = Object.values(response.lastMonthSales);
      let thisMonthSaleAry = Object.values(response.thisMonthSales);

      const currentDate = new Date();
      const currentMonthName = currentDate.toLocaleString("default", {
        month: "long",
      });

      const lastMonth = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth() - 1
      );
      const lastMonthName = lastMonth.toLocaleString("default", {
        month: "long",
      });

      new Chart(ctx3, {
        type: "line",
        data: {
          labels: daysOfMonth,
          datasets: [
            {
              label: lastMonthName + " Month sales (MMK)",
              tension: 0,
              borderWidth: 0,
              pointRadius: 5,
              pointBackgroundColor: "rgba(255, 119, 130, .8)",
              pointBorderColor: "transparent",
              borderColor: "rgba(255, 119, 130, .8)",
              borderWidth: 4,
              backgroundColor: "transparent",
              fill: true,
              data: lastMonthSaleAry,
              maxBarThickness: 6,
            },
            {
              //  label for the second dataset
              label: currentMonthName + " Month sales (MMK)",
              tension: 0,
              borderWidth: 0,
              pointRadius: 5,
              pointBackgroundColor: "rgba(10, 135, 94, .8)",
              pointBorderColor: "transparent",
              borderColor: "rgba(10, 135, 94, .8)",
              borderWidth: 4,
              backgroundColor: "transparent",
              fill: true,
              //  the comparison data array
              data: thisMonthSaleAry,
              maxBarThickness: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: "index",
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: "rgba(255, 255, 255, .2)",
              },
              ticks: {
                display: true,
                padding: 10,
                color: "#ff7782",
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5],
              },
              ticks: {
                display: true,
                color: "#f8f9fa",
                padding: 10,
                font: {
                  size: 14,
                  weight: 300,
                  family: "Roboto",
                  style: "normal",
                  lineHeight: 2,
                },
              },
            },
          },
        },
      });
    },
    error: function (error) {
      console.log("Error fetching data:", error);
    },
  });
});
