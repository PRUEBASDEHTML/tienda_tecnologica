
<?php

session_start();

require_once "conexion.php";


// ========================================
// VERIFICAR USUARIO
// ========================================

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");

    exit;
}


// ========================================
// VERIFICAR CARRITO
// ========================================

if (!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])) {

    header("Location: carrito.php");

    exit;
}


$usuario_id = intval($_SESSION["usuario_id"]);

$error = "";


// ========================================
// PROCESAR PEDIDO
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn->begin_transaction();

    try {

        $total = 0;

        $productosPedido = [];


        // ========================================
        // COMPROBAR STOCK Y PRECIOS
        // ========================================

        foreach ($_SESSION["carrito"] as $item) {

            $producto_id = intval($item["id"]);
            $cantidad = intval($item["cantidad"]);


            $stmt = $conn->prepare(
                "SELECT id, nombre, precio, stock
                 FROM productos
                 WHERE id = ?
                 FOR UPDATE"
            );

            $stmt->bind_param("i", $producto_id);

            $stmt->execute();

            $resultado = $stmt->get_result();


            if ($resultado->num_rows == 0) {

                throw new Exception(
                    "Uno de los productos ya no existe."
                );

            }


            $producto = $resultado->fetch_assoc();


            // Comprobar stock

            if ($producto["stock"] < $cantidad) {

                throw new Exception(
                    "No hay suficiente stock de: " .
                    $producto["nombre"]
                );

            }


            $subtotal =
                $producto["precio"] * $cantidad;


            $total += $subtotal;


            $productosPedido[] = [

                "id" => $producto["id"],

                "cantidad" => $cantidad,

                "precio" => $producto["precio"]

            ];

        }


        // ========================================
        // CREAR PEDIDO
        // ========================================

        $stmt = $conn->prepare(
            "INSERT INTO pedidos
            (usuario_id, total, estado)
            VALUES (?, ?, 'Pendiente')"
        );

        $stmt->bind_param(
            "id",
            $usuario_id,
            $total
        );

        $stmt->execute();


        $pedido_id = $conn->insert_id;


        // ========================================
        // GUARDAR DETALLE Y DESCONTAR STOCK
        // ========================================

        foreach ($productosPedido as $item) {

            $producto_id = $item["id"];

            $cantidad = $item["cantidad"];

            $precio = $item["precio"];


            // Detalle

            $stmt = $conn->prepare(
                "INSERT INTO detalle_pedido
                (pedido_id, producto_id, cantidad, precio)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "iiid",
                $pedido_id,
                $producto_id,
                $cantidad,
                $precio
            );

            $stmt->execute();


            // Descontar stock

            $stmt = $conn->prepare(
                "UPDATE productos
                 SET stock = stock - ?
                 WHERE id = ?"
            );

            $stmt->bind_param(
                "ii",
                $cantidad,
                $producto_id
            );

            $stmt->execute();

        }


        // Confirmar transacción

        $conn->commit();


        // Vaciar carrito

        $_SESSION["carrito"] = [];


        // Guardar número de pedido

        $_SESSION["pedido_exitoso"] = $pedido_id;


        header(
            "Location: pedido_exitoso.php"
        );

        exit;


    } catch (Exception $e) {

        $conn->rollback();

        $error = $e->getMessage();

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Confirmar Pedido</title>

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

    <div class="row justify-content-center">

        <div class="col-lg-7">


            <div class="card shadow">

                <div class="card-body p-4">


                    <h2 class="fw-bold mb-4">

                        🛍️ Confirmar pedido

                    </h2>


                    <?php if ($error != ""): ?>

                        <div class="alert alert-danger">

                            ❌
                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <div class="alert alert-info">

                        <strong>
                            Cliente:
                        </strong>

                        <?= htmlspecialchars($_SESSION["nombre"]) ?>

                        <br>

                        <strong>
                            Correo:
                        </strong>

                        <?= htmlspecialchars($_SESSION["email"]) ?>

                    </div>


                    <h5 class="fw-bold mt-4">

                        Productos

                    </h5>


                    <?php

                    $totalMostrar = 0;

                    ?>


                    <?php foreach ($_SESSION["carrito"] as $item): ?>

                        <?php

                        $subtotal =
                            $item["precio"] *
                            $item["cantidad"];

                        $totalMostrar += $subtotal;

                        ?>


                        <div
                            class="d-flex justify-content-between border-bottom py-3"
                        >

                            <div>

                                <strong>

                                    <?= htmlspecialchars($item["nombre"]) ?>

                                </strong>

                                <br>

                                <small class="text-muted">

                                    Cantidad:
                                    <?= $item["cantidad"] ?>

                                </small>

                            </div>


                            <strong>

                                $<?= number_format($subtotal, 2) ?>

                            </strong>

                        </div>


                    <?php endforeach; ?>


                    <div
                        class="d-flex justify-content-between mt-4"
                    >

                        <h4>
                            Total:
                        </h4>

                        <h3 class="text-primary">

                            $<?= number_format($totalMostrar, 2) ?>

                        </h3>

                    </div>


                    <form method="POST" class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-success btn-lg w-100"
                        >

                            ✅ Confirmar pedido

                        </button>

                    </form>


                    <a
                        href="carrito.php"
                        class="btn btn-outline-secondary w-100 mt-2"
                    >

                        ← Volver al carrito

                    </a>


                </div>

            </div>


        </div>

    </div>

</div>


</body>

</html>
```
