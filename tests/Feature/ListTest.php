<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Models\Project;

class ListTest extends TestCase
{
    /**
     * Check the list configuration endpoint.
     *
     * @return void
    */
    public function test_list_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->get(route('proton.config.list', [
            'entity_code' => 'project'
        ]));
         
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('fields', 6)
            ->has('fields.0', fn (AssertableJson $json) =>
                $json->where('title', 'id')
                     ->where('key', 'id')
                     ->where('sortable', true)
            )->has('fields.1', fn (AssertableJson $json) =>
                $json->where('title', 'user_id')
                     ->where('key', 'user_id')
                     ->where('sortable', false)
            )->has('fields.2', fn (AssertableJson $json) =>
                $json->where('title', 'name')
                     ->where('key', 'name')
                     ->where('sortable', true)
            )->has('fields.3', fn (AssertableJson $json) =>
                $json->where('title', 'description')
                     ->where('key', 'description')
                     ->where('sortable', false)
            )->has('fields.4', fn (AssertableJson $json) =>
                $json->where('title', 'priority')
                     ->where('key', 'priority')
                     ->where('sortable', true)
            )->where('primary_key', 'id')
            ->where('can_create', true)
            ->where('entity_label', 'Project')
            ->has('version')
            ->has('page_size_options')
        );
    }
    
    /**
     * Check the list data fetch endpoint.
     *
     * @return void
    */
    public function test_list_data_endpoint() : void
    { 
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->call('GET', route('proton.data.list', [
            'entity_code' => 'project',
            'page' => 1,
            'items_per_page' => 5,
        ]), [
            'sortField' => 'id',
            'sortOrder' => 'desc'
        ]);
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('totalRows', 2)
            ->has('data', 2)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', 2)
                     ->where('user_id', $user->name)
                     ->where('name', 'Fun')
                     ->where('description', 'Non-boring things to do.')
                     ->where('priority', 'normal')
            )
            ->has('data.1', fn (AssertableJson $json) =>
                $json->where('id', 1)
                     ->where('user_id', $user->name)
                     ->where('name', 'Do it yourself')
                     ->where('description', 'All the DIY jobs that need to be done.')
                     ->where('priority', 'normal')
            )
            ->has('permissions', 2)
            ->has('permissions.1', fn (AssertableJson $json) =>
                $json->where('update', true)
                     ->where('view', true)
                     ->where('delete', true)
            )
            ->has('permissions.2', fn (AssertableJson $json) =>
                $json->where('update', false)
                     ->where('view', false)
                     ->where('delete', false)
            )
        );
    }
    
    /**
     * Check the list data fetch endpoint when a context
     * has been applied.
     *
     * @return void
    */
    public function test_filtered_list_data_endpoint() : void
    { 
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->call('GET', route('proton.data.list', [
            'entity_code' => 'task',
            'page' => 1,
            'items_per_page' => 5,
        ]), [
            'contextCode' => 'project',
            'contextId' => '1',
        ]);
         
        $response->assertStatus(200);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('totalRows', 2)
            ->has('data', 2)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', 1)
                    ->etc()
            )
            ->has('data.1', fn (AssertableJson $json) =>
                $json->where('id', 2)
                    ->etc()
            )
            ->etc()
        );
    }
    
    /**
     * Check the list configuration endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_list_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.config.list', [
            'entity_code' => 'project'
        ]));
         
        $response->assertStatus(403);
    }
    
    /**
     * Check the list data fetch endpoint with an unauthed user.
     *
     * @return void
    */
    public function test_unauthed_list_data_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.data.list', [
            'entity_code' => 'project',
            'page' => 1,
            'items_per_page' => 5,
            'sort_by' => 'null',
        ]));
         
        $response->assertStatus(403);
    }
    
}
