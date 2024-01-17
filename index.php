<?php
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    footer {
        flex: 0 0 50px;
        /*or just height:50px;*/
        margin-top: auto;
    }
    </style>
</head>
<body>
    <?php
    include "side.php";
    ?>
    <div class="container">
        <div class="card" style="margin-top: 30px;">
            <div class="card-body d-flex align-items-center justify-content-center">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">Consultar</th>
                        <td>Permite consultar todos los registros de todas las tablas</td>
                    </tr>
                    <tr>
                        <th scope="row">Insertar</th>
                        <td>Permite insertar contenido nuevo de todas las tablas</td>
                    </tr>
                    <tr>
                        <th scope="row">Modificar</th>
                        <td>Permite modificar contenido de todas las tablas</td>
                    </tr>
                    <tr>
                        <th scope="row">Borrar Registros</th>
                        <td>Permite borrar contenido de todas las tablas</td>
                    </tr>
                    <tr>
                        <th scope="row">Borrar Tabla</th>
                        <td>Permite borrar todas las tablas</td>
                    </tr>
                    <tr>
                        <th scope="row">Ejectutar SQL</th>
                        <td>Permite ejecutar una consulta SQL propia</td>
                    </tr>
                    <tr>
                        <th scope="row">Consultas Predefinidas</th>
                        <td>Permite ejecutar una consulta predefinida por el sistema</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>