<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Services\GeocodingService;

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
            'working_hours' => 'required|string',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $geo = app(GeocodingService::class)->geocode(
            $validated['address'],
            $validated['city'],
            $validated['country']
        );

        $agent = Agent::create([
            'user_id'         => $userId,
            'store_name'      => $validated['store_name'],
            'address'         => $validated['address'],
            'country'         => $validated['country'],
            'city'            => $validated['city'],
            'phone_number'    => $validated['phone_number'],
            'latitude'        => $geo['latitude'] ?? null,
            'longitude'       => $geo['longitude'] ?? null,
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


    public function editProfile()
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();

        return view('agent.profile', compact('agent'));
    }

    public function updateProfile(Request $request)
    {
        $user = session('user');
        $agent = Agent::where('user_id', $user['id'])->firstOrFail();

        $validated = $request->validate([
            'store_name'    => 'sometimes|max:255|string',
            'address'       => 'sometimes|max:255|string',
            'country'       => 'sometimes|string',
            'city'          => 'sometimes|string',
            'phone_number'  => 'sometimes|string',
            'working_hours' => 'required|string|max:255',
        ]);

        // If address/city/country changed, re-geocode
        if (isset($validated['address']) || isset($validated['city']) || isset($validated['country'])) {
            $geo = app(GeocodingService::class)->geocode(
                $validated['address'] ?? $agent->address,
                $validated['city'] ?? $agent->city,
                $validated['country'] ?? $agent->country
            );
            $validated['latitude'] = $geo['latitude'] ?? $agent->latitude;
            $validated['longitude'] = $geo['longitude'] ?? $agent->longitude;
        }

        $agent->update($validated);

        return redirect()->route('agent.profile.edit')->with('success', 'Profile updated successfully.');
    }

}
