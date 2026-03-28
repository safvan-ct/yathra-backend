<?php
namespace App\Enums;

enum SuggestionType: string {
    case NewEntry     = 'new_entry';
    case Update       = 'update';
    case Verification = 'verification';
}
