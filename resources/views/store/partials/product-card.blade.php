<a href="{{ route('store.products.show', $product->slug) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group flex flex-col">
    <div class="aspect-square bg-gray-50 relative overflow-hidden">
        @if($product->primaryMedia)
            <img src="{{ asset('storage/' . $product->primaryMedia->file_path) }}" alt="{{ $product->name_ar }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        @if($product->sale_price)
            <span class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} bg-red-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
            </span>
        @endif
        @if($product->is_featured)
            <span class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'left-3' : 'right-3' }} bg-primary text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                ★
            </span>
        @endif
    </div>
    <div class="p-4 flex flex-col flex-1">
        @if($product->category)
            <span class="text-[11px] text-gray-400 uppercase tracking-wide font-medium mb-1">{{ app()->getLocale() === 'ar' ? $product->category->name_ar : $product->category->name_en }}</span>
        @endif
        <h3 class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2 mb-2 group-hover:text-primary transition-colors">{{ app()->getLocale() === 'ar' ? $product->name_ar : $product->name_en }}</h3>
        <div class="mt-auto flex items-center gap-2">
            <span class="text-lg font-bold text-primary">{{ number_format((float)$product->getDisplayPrice(), 2) }}</span>
            <span class="text-xs text-gray-400">{{ __('messages.currency') }}</span>
            @if($product->sale_price)
                <span class="text-sm text-gray-400 line-through">{{ number_format((float)$product->price, 2) }}</span>
            @endif
        </div>
    </div>
</a>
