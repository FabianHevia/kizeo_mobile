<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoge los datos del formulario
    $nombre_recinto = $_POST['nombre_recinto'];
    $ancho_recinto = $_POST['ancho_recinto'];
    $largo_recinto = $_POST['largo_recinto'];
    $alto_recinto = $_POST['alto_recinto'];
    // Recoge otros campos según sea necesario...

    // Verifica cuál botón fue presionado
    if ($_POST['action'] == 'guardar') {
        echo "GUARDAR INTERIORES";
        /*
        // Acción para el botón Guardar (ejemplo de conexión a BD y query)
        $sql = "INSERT INTO instalaciones (nombre, ancho, largo, alto) VALUES ('$nombre_recinto', '$ancho_recinto', '$largo_recinto', '$alto_recinto')";
        
        // Aquí iría la lógica para conectar a la base de datos y ejecutar la query...
        // mysqli_query($conn, $sql);

        echo "Datos guardados en la tabla de instalaciones.";
        */

    } elseif ($_POST['action'] == 'enviar') {
        echo "ENVIAR FORMULARIOS";
        /*
        // Acción para el botón Enviar
        $sql = "INSERT INTO formularios (nombre, ancho, largo, alto) VALUES ('$nombre_recinto', '$ancho_recinto', '$largo_recinto', '$alto_recinto')";
        
        // Aquí iría la lógica para conectar a la base de datos y ejecutar la query...
        // mysqli_query($conn, $sql);

        echo "Datos guardados en la tabla de formularios.";
        */
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown List</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .accordion-button {
            font-weight: bold;
        }
        .accordion-button::after {
            color: #fff;
        }
        /* Custom colors for each dropdown */
        .accordion-item:nth-child(1) .accordion-button {
            background-color: #007bff; /* Presupuesto (Azul) */
            color: #fff;
        }
        .accordion-item:nth-child(2) .accordion-button {
            background-color: #28a745; /* Cliente (Verde) */
            color: #fff;
        }
        .accordion-item:nth-child(3) .accordion-button {
            background-color: #17a2b8; /* Interiores (Celeste) */
            color: #fff;
        }
        .accordion-item:nth-child(4) .accordion-button {
            background-color: #dc3545; /* Fachadas (Rojo) */
            color: #fff;
        }
        .accordion-item:nth-child(5) .accordion-button {
            background-color: #ffc107; /* Techo (Amarillo) */
            color: #fff;
        }
        .accordion-item:nth-child(6) .accordion-button {
            background-color: #007bff; /* Cierre Perimetral (Azul) */
            color: #fff;
        }
        .accordion-item:nth-child(7) .accordion-button {
            background-color: #28a745; /* Puertas (Verde) */
            color: #fff;
        }
        .accordion-item:nth-child(8) .accordion-button {
            background-color: #007bff; /* Radier (Azul) */
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="accordion" id="accordionExample">
            <form id="presupuestoForm" novalidate>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            📋 Datos del Presupuesto
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" required>
                                </div>
                                <div class="mb-3">
                                    <label for="caso" class="form-label">Caso N°</label>
                                    <input type="text" class="form-control" id="caso" required>
                                </div>
                                <div class="mb-3">
                                    <label for="siniestro" class="form-label">N° de Siniestro</label>
                                    <input type="text" class="form-control" id="siniestro" required>
                                </div>
                                <div class="mb-3">
                                    <label for="folio" class="form-label">N° de Folio</label>
                                    <input type="text" class="form-control" id="folio">
                                </div>
                                <div class="mb-3">
                                    <label for="metros" class="form-label">M²</label>
                                    <input type="number" class="form-control" id="metros" required>
                                </div>
                                <div class="mb-3">
                                    <label for="antiguedad" class="form-label">Antigüedad del Domicilio (años)</label>
                                    <input type="number" class="form-control" id="antiguedad">
                                </div>
                                <div class="mb-3">
                                    <label for="liquidadora" class="form-label">Liquidadora</label>
                                    <input type="text" class="form-control" id="liquidadora">
                                </div>
                                <div class="mb-3">
                                    <label for="inspector" class="form-label">Inspector</label>
                                    <input type="text" class="form-control" id="inspector">
                                </div>
                                <div class="mb-3">
                                    <label for="evaluador" class="form-label">Evaluador</label>
                                    <input type="text" class="form-control" id="evaluador">
                                </div>
                                <div class="mb-3">
                                    <label for="evaluador" class="form-label">Email del Evaluador</label>
                                    <input type="text" class="form-control" id="evaluador">
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            👤 Datos del Cliente
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rut" class="form-label">RUT</label>
                                    <input type="text" class="form-control" id="caso" required>
                                </div>
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" required>
                                </div>
                                <div class="mb-3">
                                    <label for="comuna" class="form-label">Comuna</label>
                                    <input type="text" class="form-control" id="comuna">
                                </div>
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad">
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            🏠 Interiores
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="container my-2">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="form-tab" data-bs-toggle="tab" data-bs-target="#form" type="button" role="tab" aria-controls="form" aria-selected="true">Formulario</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab" aria-controls="table" aria-selected="false">Tabla</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="form" role="tabpanel" aria-labelledby="form-tab">
                                        <!-- Formulario similar al que aparece en la imagen -->
                                        <form id="recintoForm" class="mt-4">
                                            <div class="mb-3">
                                                <label for="nombreRecinto" class="form-label mt-2">Nombre del Recinto</label>
                                                <input type="text" class="form-control" id="nombreRecinto" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="anchoRecinto" class="form-label">Ancho del Recinto (Mts.)</label>
                                                <input type="number" class="form-control" id="anchoRecinto" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="largoRecinto" class="form-label">Largo del Recinto (Mts.)</label>
                                                <input type="number" class="form-control" id="largoRecinto" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="altoRecinto" class="form-label">Alto del Recinto (Mts.)</label>
                                                <input type="number" class="form-control" id="altoRecinto" required>
                                            </div>
                                            <!-- Sección de reparaciones -->
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="reparacionCielo">
                                                <label class="form-check-label" for="reparacionCielo">Reparación en Cielo</label>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="reparacionPiso">
                                                <label class="form-check-label" for="reparacionPiso">Reparación en Piso</label>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="reparacionMuro">
                                                <label class="form-check-label" for="reparacionMuro">Reparación en Muro</label>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="reparacionCornisa">
                                                <label class="form-check-label" for="reparacionCornisa">Reparación en Cornisa</label>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="retiroMobiliario">
                                                <label class="form-check-label" for="retiroMobiliario">Retiro e Instalación Mobiliario</label>
                                            </div>
                                            <!-- Fotos -->
                                            <div class="mb-3">
                                                <label for="foto1" class="form-label">Foto #1 del Recinto</label>
                                                <input type="file" class="form-control" id="foto1">
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto2" class="form-label">Foto #2 del Recinto</label>
                                                <input type="file" class="form-control" id="foto2">
                                            </div>
                                            <!-- Agrega más campos de fotos si es necesario -->
                                            <div class="mb-3">
                                                <label for="observaciones" class="form-label">Observaciones</label>
                                                <textarea class="form-control" id="observaciones" rows="3"></textarea>
                                            </div>
                                            <button type="submit" name="action" value="guardar" class="btn btn-primary">Guardar</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
                                        <!-- Tabla con los datos del formulario -->
                                        <div class="table-responsive mt-4">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre del Recinto</th>
                                                        <th>Ancho (Mts.)</th>
                                                        <th>Largo (Mts.)</th>
                                                        <th>Alto (Mts.)</th>
                                                        <th>Reparación en Cielo</th>
                                                        <th>Reparación en Piso</th>
                                                        <th>Reparación en Muro</th>
                                                        <th>Reparación en Cornisa</th>
                                                        <th>Retiro Mobiliario</th>
                                                        <th>Observaciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaDatos">
                                                    <!-- Aquí se insertarán los datos del formulario -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            🏛️ Fachadas
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaFrontal">
                                    <label class="form-check-label text-danger" for="fachadaFrontal">
                                        🏠 FACHADA FRONTAL?
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaTrasera">
                                    <label class="form-check-label text-danger" for="fachadaTrasera">
                                        📦 FACHADA TRASERA?
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaDerecha">
                                    <label class="form-check-label text-danger" for="fachadaDerecha">
                                        ➡️ FACHADA DERECHA?
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaIzquierda">
                                    <label class="form-check-label text-danger" for="fachadaIzquierda">
                                        ⬅️ FACHADA IZQUIERDA?
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            🔺 Techo
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaFrontal">
                                    <label class="form-check-label text-warning" for="fachadaFrontal">
                                        Reparación en el techo?
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaTrasera">
                                    <label class="form-check-label" for="fachadaTrasera">
                                        Reacomodo de tejas?
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            🧱 Cierre Perimetral
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaFrontal">
                                    <label class="form-check-label" for="fachadaFrontal">
                                        Reparación en el cierre perimetral?
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            🚪 Puertas
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaFrontal">
                                    <label class="form-check-label" for="fachadaFrontal">
                                        Reparación de marcos o puertas?
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            🧱 Radier
                        </button>
                    </h2>

                    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="fachadaFrontal">
                                    <label class="form-check-label" for="fachadaFrontal">
                                        Reparación de Radier?
                                    </label>
                                </div>
                        </div>
                    </div>
                </div>
                <button type="submit" name="action" value="enviar" class="btn btn-primary mt-1">Enviar</button>
            </form>
        </div>
    </div>

        <!-- JavaScript -->
        <script>
           document.getElementById("presupuestoForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita el envío inmediato

    // Validación del formulario
    if (this.checkValidity() === false) {
        event.stopPropagation();
        alert('Por favor, completa todos los campos requeridos correctamente.');
    } else {
        alert('Formulario enviado correctamente');
        // Aquí puedes hacer lo que necesites con los datos, como enviarlos a un servidor
        // Ejemplo: this.submit(); para enviarlo si está todo correcto
    }

    // Añade la clase "was-validated" para activar los estilos de validación de Bootstrap
    this.classList.add("was-validated");
});
        </script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
