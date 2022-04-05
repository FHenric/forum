<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }
    
    /** @test */
    function an_authenticated_user_may_participate_in_forun_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply');

        //quando o usuario adiciona uma resposta na thread
        $this->post($thread->path() . '/replies', $reply->toArray());

        //logo essa resposta deve estar visivel na pagina
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }
    
}
