<?php

namespace App\Console;

use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Console\Command as LaravelCommand;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Compatibility shim for symfony/console >= 7.4 on laravel/framework 11.23.
 *
 * Laravel injects the container into each command by overriding
 * Illuminate\Console\Application::add(). Symfony 7.4 deprecated add() and
 * routes all registration through the new addCommand(), which Laravel 11.23
 * does not override - so setLaravel() was never called and every command that
 * actually executed died with "Call to a member function make() on null".
 *
 * Overriding addCommand() restores the injection. Calling setLaravel() twice
 * is harmless (it is a plain setter), so the inherited add() path stays safe.
 *
 * REMOVE THIS once laravel/framework is upgraded to a release that overrides
 * addCommand() itself - at that point this class is redundant, though still
 * harmless. See also App\Console\Kernel::getArtisan().
 */
class Artisan extends ConsoleApplication
{
    public function addCommand(callable|SymfonyCommand $command): ?SymfonyCommand
    {
        if ($command instanceof LaravelCommand) {
            $command->setLaravel($this->laravel);
        }

        return parent::addCommand($command);
    }
}
