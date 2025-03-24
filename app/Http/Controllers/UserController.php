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

    public function getUserGrowthStats()
{
    // Current month new users
    $currentMonthCount = User::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->count();

    // Previous month new users
    $previousMonthCount = User::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->count();

    // Calculate percentage change
    $percentageChange = 0;
    if ($previousMonthCount > 0) {
        $percentageChange = (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
    } elseif ($currentMonthCount > 0) {
        $percentageChange = 100; // Infinite growth (from 0 to current)
    }

    return [
        'current_month' => $currentMonthCount,
        'previous_month' => $previousMonthCount,
        'percentage_change' => round($percentageChange, 2)
    ];
}
}
