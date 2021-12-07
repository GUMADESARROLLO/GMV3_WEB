<?php

    include_once('includes/config.php');
    // start session
    //session_start();
    
   /* $setting_qry    = "SELECT * FROM tbl_purchase_code ORDER BY id DESC LIMIT 1";
    $setting_result = mysqli_query($connect, $setting_qry);
    $settings_row   = mysqli_fetch_assoc($setting_result);
    $purchase_code    = $settings_row['item_purchase_code'];*/

    // if user click Login button
    if(isset($_POST['btnLogin'])) {

        // get username and password
        $username = $_POST['username'];
        $password = $_POST['password'];

        // set time for session timeout
        $currentTime = time() + 25200;
        $expired = 3600;

        // create array variable to handle error
        $error = array();

        // check whether $username is empty or not
        if(empty($username)) {
            $error['username'] = "*Campo Usuario no debe estar vacio.";
        }

        // check whether $password is empty or not
        if(empty($password)) {
            $error['password'] = "*Campo Contraseña no debe estar vacio.";
        }

        // if username and password is not empty, check in database
        if(!empty($username) && !empty($password)) {
          
        
            // change username to lowercase
            $username = strtolower($username);

            $KeysSecret = "A7M";

            //encript password to sha256
            $password = hash('sha256',$KeysSecret.$password);

            // get data from user table
            $sql_query = "SELECT Activo,username,password,permisos,GRUPO FROM view_tbl_usuario WHERE Activo = 'S' AND username = ? AND password = ? ";



          
            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                // Bind your variables to replace the ?s
                $stmt->bind_param('ss', $username, $password);
                // Execute query
                $stmt->execute();
                /* store result */
                $stmt->store_result();
                $stmt -> bind_result($rActivo, $rUserName,$rPassword,$rPermisos,$rGrupo); 
                $stmt -> fetch();
                $num = $stmt->num_rows;

                // Close statement object               
               // $stmt->close();
                if($num == 1) {
                    
                   /* if (strlen($purchase_code) >= 36) {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: dashboard.php");
                    } else {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: verify-purchase-code.php");
                    }*/
                   
                    $_SESSION['user'] = $username;
                    $_SESSION['grupos'] = $rGrupo;
                    $_SESSION['permisos'] = $rPermisos;
                    $_SESSION['timeout'] = $currentTime + $expired;


                    switch ($rPermisos) {
                        case 5:
                            header("location: manage-banner.php");
                            break;
                        default:
                            header("location: manage-product2.php");
                            break;
                    }




                } else {
                    $error['failed'] = "<center><div class='alert alert-warning'>Usuario o contraseña invalido!</div></center>";
                }
            }

        }
    }
?>

<div class="logincard2">
    <div class="pmd-card card-default pmd-z-depth dashboard">
        <div class="login-card">
            <form method="POST">  
                <div class="pmd-card-title card-header-border text-center">
                    <div class="loginlogo">
                        <img src="assets/images/ic-logo.png" alt="Logo" style="width: 98px; height: 88px;">
                    </div>
                    <div class="lead">GESTOR DE PEDIDO</div>
                </div>
                
                <div class="pmd-card-body">
                    <?php echo isset($error['failed']) ? $error['failed'] : '';?>
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Usuario</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">perm_identity</i></div>
                            <input type="text" name="username" class="form-control" id="exampleInputAmount" required>
                        </div>
                    </div>
                    
                    <div class="form-group pmd-textfield pmd-textfield-floating-label">
                        <label for="inputError1" class="control-label pmd-input-group-label">Contraseña</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="material-icons md-dark pmd-sm">lock_outline</i></div>
                            <input type="password" name="password" class="form-control" id="exampleInputAmount" required>
                        </div>
                    </div>
                </div>
                <div class="pmd-card-footer card-footer-no-border card-footer-p16 text-center">
                    <div class="form-group clearfix">
                    </div>
                    <button type="submit" name="btnLogin" class="btn pmd-ripple-effect btn-danger btn-block">Acceder</button>
                    <br>
                    <br>
                    <span class="pmd-card-subtitle-text">UNIMARK S,A &copy; <span class="auto-update-year"></span>. Todos los Derechos Reservados.</span>
            <h3 class="pmd-card-subtitle-text">Version 3.3.0</h3>
                    
                </div>
                
            </form>
        </div>
        
    </div>
</div>