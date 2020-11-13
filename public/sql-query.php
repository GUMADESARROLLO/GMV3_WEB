<?php

    function getStat() {

        try {
            include 'includes/config.php';
            $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
            $sql = 'CALL proc_stat_mes(@Mes, @Semana,@cPedidosProcesados,@PendidosPendientes);';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->closeCursor();
            $row = $pdo->query('SELECT CONCAT(IFNULL(@Mes,0),";",IFNULL(@Semana,0),";",IFNULL(@cPedidosProcesados,0),";",IFNULL(@PendidosPendientes,0)) as StatValue')->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row !== false ? $row['StatValue'] : null;
            }
        } catch (PDOException $e) {
            die("Error occurred:" . $e->getMessage());
        }
        return null;
    }

    function Insert($table, $data) {

        include 'includes/config.php';
        $fields = array_keys( $data );  
        $values = array_map(array($connect, 'real_escape_string'), array_values($data) );
        
        $sql = "INSERT INTO $table (".implode(",",$fields).") VALUES ('".implode("','", $values )."')";
        mysqli_query($connect, $sql);
    
    }

    function Delete($table_name, $where_clause = '') {

        include 'includes/config.php';
        $whereSQL = '';
        if(!empty($where_clause)) {
            if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                $whereSQL = " WHERE ".$where_clause;
            } else {
                $whereSQL = " ".trim($where_clause);
            }
        }
        $sql = "DELETE FROM ".$table_name.$whereSQL;
        return mysqli_query($connect, $sql);

    }

    // Update Data, Where clause is left optional
    function Update($table_name, $form_data, $where_clause='') {

        include 'includes/config.php';
        // check for optional where clause
        $whereSQL = '';
        if(!empty($where_clause)) {
            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE') {
                // not found, add key word
                $whereSQL = " WHERE ".$where_clause;
            } else {
                $whereSQL = " ".trim($where_clause);
            }
        }
        // start the actual SQL statement
        $sql = "UPDATE ".$table_name." SET ";

        // loop and build the column /
        $sets = array();
        foreach($form_data as $column => $value) {
             $sets[] = "`".$column."` = '".$value."'";
        }
        $sql .= implode(', ', $sets);

        // append the where statement
        $sql .= $whereSQL;
             
        // run and return the query result
        return mysqli_query($connect, $sql);
    }

?>