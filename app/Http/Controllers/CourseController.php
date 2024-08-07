<?php

namespace App\Http\Controllers;

use App\Events\NewCourse;
use App\Http\Requests\CourseStoreRequest;
use App\Jobs\SyncMedia;
use App\Models\Course;
use App\Notification\ReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;

class CourseController extends Controller
{
    public function index(Request $request): Response
    {
        $courses = Course::all();
    }

    public function store(CourseStoreRequest $request): Response
    {
        $course = Course::create($request->validated());

        Notification::send($post->author, new ReviewNotification($post));

        SyncMedia::dispatch($course);

        NewCourse::dispatch($course);

        $request->session()->flash('post.title', $post->title);
    }
}
