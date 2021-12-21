<html>
<head> <title>Cotización</title> <meta charset="utf-8">


<style>
/* Curso CSS estilos aprenderaprogramar.com*/
body {font-family: Arial, Helvetica, sans-serif;}

table {     font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
    font-size: 12px;    margin: 45px;     width: 80%; text-align: left;    border-collapse: collapse; }

th {     font-size: 13px;     font-weight: normal;     padding: 8px;     background: #b9c9fe;
    border-top: 4px solid #aabcfe;    border-bottom: 1px solid #fff; color: #039; }

td {    padding: 8px;     background: #e8edff;     border-bottom: 1px solid #fff;
    color: #669;    border-top: 1px solid transparent; }

tr:hover td { background: #d0dafd; color: #339; }
</style>
</head>
<body>
<?php
$dsn = "mysql:dbname=linksite";
$username = "cllorena";
$password = "01066319cC_";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e)
    {
        echo "Falla en la Conexion: " . $e->getMessage();
        }

//realizamos la consulta
$sql="SELECT * FROM lk3102_aejecutivo ORDER BY fecha DESC";
?>




<table >
<caption><h2>Solicitud de Cotización</h2></caption>
<tr> <th>Nombre</th> <th>Email</th> <th>Producto</th>
<th>Comentario</th> <th>Fecha</th>
</tr>

<?php
try
{
    $filas = $conn->query($sql);
    foreach ($filas as $fila) 

    {
?>
        <tr> 
        <td><?php echo $fila["name"]; ?></td> 
        <td><?php echo $fila["email"]; ?></td> 
        <td><?php echo $fila["producto"]; ?></td>
        <td><?php echo $fila["comment"]; ?></td> 
        <td><?php echo $fila["fecha"]; ?></td>
        </tr>

<?php

    }
} catch (PDOException $e)
    {
        echo "Falla en la consulta: " . $e->getMessage();
    }

$conn=null;
?>


</table>
</body>
</html>


