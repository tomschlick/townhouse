<?php

namespace TomSchlick\Townhouse;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

abstract class BaseTenantableCommand extends Command
{
    /**
     * Declare the handle method as final and use it to traverse through tenants.
     */
    final public function handle()
    {
        if ( ! $this->confirmToProceed()) {
            return;
        }

        /** @var Tenant $tenant */
        foreach ($this->parseTenantInput() as $tenant) {
            $this->info("Starting for tenant: {$tenant->id} - {$tenant->name}");

            $this->bootstrapTenant($tenant);

            $this->handleTenant($tenant);

            $this->info("\nFinished for tenant: " . $tenant->id);
        }
    }

    /**
     * @param \TomSchlick\Townhouse\Tenant $tenant
     *
     * @return void
     */
    abstract public function handleTenant(Tenant $tenant) : void;

    /**
     * Get or ask for the tenant(s) you'd like to run the commands on.
     *
     * @return \Illuminate\Support\Collection|null
     */
    protected function parseTenantInput() : ?Collection
    {
        if ($this->option('all')) {
            return Tenant::all();
        }

        $input_ids = $this->option('tenant')
            ? $this->option('tenant')
            : $this->ask('Which tenant would you like to use? [tenant_id]');

        return Tenant::find(
            array_map(
                function ($id) {
                    return trim($id);
                },
                explode(',', $input_ids)
            )
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['tenant', null, InputOption::VALUE_REQUIRED, 'The tenant you want to run the command as.'],
            ['all', null, InputOption::VALUE_NONE, 'Run all tenants instead of just one.'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }

    /**
     * Override the fire method as well.
     */
    final public function fire()
    {
        $this->handle();
    }

    /**
     * Make Laravel ready for the tenant we want to run the command on.
     *
     * @param \TomSchlick\Townhouse\Tenant $tenant
     */
    protected function bootstrapTenant(Tenant $tenant)
    {
        if ($this->checkTenantDatabaseExists && ! $tenant->databaseExists()) {
            $this->error('Tenant ' . $tenant->id . ' does not have a database setup.');
        }

        if ($tenant->databaseExists()) {
            $tenant->setCurrentTenantConfiguration();
        }
    }
}
