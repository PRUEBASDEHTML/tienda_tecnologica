<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email == "" || $password == "") {

        $mensaje = "Ingresa tu correo y contraseña.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, nombre, email, password, rol
             FROM usuarios
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {

            $usuario = $resultado->fetch_assoc();

            // Verificar contraseña
            if (password_verify($password, $usuario["password"])) {

                // Crear sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["nombre"] = $usuario["nombre"];
                $_SESSION["email"] = $usuario["email"];
                $_SESSION["rol"] = $usuario["rol"];

                // Redireccionar según rol
                if ($usuario["rol"] == "admin") {

                    header("Location: admin/index.php");
                    exit;

                } else {

                    header("Location: index.php");
                    exit;
                }

            } else {

                $mensaje = "Correo o contraseña incorrectos.";

            }

        } else {

            $mensaje = "Correo o contraseña incorrectos.";

        }
    }
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar Sesión - Tienda Tecnológica</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="css/style.css">

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

        <div class="col-md-6 col-lg-5">

            <div class="card shadow">

                <div class="card-body p-4">


                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            🔐 Iniciar Sesión
                        </h2>

                        <p class="text-muted">
                            Ingresa a tu cuenta
                        </p>

                    </div>


                    <?php if ($mensaje != ""): ?>

                        <div class="alert alert-danger">

                            <?= htmlspecialchars($mensaje) ?>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <div class="mb-3">

                            <label class="form-label">
                                Correo electrónico
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="correo@ejemplo.com"
                                required
                            >

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Tu contraseña"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            🔐 Iniciar sesión

                        </button>


                    </form>


                    <div class="text-center mt-4">

                        <p>
                            ¿No tienes una cuenta?
                        </p>

                        <a
                            href="registro.php"
                            class="btn btn-outline-primary"
                        >

                            📝 Crear cuenta

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


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


</body>

</html>
```
