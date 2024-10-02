<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start(); // Inicia la sesión

$servername = "162.214.199.14";
$username = "crmcubic_presupas";
$password = "123q1q2q3Q";
$dbname = "crmcubic_presup";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_form = isset($_POST['id_form']) ? $_POST['id_form'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $id_caso = isset($_POST['id_caso']) ? $_POST['id_caso'] : null;
    
    if (!$id_form || !$username) {
        die("Error: Su sesión ha expirado.");
    }
    // Verifica cuál botón fue presionado
    if (isset($_POST['action']) && $_POST['action'] == 'guardar') {
        
        // Validar que los campos requeridos no estén vacíos
        if (!empty($_POST['nombre_recinto']) && 
            !empty($_POST['ancho_recinto']) && 
            !empty($_POST['largo_recinto']) && 
            !empty($_POST['alto_recinto'])) {

            // Recoger los valores del formulario
            $nombre_recinto = $_POST['nombre_recinto'];
            $ancho_recinto = $_POST['ancho_recinto'];
            $largo_recinto = $_POST['largo_recinto'];
            $alto_recinto = $_POST['alto_recinto'];

            // Reparaciones (checkboxes)
            $reparacion_cielo = isset($_POST['reparacion_cielo']) ? 1 : 0;
            $reparacion_piso = isset($_POST['reparacion_piso']) ? 1 : 0;
            $reparacion_muro = isset($_POST['reparacion_muro']) ? 1 : 0;
            $reparacion_cornisa = isset($_POST['reparacion_cornisa']) ? 1 : 0;
            $retiro_mobiliario = isset($_POST['retiro_mobiliario']) ? 1 : 0;

            // Observaciones
            $observaciones = ($_POST['observaciones']) ? $_POST['observaciones'] : '';

            // Manejo de las fotos
            /*
            $foto1 = ($_FILES['foto1']['name']) ? $_FILES['foto1']['name'] : null;
            $foto2 = ($_FILES['foto2']['name']) ? $_FILES['foto2']['name'] : null;
            */
            /*
            $foto1 = ($_FILES['foto1']['name']);
            $foto2 = ($_FILES['foto2']['name']);
            // Guardar fotos en una carpeta
            $target_dir = "img_interiores/" . $id_caso . "/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); // Crear la carpeta si no existe
            }
            /*
            $target_file1 = $foto1 ? $target_dir . basename($_FILES["foto1"]["name"]) : null;
            $target_file2 = $foto2 ? $target_dir . basename($_FILES["foto2"]["name"]) : null;

            if ($foto1) {
                move_uploaded_file($_FILES["foto1"]["tmp_name"], $target_file1);
            }
            if ($foto2) {
                move_uploaded_file($_FILES["foto2"]["tmp_name"], $target_file2);
            }

            $stmt = $conn->prepare("INSERT INTO presp_interiores (id_caso, id_form, nombre_recinto, ancho_recinto, largo_recinto, alto_recinto, reparacion_cielo, reparacion_piso, reparacion_muro, reparacion_cornisa, retiro_instalacion_mobiliario, imagen_recinto1, imagen_recinto2, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Verificar si la preparación de la consulta falló
            if (!$stmt) {
                die("Error en la consulta SQL: " . $conn->error);
            }

            // Ajuste en la llamada a bind_param, asegurando 14 parámetros
            $stmt->bind_param("iisiiiiiiiisss", $id_caso, $id_form, $nombre_recinto, $ancho_recinto, $largo_recinto, $alto_recinto, $reparacion_cielo, $reparacion_piso, $reparacion_muro, $reparacion_cornisa, $retiro_mobiliario, $foto1, $foto2, $observaciones);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Datos guardados correctamente.";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Cerrar conexión
            $stmt->close();
        } else {
            echo "Por favor, completa todos los campos requeridos correctamente.";
        }

    }
    
    if ($_POST['action'] == 'enviar') {

            // Datos del formulario
            $fecha = $_POST['fecha'];
            $caso = $_POST['caso'];
            $siniestro = $_POST['siniestro'];
            $folio = $_POST['folio'];
            $metros = $_POST['metros'];
            $antiguedad = $_POST['antiguedad'];
            $liquidadora = $_POST['liquidadora'];
            $inspector = $_POST['inspector'];
            $evaluador = $_POST['evaluador'];
            $email_evaluador = $_POST['email_evaluador'];
            $nombre = $_POST['nombre'];
            $rut = $_POST['rut'];
            $direccion = $_POST['direccion'];
            $comuna = $_POST['comuna'];
            $ciudad = $_POST['ciudad'];
        
            // Checkboxes (convertir a 1 o 0)
            $fachadaFrontal = isset($_POST['fachadaFrontal']) ? 1 : 0;
            $fachadaTrasera = isset($_POST['fachadaTrasera']) ? 1 : 0;
            $fachadaDerecha = isset($_POST['fachadaDerecha']) ? 1 : 0;
            $fachadaIzquierda = isset($_POST['fachadaIzquierda']) ? 1 : 0;
            $reparacionTecho = isset($_POST['reparacionTecho']) ? 1 : 0;
            $reacomodoTejas = isset($_POST['reacomodoTejas']) ? 1 : 0;
            $reparacionCierrePerimetral = isset($_POST['reparacionCierrePerimetral']) ? 1 : 0;
            $reparacionMarcosPuertas = isset($_POST['reparacionMarcosPuertas']) ? 1 : 0;
            $reparacionRadier = isset($_POST['reparacionRadier']) ? 1 : 0;
        
            $sql = "UPDATE presp_formularios 
            SET fecha = ?, 
                n_caso = ?, 
                n_folio = ?, 
                metros_cuadrados = ?, 
                antiguedad_domicilio = ?, 
                liquidadora = ?, 
                inspector = ?, 
                evaluador = ?, 
                email_evaluador = ?, 
                cliente_nombre = ?, 
                cliente_rut = ?, 
                cliente_direccion = ?, 
                cliente_comuna = ?, 
                cliente_ciudad = ?, 
                fachada_frontal = ?, 
                fachada_trasera = ?, 
                fachada_derecha = ?, 
                fachada_izquierda = ?, 
                reparacion_techo = ?, 
                reacomodo_tejas = ?, 
                cierre_perimetral = ?, 
                marcos_puertas = ?, 
                radier = ?
            WHERE id_caso = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiiisssssssssiiiiiiiiii',
        $fecha, 
        $caso, 
        $folio, 
        $metros, 
        $antiguedad, 
        $liquidadora, 
        $inspector, 
        $evaluador, 
        $email_evaluador, 
        $nombre, 
        $rut, 
        $direccion, 
        $comuna, 
        $ciudad, 
        $fachadaFrontal, 
        $fachadaTrasera, 
        $fachadaDerecha, 
        $fachadaIzquierda, 
        $reparacionTecho, 
        $reacomodoTejas, 
        $reparacionCierrePerimetral, 
        $reparacionMarcosPuertas, 
        $reparacionRadier, 
        $id_caso);
    
    if ($stmt->execute()) {
        echo "Registro actualizado correctamente.";
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
    
    $stmt->close();
    }
} else {

// Obtener los parámetros de la URL
$id_form = isset($_GET['id_form']) ? $_GET['id_form'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$id_caso = isset($_GET['id_caso']) ? $_GET['id_caso'] : null;

if (!$id_form || !$username) {
    die("Error: Su sesión ha expirado.");
}

}
// Verificar si existe el id_caso
if ($id_caso) {
    $sql = "SELECT * FROM info_casos_presup WHERE id_caso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_caso);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // El id_caso existe, continuar con ese id
        $row = $result->fetch_assoc();
        $id_caso = $row['id_caso'];
    } else {
        // El id_caso no existe, crear un nuevo caso
        crearNuevoCaso($conn, $id_form, $username);
    }
    $stmt->close();
} else {
    // No se proporcionó id_caso, crear un nuevo caso
    crearNuevoCaso($conn, $id_form, $username);

    $sql = "SELECT * FROM info_casos_presup WHERE id_caso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_caso);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // El id_caso existe, continuar con ese id
        $row = $result->fetch_assoc();
        $id_caso = $row['id_caso'];
    }
}

// Función para crear un nuevo caso
function crearNuevoCaso($conn, $id_form, $username) {
    $fecha_actual = date("Y-m-d H:i:s"); // Obtener la fecha actual
    
    $sql = "INSERT INTO info_casos_presup (id_form, username, fecha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $id_form, $username, $fecha_actual);
    
    if ($stmt->execute()) {
        $nuevo_id_caso = $stmt->insert_id; // Obtener el id_caso generado
        echo "Nuevo caso creado con ID: $nuevo_id_caso";
    } else {
        echo "Error al crear el caso: " . $conn->error;
    }
    
    $stmt->close();
}

if ($id_caso) {
    $sql = "SELECT * FROM presp_formularios WHERE id_caso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_caso);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // El id_caso existe, continuar con ese id
        $row = $result->fetch_assoc();
        $id_presp = $row['id_presp'];
    } else {
        crearNuevoPresp($conn, $id_caso, $id_form);

        $sql = "SELECT * FROM presp_formularios WHERE id_caso = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_caso);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // El id_caso existe, continuar con ese id
            $row = $result->fetch_assoc();
            $id_presp = $row['id_presp'];
        }
    }
    $stmt->close();
}

function crearNuevoPresp($conn, $id_caso1, $id_form1) {
    // El id_caso no existe, crear un nuevo caso
    $fecha_actual = date("Y-m-d H:i:s"); // Obtener la fecha actual
    
    $sql = "INSERT INTO presp_formularios (id_caso, id_form, fecha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_caso1, $id_form1, $fecha_actual);

    if ($stmt->execute()) {
        $nuevo_id_presp = $stmt->insert_id; // Obtener el id_caso generado
        echo "Nuevo presupuesto creado con ID: $nuevo_id_presp";
    } else {
        echo "Error al crear el presupuesto: " . $conn->error;
    }
    $stmt->close();
}

// Obtener datos del caso específico
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
    // Inicializar variables en caso de que no haya datos
    $fecha = $caso = $folio = $metros = $antiguedad = $liquidadora = $inspector = $evaluador = $email_evaluador = $nombre = $rut = $direccion = $comuna = $ciudad = "";
    $fachadaFrontal = $fachadaTrasera = $fachadaDerecha = $fachadaIzquierda = $reparacionTecho = $reacomodoTejas = $reparacionCierrePerimetral = $reparacionMarcosPuertas = $reparacionRadier = 0;
}

$sql = "SELECT id, dia_fecha, ano_fecha, comuna_id, region_id, provincia_id, currency_id, vendedor_asignado, create_uid, write_uid, name, mes_fecha, rut, nombres, apellidos, estado_civil, profesion, nacionalidad, proviene, comuna_cliente, provincia_cliente, region_cliente, direccion, email, telefono, telefono_alternativo, observacion_agendar, status, tipo_siniestro, tipo_propiedad, m2_aproximado, valor_uf, banco_chile, compania_seguro, arrendatario_nombre, arrendatario_telefono, coordenadas, datos_plan, forma_pago, n_cuotas, dias_pago, Tipo_pago, stage_id, date, contract_date, description, abono, arrendatario_bool, create_date, write_date, latitud, longitud, user_id, correlativo, provinces_request_domain, comunas_request_domain, comuna_id_caso, region_id_caso, provincia_id_caso, rango_disponibles, calle_caso, provinces_request_domain_caso, comunas_request_domain_caso, valor_total_plan, valor_cuota_plan
        FROM siniestros_siniestros 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_caso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener los datos de la fila
    $row = $result->fetch_assoc();

    // Asignar valores a variables
    $id = $row['id'];
    $diaFecha = $row['dia_fecha'];
    $anoFecha = $row['ano_fecha'];
    $comunaId = $row['comuna_id'];
    $regionId = $row['region_id'];
    $provinciaId = $row['provincia_id'];
    $currencyId = $row['currency_id'];
    $vendedorAsignado = $row['vendedor_asignado'];
    $createUid = $row['create_uid'];
    $writeUid = $row['write_uid'];
    $name = $row['name'];
    $mesFecha = $row['mes_fecha'];
    $rut = $row['rut'];
    $nombres = $row['nombres'];
    $apellidos = $row['apellidos'];
    $estadoCivil = $row['estado_civil'];
    $profesion = $row['profesion'];
    $nacionalidad = $row['nacionalidad'];
    $proviene = $row['proviene'];
    $comunaCliente = $row['comuna_cliente'];
    $provinciaCliente = $row['provincia_cliente'];
    $regionCliente = $row['region_cliente'];
    $direccion = $row['direccion'];
    $email = $row['email'];
    $telefono = $row['telefono'];
    $telefonoAlternativo = $row['telefono_alternativo'];
    $observacionAgendar = $row['observacion_agendar'];
    $status = $row['status'];
    $tipoSiniestro = $row['tipo_siniestro'];
    $tipoPropiedad = $row['tipo_propiedad'];
    $m2Aproximado = $row['m2_aproximado'];
    $valorUf = $row['valor_uf'];
    $bancoChile = $row['banco_chile'];
    $companiaSeguro = $row['compania_seguro'];
    $arrendatarioNombre = $row['arrendatario_nombre'];
    $arrendatarioTelefono = $row['arrendatario_telefono'];
    $coordenadas = $row['coordenadas'];
    $datosPlan = $row['datos_plan'];
    $formaPago = $row['forma_pago'];
    $nCuotas = $row['n_cuotas'];
    $diasPago = $row['dias_pago'];
    $tipoPago = $row['Tipo_pago'];
    $stageId = $row['stage_id'];
    $date = $row['date'];
    $contractDate = $row['contract_date'];
    $description = $row['description'];
    $abono = $row['abono'];
    $arrendatarioBool = $row['arrendatario_bool'];
    $createDate = $row['create_date'];
    $writeDate = $row['write_date'];
    $latitud = $row['latitud'];
    $longitud = $row['longitud'];
    $userId = $row['user_id'];
    $correlativo = $row['correlativo'];
    $provincesRequestDomain = $row['provinces_request_domain'];
    $comunasRequestDomain = $row['comunas_request_domain'];
    $comunaIdCaso = $row['comuna_id_caso'];
    $regionIdCaso = $row['region_id_caso'];
    $provinciaIdCaso = $row['provincia_id_caso'];
    $rangoDisponibles = $row['rango_disponibles'];
    $calleCaso = $row['calle_caso'];
    $provincesRequestDomainCaso = $row['provinces_request_domain_caso'];
    $comunasRequestDomainCaso = $row['comunas_request_domain_caso'];
    $valorTotalPlan = $row['valor_total_plan'];
    $valorCuotaPlan = $row['valor_cuota_plan'];
} else {
    // Inicializar variables en caso de que no haya datos
    $id = $diaFecha = $anoFecha = $comunaId = $regionId = $provinciaId = $currencyId = $vendedorAsignado = $createUid = $writeUid = $name = $mesFecha = $rut = $nombres = $apellidos = $estadoCivil = $profesion = $nacionalidad = $proviene = $comunaCliente = $provinciaCliente = $regionCliente = $direccion = $email = $telefono = $telefonoAlternativo = $observacionAgendar = $status = $tipoSiniestro = $tipoPropiedad = $m2Aproximado = $valorUf = $bancoChile = $companiaSeguro = $arrendatarioNombre = $arrendatarioTelefono = $coordenadas = $datosPlan = $formaPago = $nCuotas = $diasPago = $tipoPago = $stageId = $date = $contractDate = $description = $abono = $arrendatarioBool = $createDate = $writeDate = $latitud = $longitud = $userId = $correlativo = $provincesRequestDomain = $comunasRequestDomain = $comunaIdCaso = $regionIdCaso = $provinciaIdCaso = $rangoDisponibles = $calleCaso = $provincesRequestDomainCaso = $comunasRequestDomainCaso = $valorTotalPlan = $valorCuotaPlan = '';
}

$stmt->close();
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rellenar Formulario</title>
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
            background-color: #dc4562; /* Fachadas (Rojo) */
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
    <div class="container mt-5">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-general-tab" data-bs-toggle="tab" data-bs-target="#info-general" type="button" role="tab" aria-controls="info-general" aria-selected="true">
              Información General
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="interiores-tab" data-bs-toggle="tab" data-bs-target="#interiores" type="button" role="tab" aria-controls="interiores" aria-selected="false">
              Interiores
            </button>
          </li>
        </ul>
      
        <!-- Tab content -->
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="info-general" role="tabpanel" aria-labelledby="info-general-tab">
            <div class="accordion" id="accordionExample">
            <form id="presupuestoForm"  class="mt-4" action="form1.php?id_form=1&id_caso=<?php echo $id_caso; ?>&username=<?php echo $username ?>" method="POST" enctype="multipart/form-data" novalidate>
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
                                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo htmlspecialchars($fecha); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="caso" class="form-label">Caso N°</label>
                                <input type="text" class="form-control" id="caso" name="caso" value="<?php echo htmlspecialchars($caso); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="siniestro" class="form-label">N° de Siniestro</label>
                                <input type="text" class="form-control" id="siniestro" name="siniestro" required>
                            </div>
                            <div class="mb-3">
                                <label for="folio" class="form-label">N° de Folio</label>
                                <input type="text" class="form-control" id="folio" name="folio" value="<?php echo htmlspecialchars($folio); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="metros" class="form-label">M²</label>
                                <input type="number" class="form-control" id="metros" name="metros" value="<?php echo htmlspecialchars($metros); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="antiguedad" class="form-label">Antigüedad del Domicilio (años)</label>
                                <input type="number" class="form-control" id="antiguedad" name="antiguedad" value="<?php echo htmlspecialchars($antiguedad); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="liquidadora" class="form-label">Liquidadora</label>
                                <input type="text" class="form-control" id="liquidadora" name="liquidadora" value="<?php echo htmlspecialchars($liquidadora); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="inspector" class="form-label">Inspector</label>
                                <input type="text" class="form-control" id="inspector" name="inspector" value="<?php echo htmlspecialchars($inspector); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="evaluador" class="form-label">Evaluador</label>
                                <input type="text" class="form-control" id="evaluador" name="evaluador" value="<?php echo htmlspecialchars($evaluador); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email_evaluador" class="form-label">Email del Evaluador</label>
                                <input type="text" class="form-control" id="email_evaluador" name="email_evaluador" value="<?php echo htmlspecialchars($email_evaluador); ?>">
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
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="rut" class="form-label">RUT</label>
                                <input type="text" class="form-control" id="rut" name="rut" value="<?php echo htmlspecialchars($rut); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="comuna" class="form-label">Comuna</label>
                                <input type="text" class="form-control" id="comuna" name="comuna" value="<?php echo htmlspecialchars($comuna); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($ciudad); ?>">
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
                            <input class="form-check-input" type="checkbox" id="fachadaFrontal" name="fachadaFrontal" <?php echo $fachadaFrontal ? 'checked' : ''; ?>>
                                <label class="form-check-label text-danger" for="fachadaFrontal">
                                    🏠 FACHADA FRONTAL?
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="fachadaTrasera" name="fachadaTrasera" <?php echo $fachadaTrasera ? 'checked' : ''; ?>>
                                <label class="form-check-label text-danger" for="fachadaTrasera">
                                    📦 FACHADA TRASERA?
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="fachadaDerecha" name="fachadaDerecha" <?php echo $fachadaDerecha ? 'checked' : ''; ?>>
                                <label class="form-check-label text-danger" for="fachadaDerecha">
                                    ➡️ FACHADA DERECHA?
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="fachadaIzquierda" name="fachadaIzquierda" <?php echo $fachadaIzquierda ? 'checked' : ''; ?>>
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
                                <input class="form-check-input" type="checkbox" id="reparacionTecho" name="reparacionTecho" <?php echo $reparacionTecho ? 'checked' : ''; ?>>
                                <label class="form-check-label text-warning" for="reparacionTecho">
                                    Reparación en el techo?
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="reacomodoTejas" name="reacomodoTejas" <?php echo $reacomodoTejas ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reacomodoTejas">
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
                                <input class="form-check-input" type="checkbox" id="reparacionCierrePerimetral" name="reparacionCierrePerimetral" <?php echo $reparacionCierrePerimetral ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reparacionCierrePerimetral">
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
                                <input class="form-check-input" type="checkbox" id="reparacionMarcosPuertas" name="reparacionMarcosPuertas" <?php echo $reparacionMarcosPuertas ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reparacionMarcosPuertas">
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
                                <input class="form-check-input" type="checkbox" id="reparacionRadier" name="reparacionRadier" <?php echo $reparacionRadier ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="reparacionRadier">
                                    Reparación de Radier?
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
             <!-- Campos ocultos para enviar los parámetros de la URL -->
                <input type="hidden" name="id_form" value="<?php echo htmlspecialchars($id_form); ?>">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <input type="hidden" name="id_caso" value="<?php echo htmlspecialchars($id_caso); ?>">
                <button type="submit" name="action" value="enviar" class="btn btn-primary mt-1">Enviar</button>
            </form>
                
            </div>
          </div>
          <div class="tab-pane fade" id="interiores" role="tabpanel" aria-labelledby="interiores-tab">
            <!-- Nested tabs for Interiores -->
            <ul class="nav nav-tabs mt-3" id="interioresTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="formulario-interiores-tab" data-bs-toggle="tab" data-bs-target="#formulario-interiores" type="button" role="tab" aria-controls="formulario-interiores" aria-selected="true">
                  Formulario Interiores
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="tabla-interiores-tab" data-bs-toggle="tab" data-bs-target="#tabla-interiores" type="button" role="tab" aria-controls="tabla-interiores" aria-selected="false">
                  Tabla Interiores
                </button>
              </li>
            </ul>
      
            <!-- Nested Tab content for Interiores -->
            <div class="tab-content mt-3" id="interioresTabContent">
              <div class="tab-pane fade show active" id="formulario-interiores" role="tabpanel" aria-labelledby="formulario-interiores-tab">
                <form id="recintoForm" class="mt-4" action="form1.php?id_form=1&id_caso=<?php echo $id_caso; ?>&username=<?php echo $username ?>" method="POST" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label for="nombreRecinto" class="form-label mt-2">Nombre del Recinto</label>
                        <input type="text" class="form-control" id="nombre_recinto" name="nombre_recinto" required>
                    </div>
                    <div class="mb-3">
                        <label for="anchoRecinto" class="form-label">Ancho del Recinto (Mts.)</label>
                        <input type="number" class="form-control" id="ancho_recinto" name="ancho_recinto" required>
                    </div>
                    <div class="mb-3">
                        <label for="largoRecinto" class="form-label">Largo del Recinto (Mts.)</label>
                        <input type="number" class="form-control" id="largo_recinto" name="largo_recinto" required>
                    </div>
                    <div class="mb-3">
                        <label for="altoRecinto" class="form-label">Alto del Recinto (Mts.)</label>
                        <input type="number" class="form-control" id="alto_recinto" name="alto_recinto" required>
                    </div>
                    <!-- Sección de reparaciones -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="reparacion_cielo" name="reparacion_cielo">
                        <label class="form-check-label" for="reparacion_cielo">Reparación en Cielo</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="reparacion_piso" name="reparacion_piso">
                        <label class="form-check-label" for="reparacionPiso">Reparación en Piso</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="reparacion_muro" name="reparacion_muro">
                        <label class="form-check-label" for="reparacionMuro">Reparación en Muro</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="reparacion_cornisa" name="reparacion_cornisa">
                        <label class="form-check-label" for="reparacionCornisa">Reparación en Cornisa</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="retiro_mobiliario" name="retiro_mobiliario">
                        <label class="form-check-label" for="retiroMobiliario">Retiro e Instalación Mobiliario</label>
                    </div>
                    <!-- Fotos -->
                    <div class="mb-3">
                        <label for="foto1" class="form-label">Foto #1 del Recinto</label>
                        <input type="file" class="form-control" id="foto1" name="foto1" accept="image/*" capture="camera">
                    </div>
                    <div class="mb-3">
                        <label for="foto2" class="form-label">Foto #2 del Recinto</label>
                        <input type="file" class="form-control" id="foto2" name="foto2" accept="image/*" capture="camera">
                    </div>
                    <!-- Agrega más campos de fotos si es necesario -->
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                     <!-- Campos ocultos para pasar id_form y username -->
                    <input type="hidden" name="id_form" value="<?php echo $id_form; ?>">
                    <input type="hidden" name="id_caso" value="<?php echo $id_caso; ?>">
                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                    <button type="submit" name="action" value="guardar" class="btn btn-primary">Agregar</button>
                </form>
              </div>
              <div class="tab-pane fade" id="tabla-interiores" role="tabpanel" aria-labelledby="tabla-interiores-tab">
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
                    <tbody>
                        <?php 
                        // Consulta para obtener los registros de presp_interiores que coinciden con id_caso
                        $sql_interiores = "SELECT * FROM presp_interiores WHERE id_caso = $id_caso AND id_form = 1";
                        $result_interiores = $conn->query($sql_interiores);

                        if ($result_interiores && $result_interiores->num_rows > 0) {
                            while($row = $result_interiores->fetch_assoc()) {
                                echo '
                                <tr>
                                    <td>' . htmlspecialchars($row["nombre_recinto"]) . '</td>
                                    <td>' . htmlspecialchars($row["ancho_recinto"]) . '</td>
                                    <td>' . htmlspecialchars($row["largo_recinto"]) . '</td>
                                    <td>' . htmlspecialchars($row["alto_recinto"]) . '</td>
                                    <td>' . htmlspecialchars($row["reparacion_cielo"]) . '</td>
                                    <td>' . htmlspecialchars($row["reparacion_piso"]) . '</td>
                                    <td>' . htmlspecialchars($row["reparacion_muro"]) . '</td>
                                    <td>' . htmlspecialchars($row["reparacion_cornisa"]) . '</td>
                                    <td>' . htmlspecialchars($row["retiro_instalacion_mobiliario"]) . '</td>
                                    <td>' . htmlspecialchars($row["observaciones"]) . '</td>
                                </tr>';
                            }
                        } else {
                            echo '
                            <tr>
                                <td colspan="10">No se encontraron registros para este caso.</td>
                            </tr>';
                        }
                        // Cerrar la conexión (opcional si se hace en otra parte)
                        $conn->close();
                        ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
            
          </div>
        </div>
      </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>