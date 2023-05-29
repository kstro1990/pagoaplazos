<?php
$nombre_archivo = $_FILES['archivo']['name'];
$tamano_archivo = $_FILES['archivo']['size'];
$ubicacion = 'archivos_csv/' . $nombre_archivo;
$ranges = [];
$credits = [];

if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ubicacion)) {
    // Procesar archivo CSV y mostrar datos en la tabla
    $file = fopen($ubicacion, 'r');

    // Ignorar la primera línea
    fgetcsv($file, 1000, ';');

    while (($datos = fgetcsv($file, 1000, ';')) !== false) {
        $todos = explode(',', $datos[2]);

        foreach ($todos as $valor) {
            // agrega un cero a la izquierda
            $numero_pad = str_pad($valor, 2, '0', STR_PAD_LEFT);
            // toma los dos primeros digitos
            $cuotas_int = substr(strval($numero_pad), 0, 2);
            

            // Verificar si el crédito ya existe en el arreglo
            $existingCredit = null;
            foreach ($credits as $credit) {
                if ($credit['code'] === $valor) {
                    $existingCredit = $credit;
                    break;
                }
            }

            // Si el crédito no existe, agregarlo al arreglo
            if (!$existingCredit) {
                $credit = [
                    'description' => 'PLAN 0% ' . $valor . ' CUOTAS',
                    'code' => $valor,
                    'installment' => $cuotas_int,
                    'terminalNumber' => $datos[0],
                    'merchantCode' => $datos[1],
                ];

                $credits[] = $credit;
            }
        }

        $range = [
            'bin' => $datos[3],
            'start' => $datos[4],
            'end' => $datos[5],
        ];

        $ranges[] = $range;
    }

    fclose($file);

    $include = [
        'ranges' => $ranges,
        'credits' => $credits,
    ];

    $result = [
        'include' => [$include],
    ];

    $json_data = json_encode($result, JSON_PRETTY_PRINT);
    echo $json_data;
} else {
    echo 'Error al cargar el archivo';
}
?>