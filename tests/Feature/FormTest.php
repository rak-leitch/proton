<?php declare(strict_types = 1);

namespace Adepta\Proton\Tests\Feature;

use Adepta\Proton\Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Adepta\Proton\Tests\Models\User;
use Adepta\Proton\Tests\Models\Project;

class FormTest extends TestCase
{
    /**
     * Check the form create configuration endpoint.
     *
     * @return void
    */
    public function test_form_create_config_endpoint() : void
    {
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->get(route('proton.config.form-create', [
            'entity_code' => 'project',
        ]));
         
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('config', fn (AssertableJson $json) =>
                $this->checkFormConfigFields($json, $user)
            )->has('data', fn (AssertableJson $json) =>
                $json->where('user_id', null)
                     ->where('name', null)
                     ->where('description', null)
                     ->where('priority', null)
            )
        );
    }
    
    /**
     * Check the form update configuration endpoint.
     *
     * @return void
    */
    public function test_form_update_config_endpoint() : void
    {        
        $user = User::findOrFail(1);
        $this->actingAs($user);
        
        $response = $this->get(route('proton.config.form-update', [
            'entity_code' => 'project',
            'entity_id' => 1
        ]));
         
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('config', fn (AssertableJson $json) =>
                $this->checkFormConfigFields($json, $user)
            )->has('data', fn (AssertableJson $json) =>
                $json->where('user_id', 1)
                     ->where('name', 'Do it yourself')
                     ->where('description', 'All the DIY jobs that need to be done.')
                     ->where('priority', 'normal')
            )
        );
    }
    
    /**
     * Check the form creation submission endpoint.
     *
     * @return void
    */
    public function test_form_create_submit_endpoint() : void
    { 
        $this->actingAs(User::findOrFail(1));
        
        $fieldValues = [
            'name' => 'Project for testing',
            'user_id' => 1,
            'description' => 'Some testing description.',
            'priority' => 'normal',
        ];
        
        $response = $this->postJson(route('proton.submit.form-create', [
            'entity_code' => 'project',
        ]), $fieldValues);
        
        $response->assertStatus(200);
        
        $newProject = Project::where(['name' => $fieldValues['name']])->first();
        $this->assertNotNull($newProject);
        $this->assertEquals($fieldValues, $newProject->only(array_keys($fieldValues)));
    }
    
    /**
     * Check the form update submission endpoint.
     *
     * @return void
    */
    public function test_form_update_submit_endpoint() : void
    { 
        $this->actingAs(User::findOrFail(1));
        $entityId = 1;
        
        $fieldValues = [
            'name' => 'Updated test project',
            'user_id' => 1,
            'description' => 'Updated test description.',
            'priority' => 'urgent',
        ];
        
        $response = $this->postJson(route('proton.submit.form-update', [
            'entity_code' => 'project',
            'entity_id' => $entityId,
        ]), $fieldValues);
        
        $response->assertStatus(200);
        
        $updatedProject = Project::find($entityId);
        $this->assertNotNull($updatedProject);
        $this->assertEquals($fieldValues, $updatedProject->only(array_keys($fieldValues)));
    }
    
    /**
     * Check the form update submission with an invalid user_id
     *
     * @return void
    */
    public function test_update_submit_with_other_user_id() : void
    { 
        $this->actingAs(User::findOrFail(1));
        $entityId = 1;
        
        $fieldValues = [
            'name' => 'Changed user',
            'user_id' => 2,
            'description' => 'Changed to another user without permision.',
            'priority' => 'normal',
        ];
        
        $response = $this->postJson(route('proton.submit.form-update', [
            'entity_code' => 'project',
            'entity_id' => 1,
        ]), $fieldValues);
        
        $response->assertStatus(403);
    }
    
    /**
     * Check failed validation response for create
     *
     * @return void
    */
    public function test_form_create_submit_endpoint_validation() : void
    { 
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->postJson(route('proton.submit.form-create', [
            'entity_code' => 'project',
        ]), []);
        
        $response->assertStatus(422);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $this->checkFormValidationErrors($json)
        );
    }
    
    /**
     * Check failed validation response for update
     *
     * @return void
    */
    public function test_form_update_submit_endpoint_validation() : void
    { 
        $this->actingAs(User::findOrFail(1));
        
        $response = $this->postJson(route('proton.submit.form-update', [
            'entity_code' => 'project',
            'entity_id' => 1,
        ]), []);
        
        $response->assertStatus(422);
        
        $response->assertJson(fn (AssertableJson $json) =>
            $this->checkFormValidationErrors($json)
        );
    }
    
    /**
     * Check the form create configuration endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_create_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->get(route('proton.config.form-create', [
            'entity_code' => 'project'
        ]));
         
        $response->assertStatus(403);
    }
    
    /**
     * Check the form update configuration endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_update_config_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(2));
        
        $response = $this->get(route('proton.config.form-update', [
            'entity_code' => 'project',
            'entity_id' => 2
        ]));
         
        $response->assertStatus(403);
    }
    
    /**
     * Check the form create submission endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_create_submit_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(3));
        
        $response = $this->postJson(route('proton.submit.form-create', [
            'entity_code' => 'project',
        ]), []);
         
        $response->assertStatus(403);
    }
    
    /**
     * Check the form update submission endpoint with a 
     * user that does not have permission.
     *
     * @return void
    */
    public function test_unauthed_update_submit_endpoint() : void
    {        
        $this->actingAs(User::findOrFail(2));
        
        $response = $this->postJson(route('proton.submit.form-update', [
            'entity_code' => 'project',
            'entity_id' => 2,
        ]), []);
         
        $response->assertStatus(403);
    }
    
    /**
     * Check the form configuration fields - these
     * should be identical in both creates and updates.
     *
     * @return void
    */
    private function checkFormConfigFields(AssertableJson $json, User $user) : void
    {
        $json->has('fields', 4)
            ->has('fields.0', fn (AssertableJson $json) =>
                $json->where('title', 'User')
                    ->where('key', 'user_id')
                    ->where('relatedEntityCode', 'user')
                    ->where('frontendType', 'select')
                    ->where('required', true)
                    ->where('selectOptions.0.value', 1)
                    ->has('selectOptions', 1)
                    ->has('selectOptions.0', fn (AssertableJson $json) =>
                        $json->where('value', 1)
                             ->where('title', $user->name)
                    )
            )->has('fields.1', fn (AssertableJson $json) =>
                $json->where('title', 'Name')
                    ->where('key', 'name')
                    ->where('relatedEntityCode', '')
                    ->where('frontendType', 'text')
                    ->where('required', true)
                    ->has('selectOptions', 0)
            )->has('fields.2', fn (AssertableJson $json) =>
                $json->where('title', 'Project Description')
                    ->where('key', 'description')
                    ->where('relatedEntityCode', '')
                    ->where('frontendType', 'textarea')
                    ->where('required', false)
                    ->has('selectOptions', 0)
            )->has('fields.3', fn (AssertableJson $json) =>
                $json->where('title', 'Priority')
                    ->where('key', 'priority')
                    ->where('relatedEntityCode', '')
                    ->where('frontendType', 'text')
                    ->where('required', true)
                    ->has('selectOptions', 0)
            );
    }
    
    /**
     * Check the form validation errors - these
     * should be identical in both creates and updates.
     *
     * @return void
    */
    private function checkFormValidationErrors(AssertableJson $json) : void
    {
        $json->has('errors', fn (AssertableJson $json) =>
            $json->where('name.0', 'The name field must be present.')->etc()
        )->etc();
    }
}
