<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class User extends Model
{
    use HasFactory;

    public function right()
    {
        return $this->hasOne(UserRight::class);
    }

    public function website()
    {
        return $this->hasOne(Website::class);
    }

    public function validate(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:10',
        ]);
    }
}
