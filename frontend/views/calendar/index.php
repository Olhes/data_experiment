<!DOCTYPE html>
<html>
<head>
    <title>Calendario Simple</title>
    <style>
        table { border-collapse: collapse; width: 300px; }
        th, td { border: 1px solid #ccc; text-align: center; padding: 8px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Calendario Simple - <?php echo date('F Y'); ?></h2>
    <table>
        <tr>
            <th>Dom</th>
            <th>Lun</th>
            <th>Mar</th>
            <th>Mié</th>
            <th>Jue</th>
            <th>Vie</th>
            <th>Sáb</th>
        </tr>
        <?php
        $year = date('Y');
        $month = date('m');
        $firstDay = date('w', strtotime("$year-$month-01"));
        $daysInMonth = date('t');
        $day = 1;

        echo "<tr>";
        for ($i = 0; $i < $firstDay; $i++) {
            echo "<td></td>";
        }
        for ($i = $firstDay; $i < 7; $i++) {
            echo "<td>$day</td>";
            $day++;
        }
        echo "</tr>";

        while ($day <= $daysInMonth) {
            echo "<tr>";
            for ($i = 0; $i < 7; $i++) {
                if ($day <= $daysInMonth) {
                    echo "<td>$day</td>";
                    $day++;
                } else {
                    echo "<td></td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>