<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RegistrationService;

class CheckRegistrationExpirations extends Command
{
    protected $signature = 'registrations:check-expirations';
    protected $description = 'Check and update expired registrations';

    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        parent::__construct();
        $this->registrationService = $registrationService;
    }

    public function handle()
    {
        $this->info('Checking for expired registrations...');
        
        $this->registrationService->checkExpiredRegistrations();
        
        $this->info('Expired registrations have been updated.');
        
        return Command::SUCCESS;
    }
}