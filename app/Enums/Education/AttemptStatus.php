<?php

namespace App\Enums\Education;

enum AttemptStatus: string {
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
    case Graded = 'graded';
    case Expired = 'expired';
}
