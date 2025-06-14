<?php
$initialYear  = (int)date('Y');
$initialMonth = (int)date('m');
$initialDay   = (int)date('d');
?>

<style>
    .calendar-context {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 32px;
        margin: 0 auto;
        width: 100%;
    }

    .calendar-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        padding: 24px 20px 24px 20px;
        margin: 0;
        width: 100%;
    }

    .sidebar-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        padding: 24px 20px 24px 20px;
        margin: 0;
        width: 30%;
    }

    .calendar-card {
        flex: 1 1 100%;
    }

    .sidebar-card {
        flex: 0 0 30%;
        max-width: 340px;
        min-width: 220px;
        overflow-y: auto;
        position: relative;
    }

    .calendar-nav {
        text-align: center;
        margin-bottom: 12px;
        font-family: sans-serif;
    }

    .calendar-nav .nav-btn {
        text-decoration: none;
        color: #007bff;
        margin: 0 16px;
        font-weight: 600;
    }

    .calendar-nav .current-month {
        font-size: 1.1em;
        font-weight: 700;
    }

    #mi-calendario {
        border-collapse: collapse;
        width: 100%;
        font-family: sans-serif;
    }

    #mi-calendario th,
    #mi-calendario td {
        width: calc(100%/7);
        height: 60px;
        overflow: hidden;
        vertical-align: top;
        position: relative;
        padding: 4px;
        box-sizing: border-box;
        background: #fff;
        transition: background 0.2s;
    }

    #mi-calendario th {
        background: #f7f7f7;
        font-weight: 600;
        color: #333;
        padding: 8px 4px;
    }

    #mi-calendario td.clickable:hover {
        background: #eef;
        cursor: pointer;
    }

    #mi-calendario td.selected {
        background: #e0f3ff;
        border: 2px solid #007bff;
        z-index: 1;
    }

    .event-bar {
        display: block;
        height: 18px;
        margin: 2px 0;
        border-radius: 4px;
        color: #fff;
        font-size: 0.75em;
        line-height: 18px;
        padding: 0 4px;
        /* white-space: nowrap; */
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .sidebar-card h3 {
        margin-top: 0;
        font-size: 1.2em;
        color: #222;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }

    #appointments .cita {
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
    }

    #appointments .cita:last-child {
        border-bottom: none;
    }

    #appointments .cita>div {
        margin: 4px 0;
    }

    #appointments .cita .meta {
        font-size: 0.85em;
        color: #555;
    }

    #appointments .cita .meta span {
        margin-right: 8px;
    }

    .sidebar-new-btn {
        display: inline-block;
        margin: 8px 0;
        padding: 6px 12px;
        background: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .sidebar-new-btn:hover {
        background: #0056b3;
    }

    /* TODO: Responsive */
    @media (max-width: 900px) {
        .container {
            width: 0px;
            visibility: hidden;
        }
        .calendar-context {
            flex-direction: column;
            align-items: stretch;
            gap: 24px;
        }

        .calendar-card {
            width: 90%;
        } 
    
        .sidebar-card {
            max-width: 100%;
            min-width: 0;
        }

        .calendar-card {
            max-width: 100%;
            min-width: 0;
        }
    }
</style>

<div class="calendar-context">
    <div class="calendar-card">
        <div class="calendar-nav">
            <a href="#" id="prev-month-btn" class="nav-btn">‹ Anterior</a>
            <span id="current-month-year" class="current-month"></span>
            <a href="#" id="next-month-btn" class="nav-btn">Siguiente ›</a>
        </div>
        <table id="mi-calendario">
            <thead>
                <tr>
                    <th>Dom</th>
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mié</th>
                    <th>Jue</th>
                    <th>Vie</th>
                    <th>Sáb</th>
                </tr>
            </thead>
            <tbody id="calendar-body"></tbody>
        </table>
    </div>
    <div class="sidebar-card" id="sidebar">
        <h3 id="sidebar-date"></h3>
        <a id="sidebar-new-btn" href="#" class="sidebar-new-btn">+ Nueva cita</a>
        <div id="appointments">
            <p>Selecciona un día para ver las citas.</p>
        </div>
    </div>
</div>
<script>
    window.CALENDAR_INITIAL_DATA = {
        year: <?= $initialYear ?>,
        month: <?= $initialMonth ?>,
        day: <?= $initialDay ?>
    };
</script>
<script src="../views/calendar/calendario.js"></script>