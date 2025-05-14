document.addEventListener('DOMContentLoaded', function() {
    // Get URL parameters from QR scan
    const urlParams = new URLSearchParams(window.location.search);
    const slotId = urlParams.get('slot_id');
    const eventId = urlParams.get('event_id');
    
    // Set hidden fields
    document.getElementById('slot-id').value = slotId;
    document.getElementById('event-id').value = eventId;

    // Get and display current location
    getCurrentLocation();
    
    // Fetch event details from backend
    fetchEventDetails(eventId);

    // Form submission handler
    document.getElementById('attendance-form').addEventListener('submit', function(e) {
        e.preventDefault();
        verifyAttendance();
    });
});

// Get current geolocation
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Set hidden fields
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                // Display formatted location
                document.getElementById('current-location').textContent = 
                    `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            },
            error => {
                console.error("Geolocation error:", error);
                document.getElementById('current-location').textContent = 
                    "Location unavailable";
            },
            { enableHighAccuracy: true }
        );
    } else {
        document.getElementById('current-location').textContent = 
            "Geolocation not supported";
    }
}

// Fetch event details from backend
function fetchEventDetails(eventId) {
    fetch(`api/get_event.php?event_id=${eventId}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate event details
                document.getElementById('event-title').textContent = data.event_name;
                document.getElementById('event-date-time').textContent = 
                    `${data.event_date} • ${data.event_time}`;
                document.getElementById('event-location').textContent = data.event_location;
            } else {
                showError("Failed to load event details");
            }
        })
        .catch(error => {
            console.error('Error fetching event details:', error);
            showError("Error loading event information");
        });
}

// Verify attendance with backend
function verifyAttendance() {
    const form = document.getElementById('attendance-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const errorElement = document.getElementById('error-message');
    
    // Show loading state
    submitBtn.disabled = true;
    document.getElementById('submit-text').classList.add('d-none');
    document.getElementById('spinner').style.display = 'inline-block';
    errorElement.classList.add('d-none');

    // Prepare form data
    const formData = {
        slot_id: document.getElementById('slot-id').value,
        event_id: document.getElementById('event-id').value,
        student_id: document.getElementById('student-id').value,
        password: document.getElementById('password').value,
        latitude: document.getElementById('latitude').value,
        longitude: document.getElementById('longitude').value
    };

    // Send to backend
    fetch('api/verify_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Success - redirect to confirmation
            window.location.href = `attendance_success.html?event=${formData.event_id}`;
        } else {
            showError(data.message || "Attendance verification failed");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError("Network error. Please try again.");
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        document.getElementById('submit-text').classList.remove('d-none');
        document.getElementById('spinner').style.display = 'none';
    });
}

// Display error message
function showError(message) {
    const errorElement = document.getElementById('error-message');
    errorElement.textContent = message;
    errorElement.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}