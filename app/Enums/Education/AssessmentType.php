<?php

namespace App\Enums\Education;

enum AssessmentType: string {
    case Quiz = 'quiz';
    case Exam = 'exam';
    case Assignment = 'assignment';
}
