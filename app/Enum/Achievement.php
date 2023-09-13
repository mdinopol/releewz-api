<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Achievement
{
    use EnumToArray;

    // Match/Bout-specific
    case SCORE;
    case WIN;
    case DRAW;
    // Player-specific/Extra predictions (Applicable for SPAN Duration Typed games)
    case CHAMPION;
    case MOST_VALUABLE_PLAYER;
    case ROOKIE_OF_THE_YEAR;
    case DEFENSIVE_PLAYER_OF_THE_YEAR;
    case MOST_IMPROVED_PLAYER_OF_THE_YEAR;
    // Game-specific
    /*
    | -----------------------------
    | Basketball
    | -----------------------------
    */
    case FIELD_GOAL;
    case TWO_POINT;
    case THREE_POINT;
    case FREE_THROW;
    case REBOUND;
    case OFFENSIVE_REBOUND;
    case DEFFENSIVE_REBOUND;
    case ASSIST;
    case STEAL;
    case TURNOVER;
    case BLOCK;
    case FOUL;
    /*
    | -----------------------------
    | Soccer
    | -----------------------------
    */
    case GOAL;
    case CLEAN_SHEET;
    case BALL_POSSESSION;
    case GOAL_ATTEMPT;
    case CORNER_KICK;
    case SAVE;
    case GOAL_SAVE;
    case PENALTY_SAVE;
    case RED_CARD;
    case YELLOW_CARD;
    /*
    | -----------------------------
    | Tennis
    | -----------------------------
    */
    case ACES;
    case DOUBLE_FAULT;
    case FIRST_SERVE;
    case RECEIVING_POINT;
    case BREAK_POINT;
    case POINTS_IN_ROW;
    case GAME_WON;
    case TIE_BREAKER_WON;
    case SET_DIFFERENCE;
}
