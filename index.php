<?php
if(isset($_GET['page'])){
    switch($_GET['page']){
        case "login":
            echo "Login";
        break;

        default:
            echo "404";
        break;
    }
}else{
    echo "Home";
}