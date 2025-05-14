document.addEventListener('DOMContentLoaded', function() {
    fetchEvents();

    document.getElementById('slotForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createSlot();
    });
});

function fetchEvents() {
    fetch('api/get_events.php')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('eventSelect');
            data.forEach(event => {
                const option = document.createElement('option');
                option.value = event.event_id;
                option.textContent = event.event_name;
                select.appendChild(option);
            });
        });
}

function createSlot() {
    const eventId = document.getElementById('eventSelect').value;
    const slotName = document.getElementById('slotName').value;

    fetch('api/create_slot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ event_id: eventId, slot_name: slotName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qrPreview').src = data.qr_code_path;
            document.getElementById('qrPreview').style.display = 'block';
            document.getElementById('qrInstructions').style.display = 'block';
        } else {
            alert('Error: ' + data.message);
        }
    });
}
