<?php
session_start();

require_once "../conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {

    header("Location: ../login.php");
    exit;

}

$mensaje = "";
$tipo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = floatval($_POST["precio"] ?? 0);
    $stock = intval($_POST["stock"] ?? 0);
    $categoria_id = intval($_POST["categoria_id"] ?? 0);
    $codigo_qr = trim($_POST["codigo_qr"] ?? "");

    if (
        $nombre == "" ||
        $precio <= 0 ||
        $stock < 0 ||
        $categoria_id <= 0
    ) {

        $mensaje = "Completa correctamente los campos obligatorios.";
        $tipo = "danger";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO productos
            (nombre, descripcion, precio, stock, categoria_id, codigo_qr)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdiss",
            $nombre,
            $descripcion,
            $precio,
            $stock,
            $categoria_id,
            $codigo_qr
        );

        if ($stmt->execute()) {

            header("Location: productos.php");
            exit;

        } else {

            $mensaje = "Error al agregar el producto.";
            $tipo = "danger";

        }

    }

}


// Obtener categorías

$categorias = $conn->query(
    "SELECT id, nombre FROM categorias ORDER BY nombre ASC"
);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agregar Producto</title>

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


<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            💻 Panel Administrador

        </a>

        <a
            href="../logout.php"
            class="btn btn-danger btn-sm"
        >

            Cerrar sesión

        </a>

    </div>

</nav>


<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-body p-4">


                    <h2 class="fw-bold mb-4">

                        ➕ Agregar producto

                    </h2>


                    <?php if ($mensaje != ""): ?>

                        <div class="alert alert-<?= $tipo ?>">

                            <?= htmlspecialchars($mensaje) ?>

                        </div>

                    <?php endif; ?>


                    <form method="POST">


                        <div class="mb-3">

                            <label class="form-label">
                                Nombre del producto *
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea
                                name="descripcion"
                                class="form-control"
                                rows="4"
                            ></textarea>

                        </div>


                        <div class="row">


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Precio *
                                </label>

                                <input
                                    type="number"
                                    name="precio"
                                    class="form-control"
                                    step="0.01"
                                    min="0.01"
                                    required
                                >

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Stock *
                                </label>

                                <input
                                    type="number"
                                    name="stock"
                                    class="form-control"
                                    min="0"
                                    required
                                >

                            </div>


                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Categoría *
                            </label>

                            <select
                                name="categoria_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Selecciona una categoría
                                </option>


                                <?php while ($categoria = $categorias->fetch_assoc()): ?>

                                    <option value="<?= $categoria["id"] ?>">

                                        <?= htmlspecialchars($categoria["nombre"]) ?>

                                    </option>

                                <?php endwhile; ?>


                            </select>

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Código QR
                            </label>

                            <input
                                type="text"
                                name="codigo_qr"
                                class="form-control"
                                placeholder="Ejemplo: QR-TEC-013"
                            >

                        </div>


                        <div class="d-flex gap-2">


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                💾 Guardar producto

                            </button>


                            <a
                                href="productos.php"
                                class="btn btn-secondary"
                            >

                                Cancelar

                            </a>


                        </div>


                    </form>


                </div>

            </div>

        </div>

    </div>

</div>


</body>

</html>
```
