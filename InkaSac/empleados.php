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
        <p class = "IdEmpleadop">ID</p>  

        <div class = "IdEmpleado">
        <?php
        $cnx = mysqli_connect("192.168.11.15","InkaSac", "INKAsac2023BD", "inkasac");
        session_start();
          $user = $_SESSION['IdEmpleado'];
          echo $user;
        ?></div>
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
        
      <form class="search">
        <input placeholder="Buscar código" name="buscar">
        <i type = "submit" class="bi bi-search" id="search" value= "busc"></i>
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
                        INNER JOIN ventasxempleado ON empleado.idEmpleado = ventasxempleado.idEmpleado 
                        INNER JOIN empresacliente ON ventasxempleado.IdEmpresa = empresacliente.IdEmpresa 
                        WHERE empleado.idEmpleado = '$user';";

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
  <i  class="bi bi-box-arrow-right" id ="salir"></i>
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
