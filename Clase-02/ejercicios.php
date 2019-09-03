<?php

    echo "Ejercicio 9";
    $arrayInt = array();
    $len = 5;
    $acum = 0;
    $prom;
    for($i = 0; $i < $len; $i++) {
        array_push($arrayInt, rand(1, 10));
    }
    foreach ($arrayInt as $num) {
        $acum += $num;
    }
    $prom = $acum / $len;
    echo "\nprom = ".$prom;
    var_dump($arrayInt);

    echo "\n\nEjercicio 10";
    $arrayImp = array();
    for($i = 1; $i < 20; $i+=2) {
        array_push($arrayImp, $i);
    }
    foreach ($arrayImp as $imp) {
        echo "\n".$imp;
    }

    echo "\n\nEjercicio 11";
    $v[1]=90; 
    $v[30]=7; 
    $v['e']=99; 
    $v['hola']='mundo';
    foreach ($v as $key => $value) {
        echo "\n$key = $value";
    }

    echo "\n\nEjercicio 12";
    $lapicera['color'] = 'Marrón';
    $lapicera['marca'] = 'fabricio-castel';
    $lapicera['trazo'] = 'idk';
    $lapicera['precio'] = 8.7;
    foreach ($lapicera as $key => $value) {
        echo "\n$key = $value";
    }

    echo "\n\nEjercicio 13";
    $animales = array("Perro", "Gato", "Ratón", "Araña", "Mosca");
    $numeros = array(1001, 444, 99);
    $tecnologias = array();
    array_push($tecnologias, "php", "mysql", "html");
    $todos = array_merge($animales, $numeros, $tecnologias);
    var_dump($todos);

    echo "\n\nEjercicio 14";
    $asoc['animales'] = $animales;
    $asoc['numeros'] = $numeros;
    $asoc['tecn'] = $tecnologias;
    $ind = array($animales, $numeros, $tecnologias);
    var_dump($asoc);
    var_dump($ind);
    ?>