<?php
namespace App\Enums;

enum RewardActivityType: string {
    case NewEntry     = 'new_entry';
    case Verification = 'verification';
}
