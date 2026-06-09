<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'KASBIT Admin Panel' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kasbitBlue: '#0d47a1',
                        kasbitDark: '#0a2540',
                        kasbitGold: '#ffcc00',
                    }
                }
            }
        }
    </script>
    <style>
        /* Invisible scrollbar functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Smooth transitions for multi-level dropdowns */
        .dropdown-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dropdown-container.open {
            max-height: 1000px; /* Increased for nested items */
        }
        .chevron { transition: transform 0.3s ease; }
        .chevron.rotate { transform: rotate(180deg); }
    </style>
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden">

    <!-- Sidebar Start -->
    <aside class="w-72 bg-kasbitDark text-gray-300 flex flex-col justify-between shadow-xl z-10 overflow-y-auto no-scrollbar">
        <div>
            <!-- Sidebar Header -->
            <div class="p-5 flex items-center space-x-3 border-b border-gray-700 bg-kasbitBlue text-white sticky top-0 z-20">
                <i class="fa-solid fa-graduation-cap text-2xl text-kasbitGold"></i>
                <span class="font-bold text-lg tracking-wider"> ADMIN</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ Request::is('admin/dashboard') ? 'bg-kasbitBlue text-white font-medium shadow-md' : 'hover:bg-gray-800 hover:text-white transition' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <i class="fa-solid fa-users w-5 text-center text-base"></i>
                    <span>All Users</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <i class="fa-solid fa-user-clock w-5 text-center text-base"></i>
                    <span>Pending Users</span>
                </a>

                <div class="pt-4 pb-1">
                    <span class="text-xs uppercase text-gray-500 font-bold px-4 tracking-wider">Website Sections</span>
                </div>

                <!-- PROGRAMS (LEVEL 1) -->
                <div>
                    <button onclick="toggleDD('programs')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-book-open w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">Programs</span>
                        </div>
                        <i id="chevron-programs" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    
                    <div id="dd-programs" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-3 pr-2 space-y-1">
                        
                        <!-- Associate Degree (LEVEL 2 Nested) -->
                        <div>
                            <button onclick="toggleNestedDD('assoc-deg', 'programs')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Associate Degree Program</span></span>
                                <i id="chevron-assoc-deg" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-assoc-deg" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Associate Degree In Commerce</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Associate Degree in Computer Science</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Associate Degree In Digital Marketing</a>
                            </div>
                        </div>

                        <!-- Undergraduate (LEVEL 2 Nested) -->
                        <div>
                            <button onclick="toggleNestedDD('undergrad', 'programs')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Undergraduate Program</span></span>
                                <i id="chevron-undergrad" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-undergrad" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">BBA</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">BS (AF)</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">BS Computer Science</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">BBA 2 Years</a>
                            </div>
                        </div>

                        <!-- Graduate (LEVEL 2 Nested) -->
                        <div>
                            <button onclick="toggleNestedDD('graduate-list', 'programs')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Graduate Program</span></span>
                                <i id="chevron-graduate-list" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-graduate-list" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">MBA (36) after 4 years Bachelors</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">MBA (66) After 16 Year Non-Business</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">MS</a>
                            </div>
                        </div>

                        <!-- Postgraduate (LEVEL 2 Nested) -->
                        <div>
                            <button onclick="toggleNestedDD('postgrad', 'programs')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Postgraduate</span></span>
                                <i id="chevron-postgrad" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-postgrad" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Ph.D</a>
                            </div>
                        </div>

                        <a href="{{ url('admin/programs/fee-structure') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Fee Structure</span></a>
                        <a href="{{ url('admin/programs/program-profile') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Program Profile</span></a>
                    </div>
                </div>

                <!-- ADMISSIONS -->
                <div>
                    <button onclick="toggleDD('admissions')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-file-signature w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">Admissions</span>
                        </div>
                        <i id="chevron-admissions" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-admissions" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Admission Policy</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Online Admission Portal</span></a>
                    </div>
                </div>

                <!-- ACADEMICS -->
                <div>
                    <button onclick="toggleDD('academics')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-university w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">Academics</span>
                        </div>
                        <i id="chevron-academics" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-academics" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-3 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Dean's Message</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Faculty</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Academic Calendar</span></a>
                        
                        <!-- Academic Departments Nested Dropdown -->
                        <div>
                            <button onclick="toggleNestedDD('acad-deps', 'academics')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Academic Departments</span></span>
                                <i id="chevron-acad-deps" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-acad-deps" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Business Administration</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Computer Science & IS</a>
                            </div>
                        </div>

                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Academic Scholarship</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Rules & Regulations</span></a>
                    </div>
                </div>

                <!-- LIFE @ KASBIT -->
                <div>
                    <button onclick="toggleDD('life')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-nodes w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">Life @ Kasbit</span>
                        </div>
                        <i id="chevron-life" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-life" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="{{ url('admin/life-at-kasbit/facilities') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Facilities & Services</span></a>
                        <a href="{{ url('admin/life-at-kasbit/premises') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Life on Premises</span></a>
                        <a href="{{ url('admin/life-at-kasbit/societies') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Student Societies</span></a>
                        <a href="{{ url('admin/life-at-kasbit/gallery') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Event Gallery</span></a>
                    </div>
                </div>

                <!-- QEC -->
                <div>
                    <button onclick="toggleDD('qec')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-shield-halved w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">QEC</span>
                        </div>
                        <i id="chevron-qec" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-qec" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-3 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>QEC Cell Message</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Quality Policy Statement</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>QEC Structure</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>QEC Staff Details</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Functions of QEC</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Student Survey Forms</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>QEC Activity Calendar</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>QEC Activities</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Yearly Progress Report</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Self Assessment Report</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Memberships</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>AT / PT Notification</span></a>
                        
                        <!-- SDG Nested Dropdown -->
                        <div>
                            <button onclick="toggleNestedDD('sdg-list', 'qec')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>SDG</span></span>
                                <i id="chevron-sdg-list" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-sdg-list" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">SDG3-Good Health & Well-Being</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">SDG4-Quality Education</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">SDG8-Decent Work & Economic Growth</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ORIC -->
                <div>
                    <button onclick="toggleDD('oric')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-flask w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">ORIC</span>
                        </div>
                        <i id="chevron-oric" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-oric" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-3 pr-2 space-y-1">
                        <!-- Introduction Nested -->
                        <div>
                            <button onclick="toggleNestedDD('oric-intro', 'oric')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Introduction</span></span>
                                <i id="chevron-oric-intro" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-oric-intro" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Overview</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Mission</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">ORIC Organogram</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">ORIC Policy</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">KASBIT ORIC HEC Notification</a>
                            </div>
                        </div>

                        <!-- Research Journals Nested -->
                        <div>
                            <button onclick="toggleNestedDD('oric-journals', 'oric')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Research Journals</span></span>
                                <i id="chevron-oric-journals" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-oric-journals" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">KASBIT Business Journal</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Journal of Marketing & Logistics</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Journal of Management & HR</a>
                            </div>
                        </div>

                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Conferences</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Trainings & Workshops</span></a>
                        
                        <!-- Research Project Nested -->
                        <div>
                            <button onclick="toggleNestedDD('oric-projects', 'oric')" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition focus:outline-none">
                                <span class="flex items-center space-x-2"><i class="fa-solid fa-circle text-[4px]"></i> <span>Research Project / Thesis</span></span>
                                <i id="chevron-oric-projects" class="fa-solid fa-chevron-down text-[10px] chevron text-gray-500"></i>
                            </button>
                            <div id="dd-oric-projects" class="dropdown-container pl-4 space-y-1 border-l border-gray-700 ml-2 mt-1">
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Downloads</a>
                                <a href="#" class="block py-1.5 text-xs text-gray-400 hover:text-white transition">Guidelines</a>
                            </div>
                        </div>

                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Industrial Linkage</span></a>
                    </div>
                </div>

                <!-- LOGIN MANAGEMENT -->
                <div>
                    <button onclick="toggleDD('loginmgmt')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-right-to-bracket w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold whitespace-nowrap">Login Management</span>
                        </div>
                        <i id="chevron-loginmgmt" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-loginmgmt" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Faculty Login</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Student Login</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Results</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Convocation Registration</span></a>
                    </div>
                </div>

                <!-- ALUMNI -->
                <div>
                    <button onclick="toggleDD('alumni')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-user-graduate w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">Alumni</span>
                        </div>
                        <i id="chevron-alumni" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-alumni" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Office of Alumni</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Alumni Login</span></a>
                    </div>
                </div>

                <!-- E LIBRARY -->
                <div>
                    <button onclick="toggleDD('elibrary')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-book w-5 text-center text-base text-kasbitGold"></i>
                            <span class="font-semibold text-kasbitGold">E Library</span>
                        </div>
                        <i id="chevron-elibrary" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-elibrary" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Kasbit E Library</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>E Library Resources</span></a>
                    </div>
                </div>

                <div class="pt-4 pb-1 border-t border-gray-700 mt-3">
                    <span class="text-xs uppercase text-gray-500 font-bold px-4 tracking-wider">CMS Control</span>
                </div>

                <!-- CMS PAGES DROPDOWN -->
                <div>
                    <button onclick="toggleDD('cms-pages')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition focus:outline-none">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-file-code w-5 text-center text-base"></i>
                            <span>CMS Pages</span>
                        </div>
                        <i id="chevron-cms-pages" class="fa-solid fa-chevron-down text-xs chevron text-gray-400"></i>
                    </button>
                    <div id="dd-cms-pages" class="dropdown-container bg-slate-900/50 rounded-lg mt-1 pl-4 pr-2 space-y-1">
                        <a href="{{ route('home.cms.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Home Page</span></a>
                        <a href="{{ route('header-menu.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Header Menu</span></a>
                        <a href="{{ route('footer-cms.index') }}" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition"><i class="fa-solid fa-circle text-[4px]"></i><span>Footer CMS</span></a>
                        <a href="#" class="flex items-center space-x-2 px-3 py-2 text-sm rounded-md hover:text-white hover:bg-gray-800 transition text-gray-400 cursor-not-allowed opacity-50"><i class="fa-solid fa-circle text-[4px]"></i><span>About Page (Coming Soon)</span></a>
                    </div>
                </div>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-800 hover:text-white transition">
                    <i class="fa-solid fa-images w-5 text-center text-base"></i>
                    <span>Gallery CMS</span>
                </a>

            </nav>
        </div>

        <!-- Sidebar Footer / Logout -->
        <div class="p-4 border-t border-gray-700 bg-slate-900 sticky bottom-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition text-left focus:outline-none">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center text-base"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    <!-- Sidebar End -->

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b flex items-center justify-between px-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-800">{{ $header ?? 'Dashboard Overview' }}</h2>
            <div class="flex items-center space-x-2 border-l pl-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=0d47a1&color=fff"
                     alt="User" class="w-8 h-8 rounded-full">
                <span class="text-sm font-medium text-gray-700 hidden md:inline-block">
                    {{ Auth::user()->name ?? 'KASBIT Admin' }}
                </span>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
            {{ $slot }}
        </main>
    </div>

    <!-- JavaScript logic handling multi-level toggles -->
    <script>
        // Toggle Parent Dropdown (Level 1)
        function toggleDD(id) {
            const menu = document.getElementById('dd-' + id);
            const icon = document.getElementById('chevron-' + id);
            const isOpen = menu.classList.contains('open');
            
            // Close all top-level dropdowns except the current one
            document.querySelectorAll('.dropdown-container').forEach(el => {
                // Check if the element is a level-1 container
                if (el.id !== 'dd-' + id && !el.id.startsWith('dd-assoc') && !el.id.startsWith('dd-undergrad') && !el.id.startsWith('dd-graduate') && !el.id.startsWith('dd-postgrad') && !el.id.startsWith('dd-acad') && !el.id.startsWith('dd-sdg') && !el.id.startsWith('dd-oric')) {
                    el.classList.remove('open');
                    // Reset its corresponding chevrons
                    const parentId = el.id.replace('dd-', '');
                    const cIcon = document.getElementById('chevron-' + parentId);
                    if(cIcon) cIcon.classList.remove('rotate');
                }
            });
            
            // Toggle the clicked parent
            if (!isOpen) {
                menu.classList.add('open');
                icon.classList.add('rotate');
            } else {
                menu.classList.remove('open');
                icon.classList.remove('rotate');
                menu.style.maxHeight = '';
                
                // If closing parent, close any nested menus inside it too
                menu.querySelectorAll('.dropdown-container').forEach(nested => {
                    nested.classList.remove('open');
                });
                menu.querySelectorAll('.chevron').forEach(chev => {
                    chev.classList.remove('rotate');
                });
            }
        }

        // Toggle Child Dropdown (Level 2 Nested)
        function toggleNestedDD(childId, parentId) {
            const childMenu = document.getElementById('dd-' + childId);
            const childIcon = document.getElementById('chevron-' + childId);
            const parentMenu = document.getElementById('dd-' + parentId);
            const isChildOpen = childMenu.classList.contains('open');

            // Close other nested submenus in the SAME parent section to keep it clean
            parentMenu.querySelectorAll('.dropdown-container').forEach(el => {
                if (el.id !== 'dd-' + childId) {
                    el.classList.remove('open');
                    const cId = el.id.replace('dd-', '');
                    const cIcon = document.getElementById('chevron-' + cId);
                    if(cIcon) cIcon.classList.remove('rotate');
                }
            });

            // Toggle child menu
            if (!isChildOpen) {
                childMenu.classList.add('open');
                childIcon.classList.add('rotate');
                // Dynamically update parent height to fit the new inner layout seamlessly
                parentMenu.style.maxHeight = (parentMenu.scrollHeight + childMenu.scrollHeight) + "px";
            } else {
                childMenu.classList.remove('open');
                childIcon.classList.remove('rotate');
                parentMenu.style.maxHeight = '';
            }
        }
    </script>
</body>
</html>
