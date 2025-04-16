document.addEventListener('DOMContentLoaded', function() {
    // Check if elements exist to prevent JavaScript errors
    const incomeChartEl = document.getElementById("donut-chart-pemasukan");
    const expenseChartEl = document.getElementById("donut-chart-pengeluaran");
    
    // Function to create income chart
    if (incomeChartEl && typeof ApexCharts !== 'undefined' && kategoriDataPemasukan.length > 0) {
        const incomeOptions = {
            series: kategoriDataPemasukan,
            chart: {
                height: 320,
                type: 'donut',
            },
            labels: kategoriLabelsPemasukan,
            colors: typeof kategoriColorsPemasukan !== 'undefined' ? kategoriColorsPemasukan : undefined,
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                    }
                }
            },
            dataLabels: {
                enabled: false,
                formatter: function (val, opts) {
                    return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(1) + "%";
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
            },
        };

        const incomeChart = new ApexCharts(incomeChartEl, incomeOptions);
        incomeChart.render();
    }
    
    // Function to create expense chart
    if (expenseChartEl && typeof ApexCharts !== 'undefined' && kategoriDataPengeluaran.length > 0) {
        const expenseOptions = {
            series: kategoriDataPengeluaran,
            chart: {
                height: 320,
                type: 'donut',
            },
            labels: kategoriLabelsPengeluaran,
            colors: typeof kategoriColorsPengeluaran !== 'undefined' ? kategoriColorsPengeluaran : undefined,
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                    }
                }
            },
            dataLabels: {
                enabled: false,
                formatter: function (val, opts) {
                    return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(1) + "%";
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
            },
        };

        const expenseChart = new ApexCharts(expenseChartEl, expenseOptions);
        expenseChart.render();
    }
});

const options = {
  series: [
    {
      name: "Pemasukan",
      data: [pemasukan],
      color: "#1ABC9C",
    },
    {
      name: "Pengeluaran",
      data: [pengeluaran],
      color: "#EB3B3B",
    },
  ],
  chart: {
    height: "150%",
    type: "bar", // pakai 'bar' biar jelas kalau cuma 1 data per kategori
    toolbar: {
      show: false,
    },
  },
  plotOptions: {
    bar: {
      borderRadius: 0,
      columnWidth: '30%',
    }
  },
  dataLabels: {
    enabled: true
  },
  xaxis: {
    categories: ['Bulan Ini'],
  },
  yaxis: {
    labels: {
      formatter: function (value) {
        return 'Rp ' + value.toLocaleString('id-ID');
      }
    }
  },
  tooltip: {
    y: {
      formatter: function (value) {
        return 'Rp' + value.toLocaleString('id-ID');
      }
    }
  }
};

// Initialize the chart
if (document.getElementById("legend-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("legend-chart"), options);
    chart.render();
}

