<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"> {{-- Add for responsive design --}}
<meta name="description" content="Your application description."> {{-- Make more descriptive --}}
<meta name="author" content="Your Name or Company Name"> {{-- Changed 'Saquib' to a placeholder --}}

<title>Infonet v2.0</title> {{-- Add a title --}}

{{-- Tailwind CSS CDN (for development/quick testing) --}}
{{-- For production, compile your Tailwind CSS to avoid FOUC and optimize size --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

{{-- Custom styles if any, or inline them here --}}
<style>
    /* Add any global custom styles here if needed */
    body {
        font-family: 'Inter', sans-serif;
        /* Example: use a clean font */
    }
</style>
