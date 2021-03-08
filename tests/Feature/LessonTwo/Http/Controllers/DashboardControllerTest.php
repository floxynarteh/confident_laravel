<?php

namespace Tests\Feature\LessonTwo\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    // use WithoutMiddleware;
    use HasFactory;
    use RefreshDatabase;

    /**
     *
     *@test 
     */
    public function it_defaults_last_video_for_a_new_user()
    {
        $this->withoutExceptionHandling();
        $video = Video::factory()->create();
        //dd($video->title);

        $user = User::factory()->create();
        //dd($user["id"]);
         

        $response = $this->actingAs($user)->get('/dashboard');



        $response->assertStatus(200);
        $response->assertViewIs('videos.show');
        $response->assertViewHas('now_playing', $video);
    }



    /**
     *
     * @test
     */
    public function it_retreives_the_last_watched_video()
    {

        // $this->withoutExceptionHandling();

        // DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // DB::table('users')->truncate();
        // DB::table('videos')->truncate();



        $video = Video::factory()->create();
        //dd($video->id);


        $user = User::factory()->create([
            'last_viewed_video_id' => $video->id
        ]);
        //dd($user);
       

        $response = $this->actingAs($user)->get('/dashboard');
        

        $response->assertStatus(200);
        $response->assertViewIs('videos.show');
        $response->assertViewHas('now_playing', $video);

        // $this->assertDatabaseHas('users', [
        //     'id' => $user->id,
        //     'last_viewed_video_id' => $video->id
        // ]);

    }
     

    

    


}
