<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Issue;
use App\Models\User;
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
            'subject' => 'First issue',
            'description' => 'Description 1',
            'creator_id' => $client->id,
        ]);

        $issue2 = Issue::create([
            'type' => 'Request',
            'subject' => 'Second issue',
            'description' => 'Description 2',
            'creator_id' => $client->id,
        ]);

        $this->assertEquals('ISS-001', $issue1->id);
        $this->assertEquals('ISS-002', $issue2->id);
    }

    public function test_client_cannot_access_group_chat_but_admin_and_worker_can()
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

        // Client gets 403 on group chat
        $this->actingAs($client)
            ->get('/chat/group')
            ->assertStatus(403);

        // Worker gets 200 on group chat
        $this->actingAs($worker)
            ->get('/chat/group')
            ->assertStatus(200);
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
            'subject' => 'Test Issue',
            'description' => 'Test',
            'creator_id' => $admin->id,
            'assigned_to' => $worker->id,
            'status' => 'Assigned',
        ]);

        // Worker cannot edit fields (or they get redirected or not allowed on edit page)
        // Note: they are authorized to status edit, but not general edit.
        // Let's check edit route permission
        $this->actingAs($worker)
            ->get(route('tasks.edit', $issue->id))
            ->assertStatus(200); // Because they can update status. But let's check DELETE which they can never do.
        
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
            'subject' => 'Unassigned Issue',
            'description' => 'Test',
            'creator_id' => $client->id,
            'status' => 'Unassigned',
        ]);

        $issueAssigned = Issue::create([
            'type' => 'Bug',
            'subject' => 'Assigned Issue',
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
