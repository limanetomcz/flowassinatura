<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Here you could also check for admin privileges if needed.
     */
    public function authorize(): bool
    {
        // Allow any user to attempt login
        return true;
    }

    /**
     * Validation rules for login.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Custom validation and authorization messages.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'The password field is required.',
        ];
    }

    /**
     * Checks if the logged-in user is an admin, in case access needs
     * to be restricted after validation.
     *
     * Can be called from the controller after validation.
     */
    public function ensureAdmin()
    {
        $user = Auth::user();

        if ($user && !$user->is_admin) {
            abort(403, 'Access denied. You do not have permission to access this area.');
        }
    }
}
