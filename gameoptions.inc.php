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
 * gameoptions.inc.php
 *
 * teotihuacan game options description
 *
 * In this file, you can define your game options (= game variants).
 *
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in teotihuacan.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

$game_options = array(

    100 => array(
        'name' => totranslate('First game setup'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('First game setup'),
                'description' => totranslate('No starting tiles, predefined actions boards location')
            )
        )
    ),

    101 => array(
        'name' => totranslate('Eclipse mode'),
        'values' => array(
            1 => array(
                'name' => totranslate('normal')
            ),
            2 => array(
                'name' => totranslate('Dark Eclipse'),
                'tmdisplay' => totranslate('Dark Eclipse'),
                'nobeginner' => true
            )
        )
    ),

    102 => array(
        'name' => totranslate('Starting tiles'),
        'values' => array(
            1 => array(
                'name' => totranslate('normal')
            ),
            2 => array(
                'name' => totranslate('Draft'),
                'tmdisplay' => totranslate('Draft'),
                'nobeginner' => true
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 100,
                'value' => array(1),
            )
        )
    ),

    103 => array(
        'name' => totranslate('Pyramid setup'),
        'values' => array(
            1 => array(
                'name' => totranslate('normal')
            ),
            2 => array(
                'name' => totranslate('Random'),
                'tmdisplay' => totranslate('Random Pyramid setup'),
                'description' => totranslate('For 2 and 3 Players only')
            )
        )
    ),

    104 => array(
        'name' => totranslate('Late Preclassic period'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('Late Preclassic period'),
                'nobeginner' => true
            )
        )
    ),

    105 => array(
        'name' => totranslate('Priests and Priestesses (M1)'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('M1'),
                'description' => totranslate('Devout worshippers grand special effects and abilities')
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 104,
                'value' => array(2),
            )
        )
    ),

    106 => array(
        'name' => totranslate('Height of Development (M2)'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('M2'),
                'description' => totranslate('Expanded board an new temple offer fresh challenges')
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 104,
                'value' => array(2),
            )
        )
    ),

    107 => array(
        'name' => totranslate('Seasons of Progress (M3)'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('M3'),
                'description' => totranslate('Variable effects impact each season and eclipse')
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 104,
                'value' => array(2),
            )
        )
    ),

    108 => array(
        'name' => totranslate('Arichitecture (M4)'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('M4'),
                'description' => totranslate('Renewed plans for beatifying the Pyramid of the Sun')
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 104,
                'value' => array(2),
            )
        )
    ),

    109 => array(
        'name' => totranslate('Development (M5)'),
        'values' => array(
            1 => array(
                'name' => totranslate('Off')
            ),
            2 => array(
                'name' => totranslate('On'),
                'tmdisplay' => totranslate('M5'),
                'description' => totranslate('Build the pyramid using new engineering techniques')
            )
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => 104,
                'value' => array(2),
            )
        )
    ),

);


