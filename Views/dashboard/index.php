<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URREA Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--    <link rel="stylesheet" href="Views/css/bootstrap.css">-->
    <!--    <link rel="stylesheet" href="Views/css/bootstrap.min.css">-->
    <!--    <link rel="stylesheet" href="Views/css/bootstrap-grid.min.css">-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="d-flex justify-content-center mt-4">
        <select name="customer" id="customer" class="p-2 rounded-2">
        </select>
    </div>
    <div>
        <div class="d-flex justify-content-center">
            <div class="rounded-4 shadow-sm col col-sm-12 col-md-12 col-lg-10 col-xl-10 p-4 m-3">
                <canvas id="myChart" height="20" width="50"></canvas>
                <div class="m-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col" class="text-secondary-emphasis fw-bold">Mes</td>
                                <td scope="col" class="text-secondary-emphasis fw-bold">Facturación año anterior</td>
                                <td scope="col" class="text-secondary-emphasis fw-bold">Facturación año actual</td>
                                <td scope="col" class="text-secondary-emphasis fw-bold">Crec vs año anterior</td>
                            </tr>
                        </thead>
                        <tbody id="bTblCustomerSale" class="fs-6">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <h3 class="text-secondary fst-normal">Promedio de compras por año</h3>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-5">
            <div class="col col-sm-12 col-md-12 col-lg-10 col-xl-10">
                <div id="carouselExampleCaptions" class="carousel slide border-1 rounded-4 shadow-sm">
                    <div class="carousel-inner" id="carouselAverages">
                    </div>
                    <button class="carousel-control-prev text-black" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-4 p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-4 p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-center mt-5">
            <h3 class="text-secondary fst-normal">Ventas por división</h3>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <div class="rounded-4 shadow-sm col col-sm-12 col-md-12 col-lg-10 col-xl-10 p-4 m-3">
                <div class="rounded-4 shadow-sm">
                    <div class="col m-4 p-4">
                        <p class="fst-italic text-secondary">Resumen de ventas por año</p>
                        <table class="table mt-4">
                            <thead>
                                <tr id="hTblYearlyDivision">
                                    <td scope="col" class="text-secondary-emphasis fw-bold">División</td>
                                </tr>
                            </thead>
                            <tbody id="bTblYearlyDivision">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="charts" class="d-flex rounded-4 shadow-sm flex-sm-column flex-md-column flex-lg-column flex-xl-row">
                </div>
            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="Views/js/jquery-3.7.1.min.js"></script>
    <script src="Views/js/index.js" type="module"></script>
</body>

</html>