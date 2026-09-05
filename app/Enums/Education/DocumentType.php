<?php

namespace App\Enums\Education;

enum DocumentType: string {
    case Pdf = 'pdf';
    case Article = 'article';
    case Guide = 'guide';
    case Report = 'report';
    case CourseMaterial = 'course_material';
    case WebResource = 'web_resource';
    case Other = 'other';
}
