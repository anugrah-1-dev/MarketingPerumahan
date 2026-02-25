<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['nama' => 'Anugrah', 'jabatan' => 'Marketing Executive', 'slug' => 'anugrah', 'aktif' => true],
            ['nama' => 'Fajar',   'jabatan' => 'Marketing Executive', 'slug' => 'fajar',   'aktif' => true],
            ['nama' => 'Rizky',   'jabatan' => 'Marketing Executive', 'slug' => 'rizky',   'aktif' => true],
        ];

        foreach ($agents as $agent) {
            Agent::updateOrCreate(['slug' => $agent['slug']], $agent);
        }
    }
}
