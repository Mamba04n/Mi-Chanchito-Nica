<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gamification\Achievement;
use App\Models\Gamification\Challenge;
use App\Enums\Gamification\AchievementCategory;
use App\Enums\Gamification\ChallengeType;

class GamificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Logros iniciales
        Achievement::create([
            'key' => 'primer_paso',
            'name' => 'Primer Paso',
            'description' => 'Completa tu primera lección.',
            'category' => AchievementCategory::Education,
            'criteria_type' => 'lesson_completed',
            'criteria_value' => 1,
            'xp_reward' => 20,
        ]);

        Achievement::create([
            'key' => 'constancia_3_dias',
            'name' => 'Con Disciplina',
            'description' => 'Mantén una racha de 3 días.',
            'category' => AchievementCategory::Activity,
            'criteria_type' => 'streak_updated',
            'criteria_value' => 3,
            'xp_reward' => 50,
        ]);

        Achievement::create([
            'key' => 'buena_nota',
            'name' => 'Buena Nota',
            'description' => 'Aprueba tu primera evaluación.',
            'category' => AchievementCategory::Education,
            'criteria_type' => 'assessment_passed',
            'criteria_value' => 1,
            'xp_reward' => 30,
        ]);

        Achievement::create([
            'key' => 'perfecto',
            'name' => 'Perfecto',
            'description' => 'Obtén 100% en una evaluación.',
            'category' => AchievementCategory::Education,
            'criteria_type' => 'perfect_assessment',
            'criteria_value' => 1,
            'xp_reward' => 50,
        ]);

        Achievement::create([
            'key' => 'primer_programa',
            'name' => 'Primer Programa',
            'description' => 'Completa tu primer programa.',
            'category' => AchievementCategory::Education,
            'criteria_type' => 'program_completed',
            'criteria_value' => 1,
            'xp_reward' => 100,
        ]);

        // 2. Retos de ejemplo
        Challenge::create([
            'key' => '2_lecciones_cxc',
            'title' => 'Dos lecciones de CxC',
            'description' => 'Completa 2 lecciones de cuentas por cobrar para ganar XP extra.',
            'challenge_type' => ChallengeType::CompleteLessons,
            'competency' => 'accounts_receivable_management',
            'target_value' => 2,
            'xp_reward' => 50,
            'active' => true,
        ]);

        Challenge::create([
            'key' => 'aprueba_quiz_inventario',
            'title' => 'Aprueba Quiz de Inventario',
            'description' => 'Demuestra tus conocimientos de inventario.',
            'challenge_type' => ChallengeType::PassAssessment,
            'competency' => 'inventory_management',
            'target_value' => 1,
            'xp_reward' => 80,
            'active' => true,
        ]);
        
        Challenge::create([
            'key' => 'ganar_100_xp',
            'title' => 'Esfuerzo del Día',
            'description' => 'Gana 100 XP hoy.',
            'challenge_type' => ChallengeType::EarnXp,
            'target_value' => 100,
            'xp_reward' => 20,
            'active' => true,
        ]);
    }
}
