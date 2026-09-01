<?php
require_once "conexion.php";

// ===============================
// BUSCADOR
// ===============================

$buscar = $_GET['buscar'] ?? '';


// ===============================
// CATEGORÍA
// ===============================

$categoria = $_GET['categoria'] ?? '';


// ===============================
// CONSULTA DE PRODUCTOS
// ===============================

$sql = "SELECT 
            productos.id,
            productos.nombre,
            productos.descripcion,
            productos.precio,
            productos.stock,
            productos.imagen,
            categorias.nombre AS categoria,
            categorias.id AS categoria_id
        FROM productos
        INNER JOIN categorias
        ON productos.categoria_id = categorias.id
        WHERE 1=1";


// Filtrar por búsqueda

if (!empty($buscar)) {

    $buscar_seguro = $conn->real_escape_string($buscar);

    $sql .= " AND (
                productos.nombre LIKE '%$buscar_seguro%'
                OR productos.descripcion LIKE '%$buscar_seguro%'
            )";
}


// Filtrar por categoría

if (!empty($categoria)) {

    $categoria_segura = intval($categoria);

    $sql .= " AND productos.categoria_id = $categoria_segura";
}


$sql .= " ORDER BY productos.id DESC";

$resultado = $conn->query($sql);


// ===============================
// OBTENER CATEGORÍAS
// ===============================

$sqlCategorias = "SELECT * FROM categorias ORDER BY nombre ASC";

$resultadoCategorias = $conn->query($sqlCategorias);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Productos - Tienda Tecnológica</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>


<body>


<!-- =============================== -->
<!-- NAVBAR -->
<!-- =============================== -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            💻 Tienda Tecnológica
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a class="nav-link" href="index.php">
                        🏠 Inicio
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link active" href="productos.php">
                        📦 Productos
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="carrito.php">
                        🛒 Carrito
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="login.php">
                        🔐 Iniciar sesión
                    </a>

                </li>


                <li class="nav-item">

                    <a class="nav-link" href="registro.php">
                        📝 Registrarse
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- =============================== -->
<!-- TÍTULO -->
<!-- =============================== -->

<div class="container mt-5">

    <div class="text-center mb-4">

        <h1 class="fw-bold">
            📦 Nuestros Productos
        </h1>

        <p class="text-muted">
            Encuentra la tecnología que necesitas
        </p>

    </div>



    <!-- =============================== -->
    <!-- BUSCADOR -->
    <!-- =============================== -->

    <form method="GET" class="mb-4">

        <div class="row g-2">

            <div class="col-md-8">

                <input
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="🔎 Buscar producto..."
                    value="<?= htmlspecialchars($buscar) ?>"
                >

            </div>


            <div class="col-md-2">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Buscar
                </button>

            </div>


            <div class="col-md-2">

                <a
                    href="productos.php"
                    class="btn btn-secondary w-100"
                >
                    Limpiar
                </a>

            </div>

        </div>

    </form>



    <!-- =============================== -->
    <!-- CATEGORÍAS -->
    <!-- =============================== -->

    <div class="mb-4">

        <h5>
            📂 Categorías
        </h5>


        <div class="d-flex flex-wrap gap-2">

            <a
                href="productos.php"
                class="btn btn-outline-primary"
            >
                Todas
            </a>


            <?php while ($cat = $resultadoCategorias->fetch_assoc()): ?>

                <a
                    href="productos.php?categoria=<?= $cat['id'] ?>"
                    class="btn btn-outline-secondary"
                >

                    <?= htmlspecialchars($cat['nombre']) ?>

                </a>

            <?php endwhile; ?>

        </div>

    </div>



    <!-- =============================== -->
    <!-- PRODUCTOS -->
    <!-- =============================== -->

    <div class="row g-4">

        <?php if ($resultado && $resultado->num_rows > 0): ?>


            <?php while ($producto = $resultado->fetch_assoc()): ?>


                <div class="col-md-6 col-lg-4">


                    <div class="card h-100 shadow-sm">


                        <?php

                        if (!empty($producto['imagen'])) {

                            $imagen = "imagenes/" . $producto['imagen'];

                        } else {

                            $imagen = "https://via.placeholder.com/400x250?text=Sin+Imagen";

                        }

                        ?>


                        <img
                            src="<?= htmlspecialchars($imagen) ?>"
                            class="card-img-top producto-imagen"
                            alt="<?= htmlspecialchars($producto['nombre']) ?>"
                        >


                        <div class="card-body d-flex flex-column">


                            <span class="badge bg-secondary mb-2">

                                <?= htmlspecialchars($producto['categoria']) ?>

                            </span>


                            <h5 class="card-title">

                                <?= htmlspecialchars($producto['nombre']) ?>

                            </h5>


                            <p class="card-text text-muted">

                                <?= htmlspecialchars($producto['descripcion']) ?>

                            </p>


                            <h4 class="text-primary fw-bold">

                                $<?= number_format($producto['precio'], 2) ?>

                            </h4>


                            <?php if ($producto['stock'] > 0): ?>

                                <p class="text-success">

                                    ✅ Disponible:
                                    <?= $producto['stock'] ?>

                                </p>


                                <a
                                    href="carrito.php?agregar=<?= $producto['id'] ?>"
                                    class="btn btn-success w-100 mt-auto"
                                >

                                    🛒 Agregar al carrito

                                </a>


                            <?php else: ?>

                                <p class="text-danger">

                                    ❌ Producto agotado

                                </p>


                                <button
                                    class="btn btn-secondary w-100 mt-auto"
                                    disabled
                                >

                                    Sin stock

                                </button>

                            <?php endif; ?>


                        </div>

                    </div>

                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="col-12">

                <div class="alert alert-warning text-center">

                    😕 No encontramos productos.

                </div>

            </div>


        <?php endif; ?>

    </div>

</div>



<!-- =============================== -->
<!-- FOOTER -->
<!-- =============================== -->

<footer class="bg-dark text-white text-center py-4 mt-5">

    <div class="container">

        <h5>
            💻 Tienda Tecnológica
        </h5>

        <p>
            Tecnología, calidad y buenos precios.
        </p>

        <small>
            © 2026 Tienda Tecnológica
        </small>

    </div>

</footer>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>