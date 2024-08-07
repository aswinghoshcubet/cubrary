<?php

namespace Tests\Feature\Http\Controllers;

use App\Events\NewCourse;
use App\Jobs\SyncMedia;
use App\Models\Course;
use App\Notification\ReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CourseController
 */
final class CourseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $courses = Course::factory()->count(3)->create();

        $response = $this->get(route('courses.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CourseController::class,
            'store',
            \App\Http\Requests\CourseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = $this->faker->name();
        $description = $this->faker->text();

        Notification::fake();
        Queue::fake();
        Event::fake();

        $response = $this->post(route('courses.store'), [
            'name' => $name,
            'description' => $description,
        ]);

        $courses = Course::query()
            ->where('name', $name)
            ->where('description', $description)
            ->get();
        $this->assertCount(1, $courses);
        $course = $courses->first();

        $response->assertSessionHas('post.title', $post->title);

        Notification::assertSentTo($post->author, ReviewNotification::class, function ($notification) use ($post) {
            return $notification->post->is($post);
        });
        Queue::assertPushed(SyncMedia::class, function ($job) use ($course) {
            return $job->course->is($course);
        });
        Event::assertDispatched(NewCourse::class, function ($event) use ($course) {
            return $event->course->is($course);
        });
    }

    public function schema()
    {
        return [
            'tags' => [
                'name',
            ],
            'course' => [
                'name',
                'description',
                'short description',
                'zoom link',
                'zoom additional info',
                'start date',
                'end date',
                'tutor id',
                'status',
            ],
            'course topics' => [
                'course_id',
                'name',
                'short description',
                'description',
                'datetime',
                'estimated_duration',
            ],
            'course_tags' => [
                'course_id',
                'tag_id',
            ],
            'attachment' => [
                'name',
                'url',
                'attachable_id',
                'attachable_type',
            ],
            'course_enrollments' => [
                'course_id',
                'user_id',
            ],
            'course_reviews' => [
                'course_id',
                'user_id',
                'rating',
                'comment',
            ],
            'progress' => [
                'course_id',
                'user_id',
                'topic_id',
                'status',
            ],
            'assignments' => [
                'course_id',
                'name',
                'description',
                'due date',
                'status',
            ],
            'assignment_submissions' => [
                'assignment_id',
                'user_id',
                'description',
                'status',
                'feedback',
            ],            
        ];

        // Register mail
        // Enroll mail
        // Review mail
        // Complete mail
        // Assignment submission mail
        // Assignment feedback mail
        // Review request mail
        // 

    }
}
