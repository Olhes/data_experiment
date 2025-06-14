<?php
header('Content-Type: application/json; charset=utf-8');
$date = $_GET['date'] ?? '';
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

$fake = [
  '2025-06-15' => [
    [
      'id'       => 1,
      'title' => 'Chequeo dental',
      'idpa'     => 1,
      'nompa' => 'Javier Manuel',
      'idodc'    => 10,
      'doctor' => 'Ramón Nuñez',
      'idlab'    => 5,
      'laboratory' => 'Cardiología',
      'start'    => '2025-06-15 09:00:00',
      'end'      => '2025-06-15 09:30:00',
      'monto'    => '30.00',
      'color' => '#CD5C5C'
    ],
    [
      'id' => 2,
      'title' => 'Consulta cardiología',
      'idpa' => 2,
      'nompa' => 'Manuel Javier',
      'idodc' => 11,
      'doctor' => 'Benito Cabrera',
      'idlab' => 4,
      'laboratory' => 'Endocrinología',
      'start' => '2025-06-15 14:30:00',
      'end' => '2025-06-15 15:00:00',
      'monto' => '50.00',
      'color' => '#FFC0CB'
    ],
  ],
];

$response = [];
foreach ($fake as $date => $events) {
    if (strpos($date, sprintf('%04d-%02d', $year, $month)) === 0) {
        $response[$date] = $events;
    }
}

echo json_encode($response);