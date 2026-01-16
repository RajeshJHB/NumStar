<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    public function job1(): View
    {
        $user = request()->user();
        $hasRoles = false;
        
        if ($user) {
            $user->load('roles');
            $hasRoles = $user->roles->count() > 0;
        }
        
        return view('job1', [
            'hasRoles' => $hasRoles,
        ]);
    }

    public function store(ClientStoreRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to save clients.',
                ], 401);
            }
            return redirect()->route('job1')->with('error', 'You must be logged in to save clients.');
        }
        
        $user->load('roles');
        
        // Users without roles cannot save clients
        if ($user->roles->count() === 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to save clients. Please contact an administrator to assign you a role.',
                ], 403);
            }
            return redirect()->route('job1')->with('error', 'You do not have permission to save clients.');
        }
        
        try {
            $overwrite = (bool) $request->input('overwrite', false);

            // Check for existing client with same SaveAs for this user
            $existingClient = Client::where('user_id', $user->id)
                ->where('save_as', $request->save_as)
                ->first();

            // Check client limits based on user roles (Role Managers are exempt)
            if (!$user->isRoleManager()) {
                $clientCount = Client::where('user_id', $user->id)->count();
                
                // If overwriting, don't count the existing client
                if ($existingClient && $overwrite) {
                    $clientCount = $clientCount - 1;
                }
                
                $maxClients = null;
                $errorMessage = null;
                
                // Determine limit based on role (highest role takes precedence)
                if ($user->hasRole4()) {
                    $maxClients = 50;
                    $errorMessage = 'You have reached your maximum of 50 clients. To save more clients, please contact numstar@tvrgod.com';
                } elseif ($user->hasRole3()) {
                    $maxClients = 10;
                    $errorMessage = 'You have reached your maximum of 10 clients. To save more clients, please contact numstar@tvrgod.com';
                } elseif ($user->hasRole2()) {
                    $maxClients = 3;
                    $errorMessage = 'You have reached your maximum of 3 clients. To save more clients, please contact numstar@tvrgod.com';
                }
                
                // Check if limit is reached
                if ($maxClients !== null && $clientCount >= $maxClients && !$existingClient) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage,
                        ], 403);
                    }
                    return redirect()->route('job1')->with('error', $errorMessage);
                }
            }

            if ($existingClient && !$overwrite) {
                // Duplicate found, prompt client-side for overwrite
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'duplicate' => true,
                        'message' => 'A client with this SaveAs already exists. Do you want to overwrite it?',
                        'client' => [
                            'id' => $existingClient->id,
                            'name' => $existingClient->name,
                            'middle_name' => $existingClient->middle_name,
                            'surname' => $existingClient->surname,
                            'date_of_birth' => $existingClient->date_of_birth?->format('Y-m-d'),
                            'time_of_birth' => $existingClient->time_of_birth,
                            'sex' => $existingClient->sex,
                            'country_of_birth' => $existingClient->country_of_birth,
                            'town_of_birth' => $existingClient->town_of_birth,
                            'save_as' => $existingClient->save_as,
                        ],
                    ], 409);
                }

                return redirect()->route('job1')->with('error', 'A client with this SaveAs already exists.');
            }

            if ($existingClient && $overwrite) {
                // Overwrite existing record
                $existingClient->update([
                    'name' => $request->name,
                    'middle_name' => $request->middle_name,
                    'surname' => $request->surname,
                    'date_of_birth' => $request->date_of_birth,
                    'time_of_birth' => $request->time_of_birth,
                    'sex' => $request->sex,
                    'country_of_birth' => $request->country_of_birth,
                    'town_of_birth' => $request->town_of_birth,
                    // save_as unchanged
                ]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'overwritten' => true,
                        'message' => 'Existing client overwritten successfully.',
                    ]);
                }

                return redirect()->route('job1')->with('success', 'Existing client overwritten successfully.');
            }

            // No existing client, create new
            Client::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'middle_name' => $request->middle_name,
                'surname' => $request->surname,
                'date_of_birth' => $request->date_of_birth,
                'time_of_birth' => $request->time_of_birth,
                'sex' => $request->sex,
                'country_of_birth' => $request->country_of_birth,
                'town_of_birth' => $request->town_of_birth,
                'save_as' => $request->save_as,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client saved successfully.',
                ]);
            }

            return redirect()->route('job1')->with('success', 'Client saved successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving the client.',
                ], 500);
            }

            return redirect()->route('job1')->with('error', 'An error occurred while saving the client.');
        }
    }

    public function index(): JsonResponse
    {
        $user = request()->user();
        
        // Users without roles cannot load clients
        if ($user->roles->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to load clients. Please contact an administrator to assign you a role.',
            ], 403);
        }
        
        // Role managers can see all clients, others only see their own
        if ($user->isRoleManager()) {
            $clients = Client::orderBy('created_at', 'desc')->get([
                'id', 'name', 'middle_name', 'surname', 'date_of_birth', 'time_of_birth', 'sex', 'country_of_birth', 'town_of_birth', 'save_as'
            ]);
        } else {
            $clients = Client::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get([
                    'id', 'name', 'middle_name', 'surname', 'date_of_birth', 'time_of_birth', 'sex', 'country_of_birth', 'town_of_birth', 'save_as'
                ]);
        }

        return response()->json([
            'success' => true,
            'clients' => $clients,
        ]);
    }

    public function destroy($id): JsonResponse|RedirectResponse
    {
        try {
            $user = request()->user();
            $client = Client::findOrFail($id);
            
            // Users can only delete their own clients, role managers can delete any
            if (!$user->isRoleManager() && $client->user_id !== $user->id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized. You can only delete your own clients.',
                    ], 403);
                }
                return redirect()->route('job1')->with('error', 'Unauthorized. You can only delete your own clients.');
            }
            
            $client->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Client deleted successfully.',
                ]);
            }

            return redirect()->route('job1')->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the client.',
                ], 500);
            }

            return redirect()->route('job1')->with('error', 'An error occurred while deleting the client.');
        }
    }

    public function calculateVedicAstrology(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer|min:1900|max:2100',
                'month' => 'required|integer|min:1|max:12',
                'day' => 'required|integer|min:1|max:31',
                'time' => 'required|date_format:H:i',
                'country' => 'required|string',
                'town' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors()),
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            // Defensive coercion: ensure we have scalar string values (avoid arrays from malformed requests)
            $town = $request->input('town');
            $country = $request->input('country');
            $time = $request->input('time');

            if (is_array($town)) {
                $town = count($town) ? (string) $town[0] : '';
            }
            if (is_array($country)) {
                $country = count($country) ? (string) $country[0] : '';
            }
            if (is_array($time)) {
                $time = count($time) ? (string) $time[0] : '';
            }

            // Get coordinates for birth place
            $coordinates = $this->getCoordinates($town, $country);
            if (!$coordinates) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not find coordinates for birth place. Please check the town and country names.',
                ], 400);
            }

            // Calculate Vedic astrology chart
            $chartData = $this->calculateVedicChart(
                $request->year,
                $request->month,
                $request->day,
                $time,
                $coordinates['lat'],
                $coordinates['lon']
            );

            return response()->json([
                'success' => true,
                'data' => $chartData,
            ]);

        } catch (\Exception $e) {
            \Log::error('Vedic Astrology Calculation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error calculating Vedic astrology chart: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getCoordinates($town, $country)
    {
        try {
            // Normalize inputs to strings in case arrays or nulls are passed
            if (is_array($town)) {
                $town = count($town) ? (string) $town[0] : '';
            }
            if (is_array($country)) {
                $country = count($country) ? (string) $country[0] : '';
            }

            $town = $town !== null ? (string) $town : '';
            $country = $country !== null ? (string) $country : '';

            $query = urlencode(trim($town) . ', ' . trim($country));
            $url = "https://nominatim.openstreetmap.org/search?format=json&q={$query}&limit=1";
            
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: LoNum Astrology App\r\n",
                    'timeout' => 10,
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            if (!$response) {
                \Log::warning('Geocoding failed: No response', ['town' => $town, 'country' => $country]);
                return null;
            }

            $data = json_decode($response, true);
            if (empty($data) || !isset($data[0]['lat']) || !isset($data[0]['lon'])) {
                \Log::warning('Geocoding failed: Invalid response', ['town' => $town, 'country' => $country, 'response' => $response]);
                return null;
            }

            return [
                'lat' => (float) $data[0]['lat'],
                'lon' => (float) $data[0]['lon'],
            ];
        } catch (\Exception $e) {
            \Log::error('Geocoding exception', [
                'town' => $town,
                'country' => $country,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function calculateVedicChart($year, $month, $day, $time, $lat, $lon)
    {
        /**
         * STEP 1: Try to use an external high‑precision Vedic ephemeris/API
         * ----------------------------------------------------------------
         * You can configure an API endpoint + key in either:
         * - config('services.vedic_api.url') / config('services.vedic_api.key'), or
         * - env('VEDIC_API_URL') / env('VEDIC_API_KEY')
         *
         * Expected (generic) response shape:
         * {
         *   "planets": {
         *      "Sun":   { "longitude": 285.123, "house": 10 },
         *      "Moon":  { "longitude": 123.456, "house": 3  },
         *      ...
         *   },
         *   "ascendant": { "longitude": 324.09 },
         *   "houses": [1,2,3,4,5,6,7,8,9,10,11,12] // optional
         * }
         *
         * If the external call fails for any reason, we fall back to the
         * existing simplified local calculation below.
         */

        $signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                  'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
        
        $nakshatras = [
            'Ashwini', 'Bharani', 'Krittika', 'Rohini', 'Mrigashira', 'Ardra',
            'Punarvasu', 'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni',
            'Hasta', 'Chitra', 'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha',
            'Mula', 'Purva Ashadha', 'Uttara Ashadha', 'Shravana', 'Dhanishta', 'Shatabhisha',
            'Purva Bhadrapada', 'Uttara Bhadrapada', 'Revati'
        ];

        // Try external API (FreeAstrologyAPI planets endpoint) if configured
        $apiUrl = config('services.vedic_api.url', env('VEDIC_API_URL'));
        $apiKey = config('services.vedic_api.key', env('VEDIC_API_KEY'));

        if (!empty($apiUrl)) {
            try {
                // Parse time into hours / minutes
                [$hours, $minutes] = array_pad(explode(':', $time), 2, 0);
                $hours = (int) $hours;
                $minutes = (int) $minutes;
                $seconds = 0;

                // Approximate timezone from longitude (to nearest 0.5h), clamped to [-12, +14]
                $timezone = (float) $lon / 15.0;
                $timezone = round($timezone * 2) / 2; // nearest 0.5
                $timezone = max(-12.0, min(14.0, $timezone));

                $payload = [
                    'year'      => (int) $year,
                    'month'     => (int) $month,   // no leading zero
                    'date'      => (int) $day,     // no leading zero
                    'hours'     => $hours,
                    'minutes'   => $minutes,
                    'seconds'   => $seconds,
                    'latitude'  => (float) $lat,
                    'longitude' => (float) $lon,
                    'timezone'  => $timezone,
                    // Match FreeAstrologyAPI sample: key is "settings"
                    'settings'  => [
                        'observation_point' => 'topocentric',
                        'ayanamsha'         => 'lahiri',
                    ],
                ];

                $request = Http::timeout(10)
                    ->withHeaders([
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json',
                    ]);

                if (!empty($apiKey)) {
                    $request = $request->withHeaders([
                        // FreeAstrologyAPI uses x-api-key header
                        'x-api-key' => $apiKey,
                    ]);
                }

                $response = $request->post($apiUrl, $payload);

                if ($response->successful()) {
                    $apiData = $response->json();

                    /**
                     * FreeAstrologyAPI planets response shape:
                     * {
                     *   "statusCode": 200,
                     *   "input": {...},
                     *   "output": [
                     *     { ...indexed planets... },
                     *     {
                     *       "Ascendant": { "fullDegree": ..., "normDegree": ..., "current_sign": ... },
                     *       "Sun": { ... },
                     *       "Moon": { ... },
                     *       ...
                     *     }
                     *   ]
                     * }
                     */

                    if (
                        is_array($apiData) &&
                        isset($apiData['statusCode']) &&
                        (int) $apiData['statusCode'] === 200 &&
                        isset($apiData['output'][1]) &&
                        is_array($apiData['output'][1])
                    ) {
                        $planetMap = $apiData['output'][1];
                        $planets = [];

                        // All planets including outer planets
                        $wantedPlanets = [
                            'Sun', 'Moon', 'Mars', 'Mercury', 'Jupiter',
                            'Venus', 'Saturn', 'Rahu', 'Ketu',
                            'Uranus', 'Neptune', 'Pluto',
                        ];

                        foreach ($wantedPlanets as $planetKey) {
                            if (!isset($planetMap[$planetKey]['fullDegree'])) {
                                continue;
                            }

                            $pData = $planetMap[$planetKey];

                            $lon = (float) $pData['fullDegree']; // full ecliptic longitude 0–360
                            // Normalize longitude to 0–360
                            $lon = fmod($lon, 360.0);
                            if ($lon < 0) {
                                $lon += 360.0;
                            }

                            // Computed values (for comparison)
                            $signIndex = (int) floor($lon / 30);
                            if ($signIndex < 0 || $signIndex > 11) {
                                $signIndex = (($signIndex % 12) + 12) % 12;
                            }

                            $degreeWithinSign = fmod($lon, 30.0);
                            if ($degreeWithinSign < 0) {
                                $degreeWithinSign += 30.0;
                            }

                            // Nakshatra from ecliptic longitude
                            $nakshatraIndex = (int) floor($lon / 13.3333333333) % 27;
                            if ($nakshatraIndex < 0) {
                                $nakshatraIndex = ($nakshatraIndex + 27) % 27;
                            }

                            // API-provided values (for comparison)
                            $apiNormDegree = isset($pData['normDegree']) ? (float) $pData['normDegree'] : null;
                            $apiCurrentSign = isset($pData['current_sign']) ? (int) $pData['current_sign'] : null;
                            $isRetro = isset($pData['isRetro']) ? ($pData['isRetro'] === 'true' || $pData['isRetro'] === true) : false;

                            // Validate apiCurrentSign is within valid range (0-11)
                            $apiCurrentSignName = null;
                            if ($apiCurrentSign !== null && $apiCurrentSign >= 0 && $apiCurrentSign <= 11) {
                                $apiCurrentSignName = $signs[$apiCurrentSign];
                            }

                            // Get house number from API if available
                            $houseNumber = isset($pData['house_number']) ? (int) $pData['house_number'] : null;
                            // Validate house number is within valid range (1-12)
                            if ($houseNumber !== null && ($houseNumber < 1 || $houseNumber > 12)) {
                                $houseNumber = null;
                            }

                            $planets[] = [
                                'name'           => $planetKey,
                                'sign'           => $signs[$signIndex], // computed
                                'degree'         => round($degreeWithinSign, 2), // computed
                                'nakshatra'      => $nakshatras[$nakshatraIndex],
                                'house'          => $houseNumber, // use API house number if available, otherwise will be calculated
                                'isRetro'        => $isRetro,
                                'apiNormDegree'  => $apiNormDegree !== null ? round($apiNormDegree, 2) : null,
                                'apiCurrentSign' => $apiCurrentSignName,
                                'computedSign'   => $signs[$signIndex], // for clarity
                                'computedDegree' => round($degreeWithinSign, 2), // for clarity
                            ];
                        }

                        // Ascendant from API
                        if (isset($planetMap['Ascendant']['fullDegree'])) {
                            $ascLon = (float) $planetMap['Ascendant']['fullDegree'];
                            $ascLon = fmod($ascLon, 360.0);
                            if ($ascLon < 0) {
                                $ascLon += 360.0;
                            }

                            $ascSignIndex = (int) floor($ascLon / 30);
                            if ($ascSignIndex < 0 || $ascSignIndex > 11) {
                                $ascSignIndex = (($ascSignIndex % 12) + 12) % 12;
                            }

                            $ascDegreeWithinSign = fmod($ascLon, 30.0);
                            if ($ascDegreeWithinSign < 0) {
                                $ascDegreeWithinSign += 30.0;
                            }

                            // Nakshatra for ascendant from ecliptic longitude
                            $ascNakshatraIndex = (int) floor($ascLon / 13.3333333333) % 27;
                            if ($ascNakshatraIndex < 0) {
                                $ascNakshatraIndex = ($ascNakshatraIndex + 27) % 27;
                            }

                            // API-provided values for ascendant (if available)
                            $ascApiNormDegree = isset($planetMap['Ascendant']['normDegree']) ? (float) $planetMap['Ascendant']['normDegree'] : null;
                            $ascApiCurrentSign = isset($planetMap['Ascendant']['current_sign']) ? (int) $planetMap['Ascendant']['current_sign'] : null;

                            // Validate ascApiCurrentSign is within valid range (0-11)
                            $ascApiCurrentSignName = null;
                            if ($ascApiCurrentSign !== null && $ascApiCurrentSign >= 0 && $ascApiCurrentSign <= 11) {
                                $ascApiCurrentSignName = $signs[$ascApiCurrentSign];
                            }

                            $ascendant = [
                                'sign'           => $signs[$ascSignIndex],
                                'degree'         => round($ascDegreeWithinSign, 2),
                                'nakshatra'      => $nakshatras[$ascNakshatraIndex],
                                'apiNormDegree'  => $ascApiNormDegree !== null ? round($ascApiNormDegree, 2) : null,
                                'apiCurrentSign' => $ascApiCurrentSignName,
                                'computedSign'   => $signs[$ascSignIndex],
                                'computedDegree' => round($ascDegreeWithinSign, 2),
                            ];
                        } else {
                            // Fallback: compute local ascendant
                            $birthDate = new \DateTime("{$year}-{$month}-{$day} {$time}");
                            $julianDay = $this->getJulianDay($birthDate);
                            $ascendant = $this->calculateAscendant($julianDay, $lat, $lon);
                            $ascSignIndex = array_search($ascendant['sign'], $signs, true);
                            if ($ascSignIndex === false) {
                                $ascSignIndex = 0;
                            }

                            // Build synthetic longitude from sign + degree for nakshatra
                            $ascLon = $ascSignIndex * 30.0 + ($ascendant['degree'] ?? 0.0);
                            $ascNakshatraIndex = (int) floor($ascLon / 13.3333333333) % 27;
                            if ($ascNakshatraIndex < 0) {
                                $ascNakshatraIndex = ($ascNakshatraIndex + 27) % 27;
                            }
                            $ascendant['nakshatra'] = $nakshatras[$ascNakshatraIndex];
                        }

                        // If API did not provide houses, derive them from sign vs ascendant
                        $ascendantSignIndex = isset($ascSignIndex)
                            ? $ascSignIndex
                            : array_search($ascendant['sign'], $signs, true);
                        if ($ascendantSignIndex === false) {
                            $ascendantSignIndex = 0;
                        }

                        foreach ($planets as &$planet) {
                            if (empty($planet['house'])) {
                                $planetSignIndex = array_search($planet['sign'], $signs, true);
                                if ($planetSignIndex === false) {
                                    $planetSignIndex = 0;
                                }
                                $planet['house'] = (($planetSignIndex - $ascendantSignIndex + 12) % 12) + 1;
                            }
                        }
                        unset($planet);

                        $houses = isset($apiData['houses']) && is_array($apiData['houses'])
                            ? $apiData['houses']
                            : [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

                        // Extract ayanamsa value
                        $ayanamsa = null;
                        if (isset($apiData['output'][0])) {
                            $output0 = $apiData['output'][0];
                            if (isset($output0['13']['value'])) {
                                $ayanamsa = (float) $output0['13']['value'];
                            } elseif (isset($output0['ayanamsa']['value'])) {
                                $ayanamsa = (float) $output0['ayanamsa']['value'];
                            }
                        }

                        return [
                            'planets'   => $planets,
                            'ascendant' => $ascendant,
                            'houses'    => $houses,
                            'ayanamsa'  => $ayanamsa !== null ? round($ayanamsa, 6) : null,
                        ];
                    } else {
                        // API returned success but data structure is invalid
                        \Log::error('Vedic API returned invalid data structure', [
                            'status_code' => $response->status(),
                            'response' => $apiData,
                        ]);
                        throw new \Exception('Vedic astrology API returned invalid data structure.');
                    }
                } else {
                    // API call was not successful
                    \Log::error('Vedic API call was not successful', [
                        'status_code' => $response->status(),
                        'response_body' => $response->body(),
                    ]);
                    throw new \Exception('Vedic astrology API call failed with status: ' . $response->status());
                }
            } catch (\Throwable $e) {
                \Log::error('Vedic API call failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                ]);
                // Don't fall back - throw exception to return error
                throw new \Exception('Vedic astrology API call failed: ' . $e->getMessage());
            }
        }

        // If we reach here, API was not configured or failed
        throw new \Exception('Vedic astrology calculation is not available. Please configure the API or ensure the API is accessible.');
    }

    private function getJulianDay(\DateTime $date)
    {
        $timestamp = $date->getTimestamp();
        return ($timestamp / 86400) + 2440587.5;
    }

    private function calculateAscendant($julianDay, $lat, $lon)
    {
        $signs = ['Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 
                  'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces'];
        
        $T = ($julianDay - 2451545.0) / 36525.0;
        $theta = 280.46061837 + 360.98564736629 * ($julianDay - 2451545.0) + $T * $T * (0.000387933 - $T / 38710000.0);
        $lst = fmod((($theta + $lon) / 15.0), 24.0);
        if ($lst < 0) {
            $lst += 24.0;
        }
        
        $ascendantDegrees = fmod(($lst * 15 + $lat), 360.0);
        if ($ascendantDegrees < 0) {
            $ascendantDegrees += 360.0;
        }
        
        $signIndex = (int) floor($ascendantDegrees / 30);
        if ($signIndex < 0 || $signIndex >= 12) {
            $signIndex = $signIndex % 12;
            if ($signIndex < 0) {
                $signIndex += 12;
            }
        }
        
        $degree = fmod($ascendantDegrees, 30.0);
        if ($degree < 0) {
            $degree += 30.0;
        }
        
        return [
            'sign' => $signs[$signIndex],
            'degree' => round($degree, 2),
        ];
    }
}

