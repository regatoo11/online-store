@extends('layouts.admin')

@section('title', __('messages.payment_details'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.payment_details') }}</h1>
@endsection

@section('header-right')
    <a href="{{ route('admin.payments.index') }}"
       class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors">
        &larr; {{ __('messages.back_to_list') }}
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Payment Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.payment_info') }}</h2>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.transaction_id') }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $payment->transaction_id ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.amount') }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.method') }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $payment->method->name_en ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.status') }}</span>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'verified' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'refunded' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.paid_at') }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $payment->paid_at?->format('Y-m-d H:i') ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 mb-1">{{ __('messages.created_at') }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Receipt --}}
        @if($payment->receipt_path)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.receipt') }}</h2>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                @if(Str::endsWith($payment->receipt_path, ['.jpg', '.jpeg', '.png', '.gif', '.webp']))
                    <img src="{{ Storage::url($payment->receipt_path) }}"
                         alt="{{ __('messages.receipt') }}"
                         class="max-w-full h-auto mx-auto">
                @else
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <a href="{{ Storage::url($payment->receipt_path) }}"
                           target="_blank"
                           class="text-primary hover:text-primary-dark text-sm font-medium transition-colors">
                            {{ __('messages.download_receipt') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Admin Notes --}}
        @if($payment->admin_notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.admin_notes') }}</h2>
            <p class="text-sm text-gray-600">{{ $payment->admin_notes }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        {{-- Order --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.order') }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.order_number') }}</span>
                    <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-primary hover:text-primary-dark transition-colors font-medium">
                        #{{ $payment->order->order_number ?? '—' }}
                    </a>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.order_total') }}</span>
                    <span class="font-medium">{{ number_format($payment->order->total ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.customer') }}</span>
                    <span>{{ $payment->order->user->name ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Verification --}}
        @if($payment->verified_by)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.verification') }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.verified_by') }}</span>
                    <span>{{ $payment->verifier->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.verified_at') }}</span>
                    <span>{{ $payment->verified_at?->format('Y-m-d H:i') ?? '—' }}</span>
                </div>
                @if($payment->rejected_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('messages.rejected_at') }}</span>
                    <span>{{ $payment->rejected_at->format('Y-m-d H:i') }}</span>
                </div>
                @endif
                @if($payment->rejected_reason)
                <div>
                    <span class="block text-gray-500 mb-1">{{ __('messages.rejection_reason') }}</span>
                    <span class="text-red-600">{{ $payment->rejected_reason }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Actions --}}
        @if($payment->status === 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.actions') }}</h3>

            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                    {{ __('messages.verify_payment') }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <input type="text"
                           name="rejected_reason"
                           placeholder="{{ __('messages.rejection_reason_placeholder') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>
                <button type="submit"
                        class="w-full bg-red-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                    {{ __('messages.reject_payment') }}
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
