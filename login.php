<?php
    include_once ',,/backend/php/login.php'
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>

<body>
    <div class="form-container">
        <h1 class="heading">
            SAN </br> 
            FRANCISCO

        </h1>
                <?php
                    if(isset($errMsg)){
                        echo '
                        <div style="color:#FF0000; text-align:center;font-size:20px;font-weight:bold;">'.$errMsg.'</div>';    
                    }
                    
                    ?>
                    

                <form action="" method="POST" autocomplete="off">            
                <input  
                    type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']?>" autocomplete="off"
                    class="for-input span-2"
                    placeholer="Nombre de usuario"
                >

                <input
                    type="text" name="password" required="true" value="<?php if(isset($_POST['password'])) echo MD5($_POST['username'])?>" autocomplete="off"
                    class=""
                >
                <button class="btn submit-btn span-2" name='login' type='submit'>Iniciar Sesión</button>
                
                <p class="btm-line">
                    By joining, you are agree to our Terms of Service and Privacy Policy
                </p>
                </form>
                    
            
    </div>
    


    
</body>
</html>