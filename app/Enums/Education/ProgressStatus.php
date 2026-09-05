<?php

namespace App\Enums\Education;

enum ProgressStatus: string {
    case NotStarted = 'not_started';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
