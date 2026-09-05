<?php

namespace App\Enums\Education;

enum ProcessingStatus: string {
    case Pending = 'pending';
    case Processed = 'processed';
    case Failed = 'failed';
}
