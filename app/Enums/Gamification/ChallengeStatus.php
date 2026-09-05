<?php
namespace App\Enums\Gamification;
enum ChallengeStatus: string {
    case Assigned = 'assigned';
    case Active = 'active';
    case Completed = 'completed';
    case Expired = 'expired';
    case Cancelled = 'cancelled';
}