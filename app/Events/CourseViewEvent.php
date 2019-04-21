<?php

namespace App\Events;

use App\Models\Course;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CourseViewEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Course
     */
    public $course;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }
}
