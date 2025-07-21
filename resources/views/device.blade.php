<!DOCTYPE html>
<html>
<head>
    <title>Device Resolution</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* full viewport height */
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        #infoBox {
            text-align: center;
            padding: 20px 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div id="infoBox">
        <h2>Device Resolution</h2>
        <p id="screenWidth"></p>
        <p id="screenHeight"></p>
        <p id="viewportWidth"></p>
    </div>

    <script>
        function displayScreenInfo() {
            // Get screen resolution
            var screenWidth = screen.width;
            var screenHeight = screen.height;
            // Get viewport width
            var viewportWidth = window.innerWidth;

            document.getElementById('screenWidth').textContent = "Screen Width: " + screenWidth + " pixels";
            document.getElementById('screenHeight').textContent = "Screen Height: " + screenHeight + " pixels";
            document.getElementById('viewportWidth').textContent = "Viewport Width: " + viewportWidth + " pixels";
        }

        // Display on load
        displayScreenInfo();

        // Update when resized or rotated
        window.addEventListener('resize', displayScreenInfo);
    </script>
</body>
</html>
