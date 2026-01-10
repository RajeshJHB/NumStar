@extends('layouts.app')

@section('title', 'NumStar')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1 flex justify-center">
                <img src="{{ asset('icons/android-chrome-192x192.png') }}" 
                     alt="Lo Shu" 
                     class="h-32 w-32"
                     onerror="this.onerror=null; this.src='{{ asset('icons/favicon-48x48.png') }}'; this.onerror=null; this.src='{{ asset('favicon.ico') }}';">
            </div>
            @if($hasRoles ?? true)
                <button type="button" id="delete-button"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 hidden">
                    Delete
                </button>
            @endif
        </div>

        <div class="max-w-2xl mx-auto">
            <!-- Success/Error Messages -->
            <div id="message-container" class="mb-4"></div>

            <form id="client-form" class="space-y-6">
                @csrf
                <!-- Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter name" required>
                        <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Middle Name
                        </label>
                        <input type="text" id="middle_name" name="middle_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter middle name">
                        <div id="middle_name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">
                            Surname
                        </label>
                        <input type="text" id="surname" name="surname"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter surname">
                        <div id="surname-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-gray-700 mb-2">Sex</span>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="sex" value="male"
                                    class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sex" value="female"
                                    class="text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Female</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sex" value="na"
                                    class="text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                                <span class="ml-2 text-sm text-gray-700">N/A</span>
                            </label>
                        </div>
                        <div id="sex-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Date of Birth - Separate Line -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date of Birth <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="dob_year" class="block text-xs text-gray-600 mb-1">Year</label>
                            <input type="number" id="dob_year" name="dob_year" min="1900" max="2100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="YYYY" required>
                        </div>
                        <div>
                            <label for="dob_month" class="block text-xs text-gray-600 mb-1">Month</label>
                            <input type="number" id="dob_month" name="dob_month" min="1" max="12"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="MM" required>
                        </div>
                        <div>
                            <label for="dob_day" class="block text-xs text-gray-600 mb-1">Date</label>
                            <input type="number" id="dob_day" name="dob_day" min="1" max="31"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="DD" required>
                        </div>
                    </div>
                    <div id="date_of_birth-error" class="text-red-500 text-sm mt-1 hidden"></div>
                </div>
                <input type="hidden" id="date_of_birth" name="date_of_birth">

                <!-- Time of Birth, Country of Birth, and Town of Birth -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="time_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Time of
                            Birth</label>
                        <input type="time" id="time_of_birth" name="time_of_birth"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <div id="time_of_birth-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        
                        @auth
                            <div class="mt-4">
                                <label for="save_as" class="block text-sm font-medium text-gray-700 mb-2">
                                    SaveAs <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="save_as" name="save_as"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter unique identifier" required>
                                <div id="save_as-error" class="text-red-500 text-sm mt-1 hidden"></div>
                            </div>
                        @endauth
                    </div>

                    <div>
                        <label for="country_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Country of
                            Birth</label>
                        <select id="country_of_birth" name="country_of_birth"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Loading countries...</option>
                        </select>
                        <div id="country_of_birth-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="town_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Town of Birth</label>
                        <select id="town_of_birth" name="town_of_birth"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2">
                            <option value="">Select town/city</option>
                        </select>
                        <div class="flex items-center gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 font-medium">Latitude:</span>
                                <span id="latitude_display" class="ml-2 text-gray-800">-</span>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Longitude:</span>
                                <span id="longitude_display" class="ml-2 text-gray-800">-</span>
                            </div>
                        </div>
                        <div id="town_of_birth-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>

                <!-- Save, Load, and Calculate Buttons -->
                <div class="pt-4 flex gap-4">
                    @if($hasRoles ?? true)
                        <button type="submit" id="save-button"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save
                        </button>
                        <button type="button" id="load-button"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Load
                        </button>
                    @endif
                    <button type="button" id="calculate-button"
                        class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        Calculate
                    </button>
                </div>
                <input type="hidden" id="current-client-id" value="">
            </form>

            <!-- Lo Shu Grid Section -->
            <div class="border-t pt-6" id="lo-shu-section">
                <h2 class="text-xl font-semibold mb-4">Lo Shu Grid</h2>
                <div id="lo-shu-container" class="flex justify-center">
                    <div class="text-gray-500 text-center py-8">
                        Click "Calculate" to generate the Lo Shu grid from the date of birth.
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Client Selection Modal -->
    <div id="client-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] flex flex-col">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">Select a Client</h2>
                    <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="clients-list" class="space-y-2">
                    <div class="text-center text-gray-500 py-8">Loading clients...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global CSRF token - try multiple sources
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                          document.querySelector('input[name="_token"]')?.value || 
                          '{{ csrf_token() }}';
        
        document.addEventListener('DOMContentLoaded', function () {
            // Calculate Lo Shu Grid
            const calculateButton = document.getElementById('calculate-button');
            const loShuContainer = document.getElementById('lo-shu-container');

            function reduceToSingleDigit(num) {
                let intermediate = null;
                let current = num;
                
                while (current > 9) {
                    const next = current.toString().split('').reduce((sum, digit) => sum + parseInt(digit), 0);
                    // Track the last intermediate value that is >= 10 and < 100
                    if (current >= 10 && current < 100) {
                        intermediate = current;
                    }
                    current = next;
                }
                
                return {
                    intermediate: intermediate,
                    final: current
                };
            }

            // Numerology letter to number mapping
            const numerologyMap = {
                'A': 1, 'I': 1, 'J': 1, 'Q': 1, 'Y': 1,
                'B': 2, 'K': 2, 'R': 2,
                'C': 3, 'G': 3, 'L': 3, 'S': 3,
                'D': 4, 'M': 4, 'T': 4,
                'E': 5, 'H': 5, 'N': 5, 'X': 5,
                'U': 6, 'V': 6, 'W': 6,
                'O': 7, 'Z': 7,
                'F': 8, 'P': 8
            };

            function calculateNameNumber(name) {
                if (!name) return null;

                let sum = 0;
                const upperName = name.toUpperCase().replace(/\s+/g, '');

                for (let i = 0; i < upperName.length; i++) {
                    const char = upperName[i];
                    if (numerologyMap[char]) {
                        sum += numerologyMap[char];
                    }
                }

                return sum > 0 ? reduceToSingleDigit(sum) : null;
            }

            function calculateSoulUrgeNumber(name) {
                if (!name) return null;

                // Extract vowels: A, E, I, O, U
                const vowels = ['A', 'E', 'I', 'O', 'U'];
                let sum = 0;
                const upperName = name.toUpperCase().replace(/\s+/g, '');

                for (let i = 0; i < upperName.length; i++) {
                    const char = upperName[i];
                    if (vowels.includes(char) && numerologyMap[char]) {
                        sum += numerologyMap[char];
                    }
                }

                return sum > 0 ? reduceToSingleDigit(sum) : null;
            }

            function calculatePersonalityNumber(name) {
                if (!name) return null;

                // Extract consonants: all letters except vowels
                const vowels = ['A', 'E', 'I', 'O', 'U'];
                let sum = 0;
                const upperName = name.toUpperCase().replace(/\s+/g, '');

                for (let i = 0; i < upperName.length; i++) {
                    const char = upperName[i];
                    if (!vowels.includes(char) && numerologyMap[char]) {
                        sum += numerologyMap[char];
                    }
                }

                return sum > 0 ? reduceToSingleDigit(sum) : null;
            }

            // Calculate Kua number based on birth date and sex
            function calculateKuaNumberFromDateString(dateOfBirth, sex) {
                if (!dateOfBirth) return null;
                const parts = dateOfBirth.split('-');
                if (parts.length !== 3) return null;

                let year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const day = parseInt(parts[2], 10);

                // If born in January or before early Feb (approx Chinese New Year), use previous year
                if (month === 1 || (month === 2 && day <= 4)) {
                    year = year - 1;
                }

                const reduceDigits = (n) => {
                    const result = reduceToSingleDigit(Math.abs(n));
                    return result.final;
                };

                sex = (sex || '').toLowerCase();

                if (sex === 'male') {
                    const r = reduceDigits(year);
                    if (year < 2000) {
                        let kua = 11 - r;
                        const kuaResult = reduceToSingleDigit(kua);
                        if (kuaResult.final === 5) return { intermediate: kuaResult.intermediate, final: 2 };
                        return kuaResult;
                    } else {
                        let kua = 9 - r;
                        const kuaResult = reduceToSingleDigit(kua);
                        if (kuaResult.final === 5) return { intermediate: kuaResult.intermediate, final: 2 };
                        return kuaResult;
                    }
                }

                if (sex === 'female') {
                    const lastTwo = year % 100;
                    const r = reduceDigits(lastTwo);
                    const sum = (year < 2000) ? (r + 5) : (r + 6);
                    const kuaResult = reduceToSingleDigit(sum);
                    if (kuaResult.final === 5) return { intermediate: kuaResult.intermediate, final: 8 };
                    return kuaResult;
                }

                // default to male formula
                const r = reduceDigits(year);
                let kua = (year < 2000) ? (11 - r) : (9 - r);
                const kuaResult = reduceToSingleDigit(kua);
                if (kuaResult.final === 5) return { intermediate: kuaResult.intermediate, final: 2 };
                return kuaResult;
            }

            // Function to get date from three separate fields
            function getDateFromFields() {
                const year = document.getElementById('dob_year')?.value;
                const month = document.getElementById('dob_month')?.value;
                const day = document.getElementById('dob_day')?.value;

                if (!year || !month || !day) {
                    return null;
                }

                // Format as YYYY-MM-DD
                const monthStr = String(month).padStart(2, '0');
                const dayStr = String(day).padStart(2, '0');
                return `${year}-${monthStr}-${dayStr}`;
            }

            function calculateLoShuGrid(dateOfBirth, name, middleName, surname, sex) {
                if (!dateOfBirth) {
                    return null;
                }

                // Parse date string directly to avoid timezone issues
                // Date format is YYYY-MM-DD
                const dateParts = dateOfBirth.split('-');
                if (dateParts.length !== 3) {
                    return null;
                }

                const year = parseInt(dateParts[0], 10);
                const month = parseInt(dateParts[1], 10);
                const day = parseInt(dateParts[2], 10);

                // Main Planet Number (day of birth reduced to single digit)
                const mainPlanet = reduceToSingleDigit(day);

                // Calculate life path number (sum of all date digits)
                const yearSum = year.toString().split('').reduce((sum, d) => sum + parseInt(d), 0);
                const lifePath = reduceToSingleDigit(day + month + yearSum);

                // Calculate Destiny Number from name
                const fullName = [name, middleName, surname].filter(Boolean).join(' ');
                const destinyNumber = calculateNameNumber(fullName);
                const soulUrgeNumber = calculateSoulUrgeNumber(fullName);
                const personalityNumber = calculatePersonalityNumber(fullName);

                // Birthday Number (day of birth - not reduced, just the day)
                const birthdayNumber = day;

                // Maturity Number (sum of Life Path and Destiny Number, then reduced)
                let maturityNumber = null;
                if (lifePath && destinyNumber) {
                    const maturitySum = lifePath.final + destinyNumber.final;
                    maturityNumber = reduceToSingleDigit(maturitySum);
                }

                // Get all digits from date of birth only (ignore 0)
                const allDateDigits = [];
                day.toString().split('').forEach(d => {
                    const digit = parseInt(d);
                    if (digit > 0) allDateDigits.push(digit);
                });
                month.toString().split('').forEach(d => {
                    const digit = parseInt(d);
                    if (digit > 0) allDateDigits.push(digit);
                });
                year.toString().split('').forEach(d => {
                    const digit = parseInt(d);
                    if (digit > 0) allDateDigits.push(digit);
                });

                // Count occurrences of each digit (1-9) in date of birth
                const digitCounts = {};
                for (let i = 1; i <= 9; i++) {
                    digitCounts[i] = 0;
                }
                allDateDigits.forEach(digit => {
                    if (digit >= 1 && digit <= 9) {
                        digitCounts[digit]++;
                    }
                });

                // Add Main Planet number digits to the count (use .final)
                const mainPlanetDigits = mainPlanet.final.toString().split('').map(d => parseInt(d)).filter(d => d > 0 && d <= 9);
                mainPlanetDigits.forEach(digit => {
                    digitCounts[digit]++;
                });

                // Add Life Path number digits to the count (use .final)
                const lifePathDigits = lifePath.final.toString().split('').map(d => parseInt(d)).filter(d => d > 0 && d <= 9);
                lifePathDigits.forEach(digit => {
                    digitCounts[digit]++;
                });

                // Add Destiny Number digits to the count (if provided, use .final)
                if (destinyNumber) {
                    const destinyDigits = destinyNumber.final.toString().split('').map(d => parseInt(d)).filter(d => d > 0 && d <= 9);
                    destinyDigits.forEach(digit => {
                        digitCounts[digit]++;
                    });
                }

                // Lo Shu grid positions with planet names
                // Position mapping: 4=Rahu, 9=Mars, 2=Moon, 3=Jupiter, 5=Mercury, 7=Ketu, 8=Saturn, 1=Sun, 6=Venus
                const gridPositions = {
                    4: { planet: 'Rahu', row: 0, col: 0 },
                    9: { planet: 'Mars', row: 0, col: 1 },
                    2: { planet: 'Moon', row: 0, col: 2 },
                    3: { planet: 'Jupiter', row: 1, col: 0 },
                    5: { planet: 'Mercury', row: 1, col: 1 },
                    7: { planet: 'Ketu', row: 1, col: 2 },
                    8: { planet: 'Saturn', row: 2, col: 0 },
                    1: { planet: 'Sun', row: 2, col: 1 },
                    6: { planet: 'Venus', row: 2, col: 2 }
                };

                // Initialize grid with empty cells
                const grid = [
                    [{ planet: 'Rahu', display: '' }, { planet: 'Mars', display: '' }, { planet: 'Moon', display: '' }],
                    [{ planet: 'Jupiter', display: '' }, { planet: 'Mercury', display: '' }, { planet: 'Ketu', display: '' }],
                    [{ planet: 'Saturn', display: '' }, { planet: 'Sun', display: '' }, { planet: 'Venus', display: '' }]
                ];

                // Fill grid with digit instances (repeat digit based on count)
                Object.keys(digitCounts).forEach(digitStr => {
                    const digit = parseInt(digitStr);
                    const count = digitCounts[digit];
                    if (count > 0 && digit >= 1 && digit <= 9) {
                        const pos = gridPositions[digit];
                        if (pos) {
                            // Display the digit repeated 'count' times
                            grid[pos.row][pos.col].display = digit.toString().repeat(count);
                        }
                    }
                });

                // Find missing numbers (1-9 not present in grid)
                const presentNumbers = new Set();
                Object.keys(digitCounts).forEach(digitStr => {
                    const digit = parseInt(digitStr);
                    if (digitCounts[digit] > 0) {
                        presentNumbers.add(digit);
                    }
                });
                const missingNumbers = [];
                for (let i = 1; i <= 9; i++) {
                    if (!presentNumbers.has(i)) {
                        missingNumbers.push(i);
                    }
                }

                // compute kua numbers
                const kuaMale = calculateKuaNumberFromDateString(dateOfBirth, 'male');
                const kuaFemale = calculateKuaNumberFromDateString(dateOfBirth, 'female');

                // If sex specified, place that Kua into the grid with single quotes
                const selectedSex = (sex || '').toLowerCase();
                let selectedKua = null;
                if (selectedSex === 'male') selectedKua = kuaMale;
                else if (selectedSex === 'female') selectedKua = kuaFemale;

                if (selectedKua && selectedKua.final && gridPositions[selectedKua.final]) {
                    const pos = gridPositions[selectedKua.final];
                    const current = grid[pos.row][pos.col].display || '';
                    grid[pos.row][pos.col].display = current + "'" + String(selectedKua.final) + "'";
                    
                    // Remove Kua number from missing numbers if it's present
                    const kuaIndex = missingNumbers.indexOf(selectedKua.final);
                    if (kuaIndex !== -1) {
                        missingNumbers.splice(kuaIndex, 1);
                    }
                }

                return {
                    grid: grid,
                    day: day,
                    month: month,
                    year: year,
                    mainPlanet: mainPlanet,
                    lifePath: lifePath,
                    destinyNumber: destinyNumber,
                    missingNumbers: missingNumbers,
                    kuaNumberMale: kuaMale,
                    kuaNumberFemale: kuaFemale,
                    soulUrgeNumber: soulUrgeNumber,
                    personalityNumber: personalityNumber,
                    birthdayNumber: birthdayNumber,
                    maturityNumber: maturityNumber,
                    selectedSex: selectedSex || null,
                };
            }

            function displayLoShuGrid(data) {
                if (!data) {
                    loShuContainer.innerHTML = `
                        <div class="text-red-500 text-center py-8">
                            Please enter a date of birth first.
                        </div>
                    `;
                    return;
                }

                const gridHtml = `
                    <div class="w-full max-w-2xl mx-auto">
                        <div class="mb-6 text-center">
                            <p class="text-sm text-gray-600 mb-2">
                                Date of Birth: ${data.day}/${data.month}/${data.year}
                            </p>
                        </div>

                        <!-- Numerology Numbers Table -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Numerology Numbers</h3>
                            <table class="w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 p-2 text-left font-semibold">Numerology Numbers</th>
                                        <th class="border border-gray-300 p-2 text-center font-semibold">Power</th>
                                        <th class="border border-gray-300 p-2 text-center font-semibold">Main Value</th>
                                        <th class="border border-gray-300 p-2 text-center font-semibold">Planet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 p-2">Main Planet</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.mainPlanet && data.mainPlanet.intermediate !== null && [11, 22, 33].includes(data.mainPlanet.intermediate) ? data.mainPlanet.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.mainPlanet ? data.mainPlanet.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.mainPlanet ? getPlanetName(data.mainPlanet.final) : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2">Life Path</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.lifePath && data.lifePath.intermediate !== null && [11, 22, 33].includes(data.lifePath.intermediate) ? data.lifePath.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.lifePath ? data.lifePath.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.lifePath ? getPlanetName(data.lifePath.final) : '-'}</td>
                                    </tr>
                                    ${data.destinyNumber ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Destiny Number (full name)</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.destinyNumber.intermediate !== null && [11, 22, 33].includes(data.destinyNumber.intermediate) ? data.destinyNumber.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.destinyNumber.final}</td>
                                        <td class="border border-gray-300 p-2 text-center">${getPlanetName(data.destinyNumber.final)}</td>
                                    </tr>
                                    ` : ''}
                                    ${data.soulUrgeNumber ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Soul Urge Number (name vowels)</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.soulUrgeNumber.intermediate !== null && [11, 22, 33].includes(data.soulUrgeNumber.intermediate) ? data.soulUrgeNumber.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.soulUrgeNumber.final}</td>
                                        <td class="border border-gray-300 p-2 text-center">${getPlanetName(data.soulUrgeNumber.final)}</td>
                                    </tr>
                                    ` : ''}
                                    ${data.personalityNumber ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Personality Number (name consonants)</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.personalityNumber.intermediate !== null && [11, 22, 33].includes(data.personalityNumber.intermediate) ? data.personalityNumber.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.personalityNumber.final}</td>
                                        <td class="border border-gray-300 p-2 text-center">${getPlanetName(data.personalityNumber.final)}</td>
                                    </tr>
                                    ` : ''}
                                    <tr>
                                        <td class="border border-gray-300 p-2">Birthday Number</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.birthdayNumber && [11, 22, 33].includes(data.birthdayNumber) ? data.birthdayNumber : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.birthdayNumber || '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.birthdayNumber ? getPlanetName(reduceToSingleDigit(data.birthdayNumber).final) : '-'}</td>
                                    </tr>
                                    ${data.maturityNumber ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Maturity Number</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.maturityNumber.intermediate !== null && [11, 22, 33].includes(data.maturityNumber.intermediate) ? data.maturityNumber.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.maturityNumber.final}</td>
                                        <td class="border border-gray-300 p-2 text-center">${getPlanetName(data.maturityNumber.final)}</td>
                                    </tr>
                                    ` : ''}
                                    ${!data.selectedSex || data.selectedSex === 'na' ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Kua Number Male</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberMale && data.kuaNumberMale.intermediate !== null && [11, 22, 33].includes(data.kuaNumberMale.intermediate) ? data.kuaNumberMale.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.kuaNumberMale ? data.kuaNumberMale.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberMale ? getPlanetName(data.kuaNumberMale.final) : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2">Kua Number Female</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberFemale && data.kuaNumberFemale.intermediate !== null && [11, 22, 33].includes(data.kuaNumberFemale.intermediate) ? data.kuaNumberFemale.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.kuaNumberFemale ? data.kuaNumberFemale.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberFemale ? getPlanetName(data.kuaNumberFemale.final) : '-'}</td>
                                    </tr>
                                    ` : ''}
                                    ${data.selectedSex === 'male' ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Kua Number Male</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberMale && data.kuaNumberMale.intermediate !== null && [11, 22, 33].includes(data.kuaNumberMale.intermediate) ? data.kuaNumberMale.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.kuaNumberMale ? data.kuaNumberMale.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberMale ? getPlanetName(data.kuaNumberMale.final) : '-'}</td>
                                    </tr>
                                    ` : ''}
                                    ${data.selectedSex === 'female' ? `
                                    <tr>
                                        <td class="border border-gray-300 p-2">Kua Number Female</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberFemale && data.kuaNumberFemale.intermediate !== null && [11, 22, 33].includes(data.kuaNumberFemale.intermediate) ? data.kuaNumberFemale.intermediate : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center font-bold">${data.kuaNumberFemale ? data.kuaNumberFemale.final : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${data.kuaNumberFemale ? getPlanetName(data.kuaNumberFemale.final) : '-'}</td>
                                    </tr>
                                    ` : ''}
                                </tbody>
                            </table>
                        </div>

                        ${data.missingNumbers.length > 0 ? `
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="font-semibold text-yellow-800 mb-2">Missing Numbers:</p>
                            <p class="text-yellow-700">${data.missingNumbers.join(', ')}</p>
                        </div>
                        ` : ''}

                        <!-- Calculated Lo Shu Grid -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Your Lo Shu Grid</h3>
                            <div class="grid grid-cols-3 gap-2 mx-auto" style="max-width: 450px;">
                                <!-- Row 1 -->
                                <div class="bg-gray-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Rahu</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[0][0].display || ''}
                                    </div>
                                </div>
                                <div class="bg-red-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Mars</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[0][1].display || ''}
                                    </div>
                                </div>
                                <div class="bg-slate-300 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-gray-800 mb-2">Moon</div>
                                    <div class="text-2xl font-bold text-gray-800">
                                        ${data.grid[0][2].display || ''}
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="bg-yellow-400 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-black mb-2">Jupiter</div>
                                    <div class="text-2xl font-bold text-black">
                                        ${data.grid[1][0].display || ''}
                                    </div>
                                </div>
                                <div class="bg-green-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Mercury</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[1][1].display || ''}
                                    </div>
                                </div>
                                <div class="bg-amber-700 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Ketu</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[1][2].display || ''}
                                    </div>
                                </div>

                                <!-- Row 3 -->
                                <div class="bg-blue-900 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Saturn</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[2][0].display || ''}
                                    </div>
                                </div>
                                <div class="bg-orange-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Sun</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[2][1].display || ''}
                                    </div>
                                </div>
                                <div class="bg-pink-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Venus</div>
                                    <div class="text-2xl font-bold text-white">
                                        ${data.grid[2][2].display || ''}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reference Lo Shu Grid -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Reference Lo Shu Grid</h3>
                            <p class="text-sm text-gray-600 mb-3 text-center">Standard grid layout showing the positions of numbers 1-9</p>
                            <div class="grid grid-cols-3 gap-2 mx-auto" style="max-width: 450px;">
                                <!-- Row 1 -->
                                <div class="bg-gray-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Rahu</div>
                                    <div class="text-3xl font-bold text-white">4</div>
                                    <div class="text-xs mt-1 text-white opacity-75">Northwest</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Practicality</div>
                                </div>
                                <div class="bg-red-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Mars</div>
                                    <div class="text-3xl font-bold text-white">9</div>
                                    <div class="text-xs mt-1 text-white opacity-75">South</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Ideals</div>
                                </div>
                                <div class="bg-slate-300 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-gray-800 mb-2">Moon</div>
                                    <div class="text-3xl font-bold text-gray-800">2</div>
                                    <div class="text-xs mt-1 text-gray-800 opacity-75">Southwest</div>
                                    <div class="text-xs mt-1 text-gray-800 opacity-90">Relations</div>
                                </div>

                                <!-- Row 2 -->
                                <div class="bg-yellow-400 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-black mb-2">Jupiter</div>
                                    <div class="text-3xl font-bold text-black">3</div>
                                    <div class="text-xs mt-1 text-black opacity-75">East</div>
                                    <div class="text-xs mt-1 text-black opacity-90">Creativity</div>
                                </div>
                                <div class="bg-green-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Mercury</div>
                                    <div class="text-3xl font-bold text-white">5</div>
                                    <div class="text-xs mt-1 text-white opacity-75">Center</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Center / Balance</div>
                                </div>
                                <div class="bg-amber-700 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Ketu</div>
                                    <div class="text-3xl font-bold text-white">7</div>
                                    <div class="text-xs mt-1 text-white opacity-75">West</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Spirituality</div>
                                </div>

                                <!-- Row 3 -->
                                <div class="bg-blue-900 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Saturn</div>
                                    <div class="text-3xl font-bold text-white">8</div>
                                    <div class="text-xs mt-1 text-white opacity-75">Northeast</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Discipline</div>
                                </div>
                                <div class="bg-orange-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Sun</div>
                                    <div class="text-3xl font-bold text-white">1</div>
                                    <div class="text-xs mt-1 text-white opacity-75">North</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Logic</div>
                                </div>
                                <div class="bg-pink-500 rounded-lg shadow-md p-4 flex flex-col items-center justify-center" style="min-height: 120px;">
                                    <div class="text-sm font-bold text-white mb-2">Venus</div>
                                    <div class="text-3xl font-bold text-white">6</div>
                                    <div class="text-xs mt-1 text-white opacity-75">Southeast</div>
                                    <div class="text-xs mt-1 text-white opacity-90">Responsibility</div>
                                </div>
                            </div>
                        </div>

                        <!-- Vedic Astrology Section -->
                        <div class="mb-6 border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4">Vedic Astrology</h3>
                            <div id="vedic-astrology-container">
                                <div class="text-gray-500 text-center py-4">
                                    Vedic astrology charts will be displayed here after calculation.
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                loShuContainer.innerHTML = gridHtml;
                
                // Calculate and display Vedic Astrology charts
                if (data.day && data.month && data.year) {
                    calculateVedicAstrology(data);
                }
            }

            function getPlanetName(number) {
                const planetMap = {
                    1: 'Sun', 2: 'Moon', 3: 'Jupiter', 4: 'Rahu',
                    5: 'Mercury', 6: 'Venus', 7: 'Ketu', 8: 'Saturn', 9: 'Mars'
                };
                return planetMap[number] || '';
            }

            // Vedic Astrology Calculations
            async function calculateVedicAstrology(data) {
                const vedicContainer = document.getElementById('vedic-astrology-container');
                if (!vedicContainer) return;

                // Get birth details from form
                const timeOfBirth = document.getElementById('time_of_birth')?.value || '';
                const countryOfBirth = document.getElementById('country_of_birth')?.value || '';
                const townOfBirth = document.getElementById('town_of_birth')?.value || '';
                
                // Get full name from form
                const name = document.getElementById('name')?.value || '';
                const middleName = document.getElementById('middle_name')?.value || '';
                const surname = document.getElementById('surname')?.value || '';
                const fullName = [name, middleName, surname].filter(n => n.trim()).join(' ').trim() || 'N/A';

                if (!timeOfBirth || !countryOfBirth || !townOfBirth) {
                    vedicContainer.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm">
                                Please enter Time of Birth, Country of Birth, and Town of Birth to calculate Vedic Astrology charts.
                            </p>
                        </div>
                    `;
                    return;
                }

                // Show loading state
                vedicContainer.innerHTML = `
                    <div class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                        <p class="mt-2 text-gray-600">Calculating Vedic Astrology charts...</p>
                    </div>
                `;

                try {
                    // Validate inputs before making API call
                    if (!timeOfBirth || !countryOfBirth || !townOfBirth) {
                        throw new Error('Time of Birth, Country of Birth, and Town of Birth are required for Vedic Astrology calculations.');
                    }

                    // Validate time format
                    const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
                    if (!timeRegex.test(timeOfBirth)) {
                        throw new Error('Time of Birth must be in HH:MM format (e.g., 14:30).');
                    }

                    const birthDate = `${data.year}-${String(data.month).padStart(2, '0')}-${String(data.day).padStart(2, '0')}`;
                    
                    console.log('Calculating Vedic Astrology with:', {
                        birthDate,
                        timeOfBirth,
                        countryOfBirth,
                        townOfBirth
                    });
                    
                    const vedicData = await getVedicChartData(birthDate, timeOfBirth, countryOfBirth, townOfBirth);
                    
                    displayVedicCharts(vedicData, fullName, timeOfBirth, `${townOfBirth}, ${countryOfBirth}`);
                } catch (error) {
                    console.error('Error calculating Vedic Astrology:', error);
                    const errorMessage = error.message || 'Error calculating Vedic Astrology charts. Please ensure all birth details are correct.';
                    vedicContainer.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 text-sm font-semibold mb-2">Error calculating Vedic Astrology charts</p>
                            <p class="text-red-700 text-sm">${errorMessage}</p>
                            <p class="text-red-600 text-xs mt-2">Please check: Time of Birth (HH:MM format), Country of Birth, and Town of Birth are all filled in correctly.</p>
                        </div>
                    `;
                }
            }

            async function getVedicChartData(birthDate, timeOfBirth, country, town) {
                try {
                    // Parse date
                    const [year, month, day] = birthDate.split('-').map(Number);
                    
                    // Call Laravel backend for Vedic astrology calculation
                    // Use global CSRF token or try to get it
                    const csrfToken = window.csrfToken || 
                                     document.querySelector('input[name="_token"]')?.value || 
                                     document.querySelector('meta[name="csrf-token"]')?.content;
                    
                    if (!csrfToken) {
                        throw new Error('CSRF token not found. Please refresh the page.');
                    }

                    const response = await fetch('{{ route("job1.vedic-astrology") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            year: year,
                            month: month,
                            day: day,
                            time: timeOfBirth,
                            country: country,
                            town: town,
                        })
                    });

                    let result;
                    try {
                        result = await response.json();
                    } catch (jsonError) {
                        console.error('JSON parse error:', jsonError);
                        const text = await response.text();
                        console.error('Response text:', text);
                        throw new Error('Invalid response from server. Please try again.');
                    }

                    if (!response.ok) {
                        const errorMsg = result.message || result.error || `Server error (${response.status})`;
                        console.error('Backend error:', {
                            status: response.status,
                            statusText: response.statusText,
                            result: result
                        });
                        throw new Error(errorMsg);
                    }

                    if (!result.success) {
                        const errorMsg = result.message || result.error || 'Error calculating Vedic astrology chart';
                        console.error('Backend returned success=false:', result);
                        throw new Error(errorMsg);
                    }

                    if (!result.data) {
                        console.error('No data in response:', result);
                        throw new Error('No data returned from server');
                    }

                    return result.data;

                } catch (error) {
                    console.error('Vedic Astrology Error:', error);
                    throw error;
                }
            }

            async function getCoordinates(country, town) {
                try {
                    // Use a geocoding service to get lat/long
                    const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(town + ', ' + country)}&limit=1`;
                    
                    const response = await fetch(geocodeUrl, {
                        headers: {
                            'User-Agent': 'LoNum Astrology App'
                        }
                    });
                    
                    const data = await response.json();
                    if (data && data.length > 0) {
                        return {
                            lat: parseFloat(data[0].lat),
                            lon: parseFloat(data[0].lon)
                        };
                    }
                    return null;
                } catch (error) {
                    console.error('Geocoding error:', error);
                    return null;
                }
            }

            async function calculateVedicChartLocal(birthDate, timeOfBirth, lat, lon) {
                // Fallback calculation using simplified methods
                // For production, use a proper ephemeris library
                const [year, month, day] = birthDate.split('-').map(Number);
                const [hours, minutes] = timeOfBirth.split(':').map(Number);

                // This is a simplified placeholder - in production, use Swiss Ephemeris or similar
                // For now, return structured data that can be replaced with real calculations
                const signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                              'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                
                // Simplified calculation - this would need proper ephemeris
                // Using approximate positions for demonstration
                const baseDate = new Date(year, month - 1, day, hours, minutes);
                const julianDay = getJulianDay(baseDate);
                
                // Calculate approximate planetary positions
                // Note: This is simplified - real calculations need proper ephemeris
                const planets = calculatePlanetaryPositions(julianDay, lat, lon);
                const ascendant = calculateAscendant(julianDay, lat, lon);
                
                return {
                    planets: planets,
                    ascendant: ascendant,
                    houses: calculateHouses(ascendant)
                };
            }

            function getJulianDay(date) {
                // Convert date to Julian Day Number
                const time = date.getTime();
                const jd = (time / 86400000) + 2440587.5;
                return jd;
            }

            function calculatePlanetaryPositions(julianDay, lat, lon) {
                // Simplified planetary position calculation
                // In production, use Swiss Ephemeris or similar library
                const signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                              'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                
                // Approximate positions based on date (simplified)
                // Real implementation would use ephemeris data
                const dayOfYear = Math.floor((julianDay - 2451545) % 365.25);
                
                // Calculate ascendant first to determine house assignments
                const ascendant = calculateAscendant(julianDay, lat, lon);
                const ascendantSignIndex = signs.indexOf(ascendant.sign);
                
                const planets = [
                    { name: 'Sun', sign: signs[Math.floor(dayOfYear / 30.44) % 12], degree: (dayOfYear % 30.44) * 0.985, nakshatra: getNakshatra((dayOfYear % 30.44) * 0.985) },
                    { name: 'Moon', sign: signs[Math.floor(dayOfYear / 2.5) % 12], degree: (dayOfYear % 2.5) * 13.2, nakshatra: getNakshatra((dayOfYear % 2.5) * 13.2) },
                    { name: 'Mars', sign: signs[Math.floor(dayOfYear / 55) % 12], degree: (dayOfYear % 55) * 0.524, nakshatra: getNakshatra((dayOfYear % 55) * 0.524) },
                    { name: 'Mercury', sign: signs[Math.floor(dayOfYear / 88) % 12], degree: (dayOfYear % 88) * 0.409, nakshatra: getNakshatra((dayOfYear % 88) * 0.409) },
                    { name: 'Jupiter', sign: signs[Math.floor(dayOfYear / 365) % 12], degree: (dayOfYear % 365) * 0.083, nakshatra: getNakshatra((dayOfYear % 365) * 0.083) },
                    { name: 'Venus', sign: signs[Math.floor(dayOfYear / 225) % 12], degree: (dayOfYear % 225) * 0.16, nakshatra: getNakshatra((dayOfYear % 225) * 0.16) },
                    { name: 'Saturn', sign: signs[Math.floor(dayOfYear / 10759) % 12], degree: (dayOfYear % 10759) * 0.033, nakshatra: getNakshatra((dayOfYear % 10759) * 0.033) },
                    { name: 'Rahu', sign: signs[Math.floor(dayOfYear / 6798) % 12], degree: (dayOfYear % 6798) * 0.053, nakshatra: getNakshatra((dayOfYear % 6798) * 0.053) },
                    { name: 'Ketu', sign: signs[(Math.floor(dayOfYear / 6798) + 6) % 12], degree: (dayOfYear % 6798) * 0.053, nakshatra: getNakshatra((dayOfYear % 6798) * 0.053) }
                ];

                // Assign houses based on sign and ascendant
                planets.forEach(planet => {
                    const planetSignIndex = signs.indexOf(planet.sign);
                    planet.house = getHouseForSign(planetSignIndex, ascendantSignIndex);
                });

                return planets;
            }

            function calculateAscendant(julianDay, lat, lon) {
                // Simplified ascendant calculation
                // Real implementation needs proper sidereal time calculation
                const signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                              'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                
                // Approximate ascendant based on time and location
                const localSiderealTime = calculateLocalSiderealTime(julianDay, lon);
                const ascendantDegrees = (localSiderealTime * 15 + lat) % 360;
                const signIndex = Math.floor(ascendantDegrees / 30);
                const degree = ascendantDegrees % 30;
                
                return {
                    sign: signs[signIndex],
                    degree: degree
                };
            }

            function calculateLocalSiderealTime(julianDay, lon) {
                // Simplified LST calculation
                const T = (julianDay - 2451545.0) / 36525.0;
                const theta = 280.46061837 + 360.98564736629 * (julianDay - 2451545.0) + T * T * (0.000387933 - T / 38710000.0);
                const lst = (theta + lon) / 15.0;
                return lst % 24;
            }

            function calculateHouses(ascendant) {
                // Calculate house cusps based on ascendant
                // Simplified - real calculation needs house system (Placidus, etc.)
                return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            }

            function getNakshatra(degree) {
                // 27 nakshatras, each ~13.33 degrees
                const nakshatras = [
                    'Ashwini', 'Bharani', 'Krittika', 'Rohini', 'Mrigashira', 'Ardra',
                    'Punarvasu', 'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni',
                    'Hasta', 'Chitra', 'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha',
                    'Mula', 'Purva Ashadha', 'Uttara Ashadha', 'Shravana', 'Dhanishta', 'Shatabhisha',
                    'Purva Bhadrapada', 'Uttara Bhadrapada', 'Revati'
                ];
                const nakshatraIndex = Math.floor(degree / 13.333333);
                return nakshatras[nakshatraIndex % 27];
            }

            function parseVedicAPIResponse(apiData) {
                // Parse API response into our format
                const signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                              'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                
                const planets = [];
                const planetMap = {
                    'Sun': 'Sun', 'Moon': 'Moon', 'Mars': 'Mars', 'Mercury': 'Mercury',
                    'Jupiter': 'Jupiter', 'Venus': 'Venus', 'Saturn': 'Saturn',
                    'Rahu': 'Rahu', 'Ketu': 'Ketu', 'True Node': 'Rahu', 'Mean Node': 'Rahu'
                };

                if (apiData.planets) {
                    Object.keys(apiData.planets).forEach(planetKey => {
                        const planet = apiData.planets[planetKey];
                        if (planetMap[planetKey]) {
                            const signIndex = Math.floor(planet.longitude / 30);
                            planets.push({
                                name: planetMap[planetKey],
                                sign: signs[signIndex],
                                degree: planet.longitude % 30,
                                nakshatra: getNakshatra(planet.longitude),
                                house: planet.house || 1
                            });
                        }
                    });
                }

                const ascendant = apiData.ascendant || { sign: 'Aries', degree: 0 };
                if (typeof ascendant.longitude !== 'undefined') {
                    const signIndex = Math.floor(ascendant.longitude / 30);
                    ascendant.sign = signs[signIndex];
                    ascendant.degree = ascendant.longitude % 30;
                }

                return {
                    planets: planets,
                    ascendant: ascendant,
                    houses: apiData.houses || [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                };
            }

            function displayVedicCharts(data, fullName, timeOfBirth, birthPlace) {
                const vedicContainer = document.getElementById('vedic-astrology-container');
                if (!vedicContainer) return;

                const signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                              'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
                
                // Find ascendant sign index
                const ascendantSignIndex = signs.indexOf(data.ascendant.sign);

                vedicContainer.innerHTML = `
                    <!-- Chart Information -->
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-700 mb-1"><span class="font-semibold">This chart is for:</span></p>
                        <p class="text-sm text-gray-800 mb-1"><span class="font-medium">Name:</span> ${fullName}</p>
                        <p class="text-sm text-gray-800 mb-1"><span class="font-medium">Time:</span> ${timeOfBirth}</p>
                        <p class="text-sm text-gray-800 mb-1"><span class="font-medium">Birth Place:</span> ${birthPlace}</p>
                        ${data.ayanamsa !== null && data.ayanamsa !== undefined ? `<p class="text-sm text-gray-800"><span class="font-medium">Ayanamsa:</span> ${data.ayanamsa.toFixed(6)}°</p>` : ''}
                    </div>
                    
                    <!-- Planetary Details -->
                    <div class="mb-6 overflow-x-auto">
                        <h4 class="text-md font-semibold mb-3">Planetary Details</h4>
                        <table class="w-full border-collapse border border-gray-300 text-xs">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 p-2 text-left font-semibold">Planet</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Retro</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Sign</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Degree</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Nakshatra</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">House</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-blue-50">
                                    <td class="border border-gray-300 p-2 font-semibold">Ascendant (Lagna)</td>
                                    <td class="border border-gray-300 p-2 text-center">-</td>
                                    <td class="border border-gray-300 p-2 text-center">${data.ascendant.computedSign || data.ascendant.sign}</td>
                                    <td class="border border-gray-300 p-2 text-center">${data.ascendant.computedDegree !== null && data.ascendant.computedDegree !== undefined ? data.ascendant.computedDegree.toFixed(2) + '°' : data.ascendant.degree.toFixed(2) + '°'}</td>
                                    <td class="border border-gray-300 p-2 text-center">${data.ascendant.nakshatra ? data.ascendant.nakshatra : '-'}</td>
                                    <td class="border border-gray-300 p-2 text-center">1</td>
                                </tr>
                                ${data.planets.map(planet => `
                                    <tr>
                                        <td class="border border-gray-300 p-2 font-semibold">${planet.name}</td>
                                        <td class="border border-gray-300 p-2 text-center">${planet.isRetro ? 'R' : '-'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${planet.computedSign || planet.sign}</td>
                                        <td class="border border-gray-300 p-2 text-center">${planet.computedDegree !== null && planet.computedDegree !== undefined ? planet.computedDegree.toFixed(2) + '°' : planet.degree.toFixed(2) + '°'}</td>
                                        <td class="border border-gray-300 p-2 text-center">${planet.nakshatra}</td>
                                        <td class="border border-gray-300 p-2 text-center">${planet.house || '-'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            function renderNorthIndianChart(data, signs, ascendantIndex) {
                // North Indian chart: Diamond style with all 12 houses
                // Houses rotate based on ascendant
                const houseOrder = [];
                for (let i = 0; i < 12; i++) {
                    houseOrder.push((ascendantIndex + i) % 12);
                }

                // Create diamond layout with all 12 houses
                let html = '<div class="relative" style="width: 500px; height: 500px; margin: 0 auto;">';
                
                // House positions in diamond layout (starting from top, going clockwise)
                const housePositions = [
                    { house: 7, angle: 0, x: 50, y: 5 },      // Top
                    { house: 8, angle: 30, x: 75, y: 15 },    // Top-right
                    { house: 9, angle: 60, x: 90, y: 30 },    // Right-top
                    { house: 10, angle: 90, x: 95, y: 50 },  // Right
                    { house: 11, angle: 120, x: 90, y: 70 }, // Right-bottom
                    { house: 12, angle: 150, x: 75, y: 85 }, // Bottom-right
                    { house: 1, angle: 180, x: 50, y: 90 },  // Bottom (Ascendant)
                    { house: 2, angle: 210, x: 25, y: 85 },  // Bottom-left
                    { house: 3, angle: 240, x: 10, y: 70 },   // Left-bottom
                    { house: 4, angle: 270, x: 5, y: 50 },    // Left
                    { house: 5, angle: 300, x: 10, y: 30 },  // Left-top
                    { house: 6, angle: 330, x: 25, y: 15 }   // Top-left
                ];

                housePositions.forEach(pos => {
                    const signIdx = houseOrder[pos.house - 1];
                    const isAscendant = pos.house === 1;
                    const borderClass = isAscendant ? 'border-blue-500 bg-blue-50' : 'border-gray-800 bg-white';
                    const houseNumClass = isAscendant ? 'text-blue-700 font-bold' : 'font-bold';
                    
                    html += `
                        <div class="absolute border-2 ${borderClass} p-1 text-center" 
                             style="width: 70px; height: 50px; left: ${pos.x}%; top: ${pos.y}%; transform: translate(-50%, -50%);">
                            <div class="text-xs ${houseNumClass}">${pos.house}</div>
                            <div class="text-xs">${signs[signIdx]}</div>
                            <div class="text-xs">${getPlanetsInHouse(data, pos.house)}</div>
                        </div>
                    `;
                });

                // Center showing ascendant
                html += `
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 border-2 border-blue-500 bg-blue-100 p-2 text-center rounded-full" 
                         style="width: 80px; height: 80px;">
                        <div class="text-xs font-bold text-blue-700">Asc</div>
                        <div class="text-xs text-blue-700">${data.ascendant.sign}</div>
                        <div class="text-xs text-blue-700">${data.ascendant.degree.toFixed(1)}°</div>
                    </div>
                `;

                html += '</div>';
                return html;
            }

            function renderSouthIndianChart(data, signs, ascendantIndex) {
                // South Indian chart: Fixed square grid with signs
                // Signs are fixed, houses rotate based on ascendant
                let html = '<div class="grid grid-cols-4 gap-1 max-w-2xl mx-auto">';
                
                // Fixed sign layout in South Indian style (4x4 grid)
                // Outer ring: signs in fixed positions
                const gridLayout = [
                    [null, 8, 9, 10, null],   // Top row
                    [7, null, null, null, 11], // Right side
                    [6, null, null, null, 0], // Right side
                    [5, null, null, null, 1], // Right side
                    [null, 4, 3, 2, null]     // Bottom row
                ];

                gridLayout.forEach((row) => {
                    row.forEach((signIdx) => {
                        if (signIdx === null) {
                            html += '<div class="p-2"></div>';
                        } else {
                            const signName = signs[signIdx];
                            const isAscendant = signIdx === ascendantIndex;
                            const planets = getPlanetsInSign(data, signName);
                            const houseNum = getHouseForSign(signIdx, ascendantIndex);
                            
                            html += `
                                <div class="border-2 ${isAscendant ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-white'} p-2 text-center min-h-[90px] flex flex-col justify-center">
                                    <div class="text-xs font-bold mb-1">${signName}</div>
                                    ${houseNum ? `<div class="text-xs text-gray-600 mb-1">H${houseNum}</div>` : ''}
                                    <div class="text-xs">${planets}</div>
                                </div>
                            `;
                        }
                    });
                });

                html += '</div>';
                return html;
            }

            function getPlanetsInHouse(data, houseNum) {
                const planets = data.planets.filter(p => p.house === houseNum);
                if (planets.length === 0) return '<span class="text-xs text-gray-400">-</span>';
                return planets.map(p => {
                    const abbrev = p.name.substring(0, 3);
                    return `<span class="text-xs font-semibold mx-0.5">${abbrev}</span>`;
                }).join('');
            }

            function getPlanetsInSign(data, signName) {
                const planets = data.planets.filter(p => p.sign === signName);
                if (planets.length === 0) return '<div class="text-xs text-gray-400">-</div>';
                return planets.map(p => `<div class="text-xs font-semibold">${p.name.substring(0, 3)}</div>`).join('');
            }

            function getHouseForSign(signIndex, ascendantIndex) {
                // Calculate which house this sign is in based on ascendant
                let house = ((signIndex - ascendantIndex + 12) % 12) + 1;
                return house;
            }

            if (calculateButton) {
                calculateButton.addEventListener('click', function () {
                    // Clear any previous error messages
                    clearErrors();
                    messageContainer.innerHTML = '';

                    const dateOfBirth = getDateFromFields();
                    const name = document.getElementById('name').value;
                    const middleName = document.getElementById('middle_name').value;
                    const surname = document.getElementById('surname').value;

                    if (!dateOfBirth) {
                        showMessage('Please enter a complete date of birth (Year, Month, and Date).', 'error');
                        // Show error on date fields
                        const dateOfBirthErrorEl = document.getElementById('date_of_birth-error');
                        const dobYearEl = document.getElementById('dob_year');
                        const dobMonthEl = document.getElementById('dob_month');
                        const dobDayEl = document.getElementById('dob_day');

                        if (dateOfBirthErrorEl) {
                            dateOfBirthErrorEl.textContent = 'Please enter Year, Month, and Date';
                            dateOfBirthErrorEl.classList.remove('hidden');
                        }
                        [dobYearEl, dobMonthEl, dobDayEl].forEach(field => {
                            if (field) {
                                field.classList.remove('border-gray-300');
                                field.classList.add('border-red-500');
                            }
                        });
                        return;
                    }

                    const sexVal = (document.querySelector('input[name="sex"]:checked') || {}).value || '';
                    const loShuData = calculateLoShuGrid(dateOfBirth, name, middleName, surname, sexVal);
                    displayLoShuGrid(loShuData);
                });
            }

            // Form submission
            const form = document.getElementById('client-form');
            const messageContainer = document.getElementById('message-container');
            const saveButton = document.getElementById('save-button');
            const loadButton = document.getElementById('load-button');
            const deleteButton = document.getElementById('delete-button');
            const currentClientId = document.getElementById('current-client-id');
            const clientModal = document.getElementById('client-modal');
            const closeModal = document.getElementById('close-modal');
            const clientsList = document.getElementById('clients-list');
            const hasRoles = {{ ($hasRoles ?? true) ? 'true' : 'false' }};

            function showMessage(message, type) {
                messageContainer.innerHTML = `
                    <div class="p-4 rounded-md ${type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'}">
                        ${message}
                    </div>
                `;
                messageContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function clearErrors() {
                document.querySelectorAll('[id$="-error"]').forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500');
                    el.classList.add('border-gray-300');
                });
            }

            function showFieldError(fieldName, message) {
                const field = document.getElementById(fieldName);
                const errorDiv = document.getElementById(fieldName + '-error');

                if (field) {
                    field.classList.remove('border-gray-300');
                    field.classList.add('border-red-500');
                }

                // If date_of_birth error, also style the individual date fields
                if (fieldName === 'date_of_birth') {
                    [dobYear, dobMonth, dobDay].forEach(dateField => {
                        if (dateField) {
                            dateField.classList.remove('border-gray-300');
                            dateField.classList.add('border-red-500');
                        }
                    });
                }

                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.classList.remove('hidden');
                }
            }

            // Countries / Cities dynamic loader
            let _countriesData = null;

            async function loadCountries() {
                const countrySelect = document.getElementById('country_of_birth');
                const townSelect = document.getElementById('town_of_birth');

                try {
                    const resp = await fetch('https://countriesnow.space/api/v0.1/countries');
                    const json = await resp.json();
                    if (json && json.data && Array.isArray(json.data)) {
                        _countriesData = json.data; // [{ country: 'Afghanistan', cities: [...] }, ...]
                        countrySelect.innerHTML = '<option value="">Select country</option>';
                        _countriesData.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.country;
                            opt.textContent = c.country;
                            countrySelect.appendChild(opt);
                        });
                        countrySelect.disabled = false;
                    } else {
                        throw new Error('Unexpected countries payload');
                    }
                } catch (err) {
                    // Fallback: fetch a simple list of countries from restcountries
                    try {
                        const resp2 = await fetch('https://restcountries.com/v3.1/all');
                        const json2 = await resp2.json();
                        const names = json2.map(c => c.name.common).sort();
                        countrySelect.innerHTML = '<option value="">Select country</option>';
                        names.forEach(name => {
                            const opt = document.createElement('option');
                            opt.value = name;
                            opt.textContent = name;
                            countrySelect.appendChild(opt);
                        });
                        countrySelect.disabled = false;
                        _countriesData = null; // city lists not available
                    } catch (err2) {
                        countrySelect.innerHTML = '<option value="">Country list unavailable</option>';
                        countrySelect.disabled = true;
                    }
                }

                // Wire change handler
                countrySelect.addEventListener('change', function () {
                    populateCitiesFor(this.value);
                });
            }

            async function populateCitiesFor(country, selectedTown = null) {
                const townSelect = document.getElementById('town_of_birth');
                if (!townSelect) return;

                townSelect.innerHTML = '<option value="">Loading...</option>';

                // If we have the countries data with cities, use it
                if (_countriesData) {
                    const entry = _countriesData.find(c => c.country.toLowerCase() === (country || '').toLowerCase());
                    if (entry && Array.isArray(entry.cities) && entry.cities.length > 0) {
                        townSelect.innerHTML = '<option value="">Select town/city</option>';
                        entry.cities.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city;
                            opt.textContent = city;
                            townSelect.appendChild(opt);
                        });
                        if (selectedTown) townSelect.value = selectedTown;
                        return;
                    }
                }

                // Fallback: call countriesnow API endpoint for cities of a specific country
                try {
                    const resp = await fetch('https://countriesnow.space/api/v0.1/countries/cities', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ country })
                    });
                    const json = await resp.json();
                    if (json && json.data && Array.isArray(json.data) && json.data.length > 0) {
                        townSelect.innerHTML = '<option value="">Select town/city</option>';
                        json.data.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city;
                            opt.textContent = city;
                            townSelect.appendChild(opt);
                        });
                        if (selectedTown) townSelect.value = selectedTown;
                        return;
                    }
                } catch (err) {
                    // ignore and fallback
                }

                // Final fallback: no city list available
                townSelect.innerHTML = '<option value="">(No city list available)</option>';
                if (selectedTown) {
                    const opt = document.createElement('option');
                    opt.value = selectedTown;
                    opt.textContent = selectedTown;
                    townSelect.appendChild(opt);
                    townSelect.value = selectedTown;
                }
            }

            // Convert decimal degrees to degrees, minutes, seconds format
            function decimalToDMS(decimal, isLatitude) {
                const absolute = Math.abs(decimal);
                const degrees = Math.floor(absolute);
                const minutesFloat = (absolute - degrees) * 60;
                const minutes = Math.floor(minutesFloat);
                const seconds = Math.round((minutesFloat - minutes) * 60 * 100) / 100; // Round to 2 decimal places
                
                const direction = isLatitude 
                    ? (decimal >= 0 ? 'N' : 'S')
                    : (decimal >= 0 ? 'E' : 'W');
                
                return `${degrees}° ${minutes}' ${seconds.toFixed(2)}" ${direction}`;
            }

            // Fetch and display coordinates for selected town
            async function updateCoordinatesDisplay() {
                const townSelect = document.getElementById('town_of_birth');
                const countrySelect = document.getElementById('country_of_birth');
                const latDisplay = document.getElementById('latitude_display');
                const lonDisplay = document.getElementById('longitude_display');
                
                if (!townSelect || !countrySelect || !latDisplay || !lonDisplay) return;
                
                const town = townSelect.value;
                const country = countrySelect.value;
                
                if (!town || !country) {
                    latDisplay.textContent = '-';
                    lonDisplay.textContent = '-';
                    return;
                }
                
                // Show loading state
                latDisplay.textContent = 'Loading...';
                lonDisplay.textContent = 'Loading...';
                
                try {
                    const coords = await getCoordinates(country, town);
                    if (coords && coords.lat !== undefined && coords.lon !== undefined) {
                        latDisplay.textContent = decimalToDMS(coords.lat, true);
                        lonDisplay.textContent = decimalToDMS(coords.lon, false);
                    } else {
                        latDisplay.textContent = 'Not found';
                        lonDisplay.textContent = 'Not found';
                    }
                } catch (error) {
                    console.error('Error fetching coordinates:', error);
                    latDisplay.textContent = 'Error';
                    lonDisplay.textContent = 'Error';
                }
            }

            // Initialize country list on page load
            loadCountries();

            // Wire town select change handler to update coordinates (after DOM is ready)
            function setupCoordinateListeners() {
                const townSelect = document.getElementById('town_of_birth');
                const countrySelect = document.getElementById('country_of_birth');
                
                if (townSelect && !townSelect.hasAttribute('data-coord-listener')) {
                    townSelect.addEventListener('change', updateCoordinatesDisplay);
                    townSelect.setAttribute('data-coord-listener', 'true');
                }
                
                if (countrySelect && !countrySelect.hasAttribute('data-coord-listener')) {
                    countrySelect.addEventListener('change', function() {
                        // Clear coordinates when country changes
                        const latDisplay = document.getElementById('latitude_display');
                        const lonDisplay = document.getElementById('longitude_display');
                        if (latDisplay) latDisplay.textContent = '-';
                        if (lonDisplay) lonDisplay.textContent = '-';
                    });
                    countrySelect.setAttribute('data-coord-listener', 'true');
                }
            }

            // Set up listeners when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupCoordinateListeners);
            } else {
                setupCoordinateListeners();
            }

            // Clear date of birth error when date fields are filled
            const dobYear = document.getElementById('dob_year');
            const dobMonth = document.getElementById('dob_month');
            const dobDay = document.getElementById('dob_day');
            const dateOfBirthError = document.getElementById('date_of_birth-error');

            function clearDateOfBirthError() {
                if (dateOfBirthError) {
                    dateOfBirthError.classList.add('hidden');
                    dateOfBirthError.textContent = '';
                }
                // Clear error styling from date fields
                [dobYear, dobMonth, dobDay].forEach(field => {
                    if (field) {
                        field.classList.remove('border-red-500');
                        field.classList.add('border-gray-300');
                    }
                });
            }

            if (dobYear) {
                dobYear.addEventListener('input', clearDateOfBirthError);
                dobYear.addEventListener('change', clearDateOfBirthError);
            }
            if (dobMonth) {
                dobMonth.addEventListener('input', clearDateOfBirthError);
                dobMonth.addEventListener('change', clearDateOfBirthError);
            }
            if (dobDay) {
                dobDay.addEventListener('input', clearDateOfBirthError);
                dobDay.addEventListener('change', clearDateOfBirthError);
            }

            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    // Users without roles cannot save
                    if (!hasRoles) {
                        showMessage('You do not have permission to save clients. Please contact an administrator to assign you a role.', 'error');
                        return;
                    }

                    clearErrors();
                    messageContainer.innerHTML = '';

                    // Combine date fields into date_of_birth before submission
                    const dateOfBirth = getDateFromFields();
                    if (!dateOfBirth) {
                        showMessage('Please enter a complete date of birth (Year, Month, and Date).', 'error');
                        return;
                    }
                    document.getElementById('date_of_birth').value = dateOfBirth;

                    const formData = new FormData(form);
                    const originalButtonText = saveButton ? saveButton.textContent : 'Save';
                    if (saveButton) {
                        saveButton.disabled = true;
                        saveButton.textContent = 'Saving...';
                    }

                    try {
                        const response = await fetch('{{ route("job1.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        // If duplicate SaveAs, ask user whether to overwrite
                        if (!response.ok && data && data.duplicate) {
                            const confirmOverwrite = window.confirm(data.message || 'A client with this SaveAs already exists. Do you want to overwrite it?');

                            if (confirmOverwrite) {
                                const overwriteFormData = new FormData(form);
                                overwriteFormData.append('overwrite', '1');

                                const overwriteResponse = await fetch('{{ route("job1.store") }}', {
                                    method: 'POST',
                                    body: overwriteFormData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                    }
                                });

                                const overwriteData = await overwriteResponse.json();

                                if (overwriteResponse.ok && overwriteData.success) {
                                    showMessage(overwriteData.message || 'Existing client overwritten successfully.', 'success');
                                    clearErrors();
                                    if (deleteButton) {
                                        deleteButton.classList.add('hidden');
                                    }
                                    if (currentClientId) {
                                        currentClientId.value = '';
                                    }
                                } else {
                                    if (overwriteData && overwriteData.errors) {
                                        Object.keys(overwriteData.errors).forEach(field => {
                                            const fieldName = field;
                                            const errorMessage = Array.isArray(overwriteData.errors[field])
                                                ? overwriteData.errors[field][0]
                                                : overwriteData.errors[field];
                                            showFieldError(fieldName, errorMessage);
                                        });
                                        showMessage('Please correct the errors in the form.', 'error');
                                    } else {
                                        showMessage(overwriteData.message || 'An error occurred while overwriting the client.', 'error');
                                    }
                                }
                            } else {
                                showMessage('Please change the SaveAs value and try again.', 'error');
                            }
                        } else if (response.ok && data.success) {
                            showMessage(data.message || 'Client saved successfully.', 'success');
                            clearErrors();
                            // Hide delete button after saving new/overwritten client
                            if (deleteButton) {
                                deleteButton.classList.add('hidden');
                            }
                            if (currentClientId) {
                                currentClientId.value = '';
                            }
                        } else {
                            if (data && data.errors) {
                                // Handle validation errors
                                Object.keys(data.errors).forEach(field => {
                                    const fieldName = field;
                                    const errorMessage = Array.isArray(data.errors[field])
                                        ? data.errors[field][0]
                                        : data.errors[field];
                                    showFieldError(fieldName, errorMessage);
                                });

                                showMessage('Please correct the errors in the form.', 'error');
                            } else {
                                showMessage(data && data.message ? data.message : 'An error occurred while saving the client.', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showMessage('An error occurred while saving the client. Please try again.', 'error');
                    } finally {
                        if (saveButton) {
                            saveButton.disabled = false;
                            saveButton.textContent = originalButtonText;
                        }
                    }
                });
            }

            // Load clients functionality
            if (loadButton) {
                loadButton.addEventListener('click', async function () {
                    // Users without roles cannot load
                    if (!hasRoles) {
                        showMessage('You do not have permission to load clients. Please contact an administrator to assign you a role.', 'error');
                        return;
                    }

                    clientsList.innerHTML = '<div class="text-center text-gray-500 py-8">Loading clients...</div>';
                    clientModal.classList.remove('hidden');

                    try {
                        const response = await fetch('{{ route("job1.clients") }}', {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            if (data.clients && data.clients.length > 0) {
                                clientsList.innerHTML = data.clients.map(client => {
                                    const fullName = [
                                        client.name,
                                        client.middle_name,
                                        client.surname
                                    ].filter(Boolean).join(' ');

                                    // Format date for form input (YYYY-MM-DD) - avoid timezone issues
                                    let dateForInput = '';
                                    let dob = 'N/A';

                                    if (client.date_of_birth) {
                                        // Extract date part (YYYY-MM-DD) from the date string
                                        // This avoids timezone conversion issues
                                        const dateStr = client.date_of_birth.split('T')[0];
                                        const dateParts = dateStr.split('-');

                                        if (dateParts.length === 3) {
                                            // Use the date string directly to avoid timezone conversion
                                            dateForInput = dateStr;

                                            // Format for display using the same date parts
                                            const year = dateParts[0];
                                            const month = dateParts[1];
                                            const day = dateParts[2];
                                            // Create date in local timezone for display
                                            const displayDate = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
                                            dob = displayDate.toLocaleDateString();
                                        } else {
                                            // Fallback if format is unexpected
                                            dateForInput = dateStr;
                                            dob = dateStr;
                                        }
                                    }

                                    const timeForInput = client.time_of_birth || '';
                                    const sexForInput = client.sex || '';
                                    const countryForInput = client.country_of_birth || '';
                                    const townForInput = client.town_of_birth || '';

                                    return `
                                        <div 
                                            class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors client-item"
                                            data-client-id="${client.id}"
                                            data-name="${client.name || ''}"
                                            data-middle-name="${client.middle_name || ''}"
                                            data-surname="${client.surname || ''}"
                                            data-date-of-birth="${dateForInput}"
                                            data-time-of-birth="${timeForInput}"
                                            data-sex="${sexForInput}"
                                            data-country-of-birth="${countryForInput}"
                                            data-town-of-birth="${townForInput}"
                                            data-save-as="${client.save_as || ''}"
                                        >
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">${fullName}</h3>
                                                    <p class="text-sm text-gray-600 mt-1">SaveAs: <span class="font-medium">${client.save_as}</span></p>
                                                    <p class="text-sm text-gray-500 mt-1">Date of Birth: ${dob}${timeForInput ? ' ' + timeForInput : ''}</p>
                                                    ${sexForInput ? `<p class="text-sm text-gray-500 mt-1">Sex: ${sexForInput}</p>` : ''}
                                                    ${townForInput ? `<p class="text-sm text-gray-500 mt-1">Town: ${townForInput}</p>` : ''}
                                                    ${countryForInput ? `<p class="text-sm text-gray-500 mt-1">Country: ${countryForInput}</p>` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('');

                                // Add click handlers to client items
                                document.querySelectorAll('.client-item').forEach(item => {
                                    item.addEventListener('click', async function () {
                                        const clientId = this.getAttribute('data-client-id');
                                        const name = this.getAttribute('data-name');
                                        const middleName = this.getAttribute('data-middle-name');
                                        const surname = this.getAttribute('data-surname');
                                        const dateOfBirth = this.getAttribute('data-date-of-birth');
                                        const timeOfBirth = this.getAttribute('data-time-of-birth');
                                        const sex = this.getAttribute('data-sex');
                                        const countryOfBirth = this.getAttribute('data-country-of-birth');
                                        const townOfBirth = this.getAttribute('data-town-of-birth');
                                        const saveAs = this.getAttribute('data-save-as');

                                        // Clear existing Lo Shu grid/astrology display so Calculate must be pressed
                                        if (loShuContainer) {
                                            loShuContainer.innerHTML = `
                                                <div class="text-gray-500 text-center py-8">
                                                    Click "Calculate" to generate the Lo Shu grid from the date of birth.
                                                </div>
                                            `;
                                        }

                                        // Populate form fields
                                        document.getElementById('name').value = name || '';
                                        document.getElementById('middle_name').value = middleName || '';
                                        document.getElementById('surname').value = surname || '';
                                        const saveAsField = document.getElementById('save_as');
                                        if (saveAsField) {
                                            saveAsField.value = saveAs || '';
                                        }

                                        // Split date and populate three fields
                                        if (dateOfBirth) {
                                            const dateParts = dateOfBirth.split('-');
                                            if (dateParts.length === 3) {
                                                document.getElementById('dob_year').value = dateParts[0] || '';
                                                document.getElementById('dob_month').value = parseInt(dateParts[1], 10) || '';
                                                document.getElementById('dob_day').value = parseInt(dateParts[2], 10) || '';
                                            }
                                        }

                                        // Populate time
                                        if (timeOfBirth) {
                                            const timeField = document.getElementById('time_of_birth');
                                            if (timeField) {
                                                timeField.value = timeOfBirth.length >= 5 ? timeOfBirth.substring(0, 5) : timeOfBirth;
                                            }
                                        }

                                        // Populate sex (radio inputs)
                                        if (sex) {
                                            const sexRadio = document.querySelector(`input[name="sex"][value="${sex}"]`);
                                            if (sexRadio) sexRadio.checked = true;
                                        }

                                        // Populate country and town (await city population when needed)
                                        if (countryOfBirth) {
                                            const countryField = document.getElementById('country_of_birth');
                                            if (countryField) {
                                                countryField.value = countryOfBirth;
                                                if (typeof populateCitiesFor === 'function') {
                                                    await populateCitiesFor(countryOfBirth, townOfBirth);
                                                }
                                                // Update coordinates display after town is set
                                                if (typeof updateCoordinatesDisplay === 'function') {
                                                    await updateCoordinatesDisplay();
                                                }
                                            }
                                        } else if (townOfBirth) {
                                            const townField = document.getElementById('town_of_birth');
                                            if (townField) townField.value = townOfBirth;
                                            // Update coordinates display after town is set
                                            if (typeof updateCoordinatesDisplay === 'function') {
                                                await updateCoordinatesDisplay();
                                            }
                                        }

                                        // Store client ID and show delete button
                                        if (currentClientId) {
                                            currentClientId.value = clientId;
                                        }
                                        if (deleteButton) {
                                            deleteButton.classList.remove('hidden');
                                        }

                                        // Close modal
                                        clientModal.classList.add('hidden');
                                        clearErrors();

                                        // Show success message
                                        showMessage('Client loaded successfully.', 'success');
                                    });
                                });
                            } else {
                                clientsList.innerHTML = '<div class="text-center text-gray-500 py-8">No clients found. Save a client first.</div>';
                            }
                        } else {
                            clientsList.innerHTML = '<div class="text-center text-red-500 py-8">Error loading clients. Please try again.</div>';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        clientsList.innerHTML = '<div class="text-center text-red-500 py-8">An error occurred while loading clients. Please try again.</div>';
                    }
                });
            }

            // Close modal functionality
            if (closeModal) {
                closeModal.addEventListener('click', function () {
                    clientModal.classList.add('hidden');
                });
            }

            // Close modal when clicking outside
            if (clientModal) {
                clientModal.addEventListener('click', function (e) {
                    if (e.target === clientModal) {
                        clientModal.classList.add('hidden');
                    }
                });
            }

            // Delete client functionality
            if (deleteButton) {
                deleteButton.addEventListener('click', async function () {
                    const clientId = currentClientId ? currentClientId.value : null;

                    if (!clientId) {
                        showMessage('No client selected to delete.', 'error');
                        return;
                    }

                    // Confirm deletion
                    if (!confirm('Are you sure you want to delete this client? This action cannot be undone.')) {
                        return;
                    }

                    const originalButtonText = deleteButton.textContent;
                    deleteButton.disabled = true;
                    deleteButton.textContent = 'Deleting...';

                    try {
                        const deleteUrl = `/job1/${clientId}`;
                        const response = await fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            showMessage(data.message || 'Client deleted successfully.', 'success');
                            form.reset();
                            clearErrors();
                            deleteButton.classList.add('hidden');
                            if (currentClientId) {
                                currentClientId.value = '';
                            }
                            // Clear Lo Shu grid and related astrology output
                            if (loShuContainer) {
                                loShuContainer.innerHTML = `
                                    <div class="text-gray-500 text-center py-8">
                                        Click "Calculate" to generate the Lo Shu grid from the date of birth.
                                    </div>
                                `;
                            }
                        } else {
                            showMessage(data.message || 'An error occurred while deleting the client.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showMessage('An error occurred while deleting the client. Please try again.', 'error');
                    } finally {
                        deleteButton.disabled = false;
                        deleteButton.textContent = originalButtonText;
                    }
                });
            }
        });
    </script>
@endsection