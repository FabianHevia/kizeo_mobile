<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
$servername = "162.214.199.14";
$username_db = "crmcubic_presupas";
$password_db = "123q1q2q3Q";
$dbname = "crmcubic_presup";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se pasó un id_caso por la URL
if (isset($_GET['id_caso'])) {
    $id_caso = $_GET['id_caso'];  // Capturar id_caso desde el parámetro de la URL
} else {
    die("No se ha proporcionado un ID de caso.");
}

// Suponemos que ya tienes el nombre de usuario en la sesión
$username = $_SESSION['username'];  // Nombre de usuario obtenido de la sesión

// Consultar si el usuario es administrador
$sql = "SELECT admin1 FROM login_presup WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);  // "s" porque es un string
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

// Consultar los datos del caso específico
$sql = "SELECT fecha, n_caso, n_folio, metros_cuadrados, antiguedad_domicilio, liquidadora, inspector, evaluador, email_evaluador, cliente_nombre, cliente_rut, cliente_direccion, cliente_comuna, cliente_ciudad, fachada_frontal, fachada_trasera, fachada_derecha, fachada_izquierda, reparacion_techo, reacomodo_tejas, cierre_perimetral, marcos_puertas, radier 
        FROM presp_formularios 
        WHERE id_caso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_caso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos de la fila
    $row = $result->fetch_assoc();

    // Asignar valores a variables
    $fecha = $row['fecha'];
    $caso = $row['n_caso'];
    $folio = $row['n_folio'];
    $metros = $row['metros_cuadrados'];
    $antiguedad = $row['antiguedad_domicilio'];
    $liquidadora = $row['liquidadora'];
    $inspector = $row['inspector'];
    $evaluador = $row['evaluador'];
    $email_evaluador = $row['email_evaluador'];
    $nombre = $row['cliente_nombre'];
    $rut = $row['cliente_rut'];
    $direccion = $row['cliente_direccion'];
    $comuna = $row['cliente_comuna'];
    $ciudad = $row['cliente_ciudad'];
    $fachadaFrontal = $row['fachada_frontal'];
    $fachadaTrasera = $row['fachada_trasera'];
    $fachadaDerecha = $row['fachada_derecha'];
    $fachadaIzquierda = $row['fachada_izquierda'];
    $reparacionTecho = $row['reparacion_techo'];
    $reacomodoTejas = $row['reacomodo_tejas'];
    $reparacionCierrePerimetral = $row['cierre_perimetral'];
    $reparacionMarcosPuertas = $row['marcos_puertas'];
    $reparacionRadier = $row['radier'];

} else {
    die("No se encontraron datos para el ID de caso proporcionado.");
}
// Query para seleccionar todos los datos
$sql = "SELECT Nombre, Unidad, Precio_Unitario FROM plantilla_presup";
$result = $conn->query($sql);

// Inicializar las listas (arrays)
$nombres = [];
$unidades = [];
$precios_unitarios = [];

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Recorrer cada fila y guardar los datos en los arrays
    while($row = $result->fetch_assoc()) {
        $nombres[] = $row['Nombre'];
        $unidades[] = $row['Unidad'];
        $precios_unitarios[] = $row['Precio_Unitario'];
    }
} else {
    die("No se encontraron datos.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF caso <?= htmlspecialchars($caso) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .wrapper {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .content {
            margin-left: 250px; /* mismo ancho que el sidebar */
            padding: 20px;
            width: calc(100% - 250px);
        }
        @media (width < 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar-collapse {
                display: grid;
            }
        }
    </style>
</head>
<body>
<div class="container">
        <h2 class="mt-5">Resumen de Casos</h2>
    
        <!-- Información del Usuario y Fecha -->
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nombre del Usuario</th>
                    <th>RUT</th>
                    <th>Fecha de Creación</th>
                    <th>Tipo de Formulario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($nombre) ?></td>
                    <td><?= htmlspecialchars($rut) ?></td>
                    <td><?= htmlspecialchars($fecha) ?></td>
                    <td><?= htmlspecialchars($folio) ?></td>
                </tr>
            </tbody>
        </table>
<?php
echo '
<table class="table table-bordered mt-4">';
echo '<thead>';
echo '<tr>';

// Consultar los datos del caso específico
$sql = "SELECT id_caso, id_form, nombre_recinto, ancho_recinto, largo_recinto, alto_recinto, reparacion_cielo, tamano_fisura_cielo, tipo_material_cielo, recubrimiento_cielo, reparacion_piso, tamano_fisura_piso, tipo_material_piso, recubrimiento_piso, reparacion_muro, tamano_fisura_muro, tipo_material_muro, recubrimiento_muro, tamano_fisura_cornisa, tipo_material_cornisa, recubrimiento_cornisa, reparacion_cornisa, retiro_instalacion_mobiliario, imagen_recinto1, imagen_recinto2, observaciones 
        FROM presp_interiores
        WHERE id_caso = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_caso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Iterar sobre todas las filas encontradas
    while ($row = $result->fetch_assoc()) {
        // Asignar valores de la fila actual a variables
        $id_caso = $row['id_caso'];
        $id_form = $row['id_form'];
        $nombre_recinto = $row['nombre_recinto'];
        $ancho_recinto = $row['ancho_recinto'];
        $largo_recinto = $row['largo_recinto'];
        $alto_recinto = $row['alto_recinto'];
        $reparacion_cielo = $row['reparacion_cielo'];
        $tamano_fisura_cielo = $row['tamano_fisura_cielo'];
        $tipo_material_cielo = $row['tipo_material_cielo'];
        $recubrimiento_cielo = $row['recubrimiento_cielo'];
        $reparacion_piso = $row['reparacion_piso'];
        $tamano_fisura_piso = $row['tamano_fisura_piso'];
        $tipo_material_piso = $row['tipo_material_piso'];
        $recubrimiento_piso = $row['recubrimiento_piso'];
        $reparacion_muro = $row['reparacion_muro'];
        $tamano_fisura_muro = $row['tamano_fisura_muro'];
        $tipo_material_muro = $row['tipo_material_muro'];
        $recubrimiento_muro = $row['recubrimiento_muro'];
        $reparacion_cornisa = $row['reparacion_cornisa'];
        $tamano_fisura_cornisa = $row['tamano_fisura_cornisa'];
        $tipo_material_cornisa = $row['tipo_material_cornisa'];
        $recubrimiento_cornisa = $row['recubrimiento_cornisa'];
        $retiro_instalacion_mobiliario = $row['retiro_instalacion_mobiliario'];
        $imagen_recinto1 = $row['imagen_recinto1'];
        $imagen_recinto2 = $row['imagen_recinto2'];
        $observaciones = $row['observaciones'];

        // Asignación de valores
        $Ancho = $ancho_recinto;
        $Alto = $alto_recinto; 
        $Largo = $largo_recinto;
        $SUMATORIA = 0;

        $limpieza = 2800;
        $acomodo_inmobiliario = 20000;
        $empaste_lijado = 32000;

        if ($reparacion_cielo == '1') {

            $Fisura = $tamano_fisura_cielo;
            $Material = $tipo_material_cielo;
            $Recubrimiento = $recubrimiento_cielo;

            $FachadaArea = $Ancho * $Alto;

            if ($Recubrimiento == "CERÁMICO") {

                // Dependiendo del material, se realizan cálculos adicionales
                if ($Material == "VOLCANITA") {
                    $precio_unitario_cielo = 9500;
                }
                elseif($Material == "HORMIGÓN ARMADO (HA)") {
                    $precio_unitario_cielo = 18000;
                }
                elseif($Material == "LADRILLO") {
                    $precio_unitario_cielo = 14500;
                }
            }

            elseif ($Recubrimiento == "PORCELANATO") {

                // Dependiendo del material, se realizan cálculos adicionales
                if ($Material == "VOLCANITA") {
                    $precio_unitario_cielo = 9500;
                }
                elseif($Material == "HORMIGÓN ARMADO (HA)") {
                    $precio_unitario_cielo = 18000;
                }
                elseif($Material == "LADRILLO") {
                    $precio_unitario_cielo = 14500;
                }
            }
            elseif ($Recubrimiento == "SOLO PINTURA") {
                // Dependiendo del material, se realizan cálculos adicionales
                if ($Material == "VOLCANITA") {
                    $precio_unitario_cielo = 9500;
                }
                elseif($Material == "HORMIGÓN ARMADO (HA)") {
                    $precio_unitario_cielo = 18000;
                }
                elseif($Material == "LADRILLO") {
                    $precio_unitario_cielo = 14500;
                }
                echo '<th>Aplicación de Pintura de Terminacion Esmalte al agua Cielo (2 manos)</th>';
            }
            if ($n_limpieza > 0) {
                echo '<th>Limpieza y Preparacion de Superficie de Cielo</th>';
            } 
            if ($tamano_fisura_cielo > 0) {
                echo '<th>Reparacion de Fisura y postura huincha en Cielo Volcometal</th>';
            } 
            echo '<th>Empaste y Lijado Total de Cielo (incluye imprimante o puente adherente)</th>';
        }

        if ($reparacion_muro == '1') {

            $Fisura = $tamano_fisura_muro;
            $Material = $tipo_material_muro;
            $Recubrimiento = $recubrimiento_muro;

            $MuroSuperficie = 2*$Ancho*$Alto+2*$Largo*$Alto;

            if ($Recubrimiento == "CERÁMICO") {

                // Dependiendo del material, se realizan cálculos adicionales
                if ($Material == "VOLCANITA") {
                    $precio_unitario_muro = 9500;
                }
                elseif($Material == "HORMIGÓN ARMADO (HA)") {
                    $precio_unitario_muro = 18000;
                }
                elseif($Material == "LADRILLO") {
                    $precio_unitario_muro = 14500;
                }
            }
            if ($n_limpieza > 0) {
                echo '<th>Limpieza y Preparacion de Superficie de Cielo</th>';
            } 
            if ($tamano_fisura_cielo > 0) {
                echo '<th>Reparacion de Fisura y postura huincha en Cielo Volcometal</th>';
            } 
            echo '<th>Empaste y Lijado Total de Cielo (incluye imprimante o puente adherente)</th>';
            echo '<th>Aplicación de Pintura de Terminacion Esmalte al agua Cielo (2 manos)</th>';
        }
        if ($reparacion_piso == '1') {

            $PisoArea = $Ancho*$Largo;

            if($Recubrimiento == "CERÁMICO") {

                // Dependiendo del material, se realizan cálculos adicionales
                if($Material == "VOLCANITA") {
    
                }
                elseif($Material == "HORMIGÓN ARMADO (HA)") {
    
                }
                elseif($Material == "LADRILLO") {
    
                }
    
                // Tercer bloque de cálculos
            }
        }
        // Resultado final de la sumatoria
        echo "SUMATORIA: $SUMATORIA\n";
    }
    echo '</tr>';
    echo '</thead>';
    echo'<tbody>
        <tr>
            <td><?= htmlspecialchars($nombre) ?></td>
            <td><?= htmlspecialchars($rut) ?></td>
            <td><?= htmlspecialchars($fecha) ?></td>
            <td><?= htmlspecialchars($folio) ?></td>
        </tr>
    </tbody>
    </table>';
} else {
    die("No se encontraron datos para el ID de caso proporcionado.");
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>