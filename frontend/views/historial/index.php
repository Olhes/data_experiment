<style>
    .historial
     {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        padding: 24px 20px 24px 20px;
        margin: 0;
        min-width: 340px;
        max-width: 450px;
        width: 100%;    
    }
</style>

<div class="historial">
    <h1>Historial Médico</h1>
    <?php 
    if (empty($historial)) {
        echo "<p>No hay registros en el historial médico.</p>";
    }
    else {
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>Fecha</th><th>Descripción</th><th>Doctor</th></tr></thead>";
        echo "<tbody>";
        foreach ($historial as $record) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($record['fecha']) . "</td>";
            echo "<td>" . htmlspecialchars($record['descripcion']) . "</td>";
            echo "<td>" . htmlspecialchars($record['doctor']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
    ?>
</div>