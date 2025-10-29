<!-- Theme Toggle Component -->
<div class="relative" x-data="themeToggle()">
    <button @click="showDropdown = !showDropdown"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                   text-gray-700 hover:text-gray-900 hover:bg-gray-100 
                   dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700"
        type="button">
        <!-- Dynamic Icon -->
        <span x-show="currentTheme === 'light'" class="text-lg">☀️</span>
        <span x-show="currentTheme === 'dark'" class="text-lg">🌙</span>
        <span x-show="currentTheme === 'system'" class="text-lg">💻</span>

        <span class="hidden sm:inline">Theme</span>

        <!-- Dropdown Arrow -->
        <svg class="w-4 h-4 transition-transform duration-200" :class="showDropdown ? 'rotate-180' : ''" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="showDropdown" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" @click.away="showDropdown = false"
        class="absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 border-b border-gray-700 dark:border-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 dark:ring-gray-700">

        <div class="py-1">
            <!-- Light Mode -->
            <button @click="setTheme('light')"
                class="flex items-center w-full px-4 py-2 text-sm text-dark dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                :class="currentTheme === 'light' ? 'bg-gray-100 dark:bg-gray-700' : ''">
                <span class="mr-3 text-lg">☀️</span>
                <span>Light</span>
                <svg x-show="currentTheme === 'light'" class="ml-auto w-4 h-4 text-blue-600 dark:text-blue-400"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- Dark Mode -->
            <button @click="setTheme('dark')"
                class="flex items-center w-full px-4 py-2 text-sm text-dark dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                :class="currentTheme === 'dark' ? 'bg-gray-100 dark:bg-gray-700' : ''">
                <span class="mr-3 text-lg">🌙</span>
                <span>Dark</span>
                <svg x-show="currentTheme === 'dark'" class="ml-auto w-4 h-4 text-blue-600 dark:text-blue-400"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>

            <!-- System Mode -->
            <button @click="setTheme('system')"
                class="flex items-center w-full px-4 py-2 text-sm text-dark dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                :class="currentTheme === 'system' ? 'bg-gray-100 dark:bg-gray-700' : ''">
                <span class="mr-3 text-lg">💻</span>
                <span>System</span>
                <svg x-show="currentTheme === 'system'" class="ml-auto w-4 h-4 text-blue-600 dark:text-blue-400"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    function themeToggle() {
        return {
            showDropdown: false,
            currentTheme: 'system', // Will be set in init()

            init() {
                // Get stored theme or default to system
                this.currentTheme = localStorage.getItem('theme') || 'system';

                // Force apply the theme immediately
                this.applyTheme();

                // Small delay to ensure DOM is ready, then apply again
                setTimeout(() => {
                    this.applyTheme();
                }, 50);

                // Listen for system theme changes - only apply if current theme is 'system'
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addEventListener('change', (e) => {
                    if (this.currentTheme === 'system') {
                        this.applyTheme();
                    }
                });

                // Debug logging
                console.log('Theme initialized:', this.currentTheme);
            },

            setTheme(theme) {
                console.log('Setting theme to:', theme);

                // Update the current theme
                this.currentTheme = theme;

                // Store in localStorage
                localStorage.setItem('theme', theme);

                // Apply the theme immediately
                this.applyTheme();

                // Close dropdown
                this.showDropdown = false;

                // Dispatch custom event for other components
                window.dispatchEvent(new CustomEvent('theme-changed', {
                    detail: {
                        theme: theme,
                        isDark: this.getIsDark(theme)
                    }
                }));

                console.log('Theme applied:', theme, 'isDark:', this.getIsDark(theme));
            },

            getIsDark(theme = this.currentTheme) {
                if (theme === 'dark') {
                    return true;
                } else if (theme === 'light') {
                    return false;
                } else { // system
                    return window.matchMedia('(prefers-color-scheme: dark)').matches;
                }
            },

            applyTheme() {
                const isDark = this.getIsDark();

                if (isDark) {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }

                // Force a repaint to ensure changes are visible
                document.documentElement.offsetHeight;

                console.log('Applied theme - isDark:', isDark, 'currentTheme:', this.currentTheme);
            }
        };
    }
</script>
