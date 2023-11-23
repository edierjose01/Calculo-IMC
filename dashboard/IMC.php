<?php require_once "vistas/parte_superior.php"?>

<?php 
    require_once('Core/autoload.php');
    $conex = new Registro();
    $rows = $conex->obtenerRegistros();

    if (isset($_POST['calcular'])) {
        $idPaciente = $_POST['idPaciente'];
        $peso = $_POST['peso'];
        $altura = $_POST['altura'];
        // Obtener el nombre del paciente
        $nombrePaciente = "";
        foreach ($rows as $row) {
            if ($row['paciente_id'] == $idPaciente) {
                $nombrePaciente = $row['nombre_paciente'];
                break;
            }
        }
    
        $usuario = new Usuario();
        $usuario->setPeso($peso);
        $usuario->setAltura($altura);
        $imc = $usuario->calcularIMC();
        $mensaje = $usuario->mensaje();
        $color = $usuario->color;
        
        // Insertar datos con el nombre del paciente
        $conex->insertarDatos($peso, $altura, $imc, $mensaje, $idPaciente, $nombrePaciente);
    
        // Obtener registros actualizados (incluyendo el nuevo registro)
        $rows = $conex->obtenerRegistros();

    }
?>
<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="Assets/css/materialize.min.css"  media="screen,projection"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="Assets/css/style.css?v=<?php echo(rand()); ?>">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <!-- Navegación -->
    <nav class="navegacion">
        <div class="nav-wrapper">
            <a href="index.php" class="brand-logo center"><img src="Assets/img/logo.png" alt=""></a>
            <ul id="nav-mobile" class="left hide-on-med-and-down"></ul>
        </div>
    </nav>
    <header class="header">
        <h2>Calculadora IMC</h2>
    </header>
    <!-- ./Navegación -->
    <!-- Contenido -->
    <main>
        <div class="contenido-centro">
            <div class="row">
                <div class="col s6 calculadora">
                    <div class="row">
                        <div class="col s6 imagen">
                            <img src="Assets/img/imc.png" alt="">
                        </div>
                        <!-- Formulario -->
                        <form method="POST" action="" class="col s6">
                        <div class="input-field">
                            <select id="idPaciente" name="idPaciente">
                                <option value="" disabled selected>Selecciona un paciente</option>
                                <?php
                                $registro = new Registro();
                                $pacientes = $registro->obtenerPacientes();

                                foreach ($pacientes as $paciente) {
                                    echo "<option value='{$paciente['id']}'>{$paciente['nombre']}</option>";
                                }
                                ?>
                            </select>
                            <label for="idPaciente">Nombre del paciente</label>
                            <p>Nombre seleccionado: <span id="nombreSeleccionado"></span></p>
                        </div>
                            <div class="input-field">
                                <i class="material-icons prefix">account_balance_wallet</i>
                                <input id="peso" name="peso" type="number" class="validate">
                                <label for="peso">Peso (kg)</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">trending_up</i>
                                <input id="altura" name="altura" type="number" class="validate">
                                <label for="altura">Altura (cm)</label>
                            </div>
                            <?php if (isset($_POST['calcular'])): ?>
                                <div class="card-panel <?= $color ?>" id="mensajeIMC">
                                    <b><span class="white-text">Tu IMC es de <?= $imc ?></span><br></b>
                                    <b><span class="white-text">Estado: <?= $mensaje ?></span></b>
                                </div>
                            <?php endif; ?>
                            <button class="btn waves-effect waves-light" type="submit" name="calcular">Calcular
                                <i class="material-icons right">send</i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col s6 tabla">
                    <!-- Tabla -->
                    <!-- ... tu tabla existente ... -->
                    <table class="tabla-scroll">
                        <thead class="headerP">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>ID</th>
                                <th>Peso(kg)</th>
                                <th>Altura(cm)</th>
                                <th>IMC</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (is_array($rows)): ?>
                                <?php $i = 1; ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><b><?= $i; ?></b></td>
                                        <td><?= $row['nombre_paciente']; ?></td>
                                        <td><?= $row['paciente_id']; ?></td>
                                        <td><?= $row['peso_usuario']; ?></td>
                                        <td><?= $row['altura_usuario']; ?></td>
                                        <td><?= $row['imc_usuario']; ?></td>
                                        <td><?= $row['estado_usuario']; ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="no-datos"><?= $rows ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
              <div class="observacion">
                <div class="card blue-grey darken-1">
                  <div class="card-content white-text">
                    <span class="card-title">Observación</span>
                    <p>En esta tabla se muestra los registros de los usuarios que han usado la aplicación.</p>
                  </div>
                </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ./Contenido -->
    <!--JavaScript at end of body for optimized loading-->
    <script type="text/javascript" src="Assets/js/materialize.min.js"></script>
    <!-- Custom JS -->
    <script type="text/javascript" src="Assets/js/app.js?v=<?php echo(rand()); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);

            var select = document.getElementById('idPaciente');
            var nombreSeleccionado = document.getElementById('nombreSeleccionado');

            select.addEventListener('change', function() {
                var selectedOption = select.options[select.selectedIndex];
                nombreSeleccionado.textContent = selectedOption.text;
            });

            // Ocultar el mensaje del IMC después de 5 segundos si está presente
            if (document.querySelector('#mensajeIMC')) {
                setTimeout(function() {
                    document.querySelector('#mensajeIMC').style.display = 'none';
                }, 8000); // Oculta el mensaje después de 5 segundos (5000 milisegundos)
            }


            // Resaltar la fila seleccionada
        <?php if (isset($_POST['calcular'])): ?>

              setTimeout(function() {
                    lastRow.scrollIntoView({ behavior: 'smooth', block: 'end', inline: 'nearest' });
                }, 100);
                // Obtener el último elemento de la tabla
                    var tableRows = document.querySelectorAll('tbody tr');
                    var lastRow = tableRows[tableRows.length - 1];

                    // Asignar colores según el mensaje
                    <?php if ($mensaje === "Peso bajo"): ?>
                        lastRow.style.backgroundColor = 'grey';
                    <?php elseif ($mensaje === "Peso normal"): ?>
                        lastRow.style.backgroundColor = 'green';
                    <?php elseif ($mensaje === "Sobrepeso"): ?>
                        lastRow.style.backgroundColor = 'orange';
                    <?php else: ?>
                        lastRow.style.backgroundColor = 'red';
                    <?php endif; ?>


            // Después de 10 segundos, quitar el resaltado
            setTimeout(function() {
                lastRow.style.backgroundColor = '';
            }, 10000);
            

            
            setTimeout(function() {
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 4000);// Espera 10 segundos (10000 milisegundos)
        <?php endif; ?>
        });
    </script>

</body>
</html>
<?php require_once "vistas/parte_inferior.php"?>