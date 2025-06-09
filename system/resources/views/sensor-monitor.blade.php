<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-time Sensor Monitor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div style="font-family: Arial, sans-serif; text-align: center; margin-top: 50px;">
        <h1>Real-time Sensor Data</h1>
        <p>Akan diperbarui setiap beberapa detik...</p>
        <div style="background-color: #f0f0f0; padding: 20px; border-radius: 8px; display: inline-block;">
            <p><strong>Temperature:</strong> <span id="temperature">-</span> °C</p>
            <p><strong>Humidity:</strong> <span id="humidity">-</span> %</p>
            <p><strong>Last Update:</strong> <span id="timestamp">-</span></p>
        </div>
    </div>
    <script type="module">
        console.log('Attempting to listen to sensor-data channel...');
    
        window.Echo.channel('sensor-data')
            .listen('.sensor.updated', (event) => {
                // Ini adalah hal paling penting untuk diperiksa:
                console.log('Sensor data RECEIVED IN JAVASCRIPT:', event);
                console.log('Event properties:', event.temperature, event.humidity, event.timestamp);
    
                // Periksa apakah elemen-elemen ini ada dan memiliki ID yang benar
                const tempElement = document.getElementById('temperature');
                const humElement = document.getElementById('humidity');
                const timeElement = document.getElementById('timestamp');
    
                if (tempElement) {
                    tempElement.textContent = event.temperature;
                    
                } else {
                    console.error("Element with ID 'temperature' not found.");
                }
                if (humElement) {
                    humElement.textContent = event.humidity;
                } else {
                    console.error("Element with ID 'humidity' not found.");
                }
                if (timeElement) {
                    timeElement.textContent = event.timestamp;
                } else {
                    console.error("Element with ID 'timestamp' not found.");
                }
            })
            .error((error) => {
                console.error('Error listening to channel or processing event:', error);
            });
    
        console.log('Listening to "sensor-data" channel for "sensor.updated" events.');
    </script>
</body>
</html>