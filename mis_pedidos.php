
<?php

session_start();

require_once "conexion.php";


// ========================================
// VERIFICAR SESIÓN
// ========================================

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");
    exit;
}


$usuario_id = intval($_SESSION["usuario_id"]);


// ========================================
// OBTENER PEDIDOS DEL CLIENTE
// ========================================

$stmt = $conn->prepare(
    "SELECT
        id,
        total,
        estado,
        fecha_pedido
     FROM pedidos
     WHERE usuario_id = ?
     ORDER BY fecha_pedido DESC"
);

$stmt->bind_param("i", $usuario_id);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Mis Pedidos - Tienda Tecnológica</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="css/style.css"
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

            💻 Tienda Tecnológica

        </a>


        <div>

            <span class="text-white me-3">

                👤 <?= htmlspecialchars($_SESSION["nombre"]) ?>

            </span>


            <a
                href="logout.php"
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


    <div class="mb-4">

        <h1 class="fw-bold">

            📦 Mis pedidos

        </h1>

        <p class="text-muted">

            Aquí puedes consultar tus compras y su estado.

        </p>

    </div>


    <?php if ($resultado->num_rows > 0): ?>


        <div class="card shadow-sm">

            <div class="card-body">


                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    Pedido
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Detalles
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php while ($pedido = $resultado->fetch_assoc()): ?>


                            <tr>


                                <!-- PEDIDO -->

                                <td>

                                    <strong>

                                        #<?= $pedido["id"] ?>

                                    </strong>

                                </td>


                                <!-- FECHA -->

                                <td>

                                    <?= date(
                                        "d/m/Y H:i",
                                        strtotime(
                                            $pedido["fecha_pedido"]
                                        )
                                    ) ?>

                                </td>


                                <!-- TOTAL -->

                                <td>

                                    <strong class="text-primary">

                                        $<?= number_format(
                                            $pedido["total"],
                                            2
                                        ) ?>

                                    </strong>

                                </td>


                                <!-- ESTADO -->

                                <td>


                                    <?php

                                    $claseEstado =
                                        "bg-secondary";


                                    if (
                                        $pedido["estado"]
                                        === "Pendiente"
                                    ) {

                                        $claseEstado =
                                            "bg-warning text-dark";

                                    } elseif (
                                        $pedido["estado"]
                                        === "Procesando"
                                    ) {

                                        $claseEstado =
                                            "bg-info text-dark";

                                    } elseif (
                                        $pedido["estado"]
                                        === "Enviado"
                                    ) {

                                        $claseEstado =
                                            "bg-primary";

                                    } elseif (
                                        $pedido["estado"]
                                        === "Entregado"
                                    ) {

                                        $claseEstado =
                                            "bg-success";

                                    } elseif (
                                        $pedido["estado"]
                                        === "Cancelado"
                                    ) {

                                        $claseEstado =
                                            "bg-danger";

                                    }

                                    ?>


                                    <span
                                        class="badge <?= $claseEstado ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $pedido["estado"]
                                        ) ?>

                                    </span>


                                </td>


                                <!-- DETALLES -->

                                <td>

                                    <a
                                        href="detalle_pedido.php?id=<?= $pedido["id"] ?>"
                                        class="btn btn-outline-primary btn-sm"
                                    >

                                        👁️ Ver detalles

                                    </a>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                        </tbody>

                    </table>

                </div>


            </div>

        </div>


    <?php else: ?>


        <div class="card shadow-sm">

            <div class="card-body text-center py-5">


                <div class="display-1">
                    📭
                </div>


                <h3 class="mt-3">

                    Todavía no tienes pedidos

                </h3>


                <p class="text-muted">

                    Cuando realices una compra,
                    aparecerá aquí.

                </p>


                <a
                    href="productos.php"
                    class="btn btn-primary"
                >

                    🛍️ Ver productos

                </a>


            </div>

        </div>


    <?php endif; ?>


    <div class="mt-4">

        <a
            href="index.php"
            class="btn btn-secondary"
        >

            ← Volver a la tienda

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
            © 2026 Todos los derechos reservados.
        </small>

    </div>

</footer>


</body>

</html>
```
