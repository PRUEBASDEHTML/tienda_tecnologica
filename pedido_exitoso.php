
<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {

    header("Location: login.php");

    exit;
}

$pedido_id = $_SESSION["pedido_exitoso"] ?? null;

unset($_SESSION["pedido_exitoso"]);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pedido realizado</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>


<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            💻 Tienda Tecnológica

        </a>

    </div>

</nav>


<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow text-center">

                <div class="card-body p-5">


                    <div class="display-1 mb-3">

                        ✅

                    </div>


                    <h1 class="fw-bold text-success">

                        ¡Pedido realizado!

                    </h1>


                    <p class="lead">

                        Tu pedido fue registrado correctamente.

                    </p>


                    <?php if ($pedido_id): ?>

                        <div class="alert alert-info">

                            <strong>
                                Número de pedido:
                            </strong>

                            #<?= $pedido_id ?>

                        </div>

                    <?php endif; ?>


                    <p>

                        Puedes consultar el estado de tu pedido
                        desde tu cuenta.

                    </p>


                    <div class="d-flex justify-content-center gap-2">

                        <a
                            href="index.php"
                            class="btn btn-primary"
                        >

                            🏠 Ir al inicio

                        </a>


                        <a
                            href="productos.php"
                            class="btn btn-outline-primary"
                        >

                            📦 Seguir comprando

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


</body>

</html>
```
