/**
 * Advanced Page Tracking Script
 * Captures detailed visitor analytics including screen resolution and time on page
 */

class AdvancedTracker {
    constructor() {
        this.visitId = null;
        this.startTime = Date.now();
        this.lastActiveTime = Date.now();
        this.isActive = true;
        this.heartbeatInterval = null;

        this.init();
    }

    init() {
        // Capture initial page data
        this.capturePageData();

        // Setup activity tracking
        this.setupActivityTracking();

        // Setup page unload tracking
        this.setupUnloadTracking();

        // Start heartbeat
        this.startHeartbeat();
    }

    capturePageData() {
        // Get screen information
        const screenData = {
            screen_width: screen.width,
            screen_height: screen.height,
            viewport_width: window.innerWidth,
            viewport_height: window.innerHeight,
            color_depth: screen.colorDepth,
            pixel_ratio: window.devicePixelRatio,
        };

        // Get additional browser info
        const browserData = {
            language: navigator.language,
            languages: navigator.languages ? navigator.languages.join(",") : "",
            timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
            online: navigator.onLine,
            cookies_enabled: navigator.cookieEnabled,
        };

        // Send initial tracking data
        this.sendTrackingData({
            action: "page_view",
            ...screenData,
            ...browserData,
        });
    }

    setupActivityTracking() {
        // Track user activity
        const activityEvents = [
            "mousedown",
            "mousemove",
            "keypress",
            "scroll",
            "touchstart",
        ];

        activityEvents.forEach((event) => {
            document.addEventListener(
                event,
                () => {
                    this.lastActiveTime = Date.now();
                    this.isActive = true;
                },
                { passive: true }
            );
        });

        // Track visibility changes
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                this.isActive = false;
                this.sendTimeUpdate();
            } else {
                this.isActive = true;
                this.lastActiveTime = Date.now();
            }
        });
    }

    setupUnloadTracking() {
        // Track when user leaves the page
        window.addEventListener("beforeunload", () => {
            this.sendTimeUpdate();
        });

        // Fallback for modern browsers
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                this.sendTimeUpdate();
            }
        });
    }

    startHeartbeat() {
        // Send periodic updates every 30 seconds
        this.heartbeatInterval = setInterval(() => {
            if (this.isActive && !document.hidden) {
                this.sendTimeUpdate();
            }
        }, 30000);
    }

    sendTimeUpdate() {
        const timeOnPage = Math.floor((Date.now() - this.startTime) / 1000);

        if (timeOnPage > 5) {
            // Only send if user stayed more than 5 seconds
            this.sendTrackingData({
                action: "time_update",
                time_on_page: timeOnPage,
                visit_id: this.visitId,
            });
        }
    }

    sendTrackingData(data) {
        // Get CSRF token
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (!csrfToken) {
            console.warn("CSRF token not found");
            return;
        }

        const payload = {
            ...data,
            url: window.location.href,
            referrer: document.referrer,
            timestamp: Date.now(),
            _token: csrfToken,
        };

        // Use sendBeacon if available (better for page unload)
        if (navigator.sendBeacon && data.action === "time_update") {
            const formData = new FormData();
            for (const [key, value] of Object.entries(payload)) {
                formData.append(key, value);
            }

            navigator.sendBeacon("/track-advanced", formData);
        } else {
            // Fallback to fetch
            fetch("/track-advanced", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(payload),
                keepalive: true,
            })
                .then((response) => {
                    if (response.ok && data.action === "page_view") {
                        return response.json();
                    }
                })
                .then((result) => {
                    if (result && result.visit_id) {
                        this.visitId = result.visit_id;
                    }
                })
                .catch((error) => {
                    console.error("Tracking error:", error);
                });
        }
    }

    // Public method to track custom events
    trackEvent(eventName, eventData = {}) {
        this.sendTrackingData({
            action: "custom_event",
            event_name: eventName,
            event_data: eventData,
            visit_id: this.visitId,
        });
    }

    // Public method to track conversions
    trackConversion(conversionType, value = null) {
        this.sendTrackingData({
            action: "conversion",
            conversion_type: conversionType,
            conversion_value: value,
            visit_id: this.visitId,
        });
    }
}

// Auto-initialize if not in admin area
if (
    typeof window !== "undefined" &&
    !window.location.pathname.includes("/admin/")
) {
    window.tracker = new AdvancedTracker();

    // Make tracking functions globally available
    window.trackEvent = (eventName, eventData) =>
        window.tracker.trackEvent(eventName, eventData);
    window.trackConversion = (type, value) =>
        window.tracker.trackConversion(type, value);
}
