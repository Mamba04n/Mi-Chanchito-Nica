<?php

namespace App\Enums\Education;

enum PublishStatus: string {
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
