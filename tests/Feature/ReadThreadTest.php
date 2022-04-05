<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        //esse teste verifica a pergunta que está sendo feita na propria função
        //se eu entrar nesse endpoint, devo receber um status 200 e visualizar os threads
        $response = $this->get('/threads');
        //assertSee verifica se ele consegue observa o que está passando como parametro
        $response->assertSee($this->thread->title);

    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {

        $this->get($this->thread->path())
            -> assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_associated_with_thread()
    {
        //criamos o reply
       $reply = factory('App\Reply')
            ->create(['thread_id'=>$this->thread->id]);

       //visitamos a pagina da thread onde está o reply e vemos se o reply está lá
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        // $threadNotInChannel = create('App\Thread');

       $this->get('/threads/' . $channel->slug)
        ->assertSee($threadInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'John']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=John')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_popularity()
    {
        //criando thread com 2 replies
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        //criando thread com 3 replies
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));;
    }
}
