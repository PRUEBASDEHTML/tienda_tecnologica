<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {

    $stmt = $conn->prepare(
        "DELETE FROM productos WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

header("Location: productos.php");

exit;

?>
```
