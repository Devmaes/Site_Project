<?php
$connexion = new PDO('mysql:host=127.0.0.1;dbname=university','root','');
if ($connexion) {
    echo "";
}else{
    echo "actualy disconnect !!!";
}



