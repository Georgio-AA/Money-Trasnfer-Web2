@include('includes.header')

<section class="page-header">
    <h1>Find Transfer Services</h1>
    <p>Filter by country, fees, speed, payout method, and offers.</p>
    </section>

<section class="filters">
    <div class="container">
        <form method="GET" action="{{ route('transfer-services.index') }}" class="filter-form">
            <div class="grid">
                <div class="field">
                    <label>Source Currency</label>
                    <input type="text" name="source_currency" value="{{ $filters['source_currency'] }}" placeholder="USD">
                </div>
                <div class="field">
                    <label>Destination Currency</label>
                    <input type="text" name="target_currency" value="{{ $filters['target_currency'] }}" placeholder="EUR">
                </div>
                <div class="field">
                    <label>Destination Country</label>
                    <input type="text" name="destination_country" value="{{ $filters['destination_country'] }}" placeholder="FR">
                </div>
                <div class="field">
                    <label>Max Fee %</label>
                    <input type="number" step="0.01" name="max_fee_percent" value="{{ $filters['max_fee_percent'] }}" placeholder="e.g. 2.5">
                </div>
                <div class="field">
                    <label>Transfer Speed</label>
                    <select name="speed">
                        <option value="">Any</option>
                        @foreach(['instant'=>'Instant','same_day'=>'Same Day','next_day'=>'Next Day','standard'=>'Standard'] as $k=>$v)
                            <option value="{{ $k }}" {{ $filters['speed']===$k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Payout Method</label>
                    <select name="payout_method">
                        <option value="">Any</option>
                        @foreach(['bank_deposit'=>'Bank Deposit','cash_pickup'=>'Cash Pickup','mobile_wallet'=>'Mobile Wallet'] as $k=>$v)
                            <option value="{{ $k }}" {{ $filters['payout_method']===$k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field checkbox">
                    <label>
                        <input type="checkbox" name="offers" value="1" {{ $filters['offers'] ? 'checked' : '' }}> Offers & Promotions
                    </label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        @if($midRate)
            <div class="midrate">Mid-market rate {{ $filters['source_currency'] }}→{{ $filters['target_currency'] }}: <strong>{{ $midRate }}</strong></div>
        @endif
    </div>
</section>

<section class="results">
    <div class="container">
        @if($services->count())
            <div class="cards">
                @foreach($services as $svc)
                    <div class="card">
                        <div class="card-header">
                            <h3>{{ $svc->name }}</h3>
                            @if($svc->has_promotions)
                                <span class="badge promo">Promo</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div><strong>Speed:</strong> {{ str_replace('_',' ',ucfirst($svc->transfer_speed)) }}</div>
                            <div><strong>Fees:</strong> {{ rtrim(rtrim(number_format($svc->fee_percent,2), '0'),'.') }}% + {{ number_format($svc->fee_fixed,2) }}</div>
                            <div><strong>Payout:</strong> {{ implode(', ', array_map('ucwords', str_replace('_',' ', $svc->payout_methods ?? []))) }}</div>
                            @if($svc->indicative_rate)
                                <div><strong>Indicative Rate:</strong> {{ $filters['source_currency'] }}→{{ $filters['target_currency'] }} = {{ $svc->indicative_rate }}</div>
                            @endif
                            <div class="meta">Supports: {{ implode(', ', $svc->destination_countries ?? []) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty">No services match your filters. Try broadening your search.</div>
        @endif
    </div>
</section>

<style>
.page-header{background:linear-gradient(135deg,#22c55e,#06b6d4);color:#fff;padding:2rem;text-align:center}
.filters,.results{padding:1.25rem}.container{max-width:1100px;margin:0 auto}
.filter-form .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-bottom:12px}
.field label{display:block;font-weight:600;margin-bottom:6px}
.field input,.field select{width:100%;padding:.5rem;border:1px solid #e5e7eb;border-radius:6px}
.field.checkbox{display:flex;align-items:end}
.btn{padding:.6rem 1rem;border:none;border-radius:8px;cursor:pointer}
.btn-primary{background:#2563eb;color:#fff}
.cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}
.card{border:1px solid #e5e7eb;border-radius:10px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.card-header{display:flex;justify-content:space-between;align-items:center;padding:.9rem 1rem;border-bottom:1px solid #f3f4f6}
.card-body{padding:1rem;color:#374151}
.badge.promo{background:#fde68a;color:#92400e;border-radius:999px;padding:.2rem .6rem;font-size:.8rem}
.midrate{margin:.75rem 0;color:#1f2937}
.empty{padding:2rem;text-align:center;color:#6b7280}
</style>

@include('includes.footer')