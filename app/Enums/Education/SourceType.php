<?php

namespace App\Enums\Education;

enum SourceType: string {
    case University = 'university';
    case Government = 'government';
    case InternationalOrganization = 'international_organization';
    case FinancialInstitution = 'financial_institution';
    case PublicDocument = 'public_document';
    case Other = 'other';
}
