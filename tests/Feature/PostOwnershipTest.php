<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_is_assigned_server_side_and_only_validated_fields_are_used(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($owner)->post(route('posts.store'), [
            'title' => 'Test title', 'description' => 'Test body', 'user_id' => $other->id, 'id' => 12345,
        ])->assertRedirect(route('posts.index'));
        $post = Post::sole();
        $this->assertEquals($owner->id, $post->user_id);
        $this->assertNotEquals(12345, $post->id);
    }

    public function test_non_owner_cannot_edit_update_or_delete(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = $owner->posts()->create(['title' => 'Original', 'description' => 'Body']);
        $this->actingAs($other)->get(route('posts.edit', $post))->assertForbidden();
        $this->put(route('posts.update', $post), ['title' => 'Changed', 'description' => 'Body'])->assertForbidden();
        $this->delete(route('posts.destroy', $post))->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Original']);
    }

    public function test_owner_can_update_and_delete(): void
    {
        $owner = User::factory()->create();
        $post = $owner->posts()->create(['title' => 'Original', 'description' => 'Body']);
        $this->actingAs($owner)->put(route('posts.update', $post), ['title' => 'Changed', 'description' => 'Body'])
            ->assertRedirect(route('posts.index'));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Changed']);
        $this->delete(route('posts.destroy', $post))->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_guest_cannot_create_posts(): void
    {
        $this->post(route('posts.store'), ['title' => 'Guest', 'description' => 'Body'])->assertRedirect(route('login'));
        $this->assertDatabaseCount('posts', 0);
    }
}
