<?php

//$con=mysqli_connect("192.168.1.142","root","","ts");

//$con=new mysqli("104.155.211.99","TS_DEV","TS_DEV","TSDEV");

// Check connection
//if ($con->connect_error) {
//    die("Connection failed: " . $con->connect_error);
//}
//echo "Connected successfully";

$con = new mysqli(null,
    'TSINT', // username
    'TSINT', // password
    'TS_INT',
    null,
    '/cloudsql/ei-html-ssomens:eihtmlssomens');
