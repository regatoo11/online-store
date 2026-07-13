@extends('layouts.admin')

@section('title', __('messages.settings'))

@section('header-left')
    <h1 class="text-2xl font-bold text-gray-900">{{ __('messages.settings') }}</h1>
@endsection

@section('content')
<div x-data="{ activeTab: 'general' }">
    {{-- Tabs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-100">
            <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">
                {{ __('messages.general') }}
            </button>
            <button @click="activeTab = 'shipping'"
                    :class="activeTab === 'shipping' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">
                {{ __('messages.shipping') }}
            </button>
            <button @click="activeTab = 'payment'"
                    :class="activeTab === 'payment' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">
                {{ __('messages.payment') }}
            </button>
            <button @click="activeTab = 'maintenance'"
                    :class="activeTab === 'maintenance' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors">
                {{ __('messages.maintenance') }}
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PATCH')

        {{-- General --}}
        <div x-show="activeTab === 'general'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.general_settings') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="general.store_name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.store_name') }}</label>
                    <input type="text"
                           id="general.store_name"
                           name="general[store_name]"
                           value="{{ $settings['general']['store_name'] ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="general.store_email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.store_email') }}</label>
                    <input type="email"
                           id="general.store_email"
                           name="general[store_email]"
                           value="{{ $settings['general']['store_email'] ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="general.store_phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.store_phone') }}</label>
                    <input type="text"
                           id="general.store_phone"
                           name="general[store_phone]"
                           value="{{ $settings['general']['store_phone'] ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label for="general.currency" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.currency') }}</label>
                    <select id="general.currency"
                            name="general[currency]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        <option value="SAR" {{ ($settings['general']['currency'] ?? '') === 'SAR' ? 'selected' : '' }}>SAR</option>
                        <option value="USD" {{ ($settings['general']['currency'] ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="AED" {{ ($settings['general']['currency'] ?? '') === 'AED' ? 'selected' : '' }}>AED</option>
                        <option value="EGP" {{ ($settings['general']['currency'] ?? '') === 'EGP' ? 'selected' : '' }}>EGP</option>
                    </select>
                </div>

                <div>
                    <label for="general.locale" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.default_locale') }}</label>
                    <select id="general.locale"
                            name="general[locale]"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                        <option value="ar" {{ ($settings['general']['locale'] ?? '') === 'ar' ? 'selected' : '' }}>العربية</option>
                        <option value="en" {{ ($settings['general']['locale'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>

                <div>
                    <label for="general.timezone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.timezone') }}</label>
                    <input type="text"
                           id="general.timezone"
                           name="general[timezone]"
                           value="{{ $settings['general']['timezone'] ?? 'Asia/Riyadh' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                </div>
            </div>

            <div>
                <label for="general.store_description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.store_description') }}</label>
                <textarea id="general.store_description"
                          name="general[store_description]"
                          rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">{{ $settings['general']['store_description'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- Shipping --}}
        <div x-show="activeTab === 'shipping'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.shipping_settings') }}</h2>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="shipping[free_shipping_enabled]" value="0">
                    <input type="checkbox"
                           id="shipping.free_shipping_enabled"
                           name="shipping[free_shipping_enabled]"
                           value="1"
                           {{ ($settings['shipping']['free_shipping_enabled'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="shipping.free_shipping_enabled" class="text-sm font-medium text-gray-700">{{ __('messages.enable_free_shipping') }}</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="shipping.free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.free_shipping_threshold') }}</label>
                        <input type="number"
                               id="shipping.free_shipping_threshold"
                               name="shipping[free_shipping_threshold]"
                               value="{{ $settings['shipping']['free_shipping_threshold'] ?? '' }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="shipping.default_shipping_cost" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.default_shipping_cost') }}</label>
                        <input type="number"
                               id="shipping.default_shipping_cost"
                               name="shipping[default_shipping_cost]"
                               value="{{ $settings['shipping']['default_shipping_cost'] ?? '' }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="shipping.shipping_class" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.shipping_class') }}</label>
                        <input type="text"
                               id="shipping.shipping_class"
                               name="shipping[shipping_class]"
                               value="{{ $settings['shipping']['shipping_class'] ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="shipping.estimated_delivery" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.estimated_delivery_days') }}</label>
                        <input type="number"
                               id="shipping.estimated_delivery"
                               name="shipping[estimated_delivery]"
                               value="{{ $settings['shipping']['estimated_delivery'] ?? '' }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div x-show="activeTab === 'payment'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.payment_settings') }}</h2>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="payment[cod_enabled]" value="0">
                    <input type="checkbox"
                           id="payment.cod_enabled"
                           name="payment[cod_enabled]"
                           value="1"
                           {{ ($settings['payment']['cod_enabled'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="payment.cod_enabled" class="text-sm font-medium text-gray-700">{{ __('messages.enable_cod') }}</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="payment[transfer_enabled]" value="0">
                    <input type="checkbox"
                           id="payment.transfer_enabled"
                           name="payment[transfer_enabled]"
                           value="1"
                           {{ ($settings['payment']['transfer_enabled'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="payment.transfer_enabled" class="text-sm font-medium text-gray-700">{{ __('messages.enable_bank_transfer') }}</label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="payment[require_receipt]" value="0">
                    <input type="checkbox"
                           id="payment.require_receipt"
                           name="payment[require_receipt]"
                           value="1"
                           {{ ($settings['payment']['require_receipt'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="payment.require_receipt" class="text-sm font-medium text-gray-700">{{ __('messages.require_payment_receipt') }}</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment.tax_rate" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.tax_rate') }} (%)</label>
                        <input type="number"
                               id="payment.tax_rate"
                               name="payment[tax_rate]"
                               value="{{ $settings['payment']['tax_rate'] ?? '' }}"
                               step="0.01"
                               min="0"
                               max="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="payment.tax_enabled" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.tax_status') }}</label>
                        <select id="payment.tax_enabled"
                                name="payment[tax_enabled]"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
                            <option value="1" {{ ($settings['payment']['tax_enabled'] ?? false) ? 'selected' : '' }}>{{ __('messages.enabled') }}</option>
                            <option value="0" {{ !($settings['payment']['tax_enabled'] ?? false) ? 'selected' : '' }}>{{ __('messages.disabled') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div x-show="activeTab === 'maintenance'" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.maintenance_settings') }}</h2>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="maintenance[mode]" value="0">
                    <input type="checkbox"
                           id="maintenance.mode"
                           name="maintenance[mode]"
                           value="1"
                           {{ ($settings['maintenance']['mode'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                    <label for="maintenance.mode" class="text-sm font-medium text-gray-700">{{ __('messages.enable_maintenance_mode') }}</label>
                </div>

                <div>
                    <label for="maintenance.maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.maintenance_message') }}</label>
                    <textarea id="maintenance.maintenance_message"
                              name="maintenance[maintenance_message]"
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">{{ $settings['maintenance']['maintenance_message'] ?? '' }}</textarea>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">{{ __('messages.maintenance_mode_warning') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="mt-6 flex justify-end">
            <button type="submit"
                    class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                {{ __('messages.save_settings') }}
            </button>
        </div>
    </form>
</div>
@endsection
