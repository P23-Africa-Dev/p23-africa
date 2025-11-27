<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Live Form Capture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }

        .test-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .status {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            display: none;
        }

        .debug {
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: #333;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            max-width: 300px;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <h1>Live Form Capture Test</h1>
    <p>This page tests if the live form capture is working. Start typing in the form below and check your admin
        dashboard for real-time updates.</p>

    <x-live-form-capture formId="testForm" :saveUrl="route('consultation.save-draft')" :saveInterval="2000" :enableLogging="true">

        <form id="testForm" class="test-form">
            <h3>Test Consultation Form</h3>

            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" id="full_name" placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="company_brand_name">Company:</label>
                <input type="text" name="company_brand_name" id="company_brand_name"
                    placeholder="Enter company name">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" name="phone" id="phone" placeholder="Enter phone number">
            </div>

            <div class="form-group">
                <label for="industry">Industry:</label>
                <select name="industry" id="industry">
                    <option value="">Select industry</option>
                    <option value="technology">Technology</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="finance">Finance</option>
                    <option value="education">Education</option>
                    <option value="retail">Retail</option>
                </select>
            </div>

            <div class="form-group">
                <label for="business_description">Business Description:</label>
                <textarea name="business_description" id="business_description" rows="4" placeholder="Describe your business..."></textarea>
            </div>

            <button type="button" onclick="simulateSubmission()">Test Submit</button>
        </form>

    </x-live-form-capture>

    <div id="status" class="status">
        <span id="status-text">Initializing...</span>
    </div>

    <div id="debug" class="debug">
        <strong>Debug Console:</strong><br>
        <div id="debug-content">Waiting for activity...</div>
    </div>

    <script>
        let debugContent = document.getElementById('debug-content');
        let statusDiv = document.getElementById('status');
        let statusText = document.getElementById('status-text');

        function addDebugMessage(message) {
            const timestamp = new Date().toLocaleTimeString();
            debugContent.innerHTML += `<br>[${timestamp}] ${message}`;
            debugContent.scrollTop = debugContent.scrollHeight;
        }

        function showStatus(message, type = 'success') {
            statusText.textContent = message;
            statusDiv.style.display = 'block';
            statusDiv.style.background = type === 'error' ? '#dc3545' : '#28a745';

            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 3000);
        }

        // Monitor form activity
        document.addEventListener('DOMContentLoaded', function() {
            addDebugMessage('Page loaded, live capture should initialize');
            showStatus('Live capture initialized');

            // Monitor all form inputs
            const form = document.getElementById('testForm');
            const inputs = form.querySelectorAll('input, textarea, select');

            inputs.forEach(input => {
                input.addEventListener('input', function(e) {
                    addDebugMessage(`Input on ${e.target.name}: "${e.target.value}"`);
                    showStatus('Capturing input...');
                });

                input.addEventListener('change', function(e) {
                    addDebugMessage(`Change on ${e.target.name}: "${e.target.value}"`);
                });

                input.addEventListener('blur', function(e) {
                    addDebugMessage(`Blur on ${e.target.name} - should trigger save`);
                    showStatus('Saving draft...');
                });
            });
        });

        function simulateSubmission() {
            addDebugMessage('Simulating form submission...');
            showStatus('Testing submission...');

            // This would normally trigger the live capture submission
            if (typeof window.notifyLiveFormCapture === 'function') {
                window.notifyLiveFormCapture('validationPassed', {});
                addDebugMessage('Notified live capture of submission');
            } else {
                addDebugMessage('ERROR: Live capture not found!');
                showStatus('Live capture not found!', 'error');
            }
        }

        // Override console.log to show in debug panel
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            addDebugMessage(args.join(' '));
        };
    </script>
</body>

</html>
