<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class TendersPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['key' => 'tenders.view', 'label' => 'İhaleleri Görüntüleme'],
            ['key' => 'tenders.create', 'label' => 'İhale Ekleme'],
            ['key' => 'tenders.edit', 'label' => 'İhale Düzenleme'],
            ['key' => 'tenders.delete', 'label' => 'İhale Silme'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], $perm);
        }
    }
}
