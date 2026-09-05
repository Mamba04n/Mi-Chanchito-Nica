<?php

namespace App\Services\Education;

use App\Models\Education\LearningProgram;
use App\Enums\Education\PublishStatus;

class LearningCatalogService
{
    public function getPublishedPrograms()
    {
        return LearningProgram::where('status', PublishStatus::Published)
            ->with(['units.lessons'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findProgramsByCompetency(string $competency)
    {
        return LearningProgram::where('status', PublishStatus::Published)
            ->whereHas('competencies', function ($query) use ($competency) {
                $query->where('competency', $competency);
            })
            ->with(['units.lessons'])
            ->get();
    }
}
