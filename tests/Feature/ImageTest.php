<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImageTest extends TestCase
{
    use WithoutMiddleware, DatabaseMigrations;

    public function testImageUploadOk()
    {
        Storage::fake('local');
        $fileName = Str::random() . ".jpg";
        $file = UploadedFile::fake()->image($fileName, 1024, 768);
        $response = $this->postJson(route("image.store"), ["file" => $file]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'filename',
            ]
        ]);

        Storage::assertExists($fileName);
        $this->assertDatabaseHas('images', ['filename' => $fileName]);
    }

    public function testImageUploadBadRequest()
    {
        $file = UploadedFile::fake()->create("test.txt");
        $response = $this->postJson(route("image.store"), ["file" => '']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->postJson(route("image.store"), ["file" => $file]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

