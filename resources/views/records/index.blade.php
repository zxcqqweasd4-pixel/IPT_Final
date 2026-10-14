@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center page-heading gap-2">
        <div>
            <h1 class="h2 mb-1">{{ $title }}</h1>
            <p class="text-secondary mb-0">Manage {{ strtolower($title) }} and their records.</p>
        </div>
        <a class="btn btn-primary" href="{{ route($resource.'.create') }}">Add {{ $singular }}</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    @foreach ($columns as $label)
                        <th scope="col">{{ $label }}</th>
                    @endforeach
                    <th scope="col" class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($records as $record)
                    <tr>
                        @foreach ($columns as $key => $label)
                            @php
                                $value = data_get($record, $key);
                                if ($value instanceof \Illuminate\Support\Carbon) {
                                    $value = $value->format('M j, Y');
                                } elseif (str_ends_with($key, '.first_name') && $value) {
                                    $value .= ' '.data_get($record, substr($key, 0, -10).'last_name');
                                }
                            @endphp
                            <td>{{ $value ?: '—' }}</td>
                        @endforeach
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route($resource.'.show', $record) }}">View</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route($resource.'.edit', $record) }}">Edit</a>
                            <button class="btn btn-sm btn-outline-danger" type="button"
                                    data-bs-toggle="modal" data-bs-target="#deleteRecordModal"
                                    data-delete-url="{{ route($resource.'.destroy', $record) }}"
                                    data-record-name="{{ $singular }} #{{ $record->getKey() }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) + 1 }}" class="text-center text-secondary py-4">No {{ strtolower($title) }} found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($records instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="card-footer bg-white">{{ $records->links() }}</div>
        @endif
    </div>

    <div class="modal fade" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="deleteRecordModalLabel">Confirm deletion</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Delete <strong id="deleteRecordName"></strong>? This action cannot be undone.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteRecordForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
