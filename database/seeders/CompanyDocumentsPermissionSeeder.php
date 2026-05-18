<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class CompanyDocumentsPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['key' => 'company_documents.view', 'name' => 'Şirket Evraklarını Görüntüleme', 'module' => 'company_documents'],
            ['key' => 'company_documents.create', 'name' => 'Şirket Evrağı Ekleme', 'module' => 'company_documents'],
            ['key' => 'company_documents.delete', 'name' => 'Şirket Evrağı Silme', 'module' => 'company_documents'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['key' => $perm['key']], $perm);
        }
    }
}
