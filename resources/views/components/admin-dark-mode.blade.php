@push('styles')
    <style>
        /* Admin Dark Mode Styles */
        .admin-container {
            @apply bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden dark-transition;
        }

        .admin-header {
            @apply bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 dark-transition;
        }

        .admin-content {
            @apply p-6 text-gray-900 dark:text-gray-100 dark-transition;
        }

        .admin-table {
            @apply bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden dark-transition;
        }

        .admin-card {
            @apply bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-sm dark-transition;
        }

        .admin-form-group {
            @apply mb-4;
        }

        .admin-label {
            @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 dark-transition;
        }

        .admin-input {
            @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 placeholder:text-gray-400 dark:placeholder:text-gray-500 dark-transition;
        }

        .admin-textarea {
            @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 placeholder:text-gray-400 dark:placeholder:text-gray-500 dark-transition resize-vertical;
        }

        .admin-select {
            @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 dark-transition;
        }

        .admin-btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 dark-transition;
        }

        .admin-btn-secondary {
            @apply bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 dark-transition;
        }

        .admin-btn-danger {
            @apply bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 dark-transition;
        }

        .admin-btn-success {
            @apply bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors duration-200 dark-transition;
        }

        .admin-link {
            @apply text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline decoration-1 underline-offset-2 dark-transition;
        }

        .admin-badge {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
        }

        .admin-badge-success {
            @apply admin-badge bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200;
        }

        .admin-badge-warning {
            @apply admin-badge bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200;
        }

        .admin-badge-danger {
            @apply admin-badge bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200;
        }

        .admin-badge-info {
            @apply admin-badge bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200;
        }

        .admin-alert {
            @apply p-4 rounded-md mb-4;
        }

        .admin-alert-success {
            @apply admin-alert bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200;
        }

        .admin-alert-danger {
            @apply admin-alert bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200;
        }

        .admin-alert-warning {
            @apply admin-alert bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200;
        }

        .admin-alert-info {
            @apply admin-alert bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200;
        }

        /* Table Specific Styles */
        .admin-table-header {
            @apply bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-medium text-sm dark-transition;
        }

        .admin-table-cell {
            @apply px-4 py-3 text-sm text-gray-900 dark:text-gray-100 dark-transition;
        }

        .admin-table-row {
            @apply border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 dark-transition;
        }

        /* Custom form styles for better dark mode support */
        .form-control {
            @apply dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder:text-gray-400;
        }

        .form-control:focus {
            @apply dark:bg-gray-700 dark:border-blue-500 dark:text-gray-100;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .form-select {
            @apply dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100;
        }

        .form-select:focus {
            @apply dark:bg-gray-700 dark:border-blue-500 dark:text-gray-100;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        /* Pagination styles */
        .pagination {
            @apply dark:text-gray-100;
        }

        .page-link {
            @apply dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-600;
        }

        .page-item.active .page-link {
            @apply dark:bg-blue-600 dark:border-blue-600;
        }

        .page-item.disabled .page-link {
            @apply dark:bg-gray-800 dark:border-gray-700 dark:text-gray-500;
        }
    </style>
@endpush
