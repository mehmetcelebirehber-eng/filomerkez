<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class TendersPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['key' => 'tenders.view', 'name' => 'İhaleleri Görüntüleme', 'module' => 'tenders'],
            ['key' => 'tenders.create', 'name' => 'İhale Ekleme', 'module' => 'tenders'],
            ['key' => 'tenders.edit', 'name' => 'İhale Düzenleme', 'module' => 'tenders'],
            ['key' => 'tenders.delete', 'name' => 'İhale Silme', 'module' => 'tenders'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], $perm);
        }
    }
}
