@include('includes.header')

<style>
    .admin-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .admin-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .admin-hero p {
        font-size: 1rem;
        opacity: 0.9;
        margin: 0;
    }

    .form-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .form-section {
        padding: 2rem;
    }

    .form-section-title {
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #667eea;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title i {
        color: #667eea;
    }

    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .input-group-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .error-alert {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: none;
        border-left: 4px solid #dc3545;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .error-alert strong {
        color: #721c24;
    }

    .error-alert ul {
        color: #721c24;
        margin: 0.75rem 0 0 1.5rem;
    }

    .invalid-feedback {
        color: #dc3545 !important;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .info-alert {
        background: linear-gradient(135deg, #cfe2ff 0%, #b6d4fe 100%);
        border: none;
        border-left: 4px solid #667eea;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .info-alert .alert-heading {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .info-alert ul {
        color: #0c5460;
        margin: 0;
        padding-left: 1.5rem;
    }

    .info-alert li {
        margin-bottom: 0.5rem;
    }

    .form-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-cancel {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        text-decoration: none;
        text-align: center;
    }

    .btn-cancel:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    .row-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .admin-hero h1 {
            font-size: 1.5rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .row-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Hero Section -->
            <div class="admin-hero">
                <h1>
                    <i class="fas fa-plus-circle me-2"></i> Add New Product
                </h1>
                <p>Create a new digital service product for your store</p>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="form-section">
                    @if($errors->any())
                        <div class="error-alert alert alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Validation Error:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.store.products.store') }}" method="POST">
                        @csrf

                        <!-- Product Information Section -->
                        <div class="form-section-title">
                            <i class="fas fa-box"></i> Product Information
                        </div>

                        <!-- Product Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Product Name
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                placeholder="e.g., Mobile Recharge $20"
                                value="{{ old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="form-text">Give your product a clear, descriptive name</span>
                        </div>

                        <!-- Provider -->
                        <div class="mb-4">
                            <label for="provider" class="form-label">
                                Provider/Service Name
                                <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('provider') is-invalid @enderror" 
                                id="provider" 
                                name="provider" 
                                placeholder="e.g., MTC, Netflix, Spotify"
                                value="{{ old('provider') }}"
                                list="provider-suggestions"
                                required
                            >
                            @error('provider')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <datalist id="provider-suggestions">
                                <option value="MTC">
                                <option value="Alfa">
                                <option value="Netflix">
                                <option value="Anghami">
                                <option value="Cablevision">
                                <option value="Spotify">
                                <option value="Apple Music">
                                <option value="Disney+">
                                <option value="PlayStation Plus">
                                <option value="Xbox Game Pass">
                            </datalist>
                            <span class="form-text">Popular: MTC, Alfa, Netflix, Spotify, Apple Music, Disney+</span>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="form-label">
                                Category
                                <span class="required">*</span>
                            </label>
                            <select 
                                class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category"
                                required
                            >
                                <option value="">-- Select a Category --</option>
                                <option value="mobile_recharge" {{ old('category') === 'mobile_recharge' ? 'selected' : '' }}>
                                    📱 Mobile Recharge
                                </option>
                                <option value="streaming" {{ old('category') === 'streaming' ? 'selected' : '' }}>
                                    🎬 Streaming Services
                                </option>
                                <option value="tv" {{ old('category') === 'tv' ? 'selected' : '' }}>
                                    📺 TV & Cable
                                </option>
                                <option value="gaming" {{ old('category') === 'gaming' ? 'selected' : '' }}>
                                    🎮 Gaming & Subscriptions
                                </option>
                                <option value="music" {{ old('category') === 'music' ? 'selected' : '' }}>
                                    🎵 Music & Audio
                                </option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>
                                    ⭐ Other
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="3"
                                placeholder="Optional: Describe what customers get with this product..."
                                style="resize: vertical;"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="form-text">Maximum 255 characters - Keep it brief and informative</span>
                        </div>

                        <!-- Pricing & Status Section -->
                        <div class="form-section-title" style="margin-top: 2rem;">
                            <i class="fas fa-dollar-sign"></i> Pricing & Status
                        </div>

                        <div class="row-grid mb-4">
                            <!-- Price -->
                            <div>
                                <label for="price" class="form-label">
                                    Price (USD)
                                    <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input 
                                        type="number" 
                                        class="form-control @error('price') is-invalid @enderror" 
                                        id="price" 
                                        name="price" 
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('price') }}"
                                        required
                                    >
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <span class="form-text">Set the price customers will pay</span>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="form-label">
                                    Status
                                    <span class="required">*</span>
                                </label>
                                <select 
                                    class="form-select @error('is_active') is-invalid @enderror" 
                                    id="is_active" 
                                    name="is_active"
                                    required
                                >
                                    <option value="">-- Select Status --</option>
                                    <option value="1" {{ old('is_active') === '1' || !old('is_active') ? 'selected' : '' }}>
                                        ✓ Active
                                    </option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>
                                        ✕ Inactive
                                    </option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <span class="form-text">Active products are visible to customers</span>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i> Create Product
                            </button>
                            <a href="{{ route('admin.store.products.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="info-alert alert-dismissible fade show" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb me-2"></i> Tips for Creating Products
                </h6>
                <ul>
                    <li><strong>Product Name:</strong> Be clear and specific (e.g., "Netflix 1-Month Premium" not just "Netflix")</li>
                    <li><strong>Providers:</strong> Help organize products and are shown to customers on purchase</li>
                    <li><strong>Categories:</strong> Choose the most relevant category to help customers search</li>
                    <li><strong>Active Status:</strong> Only active products appear in the store for customers</li>
                    <li><strong>Description:</strong> Briefly explain what customers receive with this purchase</li>
                    <li><strong>Pricing:</strong> Keep prices competitive and in line with market rates</li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
