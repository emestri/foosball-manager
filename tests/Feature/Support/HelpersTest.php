<?php

namespace Tests\Feature\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_resolve_model_returns_model_instance_when_given_model_instance()
    {
        $modelInstance = User::factory()->create();

        $result = resolveModel(User::class, $modelInstance);

        $this->assertSame($modelInstance->id, $result->id);
    }

    public function test_resolve_model_finds_model_by_id()
    {
        $modelInstance = User::factory()->create();
        $result = resolveModel(User::class, $modelInstance->id);

        $this->assertSame($modelInstance->id, $result->id);
    }

    public function test_resolve_model_throws_exception_when_model_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        resolveModel(User::class, 999);
    }
}
