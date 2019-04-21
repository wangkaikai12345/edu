<?php

namespace App\Listeners;

use App\Events\CourseViewEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class CourseViewListener implements ShouldQueue
{
    use SerializesModels;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CourseViewEvent $event)
    {
        $event->course->increment('hit_count');
    }
}
