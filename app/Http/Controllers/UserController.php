<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

    
        if (Auth::check() && Auth::user()->role === 'admin') {

            try {
                if ($user->profile_photo_url) {
                    $user->deleteProfilePhoto();
                }

                
                if ($user->tokens->count() > 0) {
                    $user->tokens->each->delete();
                }

                
                $user->delete();

            

                return redirect()->back()->with('message', 'User successfully deleted!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'An error occurred while deleting the user.');
            }
        }

        
        return redirect()->back()->with('error', 'You do not have permission to delete users.');
    }
//admin panel broj novih korisnika
    public function getUserGrowthStats()
{
    $currentMonthCount = User::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->count();

    $previousMonthCount = User::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->count();

    
    $percentageChange = 0;
    if ($previousMonthCount > 0) {
        $percentageChange = (($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100;
    } elseif ($currentMonthCount > 0) {
        $percentageChange = 100; 
    }

    return [
        'current_month' => $currentMonthCount,
        'previous_month' => $previousMonthCount,
        'percentage_change' => round($percentageChange, 2)
    ];
}
}
