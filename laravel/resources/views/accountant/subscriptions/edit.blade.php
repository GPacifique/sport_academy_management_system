@php($title = 'Edit Subscription')
@extends('layouts.app')

@section('content')
<div class="container-page">
    <div class="card">
        <div class="card-header"><h1 class="page-title">Edit Subscription</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ route('accountant.subscriptions.update', $subscription) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="label mb-1">Student</div>
                        <div class="input">{{ $subscription->student->first_name }} {{ $subscription->student->second_name }}</div>
                    </div>
                    <x-form.select label="Plan" name="subscription_plan_id">
                        @foreach($plans as $p)
                            <option value="{{ $p->id }}" @selected(old('subscription_plan_id', $subscription->subscription_plan_id)==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.select label="Status" name="status">
                        <option value="active" @selected(old('status', $subscription->status)==='active')>Active</option>
                        <option value="paused" @selected(old('status', $subscription->status)==='paused')>Paused</option>
                        <option value="cancelled" @selected(old('status', $subscription->status)==='cancelled')>Cancelled</option>
                        <option value="expired" @selected(old('status', $subscription->status)==='expired')>Expired</option>
                    </x-form.select>
                    <x-form.input label="End date" name="end_date" type="date" :value="optional($subscription->end_date)->format('Y-m-d')" />
                </div>
                <div class="flex items-center justify-end gap-2">
                    <x-button :href="route('accountant.subscriptions.index')" variant="secondary">Cancel</x-button>
                    <x-button type="submit" variant="primary">Save changes</x-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
