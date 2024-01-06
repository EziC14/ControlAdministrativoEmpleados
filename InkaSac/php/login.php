<?php
    session_start();
    include 'conexion_BE.php';

    if(isset($_POST['user']) && isset($_POST['pass'])){
        $user = $_POST['user'];
        $contra = $_POST['pass'];

        $validarEmpleado = mysqli_query($conexion, "SELECT * FROM empleado 
        WHERE IdEmpleado = '$user' AND Password = '$contra' and ReportTo is not null");

        $validarSupervisor = mysqli_query($conexion, "SELECT * FROM empleado 
        WHERE IdEmpleado = '$user' AND Password = '$contra' and ReportTo is null");

        if(mysqli_num_rows($validarEmpleado) > 0){
            $_SESSION['empleado'] = $user;
            $_SESSION['IdEmpleado'] = $user;
            header("Location: ../empleados.php");
            exit;

        } elseif(mysqli_num_rows($validarSupervisor) > 0){
            $_SESSION['empleado'] = $user;
            $_SESSION['IdEmpleado'] = $user;
            header("Location: ../supervisor.php");
            exit;
        } else{
            echo '
                <script>
                    alert("El usuario no existe");
                    window.location = "../index.php";
                </script>
            ';
            exit;
        }
    } else{
        echo "Error: Los campos de usuario y contraseña no están definidos.";
        exit;
    }
?>
