
<?php

session_start();

require_once "../conexion.php";

// ========================================
// VERIFICAR ADMIN
// ========================================

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {

    header("Location: ../login.php");
    exit;

}


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
            productos.codigo_qr,
            categorias.nombre AS categoria
        FROM productos
        INNER JOIN categorias
        ON productos.categoria_id = categorias.id
        ORDER BY productos.id DESC";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrar Productos</title>

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

                👤 <?= htmlspecialchars($_SESSION["nombre"]) ?>

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


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">
                📦 Productos
            </h1>

            <p class="text-muted">
                Administra los productos de la tienda.
            </p>

        </div>


        <a
            href="agregar_producto.php"
            class="btn btn-primary"
        >

            ➕ Agregar producto

        </a>

    </div>


    <!-- ======================================== -->
    <!-- TABLA -->
    <!-- ======================================== -->

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Producto</th>

                            <th>Categoría</th>

                            <th>Precio</th>

                            <th>Stock</th>

                            <th>QR</th>

                            <th>Acciones</th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if ($resultado && $resultado->num_rows > 0): ?>


                            <?php while ($producto = $resultado->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= $producto["id"] ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?= htmlspecialchars($producto["nombre"]) ?>
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            <?= htmlspecialchars($producto["descripcion"]) ?>

                                        </small>

                                    </td>


                                    <td>

                                        <span class="badge bg-secondary">

                                            <?= htmlspecialchars($producto["categoria"]) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <strong class="text-primary">

                                            $<?= number_format($producto["precio"], 2) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?php if ($producto["stock"] > 0): ?>

                                            <span class="badge bg-success">

                                                <?= $producto["stock"] ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-danger">

                                                Agotado

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <span class="badge bg-info text-dark">

                                            <?= htmlspecialchars($producto["codigo_qr"]) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <div class="d-flex gap-2">


                                            <a
                                                href="editar_producto.php?id=<?= $producto["id"] ?>"
                                                class="btn btn-warning btn-sm"
                                            >

                                                ✏️

                                            </a>


                                            <a
                                                href="eliminar_producto.php?id=<?= $producto["id"] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Seguro que deseas eliminar este producto?');"
                                            >

                                                🗑️

                                            </a>


                                        </div>

                                    </td>

                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center"
                                >

                                    No hay productos registrados.

                                </td>

                            </tr>

                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ======================================== -->
    <!-- BOTONES -->
    <!-- ======================================== -->

    <div class="mt-4">

        <a
            href="index.php"
            class="btn btn-secondary"
        >

            ← Volver al panel

        </a>


        <a
            href="../index.php"
            class="btn btn-outline-primary"
        >

            🏠 Ver tienda

        </a>

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
