<?php
if (isset($_FILES['archivo'])) {
    $file_name = $_FILES['archivo']['name'];
    $file_tmp = $_FILES['archivo']['tmp_name'];

    if ($file_tmp) {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $dir = "archivos/";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file_enc = md5_file($file_tmp);
        $fileName = "$file_enc.$ext";

        if (move_uploaded_file($file_tmp, $dir . $fileName)) {
            echo "El archivo se ha subido exitosamente.";
        } else {
            echo "Hubo un problema al mover el archivo.";
            var_dump(error_get_last());
        }
    } else {
        echo "No se recibió ningún archivo temporal.";
    }
} else {
    echo "Formulario no enviado correctamente.";
}
?>
