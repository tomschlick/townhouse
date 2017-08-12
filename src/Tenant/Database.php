<?php

namespace TomSchlick\Townhouse\Tenant;

use TomSchlick\Townhouse\Tenant;
use Illuminate\Support\Facades\DB;

class Database
{
    /**
     * @var \TomSchlick\Townhouse\Tenant
     */
    protected $tenant;

    /**
     * @var array
     */
    protected $config;

    /**
     * Database constructor.
     *
     * @param \TomSchlick\Townhouse\Tenant $tenant
     * @param array                        $config
     */
    public function __construct(Tenant $tenant, array $config)
    {
        $this->tenant = $tenant;
        $this->config = $config;
    }

    /**
     * Figure out the database name for the tenant.
     *
     * @return string
     */
    public function name() : string
    {
        return "tenant_{$this->tenant->id}";
    }

    /**
     * @return string
     */
    public function host() : string
    {
        return $this->tenant->host;
    }

    /**
     * @return string
     */
    public function username() : string
    {
        return $this->tenant->username;
    }

    /**
     * @return string
     */
    public function password() : string
    {
        return decryptString($this->tenant->password);
    }

    /**
     * Check if database currently exists in MySQL.
     *
     * @return bool
     */
    public function exists() : bool
    {
        $result = DB::select("SHOW DATABASES LIKE '{$this->name()}'");

        return ! empty($result);
    }

    /**
     * Create the tenant database.
     *
     * @return mixed
     */
    public function create()
    {
        return DB::statement("CREATE DATABASE {$this->name()};");
    }

    /**
     * Drop the tenant database.
     *
     * @return mixed
     */
    public function drop()
    {
        return DB::statement("DROP DATABASE {$this->name()};");
    }

    /**
     * Connect this db as the "tenant" db.
     */
    public function connect()
    {
        $this->purgeConnection();
        $this->setDbConfig();
    }

    /**
     * Purge the tenant db connection.
     */
    public function purgeConnection()
    {
        DB::disconnect('tenant');
        DB::purge('tenant');
    }

    /**
     * Set this DB's connection info to the config.
     */
    protected function setDbConfig()
    {
        config()->set('database.connections.tenant.database', $this->name());
        config()->set('database.connections.tenant.host', $this->name());
        config()->set('database.connections.tenant.username', $this->name());
        config()->set('database.connections.tenant.password', $this->name());
    }
}
