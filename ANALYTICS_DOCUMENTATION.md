# Advanced Analytics Dashboard - Documentation

## Overview

The Advanced Analytics Dashboard provides comprehensive visitor tracking and analytics for your website. It captures detailed information about user behavior, device types, geographical data, and engagement metrics.

## Features

### 1. Detailed Visitor Tracking

-   **Real-time Tracking**: Track visitors as they navigate your site
-   **Device Detection**: Identify mobile, tablet, and desktop users
-   **Browser Analytics**: Track browser types and versions
-   **Geographical Data**: Country and city information (with GeoIP integration)
-   **Screen Resolution**: Capture screen dimensions for better UX insights
-   **Time on Page**: Measure user engagement

### 2. Advanced Filtering Options

-   **Date Ranges**: Today, Yesterday, Last 7/30 Days, This/Last Month, Custom Range
-   **Device Types**: Filter by mobile, tablet, or desktop
-   **Geographical**: Filter by country or city
-   **Page Routes**: Filter by specific pages
-   **Browser Types**: Filter by browser
-   **Search**: Free-text search across multiple fields

### 3. Analytics & Reporting

-   **Summary Cards**: Total visits, unique visitors, pages viewed, average time on page
-   **Charts & Graphs**: Daily visit trends, device distribution
-   **Top Lists**: Most popular pages, countries, browsers
-   **Export Functionality**: Download filtered data as CSV

### 4. Real-time Dashboard

-   **Live Updates**: See new visitors in real-time
-   **Interactive Charts**: Visual representation of data
-   **Responsive Design**: Works on all devices

## Installation & Setup

### 1. Database Migration

The system uses two main tables:

-   `clicks` - Aggregate data (backward compatibility)
-   `page_visits` - Detailed individual visit records

Run the migrations:

```bash
php artisan migrate
```

### 2. Seed Sample Data (Optional)

For testing purposes, you can seed sample data:

```bash
php artisan db:seed --class=PageVisitSeeder
```

### 3. Add Tracking Script

Include the advanced tracking script in your layouts:

```html
<script src="{{ asset('js/advanced-tracking.js') }}"></script>
```

## Usage

### Accessing the Dashboard

Navigate to `/admin/clicks/report` to view the analytics dashboard.

### Using Filters

1. **Date Range**: Select from predefined ranges or choose custom dates
2. **Device Filter**: View data for specific device types
3. **Location Filter**: Filter by country
4. **Page Filter**: Focus on specific pages
5. **Search**: Search across page names, countries, cities, and IP addresses

### Exporting Data

Click the "Export CSV" button to download filtered data. The export includes all visible columns and respects current filters.

### Understanding Metrics

#### Unique Visitors

A unique visitor is someone who hasn't visited the same page from the same IP address in the last 24 hours.

#### Time on Page

Measured in seconds. The system tracks active time (when the user is interacting with the page).

#### Device Distribution

Shows the breakdown of visits by device type (desktop, mobile, tablet).

## Advanced Features

### Custom Event Tracking

Track custom events in your JavaScript:

```javascript
// Track a custom event
trackEvent("button_click", { button_id: "signup-btn", page: "homepage" });

// Track conversions
trackConversion("email_signup", 1);
```

### Real-time Updates

The dashboard updates automatically when new visitors arrive. This is powered by Laravel Echo and WebSockets.

### GeoIP Integration

To enable geographical tracking, integrate with a GeoIP service:

1. **MaxMind GeoIP2** (Recommended)
2. **ip-api.com** (Free tier available)
3. **ipinfo.io**

Update the `getCountryFromIP()` and `getCityFromIP()` methods in:

-   `ClickTracker.php`
-   `AdvancedTrackingController.php`

## API Endpoints

### POST /track-advanced

Handles detailed tracking data from the JavaScript tracking script.

**Parameters:**

-   `action`: Type of tracking (page_view, time_update, custom_event, conversion)
-   `url`: Current page URL
-   `referrer`: Referrer URL
-   `screen_width`: Screen width
-   `screen_height`: Screen height
-   `timezone`: User's timezone
-   Additional browser and device data

## Performance Considerations

### Database Optimization

-   Indexes are created on frequently queried columns
-   Old data can be archived or deleted periodically
-   Consider partitioning the `page_visits` table for high-traffic sites

### Caching

-   Analytics data can be cached for performance
-   Use Redis for real-time data caching
-   Consider pre-calculating daily/weekly summaries

### Data Retention

Implement a data retention policy:

```bash
# Delete visits older than 1 year
php artisan visits:cleanup --days=365
```

## Troubleshooting

### Common Issues

1. **No Data Appearing**

    - Check if migrations have been run
    - Verify tracking script is included
    - Check browser console for JavaScript errors

2. **Geographical Data Not Working**

    - Implement GeoIP integration
    - Check IP address detection in local development

3. **Charts Not Loading**
    - Ensure Chart.js CDN is accessible
    - Check for JavaScript errors in console

### Debug Mode

Enable debug logging in the tracking controller:

```php
Log::debug('Tracking Data', $request->all());
```

## Security Considerations

### CSRF Protection

All tracking endpoints use CSRF tokens for security.

### Rate Limiting

Consider implementing rate limiting on tracking endpoints:

```php
Route::post('/track-advanced', [AdvancedTrackingController::class, 'track'])
    ->middleware('throttle:100,1'); // 100 requests per minute
```

### Data Privacy

-   Ensure compliance with GDPR/privacy laws
-   Consider anonymizing IP addresses
-   Provide opt-out mechanisms for users

## Customization

### Adding New Metrics

1. Add columns to the `page_visits` table
2. Update the `PageVisit` model fillable array
3. Modify the tracking script to capture new data
4. Update the dashboard to display new metrics

### Custom Charts

Add new charts by:

1. Adding data queries to the controller
2. Passing data to the view
3. Creating new Chart.js instances

### Custom Filters

Add new filter options by:

1. Adding form elements to the view
2. Handling the filter in the controller
3. Updating the query builder

## Future Enhancements

### Planned Features

-   Funnel analysis
-   A/B testing support
-   Heat map integration
-   User session recording
-   Advanced segmentation
-   Automated reports
-   API for external integrations

### Performance Improvements

-   Background job processing for heavy analytics
-   Data aggregation tables
-   CDN integration for tracking script
-   WebSocket optimizations

## Support

For technical support or feature requests, please contact the development team or create an issue in the project repository.

## Changelog

### Version 1.0.0

-   Initial release with comprehensive visitor tracking
-   Advanced filtering and reporting
-   Real-time dashboard
-   Export functionality
-   Custom event tracking
