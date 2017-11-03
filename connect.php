<?php
$server="localhost";
$username="root";
$password="";
$db="warehouse";

$dbc=new mysqli($server,$username,$password,$db);

if($dbc){
   echo "connection established<br>";
}
else {
   die("<br>connection failed: ".$dbc->connect_error.'<br>');
}
function get_base_url($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){

    /*  
    url like: http://stackoverflow.com/questions/2820723/how-to-get-base-url-with-php

    echo base_url();    //  will produce something like: http://stackoverflow.com/questions/2820723/
    echo base_url(TRUE);    //  will produce something like: http://stackoverflow.com/
    echo base_url(TRUE, TRUE); || echo base_url(NULL, TRUE);    //  will produce something like: http://stackoverflow.com/questions/
    and finally
    echo base_url(NULL, NULL, TRUE);
    will produce something like: 
        array(3) {
            ["scheme"]=>
            string(4) "http"
            ["host"]=>
            string(12) "stackoverflow.com"
            ["path"]=>
            string(35) "/questions/2820723/"
    }     
     */


    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
        $core = $core[0];

        $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
        $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
        $base_url = sprintf( $tmplt, $http, $hostname, $end );
    }
    else $base_url = $_SERVER['SERVER_NAME'];

    if ($parse) {
        $base_url = parse_url($base_url);
        if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
    }

    return $base_url;
}
?>
