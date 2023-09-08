import ChartManagement from "./ChartManagement.js";

class Division {
    constructor() {
    }

    init() {
        console.log('Division->init()');
        this.getYearSales();
        this.getMonthlySales();
    }

    __loadYearlySales = function (response) {
        let tblHead = document.getElementById('hTblYearlyDivision');
        response.sales.years.forEach((year) => {
            let td = document.createElement('td');
            td.textContent = year;
            td.scope = 'col';
            td.classList.add('text-secondary-emphasis', 'fw-bold');
            tblHead.appendChild(td);
        });

        let tblBody = document.getElementById('bTblYearlyDivision');
        response.sales.data.forEach((data) => {
            let row = document.createElement('tr');
            let cellDivision = document.createElement('td');
            let lblDivisionAdded = false;
            data.forEach((division) => {
                if (!lblDivisionAdded) {
                    cellDivision.textContent = division.division;
                    cellDivision.classList.add('fs-6', 'text-secondary');
                    row.appendChild(cellDivision);
                    lblDivisionAdded = true;
                }
                let cell = document.createElement('td');
                cell.textContent = '$' + division.amount;
                row.appendChild(cell);
            });

            tblBody.appendChild(row);
        });
    }

    __drawMonthlySalesChart = function (response) {
        const self = this;
        let chartsContainer = document.getElementById('charts');

        let chartMng = new ChartManagement();
        response.sales.forEach((yearlyData) => {
            let datasets = [];

            let chartContainer = document.createElement('div');
            let chartCanvas = document.createElement('canvas');
            chartCanvas.width = 100;
            chartCanvas.height = 50;

            let classes = 'col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 p-2';
            chartContainer.classList.add(...classes.split(' '));

            chartContainer.appendChild(chartCanvas);
            chartsContainer.appendChild(chartContainer);

            let year = '';
            yearlyData.forEach((division) => {
                let data = [];
                year = division.year;

                division.months.forEach((month) => {
                    data.push({
                        id: month.date,
                        nested: {
                            value: Number.parseFloat(month.amount)
                        }
                    });
                });

                datasets.push({
                    label: division.division,
                    data: data,
                    borderWidth: 1,
                });
            });

            chartMng.getLabels(year);

            const barChart = new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: chartMng.labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    parsing: {
                        xAxisKey: 'id',
                        yAxisKey: 'nested.value'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'VENTA MENSUAL POR DIVISIÓN - ' + year,
                        },
                        subtitle: {
                            display: true,
                            text: 'Se muestra el historico de mes a mes'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                },
            });
        });
    }

    getYearSales = function() {
        let self = this;
        $.ajax({
            url: 'sales?type=division&search=year',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                const jsonResponse = JSON.parse(response);
                self.__loadYearlySales(jsonResponse);
            },
            error: function (response) {
                console.error(response);
            }
        });
    }

    getMonthlySales = function() {
        const self = this;
        $.ajax({
            url: 'sales?type=division&search=month',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                const jsonResponse = JSON.parse(response);
                self.__drawMonthlySalesChart(jsonResponse);
            },
            error: function (response) {
                console.error(response);
            }
        });
    }
}

export default Division;