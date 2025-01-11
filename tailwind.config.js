import preset from './vendor/filament/support/tailwind.config.preset';

export default {
    presets: [preset],
    content: [
        './app/Http/Livewire/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    plugins: [
        require('@tailwindcss/forms'),
    ],
};

