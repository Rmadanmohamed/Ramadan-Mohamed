<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_mutations_require_the_owner(): void
    {
        $this->withoutVite();
        $owner = User::create(['name' => 'Owner', 'email' => 'owner@example.test', 'password' => Hash::make('synthetic-test-password')]);
        $other = User::create(['name' => 'Other', 'email' => 'other@example.test', 'password' => Hash::make('synthetic-test-password')]);
        $this->actingAs($owner)->post(route('posts.store'), [
            'title' => 'Original', 'description' => 'Original body', 'user_id' => $other->id, 'id' => 999,
        ])->assertRedirect(route('posts.index'));
        $post = Post::where('title', 'Original')->firstOrFail();
        $this->assertEquals($owner->id, $post->user_id);
        $this->assertNotEquals(999, $post->id);

        $this->actingAs($other)->get(route('posts.show', $post))->assertOk();
        $this->get(route('posts.edit', $post))->assertForbidden();
        $this->put(route('posts.update', $post), ['title' => 'Unauthorized', 'description' => 'Changed'])->assertForbidden();
        $this->delete(route('posts.destroy', $post))->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Original']);

        $this->actingAs($owner)->put(route('posts.update', $post), [
            'title' => 'Updated', 'description' => 'Updated body', 'user_id' => $other->id,
        ])->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated', 'user_id' => $owner->id]);
        $this->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
