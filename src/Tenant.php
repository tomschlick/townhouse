<?php

namespace TomSchlick\Townhouse;

use Illuminate\Database\Eloquent\Model;
use TomSchlick\Townhouse\Tenant\Database;

class Tenant extends Model
{
    /**
     * Get the database instance.
     *
     * @return \TomSchlick\Townhouse\Tenant\Database
     */
    public function database() : Database
    {
        return new Database($this, config('database.connections.tenant'));
    }
}
