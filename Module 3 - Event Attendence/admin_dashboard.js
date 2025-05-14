// Global chart references
let attendanceChart, participationChart;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    loadFilters();
    loadDashboardData();
    
    // Form submission handler
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadDashboardData();
    });
    
    // Export handlers
    document.querySelectorAll('.export-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            exportReport(this.dataset.type);
        });
    });
});

// Load filter options
function loadFilters() {
    fetch('api/get_events.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('eventFilter');
            data.forEach(event => {
                const option = document.createElement('option');
                option.value = event.event_id;
                option.textContent = event.event_name;
                select.appendChild(option);
            });
        });
}

// Load all dashboard data
function loadDashboardData() {
    const filters = {
        event_id: document.getElementById('eventFilter').value,
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value
    };
    
    // Load summary stats
    fetch('api/get_stats.php?' + new URLSearchParams(filters))
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalEvents').textContent = data.total_events;
            document.getElementById('totalAttendance').textContent = data.total_attendance;
            document.getElementById('avgRate').textContent = data.avg_rate + '%';
        });
    
    // Load chart data
    fetch('api/get_chart_data.php?' + new URLSearchParams(filters))
        .then(response => response.json())
        .then(data => {
            renderCharts(data);
        });
    
    // Load table data
    fetch('api/get_records.php?' + new URLSearchParams(filters))
        .then(response => response.json())
        .then(data => {
            renderRecordsTable(data);
        });
}

// Render charts
function renderCharts(data) {
    const ctx1 = document.getElementById('attendanceChart').getContext('2d');
    const ctx2 = document.getElementById('participationChart').getContext('2d');
    
    // Destroy existing charts if they exist
    if (attendanceChart) attendanceChart.destroy();
    if (participationChart) participationChart.destroy();
    
    // Attendance by Event (Bar Chart)
    attendanceChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: data.events.map(e => e.event_name),
            datasets: [{
                label: 'Attendance Count',
                data: data.events.map(e => e.attendance_count),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} attendees (${data.events[context.dataIndex].attendance_rate}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Participation Rate (Doughnut Chart)
    participationChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Attended', 'Not Attended'],
            datasets: [{
                data: [data.participation.attended, data.participation.not_attended],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = Math.round((value / total) * 100);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        return Math.round((value / total) * 100) + '%';
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

// Render records table
function renderRecordsTable(data) {
    const tbody = document.querySelector('#recordsTable tbody');
    tbody.innerHTML = '';
    
    data.forEach(record => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${record.event_name}</td>
            <td>${record.student_id}</td>
            <td>${new Date(record.check_in_time).toLocaleString()}</td>
            <td>${record.latitude.toFixed(4)}, ${record.longitude.toFixed(4)}</td>
            <td><span class="badge bg-${record.is_verified ? 'success' : 'warning'}">
                ${record.is_verified ? 'Verified' : 'Pending'}
            </span></td>
        `;
        tbody.appendChild(tr);
    });
}

// Export reports
function exportReport(type) {
    const filters = {
        event_id: document.getElementById('eventFilter').value,
        date_from: document.getElementById('dateFrom').value,
        date_to: document.getElementById('dateTo').value
    };
    
    window.open(`api/export_report.php?type=${type}&` + new URLSearchParams(filters), '_blank');
}