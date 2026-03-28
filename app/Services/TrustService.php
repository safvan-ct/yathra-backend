<?php
namespace App\Services;

use App\Enums\SuggestionType;
use App\Models\User;

class TrustService
{
    public function updateTrustScore(User $user, int $scoreChange): void
    {
        $newScore = max(0, $user->trust_score + $scoreChange);

        $user->trust_score = $newScore;
        $user->trust_level = $this->calculateTrustLevel($newScore);
        $user->save();
    }

    /**
     * Calculate Trust level based on threshold mapping.
     * 0 - 49 -> low, 50 - 149 -> medium, 150+ -> high
     */
    protected function calculateTrustLevel(int $score): string
    {
        if ($score >= 150) {
            return 'high';
        } elseif ($score >= 50) {
            return 'medium';
        }

        return 'low';
    }

    public function onSuggestionApproved(User $user, SuggestionType $type): void
    {
        $scoreModifier = match ($type) {
            SuggestionType::NewEntry     => 10,
            SuggestionType::Update       => 5,
            SuggestionType::Verification => 3,
        };

        $this->updateTrustScore($user, $scoreModifier);
    }

    public function onSuggestionRejected(User $user): void
    {
        $this->updateTrustScore($user, -5);
    }

    public function onSuggestionFlagged(User $user): void
    {
        $this->updateTrustScore($user, -10);
    }
}
