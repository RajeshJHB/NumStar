@extends('layouts.app')

@section('title', 'Help - NumStar')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Help & User Guide</h1>

        <div class="space-y-6">
            <!-- Navigation -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Navigation</h2>
                <p class="text-gray-700 mb-4">
                    To navigate back to the home screen (Lo Shu calculator) from any page, simply click on the
                    <strong>"NumStar"</strong> text in the top-left corner of the navigation bar. This will take you back to
                    the main calculator page.
                </p>
                <p class="text-gray-700">
                    You can also access the <strong>"About"</strong> and <strong>"Help"</strong> pages from the navigation
                    bar at any time.
                </p>
            </div>

            <!-- Getting Started -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Getting Started</h2>
                <p class="text-gray-700 mb-4">
                    NumStar helps you calculate and analyze numerology and Vedic astrology information. Follow these steps
                    to get started:
                </p>
                <ol class="list-decimal list-inside text-gray-700 space-y-2 ml-4">
                    <li>Enter the person's name (first name, middle name, and surname)</li>
                    <li>Enter the date of birth (year, month, and day)</li>
                    <li>Optionally enter time of birth, sex, country, and town for Vedic astrology calculations</li>
                    <li>Click the "Calculate" button to generate the Lo Shu grid and numerology analysis</li>
                </ol>
            </div>

            <!-- Lo Shu Grid -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Lo Shu Grid</h2>
                <p class="text-gray-700 mb-4">
                    The Lo Shu Grid is a 3x3 magic square that reveals patterns in your life based on your date of birth and
                    name. Each square represents a planet and shows how many times certain numbers appear in your birth date
                    and name calculations.
                </p>
                <p class="text-gray-700">
                    The grid displays numbers that appear in your Conductor, Driver Number (Destiny Number), and other
                    numerology calculations. Missing numbers indicate areas that may need attention or development.
                </p>
            </div>

            <!-- Numerology Numbers -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Numerology Numbers Explained</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Conductor Number</h3>
                        <p class="text-gray-700">Calculated from your full date of birth. It represents your life's journey
                            and core personality traits.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Destiny Number (Full Name)</h3>
                        <p class="text-gray-700">Derived from all letters in your full name. It indicates your life's
                            purpose and potential.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Soul Urge Number (Name Vowels)</h3>
                        <p class="text-gray-700">Calculated from vowels in your name. It reveals your inner desires and
                            motivations.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Personality Number (Name Consonants)</h3>
                        <p class="text-gray-700">Derived from consonants in your name. It shows how others perceive you.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Birthday Number</h3>
                        <p class="text-gray-700">The day of the month you were born. It represents your natural talents and
                            abilities.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Maturity Number</h3>
                        <p class="text-gray-700">Sum of Conductor and Destiny Number. It indicates your potential in later
                            life.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Kua Number</h3>
                        <p class="text-gray-700">Used in Feng Shui, calculated differently for males and females based on
                            birth year. Helps determine favorable directions.</p>
                    </div>
                </div>
            </div>

            <!-- Power Numbers -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Power Numbers</h2>
                <p class="text-gray-700 mb-4">
                    Master numbers (11, 22, 33) are special numbers that are not reduced to a single digit. They represent
                    higher spiritual potential and are displayed in the "Power" column when they appear in calculations.
                </p>
            </div>

            <!-- Vedic Astrology -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Vedic Astrology</h2>
                <p class="text-gray-700 mb-4">
                    To calculate Vedic astrology charts, you need to provide:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Time of Birth (in HH:MM format, 24-hour)</li>
                    <li>Country of Birth</li>
                    <li>Town/City of Birth</li>
                </ul>
                <p class="text-gray-700 mt-4">
                    The system will display planetary positions, signs, degrees, nakshatras, and houses based on Vedic
                    astrology calculations.
                </p>
            </div>

            <!-- Saving Clients -->
            <div class="border-b pb-6">
                <h2 class="text-2xl font-semibold mb-4">Saving and Managing Clients</h2>
                <p class="text-gray-700 mb-4">
                    If you have a user account with appropriate user level, you can save client information:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                    <li>Enter a unique "SaveAs" identifier for each client</li>
                    <li>Click "Save" to store the client information</li>
                    <li>Use "Load" to retrieve and display saved client data</li>
                    <li>Use "Delete" to remove saved clients</li>
                </ul>
                <p class="text-gray-700 mt-4">
                    <strong>Note:</strong> Different user roles have different limits on the number of clients they can
                    save:
                </p>
                <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                    <li>Default User : Up to 3 clients</li>
                    <li>Standard User: Up to 10 clients</li>
                    <li>Premium User : Up to 50 clients</li>
                </ul>
                <p class="text-gray-700">
                    To upgrade to Standard or Premium please contact: <a href="mailto:numstar@tvrgod.com"
                        class="text-blue-600 hover:underline">numstar@tvrgod.com</a>
                </p>
            </div>

            <!-- Troubleshooting -->
            <div>
                <h2 class="text-2xl font-semibold mb-4">Troubleshooting</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Calculation not working?</h3>
                        <p class="text-gray-700">Make sure you've entered a valid date of birth (year, month, and day). All
                            three fields are required.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Vedic Astrology not displaying?</h3>
                        <p class="text-gray-700">Ensure you've filled in Time of Birth (HH:MM format), Country of Birth, and
                            Town of Birth. All three are required for Vedic calculations.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Can't save clients?</h3>
                        <p class="text-gray-700">You need to be logged in and have an assigned role. If you don't have a
                            role, contact an administrator or register for an account.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h2 class="text-xl font-semibold mb-2">Need More Help?</h2>
                <p class="text-gray-700">
                    If you need additional assistance, please contact us at: <a href="mailto:numstar@tvrgod.com"
                        class="text-blue-600 hover:underline font-semibold">numstar@tvrgod.com</a>
                </p>
            </div>
        </div>
    </div>
@endsection