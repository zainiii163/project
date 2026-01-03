<?php

namespace App\Console\Commands;

use App\Services\SubscriptionBillingService;
use Illuminate\Console\Command;

class ProcessRecurringBilling extends Command
{
    protected $signature = 'subscriptions:process-billing';
    protected $description = 'Process recurring subscription billing';

    protected $billingService;

    public function __construct(SubscriptionBillingService $billingService)
    {
        parent::__construct();
        $this->billingService = $billingService;
    }

    public function handle()
    {
        $this->info('Processing recurring subscription billing...');
        
        $this->billingService->processRecurringBilling();
        
        $this->info('Recurring billing processing completed.');
    }
}

