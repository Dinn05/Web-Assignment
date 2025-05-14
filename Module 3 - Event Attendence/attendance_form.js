document.addEventListener('DOMContentLoaded', function() {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('event');
    const slotId = urlParams.get('slot');
    const latitude = urlParams.get('lat');
    const longitude = urlParams.get('lng');

    // Set hidden field values
    document.getElementById('eventId').value = eventId;
    document.getElementById('slotId').value = slotId;
    document.getElementById('latitude').value = latitude;
    document.getElementById('longitude').value = longitude;

    // Fetch event details from API
    fetchEventDetails(eventId);

    // Form submission handler
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitAttendance();
    });
});

// Fetch event details from backend
function fetchEventDetails(eventId) {
    fetch(`api/get_event_details.php?event_id=${eventId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('eventName').textContent = data.event_name;
                document.getElementById('eventDetails').textContent = 
                    `${data.event_date} • ${data.event_time}`;
                document.getElementById('eventLocation').textContent = data.event_location;
            } else {
                alert('Error loading event details');
            }
        });
}

// Submit attendance to backend
function submitAttendance() {
    const formData = {
        event_id: document.getElementById('eventId').value,
        slot_id: document.getElementById('slotId').value,
        student_id: document.getElementById('studentId').value,
        password: document.getElementById('password').value,
        latitude: document.getElementById('latitude').value,
        longitude: document.getElementById('longitude').value
    };

    fetch('api/submit_attendance.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Attendance recorded successfully!');
            window.location.href = 'student_dashboard.html';
        } else {
            alert(`Error: ${data.message}`);
        }
    });
}