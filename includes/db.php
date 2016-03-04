<?php

class DataBase {

    public function __construct() {
        global $db;
        $db = mysql_connect(HOSTNAME_DB, USERNAME_DB, PASSWORD_DB) or die(mysql_error());
        mysql_select_db(DATABASE_DB, $db);


        $admin_folder_name = "admin";
        $url_path = $_SERVER['PHP_SELF'];

        if (!strstr($url_path, $admin_folder_name)) {
            include('querystringcheck.php');
        }
        return $db;
    }

    //select single record by id ( returns only one record ) 
    function SelectSingleRow($table, $condition, $fieldarray = "", $debug = "") {
        global $db;
        //	(empty($link))?d008():"";
        //if ( ! is_array($fieldarray) )
        if ($fieldarray == "") {
            $f_list = "*";
        } else {
            $f_list = $fieldarray;
            //$f_list = trim(implode( ", ", $fieldarray ));
        }
        $query = "SELECT $f_list FROM $table WHERE $condition";
        // echo $query;
        if ($debug == 1) {
            echo $query;
            exit();
        }
        $result = mysql_query($query, $db);
        if (!$result)
            return 0;
        $record = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $record;
    }

//select all records (return array of records)
    function SelectTable($table, $condition = "", $fieldarray = "", $debug = "") {
        global $db;
        $record = array();
//	(empty($link))?d008():"";
        if ($fieldarray == "") {
            $f_list = "*";
        } else {
            $f_list = $fieldarray;
        }
        $query = "SELECT $f_list FROM $table";
        if (!empty($condition))
            $query .= " WHERE  $condition";
        //echo $query;
        if ($debug == 1) {
            echo $query;
            exit();
        }
        $result = mysql_query($query, $db);
        print mysql_error();
        while ($result_row = mysql_fetch_assoc($result)) {
            $record[] = $result_row;
        }
        mysql_free_result($result);
        return $record;
    }

    function DeleteRecords($table, $condition) {
        global $db;
//	(empty($link))?d008():"";
        $query = "DELETE FROM $table WHERE $condition";
        $result = mysql_query($query, $db);
        if (!$result)
            return 0;
        return 1;
    }

//add record
    function InsertRecords($table, $data, $debug = "") {
        global $db;
        //	(empty($link))?d008():"";
        foreach ($data as $key => $value) {
            $field[] = $key;
            if ($value != "now()")
                $values[] = "'$value'";
            else
                $values[] = "$value";
        }
        $f_list = trim(implode(", ", $field));
        $v_list = trim(implode(", ", $values));
        $query = "INSERT INTO $table ( " . "$f_list" . " ) VALUES ( " . "$v_list" . " )";

        if ($debug == 1) {
            echo $query;
            exit();
        }

        //echo $query;
        $result = mysql_query($query, $db);
        if (!$result) {
            global $errormessage;
            $errormessage = mysql_error($db);
            return 0;
        }
        return mysql_insert_id($db);
    }

//edit record
    function UpdateRecords($table, $condition, $updata, $debug = "") {
        global $db;
//	(empty($link))?d008():"";
        foreach ($updata as $key => $value) {
            if ($value != "now()") {
                $fv[] = "$key = \"" . "$value" . "\"";
            } else {
                $fv[] = "$key = " . "$value" . "";
            }
        }

        $fv_list = trim(implode(", ", $fv));
        $query = "UPDATE $table SET " . "$fv_list";
        if (!empty($condition))
            $query .= " WHERE $condition";
        if ($debug == 1) {
            echo $query;
            exit();
        }
        $result = mysql_query($query, $db);

        //echo $query."<br>";
        if (!mysql_affected_rows($db)) {
            global $errormessage;
            $errormessage = mysql_error($db);

            if (!empty($errormessage))
                return 0;
        }

        return 1;
    }

    //select single record by id ( returns only one record ) 
    function dbquery($query, $debug = "") {
        global $db;

        // echo $query;
        if ($debug == 1) {
            echo $query;
            exit();
        }
        $result = mysql_query($query, $db);
        if (!$result)
            return 0;
        $record = mysql_fetch_assoc($result);
        mysql_free_result($result);
        return $record;
    }

}
