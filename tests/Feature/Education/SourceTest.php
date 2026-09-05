<?php

namespace Tests\Feature\Education;

use Tests\TestCase;
use App\Models\Education\EducationalSource;
use App\Enums\Education\SourceStatus;
use App\Enums\Education\SourceType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_source_and_require_url()
    {
        $source = EducationalSource::create([
            'institution' => 'MIT',
            'title' => 'Open Course',
            'source_type' => SourceType::University,
            'url' => 'https://mit.edu',
            'language' => 'en',
            'status' => SourceStatus::Verified,
        ]);

        $this->assertDatabaseHas('educational_sources', [
            'institution' => 'MIT',
            'status' => SourceStatus::Verified,
        ]);
        
        $this->assertEquals('https://mit.edu', $source->url);
    }
}
