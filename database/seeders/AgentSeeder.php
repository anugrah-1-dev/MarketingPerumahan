<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['nama' => 'Anugrah', 'jabatan' => 'Marketing Executive', 'slug' => 'anugrah', 'aktif' => true, 'email' => 'anugrah@example.com', 'phone' => '081200000001', 'commission' => 1],
            ['nama' => 'Fajar',   'jabatan' => 'Marketing Executive', 'slug' => 'fajar',   'aktif' => true, 'email' => 'fajar@example.com',   'phone' => '081200000002', 'commission' => 1],
            ['nama' => 'Rizky',   'jabatan' => 'Marketing Executive', 'slug' => 'rizky',   'aktif' => true, 'email' => 'rizky@example.com',   'phone' => '081200000003', 'commission' => 1],
        ];

        foreach ($agents as $agent) {
            Agent::updateOrCreate(['slug' => $agent['slug']], $agent);
        }
    }
}
