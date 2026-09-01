
<?php

session_start();
ini_set('display_errors', 0); // Oculta cualquier aviso técnico en pantalla

require_once "conexion.php";


// ========================================
// OBTENER PRODUCTOS
// ========================================

$sql = "SELECT
            productos.id,
            productos.nombre,
            productos.descripcion,
            productos.precio,
            productos.stock,
            productos.imagen,
            categorias.nombre AS categoria
        FROM productos
        INNER JOIN categorias
        ON productos.categoria_id = categorias.id
        ORDER BY productos.id DESC";

$resultado = $conn->query($sql);


// ========================================
// OBTENER CATEGORÍAS PARA EL MENÚ
// ========================================

$categorias = $conn->query(
    "SELECT id, nombre
     FROM categorias
     ORDER BY nombre ASC"
);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tienda Tecnológica
    </title>


    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        body {
            background-color: #f5f7fa;
        }

        .hero {

            background: linear-gradient(
                135deg,
                #0d6efd,
                #6610f2
            );

            color: white;

            padding: 70px 20px;

            border-radius: 0 0 30px 30px;
        }

        .hero h1 {

            font-size: 3rem;

            font-weight: bold;
        }

        .producto-card {

            transition: 0.3s;

            border: none;
        }

        .producto-card:hover {

            transform: translateY(-5px);
        }

        .producto-imagen {

            height: 200px;

            object-fit: contain;

            padding: 20px;
        }

        .precio {

            font-size: 1.4rem;

            font-weight: bold;

            color: #0d6efd;
        }

        .footer {

            margin-top: 70px;
        }

    </style>

</head>


<body>


<!-- ======================================== -->
<!-- NAVBAR -->
<!-- ======================================== -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">


        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            💻 Tienda Tecnológica

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarPrincipal"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarPrincipal"
        >


            <ul class="navbar-nav ms-auto">


                <!-- INICIO -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="index.php"
                    >

                        🏠 Inicio

                    </a>

                </li>


                <!-- PRODUCTOS -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="productos.php"
                    >

                        📦 Productos

                    </a>

                </li>


                <!-- ======================================== -->
                <!-- CATEGORÍAS DESPLEGABLE -->
                <!-- ======================================== -->

                <li class="nav-item dropdown">

                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >

                        📂 Categorías

                    </a>


                    <ul class="dropdown-menu dropdown-menu-dark">


                        <?php if (
                            $categorias &&
                            $categorias->num_rows > 0
                        ): ?>


                            <?php while (
                                $categoria =
                                $categorias->fetch_assoc()
                            ): ?>


                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="productos.php?categoria=<?= $categoria["id"] ?>"
                                    >

                                        📦
                                        <?= htmlspecialchars(
                                            $categoria["nombre"]
                                        ) ?>

                                    </a>

                                </li>


                            <?php endwhile; ?>


                        <?php endif; ?>


                    </ul>

                </li>


                <!-- CARRITO -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="carrito.php"
                    >

                        🛒 Carrito

                    </a>

                </li>


                <?php if (
                    isset($_SESSION["usuario_id"])
                ): ?>


                    <!-- MIS PEDIDOS -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="mis_pedidos.php"
                        >

                            📋 Mis pedidos

                        </a>

                    </li>


                    <!-- ADMIN -->

                    <?php if (
                        $_SESSION["rol"] === "admin"
                    ): ?>

                        <li class="nav-item">

                            <a
                                class="nav-link text-warning"
                                href="admin/index.php"
                            >

                                ⚙️ Administrar

                            </a>

                        </li>

                    <?php endif; ?>


                    <!-- SALIR -->

                    <li class="nav-item">

                        <a
                            class="nav-link text-danger"
                            href="logout.php"
                        >

                            🚪 Salir

                        </a>

                    </li>


                <?php else: ?>


                    <!-- LOGIN -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="login.php"
                        >

                            🔐 Iniciar sesión

                        </a>

                    </li>


                    <!-- REGISTRO -->

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="registro.php"
                        >

                            📝 Registrarse

                        </a>

                    </li>


                <?php endif; ?>


            </ul>


        </div>

    </div>

</nav>



<!-- ======================================== -->
<!-- HERO -->
<!-- ======================================== -->

<section class="hero">

    <div class="container">

        <div class="row align-items-center">


            <div class="col-lg-7">


                <h1>

                    Tecnología al mejor precio 💻

                </h1>


                <p class="lead mt-3">

                    Encuentra celulares, laptops,
                    audífonos, accesorios y productos
                    tecnológicos.

                </p>


                <a
                    href="productos.php"
                    class="btn btn-light btn-lg mt-3"
                >

                    🛍️ Ver productos

                </a>


            </div>


            <div class="col-lg-5 text-center mt-4 mt-lg-0">

                <div class="display-1">

                    💻 📱 🎧

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ======================================== -->
<!-- PRODUCTOS -->
<!-- ======================================== -->

<div class="container my-5">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h2 class="fw-bold mb-1">

                ⭐ Productos

            </h2>

            <p class="text-muted mb-0">

                Conoce nuestros productos disponibles.

            </p>

        </div>


        <a
            href="productos.php"
            class="btn btn-outline-primary"
        >

            Ver todos

        </a>


    </div>



    <div class="row g-4">


        <?php if (
            $resultado &&
            $resultado->num_rows > 0
        ): ?>


            <?php while (
                $producto =
                $resultado->fetch_assoc()
            ): ?>


                <div class="col-sm-6 col-lg-4 col-xl-3">


                    <div
                        class="card producto-card shadow-sm h-100"
                    >


                        <!-- IMAGEN -->

                        <?php if (
                            !empty($producto["imagen"])
                        ): ?>


                            <img
                                src="imagenes/<?= htmlspecialchars($producto["imagen"]) ?>"
                                class="card-img-top producto-imagen"
                                alt="<?= htmlspecialchars($producto["nombre"]) ?>"
                            >


                        <?php else: ?>


                            <div
                                class="producto-imagen d-flex align-items-center justify-content-center bg-light"
                            >

                                <span class="display-4">
                                    📦
                                </span>

                            </div>


                        <?php endif; ?>


                        <div
                            class="card-body d-flex flex-column"
                        >


                            <!-- CATEGORÍA -->

                            <span
                                class="badge bg-secondary align-self-start mb-2"
                            >

                                <?= htmlspecialchars(
                                    $producto["categoria"]
                                ) ?>

                            </span>


                            <!-- NOMBRE -->

                            <h5 class="fw-bold">

                                <?= htmlspecialchars(
                                    $producto["nombre"]
                                ) ?>

                            </h5>


                            <!-- DESCRIPCIÓN -->

                            <p class="text-muted small">

                                <?= htmlspecialchars(
                                    $producto["descripcion"]
                                ) ?>

                            </p>


                            <!-- PRECIO -->

                            <div class="precio mt-auto">

                                $<?= number_format(
                                    $producto["precio"],
                                    2
                                ) ?>

                            </div>


                            <!-- STOCK -->

                            <?php if (
                                $producto["stock"] > 0
                            ): ?>

                                <small
                                    class="text-success mb-2"
                                >

                                    ✅
                                    <?= $producto["stock"] ?>
                                    disponibles

                                </small>

                            <?php else: ?>

                                <small
                                    class="text-danger mb-2"
                                >

                                    ❌ Agotado

                                </small>

                            <?php endif; ?>


                            <!-- BOTÓN -->

                            <?php if (
                                $producto["stock"] > 0
                            ): ?>


                                <a
                                    href="carrito.php?agregar=<?= $producto["id"] ?>"
                                    class="btn btn-primary w-100"
                                >

                                    🛒 Agregar al carrito

                                </a>


                            <?php else: ?>


                                <button
                                    class="btn btn-secondary w-100"
                                    disabled
                                >

                                    Agotado

                                </button>


                            <?php endif; ?>


                        </div>

                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="col-12">

                <div class="alert alert-info text-center">

                    📦 No hay productos disponibles.

                </div>

            </div>


        <?php endif; ?>


    </div>


</div>



<!-- ======================================== -->
<!-- FOOTER -->
<!-- ======================================== -->

<footer
    class="footer bg-dark text-white py-5"
>

    <div class="container">


        <div class="row">


            <div class="col-md-6">

                <h5 class="fw-bold">

                    💻 Tienda Tecnológica

                </h5>

                <p class="text-secondary">

                    Tu tienda de productos tecnológicos.

                </p>

            </div>


            <div class="col-md-3">

                <h6>
                    Enlaces
                </h6>

                <a
                    href="index.php"
                    class="text-secondary d-block text-decoration-none"
                >

                    Inicio

                </a>

                <a
                    href="productos.php"
                    class="text-secondary d-block text-decoration-none"
                >

                    Productos

                </a>

                <a
                    href="carrito.php"
                    class="text-secondary d-block text-decoration-none"
                >

                    Carrito

                </a>

            </div>


            <div class="col-md-3">

                <h6>
                    Cuenta
                </h6>


                <?php if (
                    isset($_SESSION["usuario_id"])
                ): ?>


                    <a
                        href="mis_pedidos.php"
                        class="text-secondary d-block text-decoration-none"
                    >

                        Mis pedidos

                    </a>


                    <a
                        href="logout.php"
                        class="text-secondary d-block text-decoration-none"
                    >

                        Cerrar sesión

                    </a>


                <?php else: ?>


                    <a
                        href="login.php"
                        class="text-secondary d-block text-decoration-none"
                    >

                        Iniciar sesión

                    </a>


                    <a
                        href="registro.php"
                        class="text-secondary d-block text-decoration-none"
                    >

                        Registrarse

                    </a>


                <?php endif; ?>


            </div>


        </div>


        <hr>


        <div class="text-center text-secondary">

            © 2026 Tienda Tecnológica

        </div>


    </div>

</footer>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
```
