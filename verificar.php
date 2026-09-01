<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexion.php";


// ========================================
// COMPROBAR REGISTRO PENDIENTE
// ========================================

if (!isset($_SESSION["registro"])) {

    header("Location: registro.php");

    exit;
}


$mensaje = "";

$error = "";


// ========================================
// PROCESAR CÓDIGO
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $codigoIngresado = trim(
        $_POST["codigo"] ?? ""
    );


    $registro = $_SESSION["registro"];


    // ========================================
    // COMPROBAR EXPIRACIÓN
    // ========================================

    if (time() > $registro["expira"]) {

        $error =
            "El código ha expirado. " .
            "Debes volver a registrarte.";

        unset($_SESSION["registro"]);

    }


    // ========================================
    // COMPROBAR CÓDIGO
    // ========================================

    elseif (
        !hash_equals(
            (string)$registro["codigo"],
            (string)$codigoIngresado
        )
    ) {

        $error =
            "El código ingresado es incorrecto.";

    }


    // ========================================
    // CÓDIGO CORRECTO
    // ========================================

    else {


        // ========================================
        // INSERTAR USUARIO
        // ========================================

        $stmt = $conn->prepare(
            "INSERT INTO usuarios
            (nombre, email, password, rol)
            VALUES (?, ?, ?, 'cliente')"
        );


        $stmt->bind_param(
            "sss",
            $registro["nombre"],
            $registro["email"],
            $registro["password"]
        );


        if ($stmt->execute()) {


            // ========================================
            // OBTENER ID
            // ========================================

            $usuarioId =
                $conn->insert_id;


            // ========================================
            // INICIAR SESIÓN
            // ========================================

            $_SESSION["usuario_id"] =
                $usuarioId;

            $_SESSION["nombre"] =
                $registro["nombre"];

            $_SESSION["email"] =
                $registro["email"];

            $_SESSION["rol"] =
                "cliente";


            // ========================================
            // ELIMINAR REGISTRO TEMPORAL
            // ========================================

            unset(
                $_SESSION["registro"]
            );


            // ========================================
            // REDIRIGIR
            // ========================================

            header(
                "Location: index.php"
            );

            exit;


        } else {

            $error =
                "No se pudo crear la cuenta. " .
                "Es posible que el correo ya exista.";

        }

    }

}

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
        Verificar correo - Tienda Tecnológica
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


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

    </div>

</nav>



<!-- ======================================== -->
<!-- VERIFICACIÓN -->
<!-- ======================================== -->

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">


            <div class="card shadow-sm mt-5">

                <div class="card-body p-4">


                    <div class="text-center mb-4">

                        <div
                            style="font-size:50px;"
                        >

                            📧

                        </div>


                        <h2 class="fw-bold">

                            Verifica tu correo

                        </h2>


                        <p class="text-muted">

                            Hemos enviado un código de
                            verificación a:

                        </p>


                        <strong>

                            <?= htmlspecialchars(
                                $_SESSION["registro"]["email"]
                            ) ?>

                        </strong>

                    </div>


                    <!-- ERROR -->

                    <?php if (!empty($error)): ?>

                        <div class="alert alert-danger">

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <!-- FORMULARIO -->

                    <form method="POST">


                        <div class="mb-4">

                            <label
                                class="form-label fw-bold"
                            >

                                Código de verificación

                            </label>


                            <input
                                type="text"
                                name="codigo"
                                class="form-control form-control-lg text-center"
                                placeholder="000000"
                                maxlength="6"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-success btn-lg w-100"
                        >

                            ✅ Verificar correo

                        </button>


                    </form>


                    <div class="text-center mt-4">

                        <a
                            href="registro.php"
                            class="text-decoration-none"
                        >

                            ← Volver al registro

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
