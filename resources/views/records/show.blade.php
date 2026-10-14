@extends('layouts.app')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center page-heading gap-2">
        <div>
            <h1 class="h2 mb-1">{{ $title }} details</h1>
            <p class="text-secondary mb-0">Record #{{ $record->getKey() }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route($resource.'.index') }}">Back to {{ strtolower($title) }}</a>
            <a class="btn btn-primary" href="{{ route($resource.'.edit', $record) }}">Edit {{ $singular }}</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                @foreach ($showFields as $key => $label)
                    @php
                        $value = data_get($record, $key);
                        if ($value instanceof \Illuminate\Support\Carbon) {
                            $value = $value->format('M j, Y');
                        } elseif (str_ends_with($key, '.first_name') && $value) {
                            $value .= ' '.data_get($record, substr($key, 0, -10).'last_name');
                        }
                    @endphp
                    <dt class="col-sm-3 py-2">{{ $label }}</dt>
                    <dd class="col-sm-9 py-2 mb-0">{{ $value ?: '—' }}</dd>
                @endforeach
            </dl>
        </div>
    </div>

    @if ($relatedLessons->isNotEmpty())
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Related lessons</h2>
            <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-outline-primary">All lessons</a>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Date</th><th>Student</th><th>Instructor</th><th>Course</th><th>Vehicle</th><th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($relatedLessons as $lesson)
                        <tr>
                            <td><a href="{{ route('lessons.show', $lesson) }}">{{ $lesson->lesson_date->format('M j, Y') }}</a></td>
                            <td>{{ $lesson->student?->first_name }} {{ $lesson->student?->last_name }}</td>
                            <td>{{ $lesson->instructor?->first_name }} {{ $lesson->instructor?->last_name }}</td>
                            <td>{{ $lesson->course?->name }}</td>
                            <td>{{ $lesson->vehicle?->plate_number ?? '—' }}</td>
                            <td>{{ ucfirst($lesson->status) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif (! $record instanceof \App\Models\Lesson)
        <div class="alert alert-light border">No lessons are linked to this {{ $singular }} yet.</div>
    @endif
@endsection
