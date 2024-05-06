document.addEventListener("DOMContentLoaded", function () {
    var mergedData = [];

    barPemasukan.forEach((pemasukan) => {
        var bulan = pemasukan.bulan;
        var total = pemasukan.total_pemasukan;
        var existingData = mergedData.find((data) => data.bulan === bulan);
        if (existingData) {
            existingData.pemasukan = total;
        } else {
            mergedData.push({ bulan: bulan, pemasukan: total });
        }
    });

    barPengeluaran.forEach((pengeluaran) => {
        var bulan = pengeluaran.bulan;
        var total = pengeluaran.total_pengeluaran || 0;
        var existingData = mergedData.find((data) => data.bulan === bulan);
        if (existingData) {
            existingData.pengeluaran = total;
        } else {
            mergedData.push({ bulan: bulan, pengeluaran: total });
        }
    });

    barBiaya.forEach((biaya) => {
        var bulan = biaya.bulan;
        var total = biaya.total || 0;
        var existingData = mergedData.find((data) => data.bulan === bulan);
        if (existingData) {
            existingData.biaya = total;
        } else {
            mergedData.push({ bulan: bulan, biaya: total });
        }
    });

    mergedData.sort((a, b) => {
        return new Date(a.bulan) - new Date(b.bulan);
    });

    var categories = mergedData.map((data) => data.bulan);
    var series = [
        {
            name: "Pemasukan",
            data: mergedData.map((data) => data.pemasukan || 0),
        },
        {
            name: "Pengeluaran",
            data: mergedData.map((data) => data.pengeluaran || 0),
        },
        { name: "Biaya", data: mergedData.map((data) => data.biaya || 0) },
    ];

    var options = {
        series: series,
        chart: {
            type: "bar",
            height: 350,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "55%",
                endingShape: "rounded",
            },
        },
        dataLabels: {
            enabled: false,
        },
        title: {
            text: "Data Per-Bulan Tahun Ini",
            align: "left",
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: categories,
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return "Rp. " + value.toLocaleString("id-ID");
                },
            },
            title: {
                text: "Rp. (rupiah)",
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "Rp. " + val.toLocaleString("id-ID") + ",00";
                },
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#column"), options);
    chart.render();
});
