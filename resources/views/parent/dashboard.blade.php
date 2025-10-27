@php($title = 'Parent Dashboard')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <h1 class="page-title mb-6">My Children</h1>
    
    @if($children->isEmpty())
        <div class="card">
            <div class="card-body text-center py-8 text-slate-500">
                No children associated with your account yet.
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($children as $child)
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            {{ $child->first_name }} {{ $child->last_name }}
                        </h2>
                        
                        @if($child->subscriptions->isEmpty())
                            <div class="text-sm text-slate-500 mb-4">No active subscription</div>
                        @else
                            @php($subscription = $child->subscriptions->first())
                            <div class="space-y-3 mb-4">
                                <div>
                                    <div class="text-sm text-slate-600">Plan</div>
                                    <div class="font-medium text-slate-900">{{ $subscription->plan->name }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-600">Status</div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($subscription->status === 'active') bg-green-100 text-green-800
                                        @elseif($subscription->status === 'paused') bg-yellow-100 text-yellow-800
                                        @elseif($subscription->status === 'expired') bg-red-100 text-red-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-600">Outstanding Balance</div>
                                    <div class="font-semibold 
                                        @if($subscription->outstanding_balance > 0) text-red-600
                                        @else text-green-600
                                        @endif">
                                        {{ number_format($subscription->outstanding_balance/100, 2) }} RWF
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-600">Total Invoiced</div>
                                    <div class="text-slate-900">{{ number_format($subscription->total_invoiced/100, 2) }} RWF</div>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-600">Total Paid</div>
                                    <div class="text-slate-900">{{ number_format($subscription->total_paid/100, 2) }} RWF</div>
                                </div>
                            </div>
                        @endif
                        
                        <x-button :href="route('parent.child-payments', $child)" variant="primary" class="w-full">
                            View Payment History
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection