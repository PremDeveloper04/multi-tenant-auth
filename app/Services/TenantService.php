<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Config;

class TenantService
{
    /**
     * Resolve tenant by email.
     */
    public function resolveTenant(string $email): Client
    {
        $client = Client::query()
            ->join('client_users', 'clients.id', '=', 'client_users.client_id')
            ->where('client_users.email', $email)
            ->select('clients.*')
            ->first();

        if (!$client) {
            throw new Exception('Tenant not found.');
        }

        return $client;
    }

    public function connect(Client $client): void
    {
        Config::set('database.connections.tenant.host', $client->db_host);
        Config::set('database.connections.tenant.port', $client->db_port);
        Config::set('database.connections.tenant.database', $client->db_name);
        Config::set('database.connections.tenant.username', $client->db_user);
        Config::set('database.connections.tenant.password', $client->db_password);

        // Clear previous connection
        DB::purge('tenant');

        // Reconnect using new credentials
        DB::reconnect('tenant');
    }
}