<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalDocument extends Model
{
    use HasFactory;

    protected $table = 'educational_documents';

    protected $fillable = ['educational_source_id', 'title', 'description', 'original_url', 'document_type', 'language', 'published_at', 'processing_status', 'metadata'];

    protected $casts = ['document_type' => \App\Enums\Education\DocumentType::class, 'processing_status' => \App\Enums\Education\ProcessingStatus::class, 'published_at' => 'date', 'metadata' => 'array'];


    public function source() { return $this->belongsTo(EducationalSource::class, 'educational_source_id'); }
        
}
