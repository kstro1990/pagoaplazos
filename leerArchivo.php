<?php
$nombre_archivo = $_FILES['archivo']['name'];
$tipo_archivo = $_FILES['archivo']['type'];
$tamano_archivo = $_FILES['archivo']['size'];
$ubicacion = 'archivos_csv/' . $nombre_archivo;
$fila = 0;
$ranges = new stdClass();
$ranges = [];
$credits = new stdClass();
$credits = [];
$includeBines = [];
$includCredits = [];
$include = [];

if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ubicacion)) {
    //Procesar archivo CSV y mostrar datos en la tabla
    //var_dump($_FILES);
    $file = fopen("$ubicacion", 'r');

    while (($datos = fgetcsv($file, 1000, ';')) !== false) {
        $numero = count($datos);
        // Saltar la primera línea
        if ($fila == 0) {
            $fila++;
            continue;
        }

        // incluye la lista de bines
        $includeBines = [
            'bin' => $datos[3],
            'start' => $datos[4],
            'end' => $datos[5],
        ];

        //separa los tipos de creditos
        $todos = explode(',', $datos[2]);

        foreach ($todos as $valor) {
            //el valor de el tipo de credito lo combierte en dos digitos
            $numero_pad = str_pad($valor, 2, '0', STR_PAD_LEFT);

            $includCredits = [
                'description' => 'PLAN 0% ' . $valor . ' CUOTAS',
                'code' => "$numero_pad" . 'BCR',
                'installment' => $valor,
                'terminalNumber' => $datos[0],
                'merchantCode' => $datos[1],
            ];
            //agrega los cretios al objeto
            array_push($credits, $includCredits);
        }
        array_push($ranges, $includeBines);
    }
    fclose($file);
    $array = [];
    $include[] = [
        'ranges' => $ranges,
        'credits' => $credits,
    ];
    $array[] = $include;

    $denco = json_encode($array);
    echo $denco;
} else {
    echo 'Error al cargar el archivo';
}

?>
