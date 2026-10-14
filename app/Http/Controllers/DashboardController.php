<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $upcomingLessons = Lesson::query()
            ->with(['student', 'instructor', 'vehicle', 'course'])
            ->where('status', 'scheduled')
            ->where(function ($query) {
                $query->whereDate('lesson_date', '>', today())
                    ->orWhere(function ($today) {
                        $today->whereDate('lesson_date', today())
                            ->where('start_time', '>=', now()->format('H:i:s'));
                    });
            })
            ->orderBy('lesson_date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'counts' => [
                'Students' => Student::count(),
                'Instructors' => Instructor::count(),
                'Vehicles' => Vehicle::count(),
                'Courses' => Course::count(),
                'Lessons' => Lesson::count(),
            ],
            'upcomingLessons' => $upcomingLessons,
        ]);
    }
}
