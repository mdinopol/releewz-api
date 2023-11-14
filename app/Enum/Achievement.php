<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Achievement: string
{
    use EnumToArray;

    // Match/Bout-specific
    case SCORE = 'score';
    case WIN   = 'win';
    case DRAW  = 'draw';

    // Player-specific/Extra predictions (Applicable for SPAN Duration Typed games)
    case CHAMPION                         = 'champion';
    case MOST_VALUABLE_PLAYER             = 'most_valuable_player';
    case ROOKIE_OF_THE_YEAR               = 'rookie_of_the_year';
    case DEFENSIVE_PLAYER_OF_THE_YEAR     = 'defensive_player_of_the_year';
    case MOST_IMPROVED_PLAYER_OF_THE_YEAR = 'most_improved_player_of_the_year';

    // Game-specific
    /*
    | -----------------------------
    | Basketball
    | -----------------------------
    */
    case FIELD_GOAL         = 'field_goal';
    case THREE_POINT        = 'three_point';
    case FREE_THROW         = 'free_throw';
    case REBOUND            = 'rebound';
    case OFFENSIVE_REBOUND  = 'offensive_rebound';
    case DEFFENSIVE_REBOUND = 'defensive_rebound';
    case ASSIST             = 'assist';
    case STEAL              = 'steal';
    case TURNOVER           = 'turnover';
    case BLOCK              = 'block';
    case FOUL               = 'foul';

    /*
    | -----------------------------
    | Soccer
    | -----------------------------
    */
    case GOAL            = 'goal';
    case CLEAN_SHEET     = 'clean_sheet';
    case BALL_POSSESSION = 'ball_possession';
    case GOAL_ATTEMPT    = 'goal_attempt';
    case CORNER_KICK     = 'corner_kick';
    case SAVE            = 'save';
    case GOAL_SAVE       = 'goal_save';
    case PENALTY_SAVE    = 'penalty_save';
    case RED_CARD        = 'red_card';
    case YELLOW_CARD     = 'yellow_card';

    /*
    | -----------------------------
    | Tennis
    | -----------------------------
    */
    case ACES            = 'aces';
    case DOUBLE_FAULT    = 'double_fault';
    case FIRST_SERVE     = 'first_serve';
    case RECEIVING_POINT = 'receiving_point';
    case BREAK_POINT     = 'break_point';
    case POINTS_IN_ROW   = 'points_in_row';
    case GAME_WON        = 'game_won';
    case TIE_BREAKER_WON = 'tie_breaker_won';
    case SET_DIFFERENCE  = 'set_difference';
}
