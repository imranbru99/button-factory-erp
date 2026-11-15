<?php

declare(strict_types=1);

/**
 * Filament Master still imports the Filament 3 command trait path.
 * Filament 4 moved it under Filament\Support. Alias it before commands load.
 */
if (! trait_exists('Filament\\Commands\\Concerns\\CanManipulateFiles', false)
    && trait_exists(\Filament\Support\Commands\Concerns\CanManipulateFiles::class)) {
    class_alias(
        \Filament\Support\Commands\Concerns\CanManipulateFiles::class,
        'Filament\\Commands\\Concerns\\CanManipulateFiles'
    );
}
