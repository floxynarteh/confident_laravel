<?php

namespace Tests\Unit\LessonFour\Providers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Spatie\Newsletter\Newsletter;

class EventServiceProviderTest extends TestCase
{
    use RefreshDatabase;
    /**
     *
     * @test
     */
    public function order_placed_event_subscribes_users_and_tags_package()
    {

        $order = Order::factory()->create();

        $newsletter = \Mockery::mock();
        $newsletter->shouldReceive('subscribe')
                ->with($order->user->email);

    
        $newsletter->shouldReceive('addTags')
                 ->with([$order->product->name], $order->user->email);

        \Newsletter::swap($newsletter);        
        event('order.placed', $order);
       
    }

     /**
     *
     * @test
     */
    public function video_watched_tags_subscriber_with_video()
    {

       

        $newsletter = \Mockery::mock();
        $user = User::factory()->create();

        $video_id = $this->faker->randomDigitNotNull;
        $newsletter->shouldReceive('addTags')
                 ->with(['Video-'.$video_id], $user->email);

        \Newsletter::swap($newsletter);        
        event('video.watched', [$user, $video_id]);
       
    }
}
