<!DOCTYPE html>
<html>

<head>
    <title>Chat API Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin: 15px 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background: #0d4036;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🧪 Chat API Test</h1>

        <div class="form-group">
            <label>Application ID:</label>
            <input type="number" id="applicationId" value="{{ Session::get('consultation_application_id', '') }}"
                placeholder="Enter application ID">
            <small>Current session application ID: {{ Session::get('consultation_application_id', 'None') }}</small>
        </div>

        <div class="form-group">
            <label>Sender Name:</label>
            <input type="text" id="senderName" value="Test User" placeholder="Enter sender name">
        </div>

        <div class="form-group">
            <label>Sender Email:</label>
            <input type="email" id="senderEmail" value="test@example.com" placeholder="Enter sender email">
        </div>

        <div class="form-group">
            <label>Message:</label>
            <textarea id="message" rows="4" placeholder="Enter your test message here...">This is a test message from the chat system.</textarea>
        </div>

        <button onclick="sendTestMessage()">Send Test Message</button>

        <div id="result"></div>

        <hr style="margin: 30px 0;">

        <h2>📊 Debug Info</h2>
        <div class="info">
            <p><strong>Current Session:</strong></p>
            <ul>
                <li>Application ID: {{ Session::get('consultation_application_id', 'Not set') }}</li>
                <li>Payment Success: {{ Session::get('payment_success', 'false') }}</li>
                <li>CSRF Token: <span id="csrfToken">{{ csrf_token() }}</span></li>
            </ul>

            <p><strong>Database Check:</strong></p>
            <ul>
                <li>Total Applications: {{ \App\Models\ConsultationApplication::count() }}</li>
                <li>Total Chat Messages: {{ \App\Models\ChatMessage::count() }}</li>
                <li>API Endpoint: <code>POST /api/consultation/chat/send-message</code></li>
            </ul>
        </div>
    </div>

    <script>
        async function sendTestMessage() {
            const resultDiv = document.getElementById('result');
            const button = event.target;

            // Get form data
            const applicationId = document.getElementById('applicationId').value;
            const senderName = document.getElementById('senderName').value;
            const senderEmail = document.getElementById('senderEmail').value;
            const message = document.getElementById('message').value;

            // Validate required fields
            if (!applicationId || !senderName || !message) {
                resultDiv.innerHTML =
                    '<div class="error">❌ Please fill in Application ID, Sender Name, and Message</div>';
                return;
            }

            // Show loading
            button.disabled = true;
            button.textContent = 'Sending...';
            resultDiv.innerHTML = '<div class="info">⏳ Sending test message...</div>';

            try {
                const response = await fetch('/api/consultation/chat/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        application_id: parseInt(applicationId),
                        sender_name: senderName,
                        sender_email: senderEmail,
                        message: message
                    })
                });

                const responseText = await response.text();
                console.log('Raw response:', responseText);

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + responseText.substring(0, 200));
                }

                if (response.ok && data.success) {
                    resultDiv.innerHTML = `
                        <div class="success">
                            ✅ <strong>Success!</strong><br>
                            Message ID: ${data.data.id}<br>
                            Sent at: ${data.data.sent_at}<br>
                            Check your email at contact@p23africa.com for the notification!
                        </div>
                    `;
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }

            } catch (error) {
                console.error('Test error:', error);
                resultDiv.innerHTML = `
                    <div class="error">
                        ❌ <strong>Error:</strong><br>
                        ${error.message}<br>
                        <small>Check the browser console for more details.</small>
                    </div>
                `;
            } finally {
                button.disabled = false;
                button.textContent = 'Send Test Message';
            }
        }
    </script>
</body>

</html>
