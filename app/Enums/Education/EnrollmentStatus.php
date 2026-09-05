<?php

namespace App\Enums\Education;

enum EnrollmentStatus: string {
    case Active = 'active';
    case Completed = 'completed';
    case Paused = 'paused';
    case Cancelled = 'cancelled';
}
