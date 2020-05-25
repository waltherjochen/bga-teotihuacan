<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * teotihuacan implementation : © Jochen Walther boardgamearena@waltherjochen.de
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * stats.inc.php
 *
 * teotihuacan game statistics description
 *
 */

/*
    In this file, you are describing game statistics, that will be displayed at the end of the
    game.
    
    !! After modifying this file, you must use "Reload  statistics configuration" in BGA Studio backoffice
    ("Control Panel" / "Manage Game" / "Your Game")
    
    There are 2 types of statistics:
    _ table statistics, that are not associated to a specific player (ie: 1 value for each game).
    _ player statistics, that are associated to each players (ie: 1 value for each player in the game).

    Statistics types can be "int" for integer, "float" for floating point values, and "bool" for boolean
    
    Once you defined your statistics there, you can start using "initStat", "setStat" and "incStat" method
    in your game logic, using statistics names defined below.
    
    !! It is not a good idea to modify this file when a game is running !!

    If your game is already public on BGA, please read the following before any change:
    http://en.doc.boardgamearena.com/Post-release_phase#Changes_that_breaks_the_games_in_progress
    
    Notes:
    * Statistic index is the reference used in setStat/incStat/initStat PHP method
    * Statistic index must contains alphanumerical characters and no space. Example: 'turn_played'
    * Statistics IDs must be >=10
    * Two table statistics can't share the same ID, two player statistics can't share the same ID
    * A table statistic can have the same ID than a player statistics
    * Statistics ID is the reference used by BGA website. If you change the ID, you lost all historical statistic data. Do NOT re-use an ID of a deleted statistic
    * Statistic name is the English description of the statistic as shown to players
    
*/

$stats_type = array(

    // Statistics global to table
    "table" => array(

//        "turns_number" => array("id"=> 10,
//                    "name" => totranslate("Number of turns"),
//                    "type" => "int" ),

    ),
    
    // Statistics existing for each player
    "player" => array(

        "temple_blue" => array("id"=> 10,"name" => totranslate("Steps on temple blue"),"type" => "int" ),
        "temple_red" => array("id"=> 11,"name" => totranslate("Steps on temple red"),"type" => "int" ),
        "temple_green" => array("id"=> 12,"name" => totranslate("Steps on temple green"),"type" => "int" ),
        "avenue" => array("id"=> 13,"name" => totranslate("Steps on Avenue of Dead"),"type" => "int" ),
        "steps_on_pyramid_track_round1" => array("id"=> 14,"name" => totranslate("Steps on pyramid track in eclipse 1"),"type" => "int" ),
        "steps_on_pyramid_track_round2" => array("id"=> 15,"name" => totranslate("Steps on pyramid track in eclipse 2"),"type" => "int" ),
        "steps_on_pyramid_track_round3" => array("id"=> 16,"name" => totranslate("Steps on pyramid track in eclipse 3"),"type" => "int" ),
        "ahead_on_pyramid_track_eclipse1" => array("id"=> 17,"name" => totranslate("Are you ahead on pyramid track in eclipse 1"),"type" => "int" ),
        "ahead_on_pyramid_track_eclipse2" => array("id"=> 18,"name" => totranslate("Are you ahead on pyramid track in eclipse 2"),"type" => "int" ),
        "ahead_on_pyramid_track_eclipse3" => array("id"=> 19,"name" => totranslate("Are you ahead on pyramid track in eclipse 3"),"type" => "int" ),
        "pyramid_same_symbol" => array("id"=> 20,"name" => totranslate("Points for same symbol on Pyramid"),"type" => "int" ),
        "build_decoration" => array("id"=> 21,"name" => totranslate("Decorations added"),"type" => "int" ),
        "decoration_same_symbol" => array("id"=> 22,"name" => totranslate("Points for same symbol on Decoration"),"type" => "int" ),
        "place_nobles_building_round1" => array("id"=> 23,"name" => totranslate("Placed nobles building in eclipse 1"),"type" => "int" ),
        "place_nobles_building_round2" => array("id"=> 24,"name" => totranslate("Placed nobles building in eclipse 2"),"type" => "int" ),
        "place_nobles_building_round3" => array("id"=> 25,"name" => totranslate("Placed nobles building in eclipse 3"),"type" => "int" ),
        "aquiredTechnology_eclipse1" => array("id"=> 26,"name" => totranslate("Aquired technologies in eclipse 1"),"type" => "int" ),
        "aquiredTechnology_eclipse2" => array("id"=> 27,"name" => totranslate("Aquired technologies in eclipse 2"),"type" => "int" ),
        "aquiredTechnology_eclipse3" => array("id"=> 28,"name" => totranslate("Aquired technologies in eclipse 3"),"type" => "int" ),
        "ascension" => array("id"=> 29,"name" => totranslate("Ascensions"),"type" => "int" ),
        "masks_eclipse1" => array("id"=> 30,"name" => totranslate("Collected masks in eclipse 1"),"type" => "int" ),
        "masks_eclipse2" => array("id"=> 31,"name" => totranslate("Collected masks in eclipse 2"),"type" => "int" ),
        "masks_eclipse3" => array("id"=> 32,"name" => totranslate("Collected masks in eclipse 3"),"type" => "int" ),
        "avenue_eclipse1" => array("id"=> 33,"name" => totranslate("Steps in Avenue of Dead in eclipse 1"),"type" => "int" ),
        "avenue_eclipse2" => array("id"=> 34,"name" => totranslate("Steps in Avenue of Dead in eclipse 2"),"type" => "int" ),
        "avenue_eclipse3" => array("id"=> 35,"name" => totranslate("Steps in Avenue of Dead in eclipse 3"),"type" => "int" ),
        "summary_pyramid" => array("id"=> 36,"name" => totranslate("Summary Points for Construction (Excluding technology tiles)"),"type" => "int" ),
        "summary_decoration" => array("id"=> 37,"name" => totranslate("Summary Points for Decoration (Excluding technology tiles)"),"type" => "int" ),
        "summary_nobles" => array("id"=> 38,"name" => totranslate("Summary Points for Nobles (Excluding technology tiles)"),"type" => "int" ),
        "summary_avenue" => array("id"=> 39,"name" => totranslate("Summary Points for Avenue of Dead"),"type" => "int" ),
        "summary_score" => array("id"=> 40,"name" => totranslate("Score summary"),"type" => "int" ),

    )

);
