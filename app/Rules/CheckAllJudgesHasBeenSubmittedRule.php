<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckAllJudgesHasBeenSubmittedRule implements Rule
{
    private $ID_contestant;
    private $ID_games;

    public function __construct($ID_contestant, $ID_games)
    {
        $this->ID_contestant = $ID_contestant;
        $this->ID_games = $ID_games;
    }

    public function passes($attribute, $value)
    {
        $score_list = \DB::table('score_list')
                        ->where('ID_contestant', $this->ID_contestant)
                        ->where('ID_games', $this->ID_games)
                        ->where('ID_judge', '!=', 0)
                        ->get();
        $games = \DB::table('games')
            ->where('ID_games', $this->ID_games)
            ->first();
        $judge_list = \DB::table('judge_list')
            ->where('ID_competition', $games->ID_competition)
            ->get();
        $score_list_judge = \DB::table('score_list')
            ->where('ID_contestant', $this->ID_contestant)
            ->where('ID_games', $this->ID_games)
            ->where('ID_judge', '!=', 0)
            ->get();
        // check foreach judges that listed on judge_list for this ID_competition is already set
        $score_list = [];
        foreach ($judge_list as $judge) {
            $is_exist = false;
            foreach ($score_list_judge as $score) {
                if ($judge->ID_judge == $score->ID_judge) {
                    $is_exist = true;
                    array_push($score_list, $score->ID_judge);
                    break;
                }
            }
            if (!$is_exist) {
                return false;
                break;
            }
        }

        return true; // Return true if validation passes; otherwise, return false.
    }

    public function message()
    {
        return __('messages.response_score_is_not_complete');
    }
}
