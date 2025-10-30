<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>CSRF Test</h1>
    
    <form id="testForm" action="{{ route('test.csrf') }}" method="POST">
        @csrf
        <button type="submit">Test With CSRF</button>
    </form>
    
    <br>
    
    <button onclick="testWithoutCSRF()">Test Without CSRF (Should Fail)</button>
    
    <div id="result"></div>
    
    <script>
        function testWithoutCSRF() {
            fetch('/test-csrf-submit', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('result').innerHTML = 
                    '<p style="color: red;">ERROR: CSRF protection not working!</p>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    '<p style="color: green;">✓ CSRF protection is working! (419 error expected)</p>';
            });
        }
        
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('result').innerHTML = 
                    '<p style="color: green;">✓ ' + data.message + '</p>';
            });
        });
    </script>
</body>
</html>