<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\TeamRequest;
use App\Models\User;
use Tests\TestCase;

class ExploreProjectViewTest extends TestCase
{
    public function test_user_can_apply_to_two_projects_from_the_same_owner(): void
    {
        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $projects = collect(['Projek Satu', 'Projek Dua'])->map(fn ($title) => Project::create([
            'owner_id' => $owner->id,
            'title' => $title,
            'description' => 'Deskripsi projek',
            'status' => 'ongoing',
        ]));

        try {
            $this->actingAs($applicant);
            foreach ($projects as $project) {
                $this->post(route('projects.join', $project))
                    ->assertRedirect(route('explore.application', $project));
            }
            $this->assertSame(2, TeamRequest::where('sender_id', $applicant->id)
                ->whereIn('project_id', $projects->pluck('id'))
                ->count());
        } finally {
            TeamRequest::whereIn('project_id', $projects->pluck('id'))->delete();
            $projects->each(fn ($project) => $project->delete());
            $applicant->delete();
            $owner->delete();
        }
    }

    public function test_join_request_shows_pending_then_opens_workspace_after_approval(): void
    {
        $owner = User::factory()->create();
        $applicant = User::factory()->create();
        $project = Project::create([
            'owner_id' => $owner->id,
            'title' => 'Projek Alur Gabung',
            'description' => 'Deskripsi alur gabung',
            'status' => 'ongoing',
            'roles' => [['name' => 'Laravel', 'criteria' => 'PHP']],
        ]);

        try {
            $this->actingAs($applicant)->get(route('explore'))
                ->assertOk()
                ->assertSee(route('explore.detail', $project), false)
                ->assertSee(route('projects.join', $project), false);

            $this->get(route('explore.detail', $project))
                ->assertOk()
                ->assertSee('Projek Alur Gabung')
                ->assertSee('assets/detail-', false)
                ->assertSee(route('projects.join', $project), false);

            $this->post(route('projects.join', $project))
                ->assertRedirect(route('explore.application', $project));

            $application = TeamRequest::where('sender_id', $applicant->id)
                ->where('project_id', $project->id)
                ->firstOrFail();
            $this->assertSame('pending', $application->status);
            $this->get(route('explore.application', $project))
                ->assertOk()
                ->assertSee('Belum Diterima');
            $this->get(route('projects.workspace', $project))->assertForbidden();
            $this->get(route('explore.detail', $project))
                ->assertOk()
                ->assertSee('Belum Diterima');

            $this->actingAs($owner)->post(route('projects.requests.decide', [
                'project' => $project,
                'teamRequest' => $application,
                'decision' => 'terima',
            ]))->assertRedirect();

            $this->actingAs($applicant)->get(route('explore.application', $project))
                ->assertRedirect(route('projects.workspace', $project));
            $this->get(route('explore.detail', $project))
                ->assertOk()
                ->assertSee('Kelola Projek');
            $this->get(route('projects.workspace', $project))
                ->assertOk()
                ->assertSee('Sudah Diterima');
        } finally {
            TeamRequest::where('project_id', $project->id)->delete();
            $project->members()->detach();
            $project->delete();
            $applicant->delete();
            $owner->delete();
        }
    }
}
