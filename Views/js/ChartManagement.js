class ChartManagement {
    baseUrl = '';
    labels = [];
    customers = [];
    data = [];

    barChart = null;

    constructor() {
        this.getData = this.getData.bind(this);
    }

    init() {
        this.initialize();
    }

    getLabels = function (startYear, endYear) {
        let self = this;
        let year = new Date().getFullYear();
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
                let currentCustomer = window.localStorage.getItem('customer') ?? self.customers[0].customer;

                self.customers = jsonResponse.customers;
                self.customers.forEach((customer) => {
                    let option = document.createElement('option');
                    let optCustomer = customer.customer.replaceAll(' ', '_');
                    option.value = optCustomer;
                    option.text = customer.customer;

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
                console.log(response)
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
                self.getLabels(jsonResponse.year_range.start_year, jsonResponse.year_range.end_year);
                self.drawGraphics(jsonResponse);
            },
            error: function (response) {
                console.log('ERROR:',response);
            }
        });
    }

    drawGraphics = function (response) {
        let ctx = document.getElementById('myChart');
        let datasets = [];
        const self = this;

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
            // console.log(sale);
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

        // console.log(datasets);

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
}

export default ChartManagement;