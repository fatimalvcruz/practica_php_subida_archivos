<?php

// echo ini_get('upload_max_filesize');
$carpeta= "subidas/";
$mensaje= "";
$size= 1000000000;
$resultado = array();


if(isset($_POST['subir'])){
    echo '<pre>';
    print_r($_FILES);
    print_r(current($_FILES));
    echo '</pre>';

    require_once'upLoaderClass.php';
    try{
        $subir= new upLoaderClass($carpeta);
        $subir->doUpload();
        $resultado = $subir->messenger();

    }catch(Exception $e){
        $mensaje = $e-> getMessage();

    }



     /* 

   switch($_FILES['nombrearchivo']['error']){
        case 0;
        move_uploaded_file($_FILES['nombrearchivo']['tmp_name'], $carpeta.$_FILES['nombrearchivo']['name']);
        $mensaje = "Su archivo se ha subido correctamente";
        break;
        case 2;
        $mensaje =$_FILES['nombrearchivo']['name'].'es demasiado grande';
        break;
        case 7;
        $mensaje = "No tienes permisos suficientes para subir el archivo";
        break;
        default:
         $_FILES['nombrearchivo']['name'].'no se ha podido subir';
    } */
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivos</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" 
    method="post"
    enctype="multipart/form-data">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $size;?>">
        <label for="nombrearchivo">Seleccione su archivo</label>
        <input type="file" name="nombrearchivo[]" id="nombrearchivo" multiple>
        <input type="submit" name="subir" value="Subir archivo">
    </form>
    <?php if(is_array($resultado)){ ?>
        <ul>
            <?php foreach($resultado as $mensajes){ 
                echo "<li> $mensajes</li>";
                
            }?>
                </ul>
           
    <?php }?>

    <?php //if($mensaje){echo $mensaje ;}?>
</body>
</html>