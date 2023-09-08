import ChartManagement from "./ChartManagement.js";

class Division {

    mobileScreenWidth = 420;

    constructor() {
    }

    init() {
        this.getYearSales();
        this.getMonthlySales();
        this.getSales();
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
            chartCanvas.height = window.screen.width <= self.mobileScreenWidth ? 80 : 60;

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
                        responsive: true,
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

    computeAverages = function (response) {
        const self = this;
        let chartMng = new ChartManagement();
        let lstYearsData = [];

        response.sales.forEach((yearData) => {
            let months = [];
            chartMng.getLabels(yearData.year);
            chartMng.labels.forEach((date) => {
                let monthsData = yearData.data.filter((saleData) => date === `${saleData.month} ${saleData.year}`);

                const divideIn = monthsData.length;
                const totalAmount = monthsData.reduce((carry, sale) => {
                    carry += parseFloat(sale.amount);
                    return carry;
                }, 0);

                if (divideIn > 0) {
                    months.push({
                        month: date,
                        amount: totalAmount.toFixed(2),
                        average: (totalAmount / divideIn).toFixed(2),
                        divideIn: divideIn,
                    });
                }
            })

            lstYearsData.push({
                year: yearData.year,
                months: months
            });
        });

        let yearsData = [];

        lstYearsData.forEach((yearData) => {
            let monthlyAverages = [];
            let quarterlyAverages = [];


            let step = 1;
            let quarterly = 1;
            let quarterlySum = 0;

            yearData.months.forEach((month) => {
                if (step % 3 === 0) {
                    const label = 'T #' + quarterly;
                    quarterlyAverages.push({
                        label: label,
                        value: (quarterlySum / 3).toFixed(2),
                    });

                    quarterlySum = 0;
                    quarterly++;
                }

                monthlyAverages.push({
                    label: month.month,
                    value: month.average,
                });

                quarterlySum += Number.parseFloat(month.amount);
                step++;
            });

            let sortedAverages = [...monthlyAverages];
            sortedAverages.sort((a, b) => a.value - b.value);

            const worstMonth = sortedAverages[0];
            const bestMonth = sortedAverages[sortedAverages.length - 1];

            yearsData.push({
                year: yearData.year,
                monthlyAverage: monthlyAverages,
                quarterlyAverage: quarterlyAverages,
                worstMonth: worstMonth,
                bestMonth: bestMonth,
            });
        });

        self.loadAverages(yearsData);
    }

    loadAverages = function (yearsData) {
        const self = this;
        let carouselAverages = document.getElementById('carouselAverages');

        let active = true;
        yearsData.forEach((yearData) => {
            let divCarouselItem = document.createElement('div');
            const activeItem = active ? 'active' : 's';
            divCarouselItem.classList.add('carousel-item', activeItem)
            active = false;

            let divContainer = document.createElement('div');
            const divContainerClasses = "d-flex justify-content-center flex-column align-items-center col bg-white";
            divContainer.classList.add(...divContainerClasses.split(' '));

            let hTitle = document.createElement('h4');
            hTitle.innerText = yearData.year;

            let divTable = document.createElement('div');
            divTable.classList.add('col-8');
            divTable.appendChild(self.getAverageElement(yearData.monthlyAverage, 'Promedio mensual'));
            divTable.appendChild(self.getAverageElement(yearData.quarterlyAverage, 'Promedio trimestral', false));
            divTable.appendChild(self.getMonthElement(yearData.worstMonth, 'Més más bajo'));
            divTable.appendChild(self.getMonthElement(yearData.bestMonth, 'Més más alto'));

            divContainer.appendChild(hTitle);
            divContainer.appendChild(divTable);

            divCarouselItem.appendChild(divContainer);
            carouselAverages.appendChild(divCarouselItem);
        })
    }

    getDivContainerChild = function () {
        let divTile = document.createElement('div');
        const divTileClasses = 'd-flex justify-content-center border-bottom';
        divTile.classList.add(...divTileClasses.split(' '));

        return divTile;
    }

    getChildLabelElement = function (label) {
        const divChildClasses = 'col-3 d-flex justify-content center align-items-center';
        let childLabelElemeent = document.createElement('div');
        childLabelElemeent.classList.add(...divChildClasses.split(' '));
        childLabelElemeent.innerHTML = `<p class="fw-bold text-secondary">${label}</p>`;

        return childLabelElemeent;
    }

    getMonthElement = function (month, label) {
        const self = this;

        const childLabelElement = self.getChildLabelElement(label);

        let childValueElement = document.createElement('div');
        childValueElement.classList.add('col');

        let divColumn = document.createElement('div');
        divColumn.classList.add('col','d-flex');
        divColumn.innerHTML = `
            <div class="col-4">${month.label.split(' ')[0]}</div>
            <div class="col fw-bold">$${month.value}</div>
        `;

        childValueElement.appendChild(divColumn);

        let divChild = self.getDivContainerChild();
        divChild.appendChild(childLabelElement);
        divChild.appendChild(childValueElement);

        return divChild;
    }

    getAverageElement = function (lstData, label, split = true) {
        const self = this;

        let childLabelElement = self.getChildLabelElement(label);

        let divChildValueElement = document.createElement('div');
        divChildValueElement.classList.add('col', 'mb-3');

        lstData.forEach((data) => {
            let divColumn = document.createElement('div');
            divColumn.classList.add('col','d-flex','border-top');
            divColumn.innerHTML = `
                <div class="col-4">${split ? data.label.split(' ')[0] : data.label}</div>
                <div class="col fw-bold">$${data.value}</div>
            `;

            divChildValueElement.appendChild(divColumn);
        });

        let divChild = self.getDivContainerChild()
        divChild.appendChild(childLabelElement);
        divChild.appendChild(divChildValueElement);

        return divChild;
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

    getSales = function () {
        const self = this;
        $.ajax({
            url: 'sales', //scope + `?type=customer&search=${customer}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                self.computeAverages(jsonResponse);
            },
            error: function (response) {
                console.error(response);
            }
        });
    }
}

export default Division;