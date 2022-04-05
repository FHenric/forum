<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    // esse teste performa um erro de falta de autenticação do usuário, caso ele tente publicar algo sem estar logado

    public function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
                    ->assertRedirect('/login');

        $this->post('/threads')
        ->assertRedirect('/login');
        
    }

    /** @test */
    public function guest_cannot_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_auth_user_can_create_new_forum_thread()
    {
        
        //Criamos aqui um usuário autenticado
        $this->signIn();

        //criamos uma thread com esse usuário
        //o make funciona diferente do create, pois ele não perciste os dados no banco, só cria um array simples contendo os dados que serão testados
        $thread = make('App\Thread');

        // ir para o endpoint de criação de uma thread
        $response = $this->post('/threads', $thread->toArray());

        //agr visitamos a thread criada
        $this->get($response->headers->get('Location'))
            //testamos/certificamos se conseguimos ver a thread nova na página
            ->assertSee($thread->title)
            ->assertSee($thread->body);
       
        
    }

     /** @test */
    function a_thread_requires_a_title()
     {
         $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
     }

     /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
           ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => 100])
           ->assertSessionHasErrors('channel_id');
    }

     public function publishThread($overrides)
     {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
     }

      /** @test */
    function unauthorized_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

     /** @test */
    function an_auth_user_can_delete_his_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', $thread->toArray());
        // $this->assertDatabaseMissing('replies', $reply->toArray());
    }
}
