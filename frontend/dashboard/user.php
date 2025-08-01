<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas Médicas Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f9f9;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* 
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        } */

        .titlebar {
            margin: 30px 0 0 0;
        }

        .dash-content {
            gap: 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 0 20px;
        }

        .content {
            display: flex;
            width: 100%;
            gap: 20px;
            flex-direction: column;
            /* padding: 20px; */
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 1200px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card {
            display: inline-block;
            margin: 1%;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 10px 0;
            color: #333;
        }

        .card p {
            color: #666;
        }

        .card button {
            background-color: rgb(4, 83, 168);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .card button:hover {
            background-color: #45a049;
        }


        footer {
            text-align: center;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- <header>
        <h1>Citas Médicas Dashboard</h1>
    </header> -->
    <div class="dash-content">
        <div class="dash-sidebar">
            <div class="container">
                <div class="card">
                    <h3>Mis Citas</h3>
                    <p>Consulta tus citas programadas.</p>
                    <button>Ver Citas</button>
                </div>
                <div class="card">
                    <h3>Agendar Cita</h3>
                    <p>Programa una nueva cita médica.</p>
                    <button>Agendar</button>
                </div>
                <div class="card">
                    <h3>Historial Médico</h3>
                    <p>Revisa tu historial médico.</p>
                    <button>Ver Historial</button>
                </div>
            </div>

        </div>
        <div class="content">
            <h1 class="titlebar" >Dashboard</h1>
            <?php include '../views/calendar/index.php'; ?>
            <?php include '../views/historial/index.php'; ?>
        </div>
    </div>
    <!-- <footer>
        <p>&copy; 2025 Citas Médicas. Todos los derechos reservados.</p>
    </footer> -->
</body>

</html>