<?php

namespace App\Enums\Education;

enum SourceStatus: string {
    case Pending = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';
    case Archived = 'archived';
}
