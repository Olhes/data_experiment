<?php
    require '../backend/bd/Conexion.php';


    if(isset($_POST['login'])){

        $errMsg='';

        $username=$_POST['ussername'];

        $password=password_hash($password,PASSWORD_DEFAULT);

        if($username==''){
            $errMsg='Digite su numero';
        }else if($password==''){
            $errMsg= 'Digite su contraseña';
        }

       if($errMsg==''){
        try{
            $stmt=$connect->prepare(query:'SELECT id, username,name,email,password,rol FROM users WHERE username=:username')
            
            $stmt->execute(array(
                ':username'=>$username;

            ));
            
            $data=$stmt->fetch(PDO::FETCH_ASSOC);

            if($data==false){
                $errMsh= "El usuario: $username no se encuentra, puede solicitarlo en el administrador.";
            }else{
                if($password==$data['password']){
                    $_SESSION['id']=$data['id'];
                    $_SESSION['username']=$data['username'];
                    $_SESSION['name']=$data['name'];
                    $_SESSION['email']=$data['email'];
                    $_SESSION['password']=$data['password']
                    $_SESSION['rol']=$data['rol'];

                    if($_SESSION['rol']==1){
                        header('Location: admin/escritorio.php');

                    }

                        exit;
                    }else
                        $errMsh= 'Contraseña incorrecta';
                }
            }
        
        }


        }
       } 
    
    }
?>