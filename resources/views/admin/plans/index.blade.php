@php($title = 'Subscription Plans')
@extends('layouts.app')

@section('content')
<div class="container-page space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="page-title">Subscription Plans</h1>
        <x-button :href="route('admin.plans.create')" variant="primary">New Plan</x-button>
    </div>

    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Interval</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plans as $plan)
                    <tr>
                        <td class="px-4 py-3">{{ $plan->name }}</td>
                        <td class="px-4 py-3">{{ number_format($plan->price_cents/100, 2) }} {{ $plan->currency }}</td>
                        <td class="px-4 py-3">{{ ucfirst($plan->interval) }}</td>
                        <td class="px-4 py-3">
                            @if($plan->active)
                                <x-badge color="green">Active</x-badge>
                            @else
                                <x-badge color="slate">Inactive</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-indigo-700 hover:underline px-2" href="{{ route('admin.plans.edit', $plan) }}">Edit</a>
                            <form class="inline" method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-700 hover:underline px-2" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $plans->links() }}
    </div>
</div>
@endsection
