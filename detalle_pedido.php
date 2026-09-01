<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexion.php";


// ========================================
// VERIFICAR SESIÓN
// ========================================

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");
    exit;
}


$usuario_id = intval($_SESSION["usuario_id"]);

$pedido_id = intval($_GET["id"] ?? 0);


if ($pedido_id <= 0) {

    header("Location: mis_pedidos.php");
    exit;
}


// ========================================
// OBTENER PEDIDO
// ========================================

$stmt = $conn->prepare(
    "SELECT
        pedidos.id,
        pedidos.total,
        pedidos.estado,
        pedidos.fecha_pedido,
        usuarios.nombre,
        usuarios.email
     FROM pedidos
     INNER JOIN usuarios
     ON pedidos.usuario_id = usuarios.id
     WHERE pedidos.id = ?
     AND pedidos.usuario_id = ?"
);

$stmt->bind_param(
    "ii",
    $pedido_id,
    $usuario_id
);

$stmt->execute();

$resultadoPedido = $stmt->get_result();


if ($resultadoPedido->num_rows == 0) {

    header("Location: mis_pedidos.php");
    exit;
}


$pedido = $resultadoPedido->fetch_assoc();


// ========================================
// OBTENER PRODUCTOS
// ========================================

$stmt = $conn->prepare(
    "SELECT
        detalle_pedido.cantidad,
        detalle_pedido.precio,
        productos.nombre
     FROM detalle_pedido
     INNER JOIN productos
     ON detalle_pedido.producto_id = productos.id
     WHERE detalle_pedido.pedido_id = ?"
);

$stmt->bind_param(
    "i",
    $pedido_id
);

$stmt->execute();

$productos = $stmt->get_result();

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
        Pedido #<?= $pedido_id ?>
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            💻 Tienda Tecnológica

        </a>


        <a
            href="logout.php"
            class="btn btn-danger btn-sm"
        >

            Cerrar sesión

        </a>

    </div>

</nav>


<!-- CONTENIDO -->

<div class="container my-5">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="fw-bold">

                📦 Pedido #<?= $pedido["id"] ?>

            </h1>

            <p class="text-muted">

                <?= date(
                    "d/m/Y H:i",
                    strtotime(
                        $pedido["fecha_pedido"]
                    )
                ) ?>

            </p>

        </div>


        <?php

        $claseEstado = "bg-secondary";

        if ($pedido["estado"] === "Pendiente") {

            $claseEstado = "bg-warning text-dark";

        } elseif ($pedido["estado"] === "Procesando") {

            $claseEstado = "bg-info text-dark";

        } elseif ($pedido["estado"] === "Enviado") {

            $claseEstado = "bg-primary";

        } elseif ($pedido["estado"] === "Entregado") {

            $claseEstado = "bg-success";

        } elseif ($pedido["estado"] === "Cancelado") {

            $claseEstado = "bg-danger";

        }

        ?>


        <span
            class="badge <?= $claseEstado ?> fs-6"
        >

            <?= htmlspecialchars(
                $pedido["estado"]
            ) ?>

        </span>

    </div>


    <!-- DATOS DEL CLIENTE -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold">

                👤 Información del cliente

            </h5>

            <p class="mb-1">

                <strong>
                    Nombre:
                </strong>

                <?= htmlspecialchars(
                    $pedido["nombre"]
                ) ?>

            </p>

            <p class="mb-0">

                <strong>
                    Correo:
                </strong>

                <?= htmlspecialchars(
                    $pedido["email"]
                ) ?>

            </p>

        </div>

    </div>


    <!-- PRODUCTOS -->

    <div class="card shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-4">

                🛍️ Productos del pedido

            </h5>


            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Producto
                            </th>

                            <th>
                                Precio
                            </th>

                            <th>
                                Cantidad
                            </th>

                            <th>
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php while ($producto = $productos->fetch_assoc()): ?>

                        <?php

                        $subtotal =
                            $producto["precio"] *
                            $producto["cantidad"];

                        ?>


                        <tr>

                            <td>

                                <?= htmlspecialchars(
                                    $producto["nombre"]
                                ) ?>

                            </td>


                            <td>

                                $<?= number_format(
                                    $producto["precio"],
                                    2
                                ) ?>

                            </td>


                            <td>

                                <?= $producto["cantidad"] ?>

                            </td>


                            <td>

                                <strong>

                                    $<?= number_format(
                                        $subtotal,
                                        2
                                    ) ?>

                                </strong>

                            </td>

                        </tr>


                    <?php endwhile; ?>


                    </tbody>


                    <tfoot>

                        <tr>

                            <td
                                colspan="3"
                                class="text-end"
                            >

                                <strong>
                                    TOTAL:
                                </strong>

                            </td>

                            <td>

                                <strong
                                    class="text-primary fs-5"
                                >

                                    $<?= number_format(
                                        $pedido["total"],
                                        2
                                    ) ?>

                                </strong>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>


    <div class="mt-4">

        <a
            href="mis_pedidos.php"
            class="btn btn-secondary"
        >

            ← Mis pedidos

        </a>

        <a
            href="productos.php"
            class="btn btn-primary"
        >

            🛍️ Seguir comprando

        </a>

    </div>


</div>


</body>

</html>
```
