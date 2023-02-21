<?php
class upLoaderClass  {

    protected $ext = array(
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/gif'
        

    );

    protected $rightName = null;
    protected $carpeta = "subidas/";
    protected $mensajes = array();




    public function __construct($carpeta){
        if (!file_exists($carpeta)){
            throw new Exception("No hay carpeta de subida.");
        }
    }
    public function doUpload(){//subidas
        $archivo = current($_FILES);

        if(is_array($archivo['name'])){
            foreach($archivo['name'] as $clave => $valor){
                $multiarchivo ['name'] = $archivo ['name'] [$clave];
                $multiarchivo ['type'] = $archivo ['type'] [$clave];
                $multiarchivo ['tmp_name'] = $archivo ['tmp_name'] [$clave];
                $multiarchivo ['error'] = $archivo ['error'] [$clave];
                $multiarchivo ['size'] = $archivo ['size'] [$clave];
        if($this->checkFiles($multiarchivo)){
            $this->moveFiles($multiarchivo);
        }
      }
        }else
    
        if($this->checkFiles($archivo)){
            $this->moveFiles($archivo);
        }
    }

    public function messenger(){
        return $this->mensajes;
    }



    protected function serverTop($archivo){
        $serverLimit = self::getInBytes(ini_get('upload_max_filesize'));
        if($serverLimit > $archivo['size']){
            return true;

        }else{
            $this->mensajes[]= "El servidor ha bloqueado la subida";
        }

    }
    protected function checkMIME($archivo){

        if(in_array($archivo['type'], $this-> ext)){
            return true;

        }else{
            $this->mensajes[]= "El tipo de archivo no es válido";
            return false;
        }

    }
    protected function checkSpaces($archivo){
        $newName = str_replace(' ', '_', $archivo['name']);
        if($newName != $archivo['name']){
            $this->rightName = $newName;
        }
        $nombreDividido = pathinfo($newName);

        $nombre = isset($this->rightName) ? $this->rightName : $archivo['name'];
        $duplicado = scandir($this->carpeta);
        if(in_array($nombre, $duplicado)){
            $i=1;
            do{$this->rightName = $nombreDividido['filename'].'_'.$i++.'.'.$nombreDividido['extension'];
            }while(in_array($this->rightName, $duplicado));
        }

    }

    protected function checkFiles($archivo){ //checkear los archivos
        if(!$this->serverTop($archivo)){
            return false;
        }
        if(!$this->checkMIME($archivo)){
            return false;
        }
        $this->checkSpaces($archivo);
        if($archivo['error']==0){
            return true;
        }else{
            $this->error($archivo);
        }

    }
    protected function error($archivo){//manejo de errores

        switch($archivo['error']){
            case 2;
            $this->mensajes[]= 'es demasiado grande';
            break;
            case 4;
            $this->mensajes[]= "No se ha seleccionado un archivo";
            break;
            default:
            $this->mensajes[]='no se ha podido subir';
        }

    }
    protected function moveFiles($archivo){//mover el archivo
        $nombreArchivo = isset($this->rightName) ? $this ->rightName : $archivo['name'];
        $exito = move_uploaded_file($archivo['tmp_name'], $this->carpeta. $nombreArchivo);
        if($exito){
            if($this->rightName != null){
                $this->mensajes[]= $archivo['name'].'Se ha subido y renombrado a :'.$this->rightName.'<br/>';
    
            }else{
                $this->mensajes[]= $archivo['name'].'Se ha subido <br/>';
            }
        }
    }

    protected function getInBytes($serverMb){
       // $serverMb = (int)$serverMb * 1048576; //1024 * 1024 
       $serverMb =  trim($serverMb);
        $newSize = strtolower($serverMb[strlen($serverMb) -1]);
        if(in_array($newSize, array('g', 'm', 'k'))){
            switch($newSize){
                case 'g':
                    $serverMb *= 1073741824;
                    break;
                case 'm':
                    $serverMb *= 1048576;
                    break; 
                case 'k':
                    $serverMb *= 1024;
                        
            }

        }
        echo $serverMb;
        return $serverMb;

    }
}

