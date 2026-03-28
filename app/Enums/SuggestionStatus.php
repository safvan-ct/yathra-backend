<?php
namespace App\Enums;

enum SuggestionStatus: string {
    case Pending  = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Flagged  = 'Flagged';
}
