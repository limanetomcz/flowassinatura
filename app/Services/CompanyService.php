<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public function create(array $data): Company
    {
        return Company::create($data);
    }
}
