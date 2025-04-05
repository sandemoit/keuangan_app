<!-- Navbar -->
<nav class="fixed top-0 z-50 w-full bg-gradient-to-r from-blue-600 to-blue-800 border-b border-blue-200">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
                <!-- Sidebar Toggle Button -->
                <button data-drawer-target="sidebar" data-drawer-toggle="sidebar" aria-controls="sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-white rounded-lg md:hidden hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>

                <!-- Logo -->
                <a href="/" class="flex ml-2 md:mr-24">
                    <i class="fas fa-wallet text-white text-2xl mr-3"></i>
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">FinanceTracker</span>
                </a>
            </div>

            <!-- User Profile & Notifications -->
            <div class="flex items-center">
                <!-- Notification Bell -->
                <button type="button"
                    class="p-2 mr-3 text-gray-200 rounded-lg hover:text-white hover:bg-blue-700 focus:outline-none">
                    <span class="sr-only">View notifications</span>
                    <i class="fas fa-bell w-5 h-5 relative">
                        <span
                            class="absolute top-0 right-0 -mt-1 -mr-1 text-xs bg-red-500 text-white rounded-full h-4 w-4 flex items-center justify-center">3</span>
                    </i>
                </button>

                <!-- User Menu Dropdown -->
                <div class="flex items-center ml-3">
                    <div>
                        <button type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-blue-300"
                            id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                            data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full" src="https://randomuser.me/api/portraits/men/1.jpg"
                                alt="user photo">
                        </button>
                    </div>
                    <!-- User Dropdown Menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                        id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900">Johny Doe</span>
                            <span class="block text-sm text-gray-500 truncate">johny.doe@example.com</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-chart-line mr-2"></i> Activity Log
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
