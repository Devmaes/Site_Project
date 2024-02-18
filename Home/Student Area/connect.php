<?php
$connexion = new PDO('mysql:host=127.0.0.1;dbname=university','root','');
if ($connexion) {
    echo "actualy connect with your database";
}else{
    echo "actualy disconnect !!!";
}



