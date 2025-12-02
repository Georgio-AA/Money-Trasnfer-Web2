<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentProfileController extends Controller
{
    public function ApplyToBeAgent(Request $request)
    {

        $userId = session('user.id');
        if (!$userId) {
            return redirect()->back()->with('error', 'Please Login before applying to become an Agent');
        }

        $validated = $request->validate([
            'store_name' => 'required|max:255|string',
            'address' => 'required|max:255|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'phone_number' =>'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'working_hours' => 'required|string',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

// Create the Agent application (pending)
$agent = Agent::create([
    'user_id'         => $userId,
    'store_name'      => $validated['store_name'],
    'address'         => $validated['address'],
    'country'         => $validated['country'],
    'city'            => $validated['city'],
    'phone_number'    => $validated['phone_number'],
    'latitude'        => $validated['latitude'],
    'longitude'       => $validated['longitude'],
    'working_hours'   => $validated['working_hours'],
    'commission_rate' => $validated['commission_rate'],
    'approved'        => false,
]);

$user = session('user');
return view('agent.applicationstatus', compact('user', 'agent'));

//return redirect()->route('agent.dashboard')
  //  ->with('success', 'Application submitted and pending admin approval.');
//dd("Create succeeded!", $agent);
}

public function dashboard(Request $request)
{
    $user = session('user');

    // Fetch agent profile if exists
    $agent = \App\Models\Agent::where('user_id', $user['id'])->first();

    return view('agent.dashboard', compact('user', 'agent'));
}

public function applicationStatus()
{
    $user = session('user');
    $agent = Agent::where('user_id', $user['id'])->first();

    return view('agent.applicationstatus', [
        'agent' => $agent
    ]);
}

}
