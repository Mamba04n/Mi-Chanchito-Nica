<?php

namespace App\Enums\Education;

enum QuestionType: string {
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case TrueFalse = 'true_false';
}
