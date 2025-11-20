<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentProfileController extends Controller
{
    public function ApplyToBeAgent(Request $request)
    { dd(session('user'));

        $validated = $request->validate([
            'store_name' => 'required|max:255|string',
            'address' => 'required|max:255|string',
            'country' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'working_hours' => 'required|string',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        // Get logged-in user
       /* $userId = session('user.id');

        // Create the Agent application (pending)
        Agent::create([
            'user_id'         => $userId,
            'store_name'      => $validated['store_name'],
            'address'         => $validated['address'],
            'country'         => $validated['country'],
            'latitude'        => $validated['latitude'],
            'longitude'       => $validated['longitude'],
            'working_hours'   => $validated['working_hours'],
            'commission_rate' => $validated['commission_rate'],
            'approved'        => false, 
        ]);
*/
$userId = session('user.id');
if (!$userId) {
    return redirect()->back()->with('error', 'User not logged in.');
}

// Dump validated data and user ID
dd($userId, $validated);

$agent = Agent::create([
    'user_id'         => $userId,
    'store_name'      => $validated['store_name'],
    'address'         => $validated['address'],
    'country'         => $validated['country'],
    'latitude'        => $validated['latitude'],
    'longitude'       => $validated['longitude'],
    'working_hours'   => $validated['working_hours'],
    'commission_rate' => $validated['commission_rate'],
    'approved'        => false,
]);

dd($agent); // see the created model

        return redirect()
            ->back()
            ->with('success', 'Your agent application was submitted and is awaiting admin approval.');
    }
}
