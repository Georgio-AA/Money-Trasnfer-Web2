<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $user = session('user');
        
        $beneficiaries = Beneficiary::where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('beneficiaries.index', compact('beneficiaries'));
    }
    
    public function create()
    {
        $countries = config('transfer.countries');
        $payoutMethods = config('transfer.payout_methods');
        
        return view('beneficiaries.create', compact('countries', 'payoutMethods'));
    }
    
    public function store(Request $request)
    {
        $user = session('user');
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'relationship' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'preferred_payout_method' => 'required|in:bank_deposit,cash_pickup,mobile_wallet',
            'mobile_wallet_number' => 'nullable|string|max:50',
            'mobile_wallet_provider' => 'nullable|string|max:50',
        ]);
        
        $validated['user_id'] = $user['id'];
        
        $beneficiary = Beneficiary::create($validated);
        
        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary added successfully!');
    }
    
    public function show($id)
    {
        $user = session('user');
        $beneficiary = Beneficiary::findOrFail($id);
        
        // Verify ownership
        if ($beneficiary->user_id != $user['id']) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('beneficiaries.show', compact('beneficiary'));
    }
    
    public function edit($id)
    {
        $user = session('user');
        $beneficiary = Beneficiary::findOrFail($id);
        
        // Verify ownership
        if ($beneficiary->user_id != $user['id']) {
            abort(403, 'Unauthorized access.');
        }
        
        $countries = config('transfer.countries');
        $payoutMethods = config('transfer.payout_methods');
        
        return view('beneficiaries.edit', compact('beneficiary', 'countries', 'payoutMethods'));
    }
    
    public function update(Request $request, $id)
    {
        $user = session('user');
        $beneficiary = Beneficiary::findOrFail($id);
        
        // Verify ownership
        if ($beneficiary->user_id != $user['id']) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'relationship' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'preferred_payout_method' => 'required|in:bank_deposit,cash_pickup,mobile_wallet',
            'mobile_wallet_number' => 'nullable|string|max:50',
            'mobile_wallet_provider' => 'nullable|string|max:50',
        ]);
        
        $beneficiary->update($validated);
        
        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary updated successfully!');
    }
    
    public function destroy($id)
    {
        $user = session('user');
        $beneficiary = Beneficiary::findOrFail($id);
        
        // Verify ownership
        if ($beneficiary->user_id != $user['id']) {
            abort(403, 'Unauthorized access.');
        }
        
        $beneficiary->delete();
        
        return redirect()->route('beneficiaries.index')
            ->with('success', 'Beneficiary deleted successfully!');
    }
}
