<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_stored_new_post(): void
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');
        $file = File::create('my_file.png');
        $data = [
            'title' => 'Some title',
            'description' => 'Some description',
            'image' => $file,
        ];
        $response = $this->post('/posts', $data);

        $response->assertOk();
        $this->assertDatabaseCount('posts', 1);

        $post = Post::first();
        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($data['description'], $post->description);
        $this->assertEquals('images/' . $file->hashName(), $post->image);

        Storage::disk('public')->assertExists($post->image);

    }

    public function test_validate_title_is_required_for_storing_post()
    {
        $data = [
            'title' => '',
            'description' => 'Some description'
        ];
        $response = $this->post('/posts', $data);
        $response->assertRedirect();
        $response->assertInvalid('title');
    }
}
