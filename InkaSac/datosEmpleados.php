<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inka SAC</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/stylesEmpleado.css">
  <link rel="icon" href="inka.ico" type="image/x-icon">
</head>

<body>
  <header>
    <p>EMPLEADO</p>
  </header>
  <img src="img/user.png" alt="user" class="user">
  <p class="IdEmpleadop">ID</p>

  <div class="IdEmpleado">
    <?php
    $cnx = mysqli_connect("192.168.11.15","InkaSac", "INKAsac2023BD", "inkasac");
    session_start();
    if (isset($_GET['idEmpleado'])) {
      $idEmpleado = $_GET['idEmpleado'];
      $_SESSION['IdEmpleado'] = $idEmpleado;
    }
    echo $idEmpleado;
    ?>
  </div>
  <p class="NombreEmpleadop">Nombre</p>
  <div class="NombreEmpleado">
    <?php
    $user = $_SESSION['IdEmpleado'];

    $sql = "SELECT NombreEmpleado FROM empleado WHERE IdEmpleado = '$user'";
    $rta = mysqli_query($cnx, $sql);

    if ($rta && mysqli_num_rows($rta) > 0) {
      $row = mysqli_fetch_assoc($rta);
      echo $row['NombreEmpleado'];
    } else {
      echo "No se encontraron registros.";
    }
    ?>
  </div>

  <p class="Departamentop">Area</p>
  <div class="Departamento">
    <?php
    $user = $_SESSION['IdEmpleado'];

    $sql = "SELECT Departamento FROM empleado WHERE IdEmpleado = '$user'";
    $rta = mysqli_query($cnx, $sql);

    if ($rta && mysqli_num_rows($rta) > 0) {
      $row = mysqli_fetch_assoc($rta);
      echo $row['Departamento'];
    } else {
      echo "No se encontraron registros.";
    }
    ?>
  </div>
  <p class="Supervisorp">Supervisor</p>
  <div class="Supervisor">
    <?php
    $user = $_SESSION['IdEmpleado'];

    $sql = "SELECT ReportTo FROM empleado WHERE IdEmpleado = '$user'";
    $rta = mysqli_query($cnx, $sql);

    if ($rta && mysqli_num_rows($rta) > 0) {
      $row = mysqli_fetch_assoc($rta);
      $supervisor = $row['ReportTo'];
      $sql = "SELECT nombreEmpleado FROM empleado WHERE IdEmpleado = '$supervisor'";
      $rta1 = mysqli_query($cnx, $sql);

      if ($rta && mysqli_num_rows($rta1) > 0) {
        $row = mysqli_fetch_assoc($rta1);
        echo $row['nombreEmpleado'];
      } else {
        echo "No se encontraron registros.";
      }
    } else {
      echo "No se encontraron registros.";
    }
    ?>
  </div>

  <div class="MontoTotal">
    <?php
    $user = $_SESSION['IdEmpleado'];

    $sql = "SELECT SUM(ventasxempleado.Monto) AS Expr1
          FROM empleado
          INNER JOIN ventasxempleado ON empleado.IdEmpleado = ventasxempleado.IdEmpleado
          INNER JOIN empresacliente ON ventasxempleado.IdEmpresa = empresacliente.IdEmpresa
          WHERE empleado.IdEmpleado = '$user';";
    $rta = mysqli_query($cnx, $sql);

    if ($rta && mysqli_num_rows($rta) > 0) {
      $row = mysqli_fetch_assoc($rta);
      echo $row['Expr1'];
    } else {
      echo "No se encontraron registros.";
    }
    ?>
  </div>

  <div class="container">
    <img src="img/check.png" alt="check" class="check">
    <div class="table_header">
      <p class="ventas">Ventas Realizadas</p>
      <div>
        <form class="search" onsubmit="event.preventDefault()">
          <input placeholder="Buscar código" name="buscar">
          <i type="submit" class="bi bi-search" id="search" value="busc"></i>
        </form>
      </div>
    </div>
    <div class="table-container">
      <table class="table-fixed">
        <thead>
          <tr>
            <td>CODIGO</td>
            <td>ORGANIZACION</td>
            <td>RUC</td>
            <td>FECHA</td>
            <td>MONTO</td>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($_SESSION['IdEmpleado'])) {
            $user = $_SESSION['IdEmpleado'];
            $sql = "SELECT ventasxempleado.IdVenta, empresacliente.NombreEmpresa, empresacliente.RUC, ventasxempleado.FechaVenta, ventasxempleado.Monto FROM empleado 
                        INNER JOIN ventasxempleado ON empleado.IdEmpleado = ventasxempleado.IdEmpleado 
                        INNER JOIN empresacliente ON ventasxempleado.IdEmpresa = empresacliente.IdEmpresa 
                        WHERE empleado.IdEmpleado = '$user';";
            $rta = mysqli_query($cnx, $sql);
            while ($mostrar = mysqli_fetch_row($rta)) {
              ?>
              <tr class="fila-hover">
                <td><?php echo $mostrar[0] ?></td>
                <td><?php echo $mostrar[1] ?></td>
                <td><?php echo $mostrar[2] ?></td>
                <td><?php echo $mostrar[3] ?></td>
                <td><?php echo $mostrar[4] ?></td>
              </tr>
          <?php
            }
          } else {
            header("Location: ../index.php");
            exit;
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="table_fotter">
      <p>Monto Total</p>
    </div>
  </div>
  <a href="php/cerrar_sesion.php">Cerrar sesión</a>

  <button onclick="regresar()">Regresar</button>
  <i class="bi bi-arrow-return-left" id="regresar"></i>
  <script>
    const iconoSalir = document.getElementById("regresar");
    iconoSalir.addEventListener("click", function() {

      var supervisor = '<?php
                          $user = $_SESSION['IdEmpleado'];
                          $sql = "SELECT ReportTo FROM empleado WHERE IdEmpleado = '$user'";
                          $rta = mysqli_query($cnx, $sql);
                          if ($rta && mysqli_num_rows($rta) > 0) {
                            $row = mysqli_fetch_assoc($rta);
                            echo $row['ReportTo'];
                          } else {
                            echo "No se encontraron registros.";
                          }
                          ?>';

      const idSeleccionado = supervisor;
      window.location = "/datossupervisor.php?supervisor=" + idSeleccionado;
    });
  </script>
  <script>
    function regresar() {
      var supervisor = '<?php
                          $user = $_SESSION['IdEmpleado'];
                          $sql = "SELECT ReportTo FROM empleado WHERE IdEmpleado = '$user'";
                          $rta = mysqli_query($cnx, $sql);
                          if ($rta && mysqli_num_rows($rta) > 0) {
                            $row = mysqli_fetch_assoc($rta);
                            echo $row['ReportTo'];
                          } else {
                            echo "No se encontraron registros.";
                          }
                          ?>';

      const idSeleccionado = supervisor;
      window.location = "/datossupervisor.php?supervisor=" + idSeleccionado;
    }
  </script>

  <style>
    button {
    text-decoration: none;
    position: absolute;
    z-index: 2;
    top: 8px;
    right: 350px;
    color: #FFFFFF;
    font-family: 'Quicksand_Bold';
    letter-spacing: 5px;
    font-size: 20px;
    background-color: #D05916;
    border-radius: 30px;
    width: 220px;
    height: 40px;
    outline: none;
    padding: 5px 20px;
    border: 1px solid #D05916;
    box-sizing: border-box;
    padding-right: 90px;
    cursor: pointer;
  }

  button:hover {
    background-color: #a54711;
  }
  </style>

  <i class="bi bi-box-arrow-right" id="salir"></i>
  <script>
    const iconoSalir = document.getElementById("salir");
    iconoSalir.addEventListener("click", function() {
      window.location = "index.php";
    });
  </script>

  <script>
    const filas = document.querySelectorAll('.fila-hover');
    filas.forEach(fila => {
      fila.addEventListener('mouseover', () => {
        fila.classList.add('hovered');
      });

      fila.addEventListener('mouseout', () => {
        fila.classList.remove('hovered');
      });
    });
  </script>
<script> window.onload = function(){
    window.location.hash = "no-back-button";
    window.location.hash = "Again-No-back-button";

    window.onhashchange=function(){
        window.location.hash = "no-back-button";
    }

}
</script>
</body>
</html>
