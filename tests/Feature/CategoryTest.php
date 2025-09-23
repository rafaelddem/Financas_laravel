<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index(): void
    {
        $response = $this->get(route('category.list'));

        $response->assertStatus(200);
        $this->assertArrayHasKey('categories', $response->original);
    }

    public function test_create_successfully(): void
    {
        $category = Category::factory()->make();

        $this->post(route('category.store'), $category->toArray())
            ->assertRedirect(route('category.list', ['message' => __('Data created successfully.')]));

        $this->assertDatabaseHas('categories', $category->toArray());
    }

    public function test_create_fail_without_name(): void
    {
        $categoryData = Category::factory()->make();
        unset($categoryData['name']);

        $this->post(route('category.store'), $categoryData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.required', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_duplicate_name(): void
    {
        $categoryData = Category::factory()->create();

        $this->post(route('category.store'), $categoryData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.unique', ['attribute' => 'Nome'])]);
    }

    public function test_create_fail_very_short_name(): void
    {
        $categoryData = Category::factory()->make([
            'name' => 'Na'
        ]);

        $this->post(route('category.store'), $categoryData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_very_long_name(): void
    {
        $categoryData = Category::factory()->make([
            'name' => '1234567890123456789012345678901'
        ]);

        $this->post(route('category.store'), $categoryData->toArray())
            ->assertSessionHasErrors(['name' => __('validation.between.string', ['attribute' => 'Nome', 'min' => 3, 'max' => 30])]);
    }

    public function test_create_fail_without_relevance(): void
    {
        $categoryData = Category::factory()->make();
        unset($categoryData['relevance']);

        $this->post(route('category.store'), $categoryData->toArray())
            ->assertSessionHasErrors(['relevance' => __('validation.required', ['attribute' => 'Relevância'])]);
    }

    public function test_create_fail_invalid_relevance(): void
    {
        $categoryData = Category::factory()->make()->toArray();
        $categoryData['relevance'] = '12345';

        $this->post(route('category.store'), $categoryData)
            ->assertSessionHasErrors(['relevance' => __('validation.in', ['attribute' => 'Relevância'])]);
    }

    public function test_update_successfully(): void
    {
        $categoryData = Category::factory()->create([ 'active' => true ]);
        $categoryUpdateData = Category::factory()->make([ 'active' => false ]);

        $this->put(route('category.update', ['id' => $categoryData->id]), $categoryUpdateData->toArray())
            ->assertRedirect(route('category.list', ['message' => __('Data updated successfully.')]));

        $categoryDataOriginalData = $categoryData->toArray();
        $categoryUpdatedData = $categoryData->refresh();
        $this->assertEquals($categoryUpdatedData['name'], $categoryDataOriginalData['name']);         // Atributo não pode ser modificado
        
        $this->assertEquals($categoryUpdatedData['relevance'], $categoryUpdateData['relevance']);
        $this->assertEquals($categoryUpdatedData['active'], $categoryUpdateData['active']);
    }
}
