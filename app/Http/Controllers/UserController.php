<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        // Ensure that the currently authenticated user is not deleting themselves
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // If the authenticated user is an admin, they can delete any user
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Log the action for debugging
            \Log::info('Admin attempting to delete user: ', ['user' => $user]);

            try {
                // Optionally delete associated profile photo if it exists
                if ($user->profile_photo_url) {
                    $user->deleteProfilePhoto();
                }

                // Delete all user tokens if they exist
                if ($user->tokens->count() > 0) {
                    $user->tokens->each->delete();
                }

                // Delete the user from the database
                $user->delete();

                // Log the successful deletion
                \Log::info('Admin successfully deleted user: ', ['user' => $user]);

                return redirect()->back()->with('message', 'User successfully deleted!');
            } catch (\Exception $e) {
                // Log any errors
                \Log::error('Error deleting user: ', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'An error occurred while deleting the user.');
            }
        }

        // If not an admin, return an error message
        return redirect()->back()->with('error', 'You do not have permission to delete users.');
    }
}
