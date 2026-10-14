@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h1 class="h2 mb-1">Campus dashboard</h1>
        <p class="text-secondary mb-0">An overview of the driving school and its upcoming lessons.</p>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($counts as $label => $count)
            @php($route = strtolower($label))
            <div class="col-6 col-lg">
                <a href="{{ route($route.'.index') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="text-secondary">{{ $label }}</div>
                            <div class="display-6 fw-semibold text-dark">{{ $count }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Upcoming lessons</h2>
        <a href="{{ route('lessons.create') }}" class="btn btn-primary">Schedule a lesson</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr><th>Date</th><th>Time</th><th>Student</th><th>Instructor</th><th>Course</th><th>Vehicle</th><th>Status</th></tr>
                </thead>
                <tbody>
                @forelse ($upcomingLessons as $lesson)
                    <tr>
                        <td><a href="{{ route('lessons.show', $lesson) }}">{{ $lesson->lesson_date->format('M j, Y') }}</a></td>
                        <td>{{ substr($lesson->start_time, 0, 5) }}–{{ substr($lesson->end_time, 0, 5) }}</td>
                        <td>{{ $lesson->student->first_name }} {{ $lesson->student->last_name }}</td>
                        <td>{{ $lesson->instructor->first_name }} {{ $lesson->instructor->last_name }}</td>
                        <td>{{ $lesson->course->name }}</td>
                        <td>{{ $lesson->vehicle?->plate_number ?? '—' }}</td>
                        <td><span class="badge text-bg-primary">{{ ucfirst($lesson->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">No upcoming lessons scheduled.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
