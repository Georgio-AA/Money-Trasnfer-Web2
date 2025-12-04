@include('includes.header')

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="container">
        <!-- Hero Section -->
        <div style="color: white; margin-bottom: 3rem; text-align: center;">
            <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">
                <i class="fas fa-shopping-bag me-3" style="color: #ffd700;"></i>Digital Services Store
            </h1>
            <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 0;">
                Get instant access to your favorite services
            </p>
        </div>

        <!-- Balance Card -->
        @if(session('user'))
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.2); border-left: 6px solid #11998e;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
                        <div>
                            <p style="color: #666; margin: 0; font-size: 0.95rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                <i class="fas fa-wallet me-2" style="color: #11998e;"></i>Available Balance
                            </p>
                            <h2 style="color: #11998e; margin: 0.5rem 0 0 0; font-size: 2.5rem; font-weight: 800;">
                                ${{ number_format(session('user')['balance'] ?? 0, 2) }}
                            </h2>
                        </div>
                        <div>
                            <a href="{{ route('wallet.index') }}" style="display: inline-block; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1rem 2rem; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3); transition: all 0.3s ease;">
                                <i class="fas fa-plus-circle me-2"></i> Add Funds
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Section -->
        @php
            $categories = $products->pluck('category')->unique()->values();
        @endphp

        <div class="row mb-4">
            <div class="col-md-12">
                <div style="background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <h6 style="color: #333; font-weight: 700; margin-bottom: 1.2rem; font-size: 1.05rem;">
                        <i class="fas fa-filter me-2" style="color: #667eea;"></i>Filter by Category
                    </h6>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.8rem;">
                        <a href="{{ route('store.index') }}" style="padding: 0.7rem 1.5rem; background: {{ !request('category') ? '#667eea' : '#f0f0f0' }}; color: {{ !request('category') ? 'white' : '#333' }}; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; border: 2px solid {{ !request('category') ? '#667eea' : 'transparent' }};">
                            All Categories
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('store.index', ['category' => $category]) }}" style="padding: 0.7rem 1.5rem; background: {{ request('category') == $category ? '#667eea' : '#f0f0f0' }}; color: {{ request('category') == $category ? 'white' : '#333' }}; border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; border: 2px solid {{ request('category') == $category ? '#667eea' : 'transparent' }};">
                                {{ ucfirst(str_replace('_', ' ', $category)) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; margin-top: 2rem;">
            @foreach($products as $product)
            <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer; hover-effect: 1;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)';">
                
                <!-- Product Header -->
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; color: white; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h5 style="margin: 0 0 0.5rem 0; font-size: 1.3rem; font-weight: 700;">{{ $product->name }}</h5>
                            <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">
                                <i class="fas fa-building me-2"></i>{{ $product->provider }}
                            </p>
                        </div>
                        @if(!$product->is_active)
                            <span style="background: #ff4757; padding: 0.4rem 0.8rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">Unavailable</span>
                        @endif
                    </div>
                    <span style="display: inline-block; background: rgba(255,255,255,0.3); padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; backdrop-filter: blur(10px);">
                        {{ ucfirst(str_replace('_', ' ', $product->category)) }}
                    </span>
                </div>

                <!-- Product Body -->
                <div style="padding: 1.5rem;">
                    @if($product->description)
                    <p style="color: #666; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $product->description }}
                    </p>
                    @endif

                    <!-- Price and Action -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #f0f0f0;">
                        <div>
                            <p style="color: #999; margin: 0 0 0.3rem 0; font-size: 0.85rem; font-weight: 600;">Price</p>
                            <h3 style="color: #11998e; margin: 0; font-size: 1.8rem; font-weight: 800;">
                                ${{ number_format($product->price, 2) }}
                            </h3>
                        </div>

                        @if($product->is_active)
                            @if(session('user') && (session('user')['balance'] ?? 0) >= $product->price)
                                <form action="{{ route('store.buy', $product->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                        <i class="fas fa-shopping-cart me-2"></i> Buy Now
                                    </button>
                                </form>
                            @else
                                <button style="background: #f0f0f0; color: #999; border: 2px solid #ddd; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600; cursor: not-allowed; font-size: 0.95rem;" disabled title="Insufficient balance">
                                    <i class="fas fa-exclamation-circle me-1"></i> Insufficient
                                </button>
                            @endif
                        @else
                            <button style="background: #f0f0f0; color: #999; border: 2px solid #ddd; padding: 0.8rem 1.5rem; border-radius: 12px; font-weight: 600; cursor: not-allowed; font-size: 0.95rem;" disabled>
                                <i class="fas fa-lock me-1"></i> Locked
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="background: white; border-radius: 16px; padding: 3rem; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
            <h4 style="color: #666; margin-bottom: 0.5rem;">No products available</h4>
            <p style="color: #999; margin-bottom: 0;">in this category. Try selecting a different one!</p>
        </div>
        @endif

        <!-- Quick Links Section -->
        <div style="margin-top: 3rem;">
            <div class="row">
                <div class="col-md-6">
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 4px solid #667eea;">
                        <h5 style="color: #333; margin-bottom: 0.5rem; font-weight: 700;">
                            <i class="fas fa-history me-2" style="color: #667eea;"></i> My Purchases
                        </h5>
                        <p style="color: #666; margin-bottom: 1.5rem; font-size: 0.95rem;">View your purchase history and redemption codes</p>
                        <a href="{{ route('store.my-purchases') }}" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                            <i class="fas fa-arrow-right me-2"></i> View History
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 4px solid #11998e;">
                        <h5 style="color: #333; margin-bottom: 0.5rem; font-weight: 700;">
                            <i class="fas fa-wallet me-2" style="color: #11998e;"></i> Manage Wallet
                        </h5>
                        <p style="color: #666; margin-bottom: 1.5rem; font-size: 0.95rem;">Add funds, check balance, and manage your wallet</p>
                        <a href="{{ route('wallet.index') }}" style="display: inline-block; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.8rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                            <i class="fas fa-arrow-right me-2"></i> Open Wallet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [hover-effect] {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 2rem !important;
        }
        
        [style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }

    body {
        overflow-x: hidden;
    }
</style>

@include('includes.footer')
