<?php
    if(!isset($_SESSION)){
        session_start();
    }
    // TODO: Implement a better way to manage the connection to the database, such as using environment variables or a configuration file.
    define(constant_name: 'dbhost', value: 'localhost');
    define(constant_name:'dbuser',value:'root');
    define(constant_name:'dbpass',value:'-Jsma2022');
    define(constant_name:'dbname',value:'citas_medicas');
    
    try{
        $connect=new PDO(dsn: "mysql:host=".dbhost."; dbname=".dbname, username: dbuser,password:dbpass);
        $connect->query("set names utf8;");

        $connect->setAttribute(attribute: PDO::ATTR_ERRMODE,value: PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e){
        echo $e->getMessage();

    }
?>