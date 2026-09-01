
<?php

session_start();

require_once "../conexion.php";

// ========================================
// VERIFICAR SESIÓN
// ========================================

if (!isset($_SESSION["usuario_id"])) {

    header("Location: ../login.php");
    exit;

}


// ========================================
// VERIFICAR ADMINISTRADOR
// ========================================

if ($_SESSION["rol"] !== "admin") {

    header("Location: ../index.php");
    exit;

}


// ========================================
// ESTADÍSTICAS
// ========================================

// Productos
$resultadoProductos = $conn->query(
    "SELECT COUNT(*) AS total FROM productos"
);

$totalProductos = $resultadoProductos->fetch_assoc()["total"];


// Usuarios
$resultadoUsuarios = $conn->query(
    "SELECT COUNT(*) AS total FROM usuarios"
);

$totalUsuarios = $resultadoUsuarios->fetch_assoc()["total"];


// Pedidos
$resultadoPedidos = $conn->query(
    "SELECT COUNT(*) AS total FROM pedidos"
);

$totalPedidos = $resultadoPedidos->fetch_assoc()["total"];


// Categorías
$resultadoCategorias = $conn->query(
    "SELECT COUNT(*) AS total FROM categorias"
);

$totalCategorias = $resultadoCategorias->fetch_assoc()["total"];

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrador - Tienda Tecnológica</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

</head>


<body>


<!-- ======================================== -->
<!-- NAVBAR -->
<!-- ======================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            💻 Panel Administrador

        </a>


        <div>

            <span class="text-white me-3">

                👤
                <?= htmlspecialchars($_SESSION["nombre"]) ?>

            </span>


            <a
                href="../logout.php"
                class="btn btn-danger btn-sm"
            >

                Cerrar sesión

            </a>

        </div>

    </div>

</nav>


<!-- ======================================== -->
<!-- CONTENIDO -->
<!-- ======================================== -->

<div class="container my-5">


    <div class="mb-5">

        <h1 class="fw-bold">

            👋 Bienvenido,
            <?= htmlspecialchars($_SESSION["nombre"]) ?>

        </h1>

        <p class="text-muted">

            Panel de administración de Tienda Tecnológica.

        </p>

    </div>



    <!-- ======================================== -->
    <!-- ESTADÍSTICAS -->
    <!-- ======================================== -->

    <div class="row g-4">


        <!-- PRODUCTOS -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="fs-1">
                        📦
                    </div>

                    <h5>
                        Productos
                    </h5>

                    <h2 class="fw-bold text-primary">

                        <?= $totalProductos ?>

                    </h2>

                    <a
                        href="productos.php"
                        class="btn btn-primary"
                    >

                        Administrar

                    </a>

                </div>

            </div>

        </div>



        <!-- CATEGORÍAS -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="fs-1">
                        📂
                    </div>

                    <h5>
                        Categorías
                    </h5>

                    <h2 class="fw-bold text-success">

                        <?= $totalCategorias ?>

                    </h2>

                    <a
                        href="productos.php"
                        class="btn btn-success"
                    >

                        Ver productos

                    </a>

                </div>

            </div>

        </div>



        <!-- USUARIOS -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="fs-1">
                        👥
                    </div>

                    <h5>
                        Usuarios
                    </h5>

                    <h2 class="fw-bold text-info">

                        <?= $totalUsuarios ?>

                    </h2>

                    <a
                        href="#"
                        class="btn btn-info text-white"
                    >

                        Usuarios

                    </a>

                </div>

            </div>

        </div>



        <!-- PEDIDOS -->

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <div class="fs-1">
                        🛒
                    </div>

                    <h5>
                        Pedidos
                    </h5>

                    <h2 class="fw-bold text-warning">

                        <?= $totalPedidos ?>

                    </h2>

                    <a
                        href="pedidos.php"
                        class="btn btn-warning"
                    >

                        Ver pedidos

                    </a>

                </div>

            </div>

        </div>


    </div>



    <!-- ======================================== -->
    <!-- ACCIONES -->
    <!-- ======================================== -->

    <div class="card shadow-sm mt-5">

        <div class="card-body">

            <h4 class="fw-bold mb-4">

                ⚙️ Acciones rápidas

            </h4>


            <div class="d-flex flex-wrap gap-2">


                <a
                    href="agregar_producto.php"
                    class="btn btn-primary"
                >

                    ➕ Agregar producto

                </a>


                <a
                    href="productos.php"
                    class="btn btn-outline-primary"
                >

                    📦 Administrar productos

                </a>


                <a
                    href="pedidos.php"
                    class="btn btn-outline-warning"
                >

                    🛒 Ver pedidos

                </a>


                <a
                    href="../index.php"
                    class="btn btn-outline-secondary"
                >

                    🏠 Ver tienda

                </a>


            </div>

        </div>

    </div>


</div>


<!-- ======================================== -->
<!-- FOOTER -->
<!-- ======================================== -->

<footer class="bg-dark text-white text-center py-4 mt-5">

    <div class="container">

        <h5>
            💻 Tienda Tecnológica
        </h5>

        <small>
            Panel de administración © 2026
        </small>

    </div>

</footer>


</body>

</html>
```