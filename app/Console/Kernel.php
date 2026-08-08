<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Symfony\Component\Console\EventDispatcher\ConsoleEventDispatcher as EventDispatcher;

/**
 * NOTE: Laravel 11's bootstrap/app.php does not bind this class by default, so
 * until it was registered explicitly (see bootstrap/app.php) neither the
 * schedule below nor the commands() hook ran at all.
 */
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Check for expired registrations daily at midnight
        $schedule->command('registrations:check-expirations')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }

    /**
     * Build the console application using our compatibility subclass.
     *
     * Mirrors the parent implementation; the only change is instantiating
     * App\Console\Artisan instead of Illuminate\Console\Application. See that
     * class for why.
     */
    protected function getArtisan()
    {
        if (is_null($this->artisan)) {
            $this->artisan = (new Artisan($this->app, $this->events, $this->app->version()))
                ->resolveCommands($this->commands)
                ->setContainerCommandLoader();

            if ($this->symfonyDispatcher instanceof EventDispatcher) {
                $this->artisan->setDispatcher($this->symfonyDispatcher);
                $this->artisan->setSignalsToDispatchEvent();
            }
        }

        return $this->artisan;
    }
}
