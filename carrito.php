
<?php

session_start();

require_once "conexion.php";

// Crear carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}


// ========================================
// AGREGAR PRODUCTO
// ========================================

if (isset($_GET['agregar'])) {

    $id = intval($_GET['agregar']);

    $sql = "SELECT id, nombre, precio, stock, imagen
            FROM productos
            WHERE id = $id";

    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {

        $producto = $resultado->fetch_assoc();

        if ($producto['stock'] > 0) {

            if (isset($_SESSION['carrito'][$id])) {

                // Aumentar cantidad
                if ($_SESSION['carrito'][$id]['cantidad'] < $producto['stock']) {

                    $_SESSION['carrito'][$id]['cantidad']++;

                }

            } else {

                // Agregar producto nuevo
                $_SESSION['carrito'][$id] = [
                    'id' => $producto['id'],
                    'nombre' => $producto['nombre'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen'],
                    'cantidad' => 1
                ];

            }
        }
    }

    header("Location: carrito.php");
    exit;
}


// ========================================
// AUMENTAR CANTIDAD
// ========================================

if (isset($_GET['aumentar'])) {

    $id = intval($_GET['aumentar']);

    if (isset($_SESSION['carrito'][$id])) {

        $sql = "SELECT stock FROM productos WHERE id = $id";

        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {

            $producto = $resultado->fetch_assoc();

            if ($_SESSION['carrito'][$id]['cantidad'] < $producto['stock']) {

                $_SESSION['carrito'][$id]['cantidad']++;

            }
        }
    }

    header("Location: carrito.php");
    exit;
}


// ========================================
// DISMINUIR CANTIDAD
// ========================================

if (isset($_GET['disminuir'])) {

    $id = intval($_GET['disminuir']);

    if (isset($_SESSION['carrito'][$id])) {

        if ($_SESSION['carrito'][$id]['cantidad'] > 1) {

    $_SESSION['carrito'][$id]['cantidad']--;

} else {

    unset($_SESSION['carrito'][$id]);

}
    }

    header("Location: carrito.php");
    exit;
}


// ========================================
// ELIMINAR PRODUCTO
// ========================================

if (isset($_GET['eliminar'])) {

    $id = intval($_GET['eliminar']);

    if (isset($_SESSION['carrito'][$id])) {

        unset($_SESSION['carrito'][$id]);

    }

    header("Location: carrito.php");
    exit;
}


// ========================================
// VACIAR CARRITO
// ========================================

if (isset($_GET['vaciar'])) {

    $_SESSION['carrito'] = [];

    header("Location: carrito.php");
    exit;
}


// ========================================
// CALCULAR TOTAL
// ========================================

$total = 0;
$cantidadProductos = 0;

foreach ($_SESSION['carrito'] as $producto) {

    $subtotal = $producto['precio'] * $producto['cantidad'];

    $total += $subtotal;

    $cantidadProductos += $producto['cantidad'];
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Carrito - Tienda Tecnológica</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<!-- ======================================== -->
<!-- NAVBAR -->
<!-- ======================================== -->

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

                    <a class="nav-link" href="productos.php">
                        📦 Productos
                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link active" href="carrito.php">
                        🛒 Carrito (<?= $cantidadProductos ?>)
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


<!-- ======================================== -->
<!-- CONTENIDO -->
<!-- ======================================== -->

<div class="container my-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            🛒 Mi Carrito
        </h1>

        <p class="text-muted">
            Revisa los productos que deseas comprar
        </p>

    </div>


    <?php if (empty($_SESSION['carrito'])): ?>

        <div class="alert alert-info text-center p-5">

            <h3>
                🛒 Tu carrito está vacío
            </h3>

            <p>
                Todavía no has agregado ningún producto.
            </p>

            <a
                href="productos.php"
                class="btn btn-primary"
            >
                📦 Ver productos
            </a>

        </div>

    <?php else: ?>


        <div class="row">


            <!-- ======================================== -->
            <!-- PRODUCTOS -->
            <!-- ======================================== -->

            <div class="col-lg-8">


                <?php foreach ($_SESSION['carrito'] as $producto): ?>

                    <?php

                    $subtotal =
                        $producto['precio'] *
                        $producto['cantidad'];

                    ?>


                    <div class="card mb-3 shadow-sm">


                        <div class="card-body">


                            <div class="row align-items-center">


                                <!-- IMAGEN -->

                                <div class="col-md-2">

                                    <?php if (!empty($producto['imagen'])): ?>

                                        <img
                                            src="imagenes/<?= htmlspecialchars($producto['imagen']) ?>"
                                            class="img-fluid rounded"
                                            style="height:100px; width:100%; object-fit:cover;"
                                            alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                        >

                                    <?php else: ?>

                                        <div class="bg-light text-center p-4">
                                            📦
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- NOMBRE -->

                                <div class="col-md-3">

                                    <h5>
                                        <?= htmlspecialchars($producto['nombre']) ?>
                                    </h5>

                                    <p class="text-muted mb-0">

                                        $<?= number_format($producto['precio'], 2) ?>

                                    </p>

                                </div>


                                <!-- CANTIDAD -->

                                <div class="col-md-3">

                                    <div class="d-flex align-items-center gap-2">

                                        <a
                                            href="carrito.php?disminuir=<?= $producto['id'] ?>"
                                            class="btn btn-outline-secondary"
                                        >
                                            −
                                        </a>


                                        <span class="fw-bold">

                                            <?= $producto['cantidad'] ?>

                                        </span>


                                        <a
                                            href="carrito.php?aumentar=<?= $producto['id'] ?>"
                                            class="btn btn-outline-secondary"
                                        >
                                            +
                                        </a>

                                    </div>

                                </div>


                                <!-- SUBTOTAL -->

                                <div class="col-md-2">

                                    <strong class="text-primary">

                                        $<?= number_format($subtotal, 2) ?>

                                    </strong>

                                </div>


                                <!-- ELIMINAR -->

                                <div class="col-md-2">

                                    <a
                                        href="carrito.php?eliminar=<?= $producto['id'] ?>"
                                        class="btn btn-danger"
                                    >
                                        🗑️
                                    </a>

                                </div>


                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


                <a
                    href="carrito.php?vaciar=1"
                    class="btn btn-outline-danger"
                >
                    🗑️ Vaciar carrito
                </a>


                <a
                    href="productos.php"
                    class="btn btn-outline-primary"
                >
                    ← Seguir comprando
                </a>


            </div>


            <!-- ======================================== -->
            <!-- RESUMEN -->
            <!-- ======================================== -->

            <div class="col-lg-4">


                <div class="card shadow-sm">


                    <div class="card-body">


                        <h4 class="fw-bold mb-4">

                            Resumen de compra

                        </h4>


                        <div class="d-flex justify-content-between">

                            <span>
                                Productos:
                            </span>

                            <strong>
                                <?= $cantidadProductos ?>
                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <span>
                                Total:
                            </span>

                            <h3 class="text-primary">

                                $<?= number_format($total, 2) ?>

                            </h3>

                        </div>


                        <a
                             href="confirmar_pedido.php"
                            class="btn btn-success w-100 mt-3"
                     >
                            💳 Continuar compra
                        </a>


                    </div>

                </div>


            </div>

        </div>


    <?php endif; ?>

</div>


<!-- ======================================== -->
<!-- FOOTER -->
<!-- ======================================== -->

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
```
