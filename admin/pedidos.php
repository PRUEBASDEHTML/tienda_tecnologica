
<?php

session_start();

require_once "../conexion.php";

// ========================================
// VERIFICAR ADMINISTRADOR
// ========================================

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {

    header("Location: ../login.php");
    exit;

}


// ========================================
// CAMBIAR ESTADO DEL PEDIDO
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pedido_id = intval($_POST["pedido_id"] ?? 0);
    $estado = $_POST["estado"] ?? "";

    $estadosPermitidos = [
        "Pendiente",
        "Procesando",
        "Enviado",
        "Entregado",
        "Cancelado"
    ];

    if (
        $pedido_id > 0 &&
        in_array($estado, $estadosPermitidos, true)
    ) {

        $stmt = $conn->prepare(
            "UPDATE pedidos
             SET estado = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $estado,
            $pedido_id
        );

        $stmt->execute();
    }

    header("Location: pedidos.php");
    exit;
}


// ========================================
// OBTENER PEDIDOS
// ========================================

$sql = "SELECT
            pedidos.id,
            pedidos.total,
            pedidos.estado,
            pedidos.fecha_pedido,
            usuarios.nombre,
            usuarios.email
        FROM pedidos
        INNER JOIN usuarios
        ON pedidos.usuario_id = usuarios.id
        ORDER BY pedidos.fecha_pedido DESC";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pedidos - Administrador</title>

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
                🛒 Pedidos
            </h1>

            <p class="text-muted">
                Administra las compras realizadas por los clientes.
            </p>

        </div>


        <a
            href="index.php"
            class="btn btn-secondary"
        >

            ← Panel

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

                            <th>Pedido</th>

                            <th>Cliente</th>

                            <th>Correo</th>

                            <th>Total</th>

                            <th>Fecha</th>

                            <th>Estado</th>

                            <th>Acción</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if ($resultado && $resultado->num_rows > 0): ?>


                        <?php while ($pedido = $resultado->fetch_assoc()): ?>

                            <tr>


                                <!-- PEDIDO -->

                                <td>

                                    <strong>
                                        #<?= $pedido["id"] ?>
                                    </strong>

                                </td>


                                <!-- CLIENTE -->

                                <td>

                                    <?= htmlspecialchars($pedido["nombre"]) ?>

                                </td>


                                <!-- CORREO -->

                                <td>

                                    <?= htmlspecialchars($pedido["email"]) ?>

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


                                <!-- FECHA -->

                                <td>

                                    <?= date(
                                        "d/m/Y H:i",
                                        strtotime($pedido["fecha_pedido"])
                                    ) ?>

                                </td>


                                <!-- ESTADO -->

                                <td>

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
                                        class="badge <?= $claseEstado ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $pedido["estado"]
                                        ) ?>

                                    </span>

                                </td>


                                <!-- CAMBIAR ESTADO -->

                                <td>

                                    <form
                                        method="POST"
                                        class="d-flex gap-2"
                                    >

                                        <input
                                            type="hidden"
                                            name="pedido_id"
                                            value="<?= $pedido["id"] ?>"
                                        >


                                        <select
                                            name="estado"
                                            class="form-select form-select-sm"
                                        >

                                            <option
                                                value="Pendiente"
                                                <?= $pedido["estado"] === "Pendiente" ? "selected" : "" ?>
                                            >
                                                Pendiente
                                            </option>


                                            <option
                                                value="Procesando"
                                                <?= $pedido["estado"] === "Procesando" ? "selected" : "" ?>
                                            >
                                                Procesando
                                            </option>


                                            <option
                                                value="Enviado"
                                                <?= $pedido["estado"] === "Enviado" ? "selected" : "" ?>
                                            >
                                                Enviado
                                            </option>


                                            <option
                                                value="Entregado"
                                                <?= $pedido["estado"] === "Entregado" ? "selected" : "" ?>
                                            >
                                                Entregado
                                            </option>


                                            <option
                                                value="Cancelado"
                                                <?= $pedido["estado"] === "Cancelado" ? "selected" : "" ?>
                                            >
                                                Cancelado
                                            </option>

                                        </select>


                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-sm"
                                        >

                                            💾

                                        </button>

                                    </form>

                                </td>


                            </tr>

                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <h4>
                                    📭 No hay pedidos
                                </h4>

                                <p class="text-muted">
                                    Todavía no se han realizado compras.
                                </p>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>

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
