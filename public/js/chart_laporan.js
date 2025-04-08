const chartDonutPemasukan = () => {
  return {
    series: kategoriDataPemasukan,
    labels: kategoriLabelsPemasukan,
    chart: {
      height: 320,
      width: "100%",
      type: "donut",
    },
    stroke: {
      colors: ["transparent"],
    },
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(2) + "%";
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "Rp " + val.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        }
      }
    },
    legend: {
      position: 'bottom',
    },
  }
}
const chartDonutPengeluaran = () => {
  return {
    series: kategoriDataPengeluaran,
    labels: kategoriLabelsPengeluaran,
    chart: {
      height: 320,
      width: "100%",
      type: "donut",
    },
    stroke: {
      colors: ["transparent"],
    },
    dataLabels: {
      enabled: true,
      formatter: function (val, opts) {
        return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(2) + "%";
      },
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "Rp " + val.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        }
      }
    },
    legend: {
      position: 'bottom',
    },
  }
}

if (document.getElementById("donut-chart-pemasukan") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("donut-chart-pemasukan"), chartDonutPemasukan());
  chart.render();
}
if (document.getElementById("donut-chart-pengeluaran") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("donut-chart-pengeluaran"), chartDonutPengeluaran());
  chart.render();
}

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

