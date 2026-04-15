<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Slot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotRegistrationTest extends TestCase
{
    // Cette ligne permet de vider la base de données de TEST après chaque essai
    use RefreshDatabase;

    public function test_un_utilisateur_ne_peut_pas_se_desister_a_moins_de_24h()
    {
        $user = User::factory()->create(['role' => 'volunteer']);

        // On crée une mission "Buvette" qui commence dans 2 heures
        // C'est bien moins de 24h, donc le désistement DOIT être bloqué.
        $slot = Slot::factory()->create([
            'category' => 'Buvette',
            'start_time' => now()->addHours(2),
            'end_time' => now()->addHours(4)
        ]);

        // On inscrit l'utilisateur manuellement en base de données
        $user->slots()->attach($slot->id);

        // On tente la suppression
        $response = $this->actingAs($user)
            ->delete(route('slots.unregister', $slot));

        // On vérifie qu'on est redirigé avec un message d'erreur
        $response->assertSessionHas('error');

        // On vérifie qu'il est TOUJOURS inscrit
        $this->assertCount(1, $slot->fresh()->users);
    }

    public function test_on_peut_creer_une_mission_sans_description()
    {
        // 1. ARRANGE : On crée un admin (si ta route est protégée)
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. ACT : On tente de créer une mission sans le champ description
        $response = $this->actingAs($admin)
            ->post(route('slots.store'), [
                'title' => 'Mission Test Sans Desc',
                'category' => 'PÔLE CAISSE',
                'start_time' => now()->addDays(1)->format('Y-m-d H:i:s'), // Format texte
                'end_time' => now()->addDays(1)->addHours(2)->format('Y-m-d H:i:s'), // Format texte
                'min_volunteers' => 1,
                'max_volunteers' => 10,
            ]);

        // 3. ASSERT : On vérifie que la mission existe en base
        $this->assertDatabaseHas('slots', [
            'title' => 'Mission Test Sans Desc',
            'description' => null
        ]);

        $response->assertRedirect(); // Vérifie qu'on est redirigé après création
    }

    public function test_un_utilisateur_non_admin_est_redirige_s_il_tente_de_creer_une_mission()
    {
        $user = User::factory()->create(['role' => 'benevole']);

        $response = $this->actingAs($user)->get(route('slots.create'));

        // On vérifie qu'il est redirigé vers l'index avec une erreur
        $response->assertRedirect(route('slots.index'));
        $response->assertSessionHas('error', 'Accès réservé aux administrateurs.');
    }

    public function test_un_benevole_ne_peut_pas_acceder_a_la_liste_infirmerie_via_url()
    {
        $volunteer = User::factory()->create(['role' => 'volunteer']);

        // On tente d'accéder à l'URL directement
        $response = $this->actingAs($volunteer)
            ->get('/slots/category/Infirmerie');

        // On s'attend à ce que l'accès soit refusé (403)
        $response->assertStatus(403);
    }

    public function test_un_benevole_ne_peut_pas_sinscrire_a_infirmerie()
    {
        // 1. Un bénévole standard
        $user = User::factory()->create(['role' => 'volunteer']);

        // 2. Une mission Infirmerie qui commence dans 3 jours 
        // (Comme ça, on est sûr que la règle des 24h ne nous dérange pas)
        $slot = Slot::factory()->create([
            'category' => 'Infirmerie',
            'start_time' => now()->addDays(3),
            'end_time' => now()->addDays(3)->addHours(2),
        ]);

        // 3. Tentative d'inscription
        $response = $this->actingAs($user)
            ->post(route('slots.register', $slot));

        // 4. On vérifie que la session a BIEN une erreur
        $response->assertSessionHas('error');

        // 5. On vérifie que la base de données est toujours vide pour ce slot
        $this->assertCount(0, $slot->fresh()->users);
    }

    public function test_un_infirmier_peut_sinscrire_a_une_mission_infirmerie()
    {
        $infirmier = User::factory()->create(['role' => 'infirmier']);
        $slot = Slot::factory()->create(['category' => 'Infirmerie']);

        $response = $this->actingAs($infirmier)
            ->post(route('slots.register', $slot));

        $this->assertCount(1, $slot->fresh()->users);
    }

    public function test_un_admin_ne_peut_pas_sinscrire_a_une_mission_infirmerie()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $slot = Slot::factory()->create(['category' => 'Infirmerie']);

        $response = $this->actingAs($admin)
            ->post(route('slots.register', $slot));

        // MODIFIE CETTE LIGNE CI-DESSOUS :
        $response->assertSessionHas('error', 'Seuls les infirmiers peuvent s\'inscrire à ces missions.');

        $this->assertCount(0, $slot->fresh()->users);
    }

    public function test_un_utilisateur_ne_peut_pas_sinscrire_si_la_mission_est_complete()
    {
        $slot = Slot::factory()->create(['max_volunteers' => 1]);
        $volunteer1 = User::factory()->create(['role' => 'volunteer']);
        $volunteer2 = User::factory()->create(['role' => 'volunteer']);

        // Le premier s'inscrit : OK
        $this->actingAs($volunteer1)->post(route('slots.register', $slot));

        // Le deuxième tente de s'inscrire : DOIT ÉCHOUER
        $response = $this->actingAs($volunteer2)->post(route('slots.register', $slot));

        $this->assertCount(1, $slot->fresh()->users); // Il ne doit y avoir qu'une personne
    }
}
