@php($title = 'Subscriptions')
@extends('layouts.app')

@section('content')
<div class="container-page space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="page-title">Subscriptions</h1>
        <x-button :href="route('accountant.subscriptions.create')" variant="primary">Assign Subscription</x-button>
    </div>
    <div class="card overflow-hidden">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Start</th>
                    <th>Next Billing</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subs as $s)
                    <tr>
                        <td class="px-4 py-3">{{ $s->student->first_name }} {{ $s->student->second_name }}</td>
                        <td class="px-4 py-3">{{ $s->plan->name }}</td>
                        <td class="px-4 py-3">
                            <x-badge color="{{ $s->status === 'active' ? 'green' : 'slate' }}">{{ ucfirst($s->status) }}</x-badge>
                        </td>
                        <td class="px-4 py-3">{{ optional($s->start_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ optional($s->next_billing_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-indigo-700 hover:underline px-2" href="{{ route('accountant.subscriptions.edit', $s) }}">Edit</a>
                            <form class="inline" method="POST" action="{{ route('accountant.subscriptions.destroy', $s) }}" onsubmit="return confirm('Delete this subscription?');">
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
    <div class="mt-4">{{ $subs->links() }}</div>
</div>
@endsection
