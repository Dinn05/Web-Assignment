// Global variables
let currentScanResult = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize QR Scanner
    const html5QrCode = new Html5Qrcode("qr-reader");
    const qrScannerConfig = { 
        fps: 10, 
        qrbox: { width: 250, height: 250 } 
    };

    // Start scanner
    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            const cameraId = devices[0].id; // Use first available camera
            html5QrCode.start(
                cameraId,
                qrScannerConfig,
                onScanSuccess,
                onScanError
            ).catch(err => {
                console.error("Camera start error:", err);
                showError("Could not access camera. Please enable camera permissions.");
            });
        }
    }).catch(err => {
        console.error("Camera access error:", err);
        showError("Could not access camera. Please enable camera permissions.");
    });

    // Handle successful scan
    function onScanSuccess(decodedText) {
        // Stop scanner after first successful scan
        html5QrCode.stop().then(() => {
            currentScanResult = decodedText;
            document.getElementById('loadingSpinner').style.display = 'block';
            verifyLocationAndProceed();
        }).catch(err => {
            console.error("Scanner stop error:", err);
        });
    }

    // Handle scan errors
    function onScanError(errorMessage) {
        // Don't show errors if we've already successfully scanned
        if (!currentScanResult) {
            console.warn("Scan error:", errorMessage);
        }
    }
});

// Verify geolocation and proceed to attendance form
function verifyLocationAndProceed() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const studentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                processScannedData(studentLocation);
            },
            error => {
                console.error("Geolocation error:", error);
                showError("Location access is required for attendance verification.");
                document.getElementById('loadingSpinner').style.display = 'none';
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        showError("Geolocation is not supported by your browser.");
        document.getElementById('loadingSpinner').style.display = 'none';
    }
}

// Process scanned data and redirect
function processScannedData(studentLocation) {
    // Extract data from QR code (format: PETAKOM_SLOT:EVENT_ID:SLOT_ID)
    const qrData = currentScanResult.split(':');
    
    if (qrData.length === 3 && qrData[0] === "PETAKOM_SLOT") {
        const eventId = qrData[1];
        const slotId = qrData[2];
        
        // Redirect to attendance form with parameters
        window.location.href = `attendance_form.html?event=${eventId}&slot=${slotId}&lat=${studentLocation.lat}&lng=${studentLocation.lng}`;
    } else {
        showError("Invalid QR code. Please scan a valid Petakom event QR.");
        document.getElementById('loadingSpinner').style.display = 'none';
    }
}

// Show error message
function showError(message) {
    const errorElement = document.getElementById('errorMessage');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}