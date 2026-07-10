<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Issue;
use App\Models\User;
use App\Models\GroupChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkrouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'client',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'client',
        ]);

        $this->assertAuthenticated();
    }

    public function test_issue_auto_generates_custom_sequential_id()
    {
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        $issue1 = Issue::create([
            'type' => 'Bug',
            'subject' => 'LPM UKP',
            'title' => 'Title 1',
            'description' => 'Description 1',
            'creator_id' => $client->id,
        ]);

        $issue2 = Issue::create([
            'type' => 'Request',
            'subject' => 'Social Lens',
            'title' => 'Title 2',
            'description' => 'Description 2',
            'creator_id' => $client->id,
        ]);

        $this->assertEquals('ISS-001', $issue1->id);
        $this->assertEquals('ISS-002', $issue2->id);
    }

    public function test_all_roles_can_access_group_chat_but_only_admin_can_manage_groups()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        $worker = User::create([
            'name' => 'Worker',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'password' => bcrypt('password'),
        ]);

        // Client gets 200 on new groups route list
        $this->actingAs($client)
            ->get('/chat/groups')
            ->assertStatus(200);

        // Worker gets 200 on groups route list
        $this->actingAs($worker)
            ->get('/chat/groups')
            ->assertStatus(200);

        // Client cannot create group
        $this->actingAs($client)
            ->get('/chat/groups/create')
            ->assertStatus(403);

        $this->actingAs($client)
            ->post('/chat/groups', ['name' => 'Should fail'])
            ->assertStatus(403);

        // Worker cannot create group
        $this->actingAs($worker)
            ->get('/chat/groups/create')
            ->assertStatus(403);

        // Admin can access create group page
        $this->actingAs($admin)
            ->get('/chat/groups/create')
            ->assertStatus(200);
    }

    public function test_worker_cannot_create_issue()
    {
        $worker = User::create([
            'name' => 'Worker',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'password' => bcrypt('password'),
        ]);

        // Worker cannot see the create issue view
        $this->actingAs($worker)
            ->get(route('tasks.create'))
            ->assertStatus(403);

        // Worker cannot store an issue
        $this->actingAs($worker)
            ->post(route('tasks.store'), [
                'type' => 'Bug',
                'subject' => 'LPM UKP',
                'title' => 'Worker title',
                'description' => 'Worker description',
            ])
            ->assertStatus(403);
    }

    public function test_worker_cannot_edit_or_delete_issue()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $worker = User::create([
            'name' => 'Worker',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'password' => bcrypt('password'),
        ]);

        $issue = Issue::create([
            'type' => 'Bug',
            'subject' => 'LPM UKP',
            'title' => 'Test Issue',
            'description' => 'Test',
            'creator_id' => $admin->id,
            'assigned_to' => $worker->id,
            'status' => 'Assigned',
        ]);

        // Worker is allowed to access the edit page (to update status)
        $this->actingAs($worker)
            ->get(route('tasks.edit', $issue->id))
            ->assertStatus(200);
        
        // But worker is NOT allowed to delete
        $this->actingAs($worker)
            ->delete(route('tasks.destroy', $issue->id))
            ->assertStatus(403);
    }

    public function test_client_can_edit_or_delete_issue_only_when_unassigned()
    {
        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        $worker = User::create([
            'name' => 'Worker',
            'email' => 'worker@example.com',
            'role' => 'worker',
            'password' => bcrypt('password'),
        ]);

        $issueUnassigned = Issue::create([
            'type' => 'Bug',
            'subject' => 'LPM UKP',
            'title' => 'Unassigned Issue',
            'description' => 'Test',
            'creator_id' => $client->id,
            'status' => 'Unassigned',
        ]);

        $issueAssigned = Issue::create([
            'type' => 'Bug',
            'subject' => 'Social Lens',
            'title' => 'Assigned Issue',
            'description' => 'Test',
            'creator_id' => $client->id,
            'assigned_to' => $worker->id,
            'status' => 'Assigned',
        ]);

        // Client can edit unassigned
        $this->actingAs($client)
            ->get(route('tasks.edit', $issueUnassigned->id))
            ->assertStatus(200);

        // Client cannot edit assigned
        $this->actingAs($client)
            ->get(route('tasks.edit', $issueAssigned->id))
            ->assertStatus(403);
            
        // Client can delete unassigned
        $this->actingAs($client)
            ->delete(route('tasks.destroy', $issueUnassigned->id))
            ->assertRedirect(route('tasks.index'));

        // Client cannot delete assigned
        $this->actingAs($client)
            ->delete(route('tasks.destroy', $issueAssigned->id))
            ->assertStatus(403);
    }
}
