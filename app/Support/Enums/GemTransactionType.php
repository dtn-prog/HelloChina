<?php

namespace App\Support\Enums;

enum GemTransactionType: string
{
    case DailyQuest = 'daily_quest';
    case LessonCompleted = 'lesson_completed';
    case ExerciseCompleted = 'exercise_completed';
    case MatchWon = 'match_won';
    case EnergyPurchase = 'energy_purchase';
    case Streak = 'streak';
    case Achievement = 'achievement';
    case Challenge = 'challenge';
    case Chest = 'chest';
    case AdReward = 'ad_reward';
    case StreakFreeze = 'streak_freeze';
    case DoubleXp = 'double_xp';
    case MatchTicket = 'match_ticket';
    case StreakRestore = 'streak_restore';
    case ProfileDecoration = 'profile_decoration';
    case MiniGame = 'mini_game';
    case LevelUp = 'level_up';
    case AdminAdjustment = 'admin_adjustment';

    public function isDebit(): bool
    {
        return in_array($this, [
            self::EnergyPurchase,
            self::StreakFreeze,
            self::DoubleXp,
            self::MatchTicket,
            self::StreakRestore,
            self::ProfileDecoration,
            self::MiniGame,
        ], true);
    }
}
