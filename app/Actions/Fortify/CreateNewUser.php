<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;


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
                'max:255',
                Rule::unique('users'),
            ],
            'password' => $this->passwordRules(),
            'phone' => ['nullable', 'string', 'max:20'],
            'account_type' => 'required|string|in:client,applicant',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Create the user and store in a variable
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'password' => Hash::make($input['password']),
            'account_type' => $input['account_type']
        ]);

        // Send welcome email
        Mail::to($user->email)->send(new WelcomeEmail($user));

        // Notify all admins of new registration
        $admins = \App\Models\User::where('user_role', '>=', 1)->get();
        foreach ($admins as $admin) {
            if ($user->account_type === 'client') {
                $admin->notify(new \App\Notifications\NewClientRegisteredNotification($user));
            } else {
                $admin->notify(new \App\Notifications\NewStaffRegisteredNotification($user));
            }
        }

        // Return the user
        return $user;
    }
}
