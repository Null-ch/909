<div>
    @if($products->isNotEmpty())
        <section class="product-related py-5 bg-light">
            <div class="container">
                <h2 class="section-title mb-4">Похожие товары</h2>
                <div class="row g-4">
                    @foreach($products as $relatedProduct)
                        <div class="col-xl-3 col-lg-4 col-md-6" wire:key="related-product-{{ $relatedProduct->id }}">
                            <livewire:product-card
                                :product="$relatedProduct"
                                :key="'related-card-'.$product->id.'-'.$relatedProduct->id"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
