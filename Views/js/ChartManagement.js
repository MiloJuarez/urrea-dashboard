class ChartManagement {
    baseUrl = '';
    labels = [];
    customers = [];
    data = [];
    mobileScreenWidth = 420;

    barChart = null;

    constructor() {}

    init() {
        this.initialize();
    }

    getLabels = function (providedYear = null) {
        let self = this;
        let year = providedYear ?? new Date().getFullYear();
        self.labels = [
            'Ene ' + year,
            'Feb ' + year,
            'Mar ' + year,
            'Abr ' + year,
            'May ' + year,
            'Jun ' + year,
            'Jul ' + year,
            'Ago ' + year,
            'Sep ' + year,
            'Oct ' + year,
            'Nov ' + year,
            'Dic ' + year,
        ];
    };

    initialize = function () {
        let self = this;
        let selectCustomers = document.getElementById('customer');

        $.ajax({
            url: 'customers',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                self.customers = jsonResponse.customers;

                let currentCustomer = window.localStorage.getItem('customer') ?? self.customers[0].customer;
                currentCustomer = currentCustomer.replaceAll(' ', '_');
                self.customers.forEach((customer) => {
                    let option = document.createElement('option');
                    let optCustomer = customer.customer.replaceAll(' ', '_');
                    option.value = optCustomer;
                    option.text = customer.name;

                    if (currentCustomer === optCustomer) {
                        option.selected = true;
                    }
                    selectCustomers.appendChild(option);
                });

                selectCustomers.onchange = function (e) {
                    window.localStorage.setItem('customer', this.value);
                    self.getData('sales', this.value);
                }

                self.getData('sales', currentCustomer);
            },
            error: function (response) {
                console.error(response)
            }
        });
    }

    getData = function (scope, customer) {
        const self = this;
        $.ajax({
            url: scope + `?type=customer&search=${customer}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                self.data = jsonResponse.sales;
                self.getLabels();
                self.drawGraphics(jsonResponse);
                self.loadCustomerSaleData();
            },
            error: function (response) {
                console.error(response);
            }
        });
    }

    drawGraphics = function (response) {
        let ctx = document.getElementById('myChart');
        const self = this;

        ctx.width = 100;
        ctx.height = window.screen.width <= self.mobileScreenWidth ? 80 : 30;

        let datasets = [];

        if (self.barChart) {
            self.barChart.destroy();
        }
        let count = 1;

        const titles = [
            'Consumo año anterior',
            "Consumo año actual",
        ];

        const bgColorWorst = [
            '#d38d8d',
            '#c06740',
        ];
        const bgColorBest = [
            '#a7fc61',
            '#38e00c',
        ];

        const backgroundColors = [
            '#fff',
            '#044393',
        ];

        let step = 0;

        for (let sale of response.sales) {
            let bgColors = [];
            let data = [];

            let amounts = sale.data.map((saleData) => saleData.amount);

            let minAmount = Math.min(...amounts);
            let maxAmount = Math.max(...amounts);

            for (let saleData of sale.data) {
                if (saleData.amount == minAmount) {
                    bgColors.push(bgColorWorst[step])
                } else if (saleData.amount == maxAmount) {
                    bgColors.push(bgColorBest[step])
                } else {
                    bgColors.push(backgroundColors[step]);
                }
                    data.push(saleData.amount);

            }
            let dataset = {
                label: titles[step],
                data: data,
                borderWidth: 1,
                backgroundColor: bgColors,
            }

            if (step === 0) {
                dataset = {
                    label: titles[step],
                    data: data,
                    borderWidth: 1,
                    backgroundColor: bgColors,
                    borderColor: '#6d9dda',
                }
            }

            datasets.push(dataset);

            step++;
        }

        self.barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: self.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'VOLÚMEN DE FATURACIÓN',
                    },
                    subtitle: {
                        display: true,
                        text: 'Se muestra el historico de mes a mes del volúmen adquirirdo Vs Año anterior'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
            },

        });
    }

    loadCustomerSaleData = function (data) {
        const self = this;
        let bTblCustomerSale = document.getElementById('bTblCustomerSale');
        bTblCustomerSale.innerHTML = ''; // Clear for loaded data

        let lstSales = [];

        const previousYearData = self.data[0];
        const currentYearData = self.data[1];

        for (const yearData of previousYearData.data) {
            let currentYearValue = null;
            const currentYearSale = currentYearData.data.filter((monthSale) => {
                return monthSale.month === yearData.month
            });

            if (currentYearSale.length > 0) {
                currentYearValue = Number.parseFloat(currentYearSale[0].amount);
            }

            let growthPercentage = currentYearValue ? ((currentYearValue * 100) / yearData.amount) - 100 : null;

            lstSales.push({
                month: yearData.month,
                previousYearValue: Number.parseFloat(yearData.amount).toFixed(2),
                currentYearValue: currentYearValue ? currentYearValue.toFixed(2) : '-',
                growthPercentage: growthPercentage !== null ? growthPercentage.toFixed(2) : '-',
            });
        }

        lstSales.forEach((saleData) => {
            let row = document.createElement('tr');
            let cellMonth = document.createElement('td');
            let cellPreviousYear = document.createElement('td');
            let cellCurrentYear = document.createElement('td');
            let cellGrowthPercentage = document.createElement('td');

            cellMonth.textContent = saleData.month;
            cellPreviousYear.textContent = '$' + saleData.previousYearValue;
            cellCurrentYear.textContent = saleData.currentYearValue !== '-' ? '$' + saleData.currentYearValue : saleData.currentYearValue;

            if (saleData.growthPercentage < 0) {
                cellGrowthPercentage.classList.add('text-danger');
            }

            cellGrowthPercentage.textContent = saleData.growthPercentage !== '-' ? saleData.growthPercentage + '%' : saleData.growthPercentage;

            row.appendChild(cellMonth);
            row.appendChild(cellPreviousYear);
            row.appendChild(cellCurrentYear);
            row.appendChild(cellGrowthPercentage);

            bTblCustomerSale.appendChild(row);
        });
    }
}

export default ChartManagement;