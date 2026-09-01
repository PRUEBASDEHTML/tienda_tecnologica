<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexion.php";
require_once "enviar_codigo.php";

$mensaje = "";
$error = "";


// ========================================
// PROCESAR REGISTRO
// ========================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";


    // ========================================
    // VALIDACIONES
    // ========================================

    if (
        empty($nombre) ||
        empty($email) ||
        empty($password) ||
        empty($confirmar)
    ) {

        $error = "Todos los campos son obligatorios.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Ingresa un correo electrónico válido.";

    } elseif ($password !== $confirmar) {

        $error = "Las contraseñas no coinciden.";

    } elseif (strlen($password) < 6) {

        $error = "La contraseña debe tener al menos 6 caracteres.";

    } else {


        // ========================================
        // COMPROBAR SI EL CORREO YA EXISTE
        // ========================================

        $stmt = $conn->prepare(
            "SELECT id FROM usuarios WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $resultado = $stmt->get_result();


        if ($resultado->num_rows > 0) {

            $error = "Este correo ya está registrado.";

        } else {


            // ========================================
            // GENERAR CÓDIGO
            // ========================================

            $codigo = random_int(100000, 999999);


            // ========================================
            // GUARDAR DATOS TEMPORALMENTE
            // ========================================

            $_SESSION["registro"] = [

                "nombre" => $nombre,

                "email" => $email,

                "password" => password_hash(
                    $password,
                    PASSWORD_DEFAULT
                ),

                "codigo" => $codigo,

                "expira" => time() + 600

            ];


            // ========================================
            // ENVIAR CÓDIGO AL CORREO
            // ========================================

            if (
                enviarCodigoVerificacion(
                    $email,
                    $codigo
                )
            ) {

                header(
                    "Location: verificar.php"
                );

                exit;

            } else {

                unset($_SESSION["registro"]);

                $error =
                    "No se pudo enviar el código. " .
                    "Revisa la configuración de Gmail.";

            }

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
        Crear cuenta - Tienda Tecnológica
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body
    class="bg-light"
>


<!-- ======================================== -->
<!-- NAVBAR -->
<!-- ======================================== -->

<nav
    class="navbar navbar-dark bg-dark"
>

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
<!-- REGISTRO -->
<!-- ======================================== -->

<div class="container">

    <div
        class="row justify-content-center"
    >

        <div
            class="col-md-6 col-lg-5"
        >


            <div
                class="card shadow-sm mt-5"
            >


                <div
                    class="card-body p-4"
                >


                    <div
                        class="text-center mb-4"
                    >

                        <h2 class="fw-bold">

                            📝 Crear cuenta

                        </h2>

                        <p class="text-muted">

                            Regístrate en Tienda Tecnológica

                        </p>

                    </div>


                    <?php if (!empty($error)): ?>

                        <div
                            class="alert alert-danger"
                        >

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <form
                        method="POST"
                    >


                        <!-- NOMBRE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Nombre

                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars(
                                    $_POST["nombre"] ?? ""
                                ) ?>"
                            >

                        </div>


                        <!-- CORREO -->

                        <div class="mb-3">

                            <label class="form-label">

                                Correo electrónico

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="ejemplo@gmail.com"
                                required
                                value="<?= htmlspecialchars(
                                    $_POST["email"] ?? ""
                                ) ?>"
                            >

                        </div>


                        <!-- CONTRASEÑA -->

                        <div class="mb-3">

                            <label class="form-label">

                                Contraseña

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >

                            <small class="text-muted">

                                Mínimo 6 caracteres.

                            </small>

                        </div>


                        <!-- CONFIRMAR -->

                        <div class="mb-3">

                            <label class="form-label">

                                Confirmar contraseña

                            </label>

                            <input
                                type="password"
                                name="confirmar"
                                class="form-control"
                                required
                            >

                        </div>


                        <!-- BOTÓN -->

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            📧 Registrarme y verificar correo

                        </button>


                    </form>


                    <div
                        class="text-center mt-4"
                    >

                        <p class="mb-0">

                            ¿Ya tienes una cuenta?

                            <a href="login.php">

                                Iniciar sesión

                            </a>

                        </p>

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>


</body>

</html>
```
