<script>
    (function() {
        const checkVersion = async () => {
            try {
                const response = await fetch('/version.json', {
                    cache: 'no-cache'
                });
                const data = await response.json();
                const currentVersion = localStorage.getItem('app_version');

                if (currentVersion && currentVersion !== data.version) {
                    localStorage.setItem('app_version', data.version);
                    location.reload(true); // Force reload to get the new version
                } else if (!currentVersion) {
                    localStorage.setItem('app_version', data.version);
                }
            } catch (e) {
                console.error('Version check failed', e);
            }
        };

        // Check version on page load
        checkVersion();

        // Optional: Check periodically (every 5 minutes)
        setInterval(checkVersion, 5 * 60 * 1000);
    })();
</script>
