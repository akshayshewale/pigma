<?php
    require_once("DeveloperUtils.php");//checked and working
    class FileHandling{
        public $fileUploadSuccess=0;
        public $fileUploadFail=1;
        public $largeFile = 2;
        public $invalidExtension = 3;
        
        protected function error($text){
            $error = "<br><br>Please refer to the docs<br><br>";
            $error .= "SYNTAX ERROR::".$text."<br>";
            echo $error;
        }

        function FileHandling(){
            ini_set('file_uploads','on');
        }
        //uploads file to the server
        //upload(str tagname,str filename,str extension,int size,array allowedExtensions)
        //upload(str tagname,str filename,str extension,int size)
        //upload(str tagname,str filename,array allowedExtensions,int size)
        //upload(str tagname,str filename,str extension,array allowedExtensions)
        //upload(str tagname,str filename,str extension)
        //upload(str tagname,str filename,int size)
        //upload(str tagname,str filename,array allowedExtensions)
        //upload(str tagname,str filename)
        //upload(str tagname)
        function upload(){
            $args = func_get_args();
            $cnt = count($args);
            $tagname = $args[0];
            if($cnt==5){
                $file = $args[1];
                $ext = strtolower(pathinfo(files($tagname,'name'),PATHINFO_EXTENSION));
                $size = $args[3];
                $allExt = $args[4];
                $filename = $file.'.'.$args[2];
                $accept=false;

                foreach($allExt as $check){
                    if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                        $accept=true;
                    }
                }

                if($accept==true){
                    if(files($tagname,'size')<=($size*1000000)){
                       return $this->upload($tagname,$filename);
                    }
                    else{
                        return $this->largeFile;
                    }
                }
                else{
                    return $this->invalidExtension;
                }
            }
            elseif($cnt==4){
                $file = $args[1];
                if(is_int($args[3])){
                    $size = $args[3];
                    if(is_string($args[2])){
                        $filename = $file.'.'.$args[2];
                        if(files($tagname,'size')<=($size*1000000)){
                            return $this->upload($tagname,$filename);
                        }
                        else{
                            return $this->largeFile;
                        }
                    }
                    elseif(is_array($args[2])){
                        $ext = strtolower(pathinfo(files($tagname,'name'),PATHINFO_EXTENSION));
                        $allExt = $args[2];
                        $filename = $file.'.'.$ext;
                        $accept=false;

                        foreach($allExt as $check){

                            if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                                $accept=true;
                            }
                        }

                        if($accept==true){
                            if(files($tagname,'size')<=($size*1000000)){
                               return $this->upload($tagname,$filename);
                            }
                            else{
                                return $this->largeFile;
                            }
                        }
                        else{
                            return $this->invalidExtension;
                        }
                    }
                    else{
                        $this->error("invalid Parameters");
                    }
                }   
                elseif(is_string($args[2])){
                    $ext = strtolower(pathinfo(files($tagname,'name'),PATHINFO_EXTENSION));
                    $allExt = $args[3];
                    $filename = $file.'.'.$args[2];
                    $accept=false;

                    foreach($allExt as $check){
                        if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                            $accept=true;
                        }
                    }

                    if($accept==true){
                       return $this->upload($tagname,$filename);
                    }
                    else{
                        return $this->invalidExtension;
                    }
                }
            }
            elseif($cnt==3){
                $file = $args[1];
                if(is_int($args[2])){
                    $ext = strtolower(pathinfo(files($tagname,'name'),PATHINFO_EXTENSION));
                    $size = $args[2];
                    $filename = $file.'.'.$ext;
                    if(files($tagname,'size')<=($size*1000000)){
                       return $this->upload($tagname,$filename);
                    }
                    else{
                        return $this->largeFile;
                    }
                }
                elseif(is_string($args[2])){
                    $filename = $file.'.'.$args[2];
                   return $this->upload($tagname,$filename);
                }
                elseif(is_array($args[2])){
                    $ext = strtolower(pathinfo(files($tagname,'name'),PATHINFO_EXTENSION));
                    $allExt = $args[2];
                    $filename = $file.'.'.$ext;
                    $accept=false;

                    foreach($allExt as $check){
                        if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                            $accept=true;
                        }
                    }

                    if($accept==true){
                       return $this->upload($tagname,$filename);
                    }
                    else{
                        return $this->invalidExtension;
                    }
}
                else{
                    $this->error("Invalid Parameters");
                }
            }
            elseif($cnt==2){
                $filename = $args[1];
                if (move_uploaded_file(files($tagname,'tmp_name'), $filename)) {
                    return $this->fileUploadSuccess;
                } else {
                    return $this->fileUploadFail;
                }
            }   
            else{
                $this->error("Invalid parameter passed");
            }
        }
        //uploads multi files to the server
        //uploadMultiple(array tagname,str filename,str extension,int size,array allowedExtensions)
        //uploadMultiple(array tagname,str filename,str extension,int size)
        //uploadMultiple(array tagname,str filename,array allowedExtensions,int size)
        //uploadMultiple(array tagname,str filename,str extension,array allowedExtensions)
        //uploadMultiple(array tagname,str filename,str extension)
        //uploadMultiple(array tagname,str filename,int size)
        //uploadMultiple(array tagname,str filename,array allowedExtensions)
        //uploadMultiple(array tagname,str filename)
        //uploadMultiple(array tagname)
        function uploadMultiple(){
            $args = func_get_args();
            $cnt = count($args);
            $tagname = $args[0];
            $tagcount = count(files($tagname,'name'));
            $return = array();
            if($cnt==5){
                $file = $args[1];
                $size = $args[3];
                $allExt = $args[4];
                for($i=0;$i<$tagcount;$i++){
                    $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                    $filename = $file.$i.'.'.$args[2];
                    $accept=false;
                    
                    foreach($allExt as $check){
                        if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                            $accept=true;
                        }
                    }   
                    
                    if($accept==true){
                        if(files($tagname,'size',$i)<=($size*1000000)){
                            $this->uploadMultiple($tagname,$filename,$i,true);
                        }
                        else{
                            $return[count($return)-1] = $this->largeFile;
                        }
                    }
                    else{
                        $return[count($return)-1] = $this->invalidExtension;
                    }
                }
            }
            elseif($cnt==4){
                $file = $args[1];
                if(is_bool($args[3])){
                    $filename = $args[1];
                    $index = $args[2];
                    if (move_uploaded_file(files($tagname,"tmp_name",$index), $filename)) {
                        $return[count($return)-1] = $this->fileUploadSuccess;
                    } else {
                        $return[count($return)-1] = $this->fileUploadFail;
                    }
                }
                elseif(is_int($args[3])){
                    $size = $args[3];
                    if(is_string($args[2])){
                        for($i=0;$i<$tagcount;$i++){
                            $filename = $file.$i.'.'.$args[2];
                            if(files($tagname,'size',$i)<=($size*1000000)){
                                $this->uploadMultiple($tagname,$filename,$i,true);
                            }
                            else{
                                $return[count($return)-1] = $this->largeFile;
                            }
                        }
                    }
                    elseif(is_array($args[2])){
                        $allExt = $args[2];
                        for($i=0;$i<$tagcount;$i++){
                            $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                            $filename = $file.$i.'.'.$ext;
                            $accept=false;

                            foreach($allExt as $check){
                                if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                                    $accept=true;
                                }
                            }   

                            if($accept==true){
                                if(files($tagname,'size',$i)<=($size*1000000)){
                                    $this->uploadMultiple($tagname,$filename,$i,true);
                                }
                                else{
                                    $return[count($return)-1] = $this->largeFile;
                                }
                            }
                            else{
                                $return[count($return)-1] = $this->invalidExtension;
                            }
                        }
                    }
                    else{
                        $this->error("Invalid Parameters");
                    }
                }
                elseif(is_string($args[2])){
                    $allExt = $args[3];
                    for($i=0;$i<$tagcount;$i++){
                        $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                        $filename = $file.$i.'.'.$args[2];
                        $accept=false;

                        foreach($allExt as $check){
                            if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                                $accept=true;
                            }
                        }   

                        if($accept==true){
                            $this->uploadMultiple($tagname,$filename,$i,true);
                        }
                        else{
                            $return[count($return)-1] = $this->invalidExtension;
                        }
                    }
                }
                else{
                    $this->error("Invalid parameters");
                }
            }
            elseif($cnt==3){
                if(is_string($args[2])){
                    $file = $args[1];
                    for($i=0;$i<$tagcount;$i++){
                        $filename = $file.$i.'.'.$args[2];
                        $this->uploadMultiple($tagname,$filename,$i,true);
                    }
                }
                elseif(is_int($args[2])){
                    $file = $args[1];
                    $size = $args[2];
                    for($i=0;$i<$tagcount;$i++){
                        $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                        $filename = $file.$i.'.'.$ext;
                        if(files($tagname,'size',$i)<=($size*1000000)){
                            $this->uploadMultiple($tagname,$filename,$i,true);
                        }
                        else{
                            $return[count($return)-1] = $this->largeFile;
                        }
                    }
                }
                elseif(is_array($args[2])){
                    $file = $args[1];
                    $allExt = $args[2];
                    for($i=0;$i<$tagcount;$i++){
                        $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                        $filename = $file.$i.'.'.$ext;
                        $accept=false;

                        foreach($allExt as $check){
                            if($ext==strtolower($check) || ('.'.$ext)==strtolower($check)){
                                $accept=true;
                            }
                        }   

                        if($accept==true){
                            $this->uploadMultiple($tagname,$filename,$i,true);
                        }
                        else{
                            $return[count($return)-1] = $this->invalidExtension;
                        }
                    }
                }
                else{
                    $this->error("Invalid Parameters");
                }
            }
            elseif($cnt==2){
                $file = $args[1];
                for($i=0;$i<$tagcount;$i++){
                    $ext = strtolower(pathinfo(files($tagname,'name',$i),PATHINFO_EXTENSION));
                    $filename = $file.$i.'.'.$ext;
                    $this->uploadMultiple($tagname,$filename,$i,true);
                }
            }
            else{
                $this->error("Invalid Parameters");
            }
            return $return;
        }
    }
?>
