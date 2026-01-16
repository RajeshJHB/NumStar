@extends('layouts.app')

@section('title', 'About - NumStar')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">About NumStar</h1>

        <div class="space-y-4">
            <div class="border-b pb-4">
                <h2 class="text-xl font-semibold mb-2">Version Information</h2>
                <p class="text-gray-700">
                    <strong>Version:</strong> 1.0
                </p>
            </div>

            <div class="border-b pb-4">
                <h2 class="text-xl font-semibold mb-2">What is NumStar?</h2>
                <p class="text-gray-700">
                    NumStar is a basic numerology and Vedic astrology application that helps you explore the
                    mystical connections between numbers, names, and cosmic influences. Our platform combines traditional Lo
                    Shu grid calculations with modern Vedic astrology calculations.
                </p>
            </div>

            <div class="border-b pb-4">
                <h2 class="text-xl font-semibold mb-2">Features</h2>
                <ul class="list-disc list-inside text-gray-700 space-y-2">
                    <li>Lo Shu Grid calculation based on date of birth</li>
                    <li> Basic numerology (Conductor, Destiny, Soul Urge, Personality, Birthday, Maturity
                        Numbers)</li>
                    <li>Kua Number calculations for both male and female</li>
                    <li>Vedic Astrology charts with planetary positions</li>
                    <li>Client management system for saving and organizing calculations</li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-2">Contact</h2>
                <p class="text-gray-700">
                    For inquiries,support, upgrade please contact: <a href="mailto:numstar@tvrgod.com"
                        class="text-blue-600 hover:underline">numstar@tvrgod.com</a>
                </p>
            </div>
        </div>
    </div>
@endsection

