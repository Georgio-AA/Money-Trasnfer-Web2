@include('includes.header')

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit me-3" style="font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="font-size: 1.25rem; font-weight: 600;">
                            Edit Product
                        </h5>
                    </div>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%); border-left: 4px solid #dc3545;">
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

                    <form action="{{ route('admin.store.products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Form Section Title -->
                        <h6 style="font-weight: 700; color: #212529; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f8f9fa;">
                            Product Information
                        </h6>

                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Product Name *</label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                placeholder="e.g., Mobile Recharge 20$"
                                value="{{ old('name', $product->name) }}"
                                required
                                style="border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.95rem;"
                            >
                            @error('name')
                                <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Provider -->
                        <div class="mb-3">
                            <label for="provider" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Provider *</label>
                            <div class="input-group" style="border-radius: 6px; overflow: hidden;">
                                <input 
                                    type="text" 
                                    class="form-control @error('provider') is-invalid @enderror" 
                                    id="provider" 
                                    name="provider" 
                                    placeholder="e.g., MTC, Alfa, Netflix, Anghami"
                                    value="{{ old('provider', $product->provider) }}"
                                    list="provider-suggestions"
                                    required
                                    style="border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.95rem;"
                                >
                                @error('provider')
                                    <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <small class="text-muted d-block mt-2">
                                Suggested: MTC, Alfa, Netflix, Anghami, Cablevision, Spotify, Apple Music, Disney+, PlayStation Plus, Xbox Game Pass
                            </small>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Category *</label>
                            <select 
                                class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category"
                                required
                                style="border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.95rem;"
                            >
                                <option value="">-- Select Category --</option>
                                <option value="mobile_recharge" {{ old('category', $product->category) === 'mobile_recharge' ? 'selected' : '' }}>
                                    Mobile Recharge
                                </option>
                                <option value="streaming" {{ old('category', $product->category) === 'streaming' ? 'selected' : '' }}>
                                    Streaming Services
                                </option>
                                <option value="tv" {{ old('category', $product->category) === 'tv' ? 'selected' : '' }}>
                                    TV & Cable
                                </option>
                                <option value="gaming" {{ old('category', $product->category) === 'gaming' ? 'selected' : '' }}>
                                    Gaming & Subscriptions
                                </option>
                                <option value="music" {{ old('category', $product->category) === 'music' ? 'selected' : '' }}>
                                    Music & Audio
                                </option>
                                <option value="other" {{ old('category', $product->category) === 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing & Status Section -->
                        <h6 style="font-weight: 700; color: #212529; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f8f9fa;">
                            Pricing & Status
                        </h6>

                        <!-- Price -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Price (USD) *</label>
                                <div class="input-group" style="border-radius: 6px; overflow: hidden;">
                                    <span class="input-group-text" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">$</span>
                                    <input 
                                        type="number" 
                                        class="form-control @error('price') is-invalid @enderror" 
                                        id="price" 
                                        name="price" 
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                        value="{{ old('price', $product->price) }}"
                                        required
                                        style="border-radius: 0; padding: 0.75rem 1rem; font-size: 0.95rem;"
                                    >
                                    @error('price')
                                        <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Status *</label>
                                <select 
                                    class="form-select @error('is_active') is-invalid @enderror" 
                                    id="is_active" 
                                    name="is_active"
                                    required
                                    style="border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.95rem;"
                                >
                                    <option value="">-- Select Status --</option>
                                    <option value="1" {{ old('is_active', $product->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('is_active', $product->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold" style="color: #212529; margin-bottom: 0.5rem;">Description</label>
                            <textarea 
                                class="form-control @error('description') is-invalid @enderror" 
                                id="description" 
                                name="description" 
                                rows="3"
                                placeholder="Optional: Brief description of the product or service"
                                style="border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.95rem; resize: vertical;"
                            >{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block mt-2" style="color: #dc3545;">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Max 255 characters</small>
                        </div>

                        <!-- Product Info -->
                        <div class="alert alert-secondary alert-dismissible fade show border-0 mb-4" style="background: linear-gradient(135deg, #e2e3e5 0%, #d3d4d6 100%); border-left: 4px solid #6c757d;">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <h6 style="color: #495057; font-weight: 600; margin-bottom: 0.75rem;">
                                <i class="fas fa-info-circle me-2"></i> Product Details
                            </h6>
                            <p class="mb-2" style="color: #495057;">
                                <strong>Product ID:</strong> {{ $product->id }}
                            </p>
                            <p class="mb-2" style="color: #495057;">
                                <strong>Total Orders:</strong> {{ $product->orders_count ?? 0 }}
                            </p>
                            <p class="mb-0" style="color: #495057;">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i A') }}
                            </p>
                            @if($product->updated_at->ne($product->created_at))
                                <p class="mb-0" style="color: #495057;">
                                    <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i A') }}
                                </p>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4 pt-2">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100" style="padding: 0.75rem 1rem; font-weight: 600; border-radius: 6px; font-size: 1rem;">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary w-100" style="padding: 0.75rem 1rem; font-weight: 600; border-radius: 6px; font-size: 1rem;">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
