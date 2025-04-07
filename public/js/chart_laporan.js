const getChartOptions = () => {
  return {
    series: [18.7, 25.3, 35.4, 10.2, 5.1, 2.8, 2.5],
    colors: ["#9c446e", "#7e5686", "#9f7ca6", "#a77e96", "#a28799", "#b79599", "#e9967a"],
    chart: {
      height: 300,
      width: "100%",
      type: "donut",
    },
    stroke: {
      colors: ["#ffffff"],
      width: 2
    },
    plotOptions: {
      pie: {
        donut: {
          size: "70%",
          background: "transparent",
        },
        expandOnClick: false
      }
    },
    tooltip: {
      enabled: true,
      y: {
        formatter: function(value) {
          return value.toFixed(1) + '%';
        },
        title: {
          formatter: function(seriesName) {
            return seriesName;
          }
        }
      }
    },
    dataLabels: {
      enabled: false
    },
    legend: {
      show: false
    },
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          height: 250
        }
      }
    }]
  }
}

// Initialize the chart
if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
  chart.render();
  
  // Get all the checkboxes
  const checkboxes = document.querySelectorAll('#devices input[type="checkbox"]');
  
  // Initial data mappings for different devices
  const deviceData = {
    desktop: [22.5, 30.1, 25.4, 12.2, 6.3, 2.1, 1.4],
    tablet: [15.6, 28.2, 33.5, 8.7, 7.2, 4.5, 2.3],
    mobile: [18.7, 25.3, 35.4, 10.2, 5.1, 2.8, 2.5] // Default data
  };
  
  // Labels for each segment
  const labels = ["Jajan", "Makanan", "Transportasi", "Belanja", "Tagihan", "Hiburan", "Lainnya"];
  
  // Function to handle checkbox changes
  function handleCheckboxChange() {
    // Get all checked checkboxes
    const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
    
    if (checkedBoxes.length === 0) {
      // If no checkboxes checked, use default data
      chart.updateOptions({
        series: deviceData.mobile,
        labels: labels
      });
    } else {
      // Use data for the first checked checkbox
      const deviceType = checkedBoxes[0].value;
      chart.updateOptions({
        series: deviceData[deviceType],
        labels: labels
      });
    }
  }
  
  // Attach event listeners
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', handleCheckboxChange);
  });
}

const options = {
series: [
  {
    name: "Pemasukan",
    data: [1500, 1418, 1456, 1526, 1356, 1256],
    color: "#1ABC9C",
  },
  {
    name: "Pengeluaran",
    data: [643, 413, 765, 412, 1423, 1731],
    color: "#EB3B3B",
  },
],
chart: {
  height: "100%",
  maxWidth: "100%",
  type: "area",
  fontFamily: "Inter, sans-serif",
  dropShadow: {
    enabled: false,
  },
  toolbar: {
    show: false,
  },
},
tooltip: {
  enabled: true,
  x: {
    show: false,
  },
},
legend: {
  show: true
},
fill: {
  type: "gradient",
  gradient: {
    opacityFrom: 0.55,
    opacityTo: 0,
    shade: "#1C64F2",
    gradientToColors: ["#1C64F2"],
  },
},
dataLabels: {
  enabled: false,
},
stroke: {
  width: 6,
},
grid: {
  show: false,
  strokeDashArray: 4,
  padding: {
    left: 2,
    right: 2,
    top: -26
  },
},
xaxis: {
  labels: {
    show: false,
  },
  axisBorder: {
    show: false,
  },
  axisTicks: {
    show: false,
  },
},
yaxis: {
  show: false,
  labels: {
    formatter: function (value) {
      return '$' + value;
    }
  }
},
}

// Initialize the chart
if (document.getElementById("legend-chart") && typeof ApexCharts !== 'undefined') {
const chart = new ApexCharts(document.getElementById("legend-chart"), options);
chart.render();
}
