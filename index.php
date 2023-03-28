<?php

$fila = 0;
$ranges = new stdClass();
$ranges = [];
$credits = new stdClass();
$credits = [];
$includeBines = [];
$includCredits = [];
$include = new stdClass();

if (($gestor = fopen('cdm_bcr.csv', 'r')) !== false) {
    while (($datos = fgetcsv($gestor, 1000, ';')) !== false) {
        // Saltar la primera línea
        if ($fila == 0) {
            $fila++;
            continue;
        }
        $numero = count($datos);
        echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
        //$fila++;
        echo $datos[2] . "<- creditos <br />\n";
        echo $datos[3] . "<- bin <br />\n";
        echo $datos[4] . "<- Rango_Inicial <br />\n";
        echo $datos[5] . "<- Rango_Final <br />\n";

        $includeBines = [
            'bin' => $datos[3],
            'start' => $datos[4],
            'end' => $datos[5],
        ];

        $todos = explode(',', $datos[2]);
        // crear un switch case
        foreach ($todos as $valor) {
            //echo $valor . '';
            echo '</br>';
            $numero_f = str_pad($valor, 2, '0', STR_PAD_LEFT);
            //echo $numero_f;

            $includCredits = [
                'description' => 'PLAN 0 BCR ' . $valor . 'C BCR',
                'code' => "$numero_f" . 'BCR',
                'installment' => $valor,
                'terminalNumber' => $datos[0],
                'merchantCode' => $datos[1],
            ];
            array_push($credits, $includCredits);
        }

        echo '</br>';

        array_push($ranges, $includeBines);

        for ($c = 0; $c < $numero; $c++) {
            echo $datos[$c] . "<br />\n";
        }
    }
    fclose($gestor);
}

$array = [];
$array = [
    'ranges' => $ranges,
    'credits' => $credits,
];

$total = new stdClass();
$total->include = $array;

$denco = json_encode($total);
echo $denco;

?>
