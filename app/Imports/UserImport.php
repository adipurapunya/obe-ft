<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Crypt;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $rememberToken = Str::random(40);
        $encryptedToken = Crypt::encryptString($rememberToken);

        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'role_id' => $row['role_id'],
            'remember_token' => $encryptedToken
        ]);
    }
}

