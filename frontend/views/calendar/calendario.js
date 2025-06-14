document.addEventListener('DOMContentLoaded', () => {
    const calendarBody = document.getElementById('calendar-body');
    const monthYearEl = document.getElementById('current-month-year');
    const sidebarDateEl = document.getElementById('sidebar-date');
    const appointmentsEl = document.getElementById('appointments');
    const newAppointmentBtn = document.getElementById('sidebar-new-btn');
    const prevMonthBtn = document.getElementById('prev-month-btn');
    const nextMonthBtn = document.getElementById('next-month-btn');

    let currentYear = window.CALENDAR_INITIAL_DATA.year;
    let currentMonth = window.CALENDAR_INITIAL_DATA.month;
    let selectedDateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(window.CALENDAR_INITIAL_DATA.day).padStart(2, '0')}`;
    let monthlyAppointments = {};

    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    async function renderCalendar(year, month) {
        const response = await fetch(`../views/calendar/getAppointments.php?year=${year}&month=${month}`);
        monthlyAppointments = await response.json();

        monthYearEl.textContent = `${monthNames[month - 1]} ${year}`;

        const firstDay = new Date(year, month - 1, 1).getDay();
        const daysInMonth = new Date(year, month, 0).getDate();

        calendarBody.innerHTML = '';

        let date = 1;
        for (let i = 0; i < 6; i++) {
            const row = document.createElement('tr');
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    row.appendChild(document.createElement('td'));
                } else if (date > daysInMonth) {
                    break;
                } else {
                    const cell = document.createElement('td');
                    const fullDateStr = `${year}-${String(month).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    cell.dataset.date = fullDateStr;
                    cell.classList.add('clickable');
                    cell.innerHTML = `<span>${date}</span>`;

                    if (monthlyAppointments[fullDateStr]) {
                        monthlyAppointments[fullDateStr].forEach(event => {
                            const eventBar = document.createElement('div');
                            eventBar.className = 'event-bar';
                            eventBar.style.backgroundColor = event.color;
                            eventBar.textContent = event.title;
                            eventBar.title = `${event.start.slice(11, 16)} – ${event.title}`;
                            cell.appendChild(eventBar);
                        });
                    }

                    row.appendChild(cell);
                    date++;
                }
            }
            calendarBody.appendChild(row);
            if (date > daysInMonth) break;
        }
        
        updateSidebar(selectedDateStr);
    }

    function updateSidebar(dateStr) {
        document.querySelectorAll('#mi-calendario td.selected').forEach(td => td.classList.remove('selected'));
        const cell = document.querySelector(`#mi-calendario td[data-date='${dateStr}']`);
        if (cell) {
            cell.classList.add('selected');
        }

        selectedDateStr = dateStr;
        sidebarDateEl.textContent = `Citas del ${dateStr}`;
        newAppointmentBtn.href = `#`;

        const events = monthlyAppointments[dateStr] || [];
        if (!events.length) {
            appointmentsEl.innerHTML = '<p>No hay citas para este día.</p>';
        } else {
            appointmentsEl.innerHTML = events.map(e => `
                <div class="cita">
                    <div><strong>${e.start.slice(11, 16)}</strong> ${e.title}</div>
                    <div class="meta">
                        <span>Paciente: ${e.nompa}</span>
                        <span>Médico: ${e.doctor}</span>
                        <span>Lab: ${e.laboratory}</span>
                        <span>S/.${e.monto}</span>
                    </div>
                </div>
            `).join('');
        }
    }

    prevMonthBtn.addEventListener('click', (e) => {
        e.preventDefault();
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        renderCalendar(currentYear, currentMonth);
    });

    nextMonthBtn.addEventListener('click', (e) => {
        e.preventDefault();
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        renderCalendar(currentYear, currentMonth);
    });

    calendarBody.addEventListener('click', (e) => {
        const cell = e.target.closest('td.clickable');
        if (cell) {
            updateSidebar(cell.dataset.date);
        }
    });

    newAppointmentBtn.addEventListener('click', (e) => {
        e.preventDefault();
        alert(`Funcionalidad para crear nueva cita en la fecha: ${selectedDateStr}`);
    });


    renderCalendar(currentYear, currentMonth);
});