<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'password_confirmation' => ['required']
        ],
        [
            'email.unique' => 'L\'email inserita è già in uso',
            'email.required' => 'Devi inserire un\'email',
            'password.required' => 'Devi inserire una password',
            'password_confirmation.required' => 'Devi confermare la password',
            'password.min' => 'La password deve contenere almeno :min caratteri',
            'password.confirmed' => 'Le password non corrispondono',
            'name.required'=>'Devi inserire un nome'
        ]
        )->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
