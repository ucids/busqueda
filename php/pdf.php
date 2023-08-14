<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filename = basename($file); // Obtiene el nombre del archivo sin la ruta

    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');

    @readfile($file);
} else {
    echo 'Archivo no encontrado.';
}
?>