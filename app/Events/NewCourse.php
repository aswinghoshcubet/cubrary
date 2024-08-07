<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCourse
{
    use Dispatchable, SerializesModels;

    public $course;

    /**
     * Create a new event instance.
     */
    public function __construct($course)
    {
        $this->course = $course;
    }
}
