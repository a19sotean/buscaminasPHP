<?php
    /**
     * Buscaminas.
     * 
     * @author Andrea Solís Tejada
     */
    define('TAM_FILA', 10);
    define('TAM_COLUMNA', 10);
    define('NUM_MINAS', 10);
    define('NUM_CASILLAS', TAM_COLUMNA * TAM_FILA);

    session_start();

    if (!isset($_SESSION['aTablero'])) {
        $_SESSION['aTablero'] = crearTablero();
        $_SESSION['tableroVisible'] = crearTablero();
        $_SESSION['aTablero'] = generarMinas($_SESSION['aTablero']);
    }

    if (isset($_GET['fila'])) {
        $fila = $_GET['fila'];
        $columna = $_GET['columna'];    
        clicCasilla($fila,$columna);
    }

    jugada();

    function crearTablero() {
        $tablero = array();
        for ($i = 0; $i < TAM_FILA; $i++) { 
            for ($j = 0; $j < TAM_COLUMNA; $j++) {
                $tablero[$i][$j] = 0;
            }
        }
        return $tablero;
    }

    function generarMinas($tablero) {
        for ($i = 0; $i < NUM_MINAS; $i++) {
            // Genero aleatoriamente las minas
            do {
                $fila = rand(0, TAM_FILA - 1);
                $columna = rand(0, TAM_COLUMNA - 1);
            } while ($tablero[$fila][$columna] == 9);
            $tablero[$fila][$columna] = 9;
            // Incremento los números de alrededor de la mina en función de cuántas minas hayan a su alrededor
            for ($x = max($fila - 1, 0); $x <= min($fila + 1, TAM_FILA - 1); $x++) {
                for ($y = max($columna - 1, 0); $y <= min($columna + 1, TAM_COLUMNA - 1); $y++) {
                    if ($tablero[$x][$y] != 9) {
                        $tablero[$x][$y]++;
                    }
                }
            }
        }
        return $tablero;
    }

    function mostrarTablero($tablero) {
        echo "<table>";
        for ($i = 0; $i < TAM_FILA; $i++) { 
            echo "<tr>";
            for ($j = 0; $j < TAM_COLUMNA; $j++) {
                echo "<td><button>" . $tablero[$i][$j] . "</button></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    function jugada() {
        echo "<table>";
        for ($i = 0; $i < TAM_FILA; $i++) {
            echo "<tr>";
            for ($j = 0; $j < TAM_COLUMNA; $j++) {
                if ($_SESSION['tableroVisible'][$i][$j] == 0) {
                    echo "<td><a href='buscaminas.php?fila=$i&columna=$j'><button></button></a></td>";
                } else {
                    echo "<td>".$_SESSION['aTablero'][$i][$j]."</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    function ganador() {
        $contador = 0;
        for ($i = 0; $i < TAM_FILA; $i++) {
            for ($j = 0; $j < TAM_COLUMNA; $j++) {
                if ($_SESSION['tableroVisible'][$i][$j] == 1) {
                    $contador++;
                }
            }
        }
        return ($contador == NUM_CASILLAS - NUM_MINAS ? true : false);
    }

    /**
     * Función que implementa la funcionalidad del juego.
     * Se pulsa sobre un enlace, se envían por la url la fila y la columna
     * LLamada rcursiva para ir destapando casillas.
     * 
     * @param $f, $c
     * @return 0 pierde, 1 gana
     */
    function clicCasilla($f,$c) {
        // Si la casilla está oculta
        if ($_SESSION['tableroVisible'][$f][$c] == 0) {
            // Destapo casilla
            $_SESSION['tableroVisible'][$f][$c] = 1;
            
            if ($_SESSION['aTablero'][$f][$c] == 9) {
                header("location: perder.html"); // has perdido
            } else {
                // Compruebo ganador
                if (ganador()) {
                    header("location: ganar.html"); // has ganado
                } else {
                    if ($_SESSION['aTablero'][$f][$c] == 0) {
                        for ($x = max($f - 1, 0); $x <= min($f + 1, TAM_FILA - 1); $x++) {
                            for ($y = max($c - 1, 0); $y <= min($c + 1, TAM_COLUMNA - 1); $y++) {
                                clicCasilla($x, $y);
                            }
                        }
                    }
                }
            }
        }
    }

    echo "<br><a href='cierra_sesion.php'>Nuevo Juego</a><br><br>";
?>
<style>
    table td {
    height: 50px;
    width: 50px;
    text-align: center;
}

table td button{
    height: 50px;
    width: 50px;
    background-color: blueviolet;
}
</style>