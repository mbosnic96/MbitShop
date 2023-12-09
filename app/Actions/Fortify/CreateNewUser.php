<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

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
        // Determine the default role
        $defaultRole = 'customer';

        // Check if there are existing users
        $existingUsersCount = User::count();

        // Check if the logged-in user is an admin (optional)
        $loggedInUser = Auth::user();
        if ($loggedInUser && $loggedInUser->role === 'admin') {
            // If the logged-in user is an admin, set the role accordingly
            $role = 'admin';
        } else {
            // If there are no existing users, set the role to 'admin' for the first user
            $role = ($existingUsersCount == 0) ? 'admin' : $defaultRole;
        }
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
           // 'credit_card' => ['string', 'max:255'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $role,
            'address' => $input['address'],
            'city' => $input['city'],
            'country' => $input['country'],
            'phone_number' => $input['phone_number'],
           // 'credit_card' => $input['credit_card'],
        ]);
    }
}
