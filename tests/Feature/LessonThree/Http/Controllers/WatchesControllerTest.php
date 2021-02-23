<?php

namespace Tests\Feature\LessonThree\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WatchesControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     * @test
     */
    public function store_returns_a_204()
    {


        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $video = Video::factory()->create();



        $event = Event::fake();

        // $mock = \Mockery::mock();
        // $mock->expects('info')
        //     ->with('video.watched', [$video->id]);
        // Event::swap($mock);

        $response = $this->actingAs($user)->post(route('watches.store'),[
            'user_id' => $user->id,
            'video_id'=> $video->id,
        ]);


        $response->assertStatus(204);

        //call back function.
        $event->assertDispatched('video.watched', function($event, $arguments) use($video){
            $this->assertEquals([$video->id], $arguments, 'The arguments passed to the [' . $event. '] event were unexpected');

                return true;


        });


        $this->assertDatabaseHas('watches', [
            'user_id' => $user->id,
            'video_id'=> $video->id,
        ]);
    }
}
