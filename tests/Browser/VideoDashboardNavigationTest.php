<?php

namespace Tests\Browser;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use App\Models\Order;
use App\Models\Product;
use App\Models\Video;
use App\Models\Watch;
use Tests\DuskTestCase;

class VideoDashboardNavigationTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     *
     * @test
     */
    public function it_displays_course_navigations_correctly()
    {
        $this->browse(function (Browser $browser) {
            $order = Order::factory()->create();

            $freeLesson = Lesson::factory()->create([
                    'ordinal' => 1,
            ]);

            $freeVideos =factory(Video::class, 2)->create(
                [
                    'lesson_id' => $freeLesson->id
            ]);

            $packageLesson = Lesson::factory()->create([
                        'ordinal' => 2,
                        'product_id' => $order->product_id,
            ]); 


            $packageVideos =factory(Video::class, 3)->create(
                [
                    'lesson_id' => $freeLesson->id
                ]);
            
            $paidLesson = Lesson::factory()->create(
                [
                    'ordinal' => 3,
                    'product_id' => Product::factory()->create()->id,
            ]); 

            $paidVideos =factory(Video::class, 2)->create(
                [
                    'lesson_id' => $freeLesson->id
                ])->sortBy('ordinal')->values();


            $freeVideos->push($packageVideos[0])->each(function ($video) use ($order){
                 Watch::create([
                     'video_id'=> $video->id,
                     'user_id' => $order->user_id
                 ]);
            });


            $order->user->last_viewed_video_id = $packageVideos[1]->id;
            $order->user->save();


            $browser->loginAs($order->user)
                    ->visit('/dashboard')
                    ->assertSee($freeLesson->name);
         });

    }
}
