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
            <div class="rounded-4 shadow-sm col col-md-8 col-lg-8 col-xl-8 p-4 m-3">
                <canvas id="myChart" height="20" width="50"></canvas>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <h3 class="text-secondary fst-normal">Promedio de compras por año</h3>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-5">
            <div class="bg-dark col col-md-8 col-lg-8 col-xl-8">
                <div id="carouselExampleCaptions" class="carousel slide border-1 rounded-4 shadow-sm">
                    <div class="carousel-indicators">
                        <button
                                type="button"
                                data-bs-target="#carouselExampleCaptions"
                                data-bs-slide-to="0"
                                class=""
                                aria-label="Slide 1"></button>
                        <button
                                type="button"
                                data-bs-target="#carouselExampleCaptions"
                                data-bs-slide-to="1"
                                aria-label="Slide 2"
                                class="active"
                                aria-current="true"></button>

                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="d-flex justify-content-center col bg-white">
                                <h4 class="">2022</h4>
                                <div>
                                    <table class="table mt-4" id="sales_averages">
                                        <thead>
                                        <tr>
                                            <td scope="col" class="text-secondary-emphasis">Promedio de compra mensual</td>
                                            <td scope="col" class="text-secondary-emphasis">Promedio de compra trimestral</td>
                                            <td scope="col" class="text-secondary-emphasis">Mes más alto de compras</td>
                                            <td scope="col" class="text-secondary-emphasis">Mes más bajo de compras</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Mes</td>
                                            <td>1213</td>
                                            <td>12312312</td>
                                            <td>12</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="d-flex justify-content-center col bg-white">
                                <h4>2023</h4>
                                <div>
                                    <table class="table mt-4" id="sales_averages">
                                        <thead>
                                        <tr>
                                            <td scope="col" class="text-secondary-emphasis">Promedio de compra mensual</td>
                                            <td scope="col" class="text-secondary-emphasis">Promedio de compra trimestral</td>
                                            <td scope="col" class="text-secondary-emphasis">Mes más alto de compras</td>
                                            <td scope="col" class="text-secondary-emphasis">Mes más bajo de compras</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Mes</td>
                                            <td>1213</td>
                                            <td>12312312</td>
                                            <td>12</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <button
                            class="carousel-control-prev text-black"
                            type="button"
                            data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev"
                    >
                        <span class="carousel-control-prev-icon bg-dark rounded-4 p-2" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button
                            class="carousel-control-next"
                            type="button"
                            data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next"
                    >
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
            <div class="rounded-4 shadow-sm col col-md-8 col-lg-8 col-xl-8 p-4 m-3">
                <div class="rounded-4 shadow-sm">
                    <div class="col m-4 p-4 rounded-3 shadow-sm">
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
                <div id="charts" class="d-flex rounded-4 shadow-sm">
                </div>
            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="Views/js/jquery-3.7.1.min.js"></script>
    <script src="Views/js/index.js" type="module"></script>
</body>

</html>