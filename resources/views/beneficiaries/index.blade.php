@include('includes.header')

<section class="page-header">
    <h1>My Beneficiaries</h1>
    <p>Manage people you send money to</p>
</section>

<section class="beneficiaries-section">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="page-actions">
            <a href="{{ route('beneficiaries.create') }}" class="btn btn-primary">
                <span>+</span> Add Beneficiary
            </a>
        </div>
        
        @if($beneficiaries->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>No beneficiaries yet</h3>
                <p>Add beneficiaries to start sending money</p>
                <a href="{{ route('beneficiaries.create') }}" class="btn btn-primary">Add Your First Beneficiary</a>
            </div>
        @else
            <div class="beneficiaries-grid">
                @foreach($beneficiaries as $beneficiary)
                    <div class="beneficiary-card">
                        <div class="card-header">
                            <div class="beneficiary-avatar">{{ substr($beneficiary->full_name, 0, 1) }}</div>
                            <div class="beneficiary-info">
                                <h3>{{ $beneficiary->full_name }}</h3>
                                <p class="phone">📱 {{ $beneficiary->phone_number }}</p>
                                <p class="country">🌍 {{ $beneficiary->country }}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($beneficiary->email)
                                <div class="detail">
                                    <span class="label">Email:</span>
                                    <span class="value">{{ $beneficiary->email }}</span>
                                </div>
                            @endif
                            @if($beneficiary->relationship)
                                <div class="detail">
                                    <span class="label">Relationship:</span>
                                    <span class="value">{{ ucfirst($beneficiary->relationship) }}</span>
                                </div>
                            @endif
                            <div class="detail">
                                <span class="label">Payout Method:</span>
                                <span class="value">{{ ucwords(str_replace('_', ' ', $beneficiary->preferred_payout_method)) }}</span>
                            </div>
                            @if($beneficiary->mobile_wallet_provider && $beneficiary->preferred_payout_method === 'mobile_wallet')
                                <div class="detail">
                                    <span class="label">Wallet Provider:</span>
                                    <span class="value">{{ ucfirst($beneficiary->mobile_wallet_provider) }}</span>
                                </div>
                            @endif
                            <div class="detail">
                                <span class="label">Added:</span>
                                <span class="value">{{ $beneficiary->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('transfers.create') }}?beneficiary={{ $beneficiary->id }}" class="btn btn-small btn-primary">Send Money</a>
                            <a href="{{ route('beneficiaries.edit', $beneficiary->id) }}" class="btn btn-small btn-outline">Edit</a>
                            <form method="POST" action="{{ route('beneficiaries.destroy', $beneficiary->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this beneficiary?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.beneficiaries-section{padding:2rem 0;min-height:60vh}
.container{max-width:1200px;margin:0 auto;padding:0 1rem}
.alert{padding:1rem;border-radius:8px;margin-bottom:1.5rem}
.alert-success{background:#f0fdf4;border:1px solid #86efac;color:#166534}
.page-actions{display:flex;justify-content:flex-end;margin-bottom:1.5rem}
.btn{padding:0.75rem 1.5rem;border:none;border-radius:8px;cursor:pointer;font-size:1rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem}
.btn-primary{background:#2563eb;color:#fff}
.btn-primary:hover{background:#1d4ed8}
.empty-state{text-align:center;padding:4rem 2rem;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.empty-icon{font-size:4rem;margin-bottom:1rem}
.empty-state h3{margin:0 0 0.5rem 0;color:#374151;font-size:1.5rem}
.empty-state p{color:#6b7280;margin-bottom:2rem}
.beneficiaries-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem}
.beneficiary-card{background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);overflow:hidden;transition:transform 0.2s}
.beneficiary-card:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.15)}
.card-header{display:flex;align-items:center;gap:1rem;padding:1.5rem;background:#f9fafb;border-bottom:1px solid #e5e7eb}
.beneficiary-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#06b6d4);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:600}
.beneficiary-info h3{margin:0;color:#111827;font-size:1.125rem}
.beneficiary-info .phone{margin:0.25rem 0;color:#059669;font-size:0.875rem;font-weight:500}
.beneficiary-info .country{margin:0.25rem 0 0 0;color:#6b7280;font-size:0.875rem}
.card-body{padding:1.5rem}
.detail{display:flex;justify-content:space-between;margin-bottom:0.75rem}
.detail .label{color:#6b7280;font-size:0.875rem}
.detail .value{color:#111827;font-weight:500;font-size:0.875rem}
.card-actions{display:flex;gap:0.5rem;padding:1rem 1.5rem;background:#f9fafb;border-top:1px solid #e5e7eb}
.btn-small{padding:0.5rem 1rem;font-size:0.875rem}
.btn-outline{background:#fff;color:#374151;border:1px solid #d1d5db}
.btn-outline:hover{background:#f9fafb}
.btn-danger{background:#dc2626;color:#fff}
.btn-danger:hover{background:#b91c1c}
@media(max-width:768px){
.beneficiaries-grid{grid-template-columns:1fr}
.card-actions{flex-direction:column}
}
</style>

@include('includes.footer')
