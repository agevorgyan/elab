<?php

namespace Tests\Feature;

use Database\Seeders\ElabAgencySeeder;
use Database\Seeders\SystemSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemSettingsSeeder::class);
        $this->seed(ElabAgencySeeder::class);
    }

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('eLab');
        $response->assertSee('Ծառայություններ');
    }

    public function test_services_index_and_detail_routes(): void
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertSee('Ծառայությունները');

        $detailResponse = $this->get('/services/corporate-website');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Կորպորատիվ Կայք');
    }

    public function test_portfolio_index_and_detail_routes(): void
    {
        $response = $this->get('/portfolio');
        $response->assertStatus(200);
        $response->assertSee('Պորտֆոլիոն');

        $detailResponse = $this->get('/portfolio/littleprince-am');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('LittlePrince.am');
    }

    public function test_contact_page_and_lead_submission(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);

        $submitResponse = $this->post('/contact', [
            'name' => 'Արմեն Թեստյան',
            'email' => 'test@elab.am',
            'phone' => '+374 99 123456',
            'company' => 'eLab Test LLC',
            'project_type' => 'Web Development',
            'budget' => '500,000 - 1,000,000 AMD',
            'message' => 'Սա թեստային հաղորդագրություն է կայքի պատրաստման համար։',
        ], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('leads', [
            'email' => 'test@elab.am',
            'name' => 'Արմեն Թեստյան',
        ]);
    }
}
