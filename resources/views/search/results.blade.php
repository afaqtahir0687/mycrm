@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="content-card">
    <div class="form-header">
        <h1 class="form-title">Search Results for "{{ $query }}"</h1>
        <div style="color: #666;">Found {{ $total }} results</div>
    </div>
    
    @if($results['leads']->count() > 0)
    <div style="margin-bottom: 30px;">
        <h2 style="color: #1976d2; margin-bottom: 15px;">Leads ({{ $results['leads']->count() }})</h2>
        <table class="table-container">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['leads'] as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['subtitle'] ?? 'N/A' }}</td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td><a href="{{ $item['url'] }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($results['contacts']->count() > 0)
    <div style="margin-bottom: 30px;">
        <h2 style="color: #1976d2; margin-bottom: 15px;">Contacts ({{ $results['contacts']->count() }})</h2>
        <table class="table-container">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['contacts'] as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['subtitle'] }}</td>
                    <td><a href="{{ $item['url'] }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($results['accounts']->count() > 0)
    <div style="margin-bottom: 30px;">
        <h2 style="color: #1976d2; margin-bottom: 15px;">Accounts ({{ $results['accounts']->count() }})</h2>
        <table class="table-container">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Industry</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['accounts'] as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['subtitle'] }}</td>
                    <td><a href="{{ $item['url'] }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($results['deals']->count() > 0)
    <div style="margin-bottom: 30px;">
        <h2 style="color: #1976d2; margin-bottom: 15px;">Deals ({{ $results['deals']->count() }})</h2>
        <table class="table-container">
            <thead>
                <tr>
                    <th>Deal Name</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results['deals'] as $item)
                <tr>
                    <td>{{ $item['title'] }}</td>
                    <td>{{ $item['subtitle'] }}</td>
                    <td><a href="{{ $item['url'] }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    @if($total == 0)
    <div style="text-align: center; padding: 40px; color: #666;">
        <p>No results found for "{{ $query }}"</p>
        <p>Try different keywords or check your spelling.</p>
    </div>
    @endif
</div>
@endsection

