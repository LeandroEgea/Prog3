<?php
include 'clases/persona.php';
include 'clases/alumno.php';
include 'funciones.php';

    echo "Ejercicio 1";
    $nombre = "Gabriel";
    $apellido = "Saliba";
    echo "\n".$apellido.", ".$nombre;

    echo "\n\nEjercicio 2";
    $x = -3;
    $y = 15;
    $resultadoFinal = $x + $y;
    echo "\nResultado final: ".$resultadoFinal;

    echo "\n\nEjercicio 3\n";
    echo $x."<br/>".$y."<br/>".$resultadoFinal;

    echo "\n\nHERENCIA\n";
    $personaUno = new Persona("Gaby", 42118165);
    var_dump($personaUno);
    $personaUno->saludar();
    $alumnoUno = new Alumno("Gaby", 42118165, 1001, 3);
    var_dump($alumnoUno);
    $alumnoUno->saludar();

    echo "\n\nEjercicio 4\n";
    $acumulador = 0;
    $i;
    for($i=1; $acumulador<=1000; $i++) {
        $acumulador += $i;
        if($acumulador<=1000) {
            echo $acumulador."-";
        }
        else{
            echo "Fin";
            $i--;
        }
    }
    echo "\n".$i;

    echo "\n\nEjercicio 5\n";
    $a=5;
    $b=7;
    $c=5;
    if(($a<$b && $a>$c) || ($a>$b && $a<$c)) {
        echo $a;
    }
    else if(($b<$a && $b>$c) || ($b>$a && $b<$c)) {
        echo $b;
    }
    else if(($c<$b && $c>$a) || ($c>$b && $c<$a)) {
        echo $c;
    }
    else {
        echo "No hay";
    }

    echo "\n\nEjercicio 6\n";
    $op="/";
    $d=7;
    $e=9;
    switch($op){
        case "+":
            echo $d+$e;
            break;
        case "-":
            echo $d-$e;
            break;
        case "/":
            echo $d/$e;
            break;
        case "*":
            echo $d*$e;
            break;
        default:
            echo "Mal";
            break;
    }

    echo "\n\nEjercicio 7\n";
    echo date("D, d M Y   ");
    echo date("d/m/Y");
?>