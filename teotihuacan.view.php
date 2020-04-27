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
 * teotihuacan.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in teotihuacan_teotihuacan.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

require_once(APP_BASE_PATH . "view/common/game.view.php");

class view_teotihuacan_teotihuacan extends game_view
{
    function getGameName()
    {
        return "teotihuacan";
    }

    function build_page($viewArgs)
    {
        // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count($players);

        /*********** Place your code below:  ************/


        /*
        
        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::raw( $some_html_code );
        
        */

        /*
        
        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 
        

        $this->page->begin_block( "teotihuacan_teotihuacan", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }
        
        */
        $template = self::getGameName() . "_" . self::getGameName();

        $this->page->begin_block($template, "diceGroup");
        $this->page->begin_block($template, "nobles_building");
        $this->page->begin_block($template, "nobles_row");
        $this->page->begin_block($template, "actionBoards");

        for ($i = 1; $i <= 8; $i++) {

            $this->page->reset_subblocks('diceGroup');
            $this->page->reset_subblocks('nobles_building');
            $this->page->reset_subblocks('nobles_row');

            foreach ($players as $player_id => $player) {
                $this->page->insert_block("diceGroup",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ACTION_BOARD_POS" => $i
                    ));
            }

            for ($j = 0; $j < (4 - $players_nbr); $j++) {
                $this->page->insert_block("diceGroup",
                    array(
                        "PLAYER_ID" => $j,
                        "ACTION_BOARD_POS" => $i
                    ));
            }

            for ($k = 0; $k < 3; $k++) {

                $this->page->reset_subblocks('nobles_building');

                for ($l = 0; $l < 5; $l++) {
                    $this->page->insert_block("nobles_building",
                        array(
                            "ROW" => $k,
                            "ID" => $l
                        ));
                }

                $this->page->insert_block("nobles_row",
                    array(
                        "ROW" => $k
                    ));
            }

            $this->page->insert_block("actionBoards",
                array(
                    "ACTION_BOARD_POS" => $i
                ));

        }

        $this->page->begin_block($template, "players");
        foreach ($players as $player_id => $player) {
            $this->page->insert_block("players",
                array(
                    "PLAYER_ID" => $player_id
                ));
        }

        for ($j = 0; $j < (4 - $players_nbr); $j++) {
            $this->page->insert_block("players",
                array(
                    "PLAYER_ID" => $j
                ));
        }

        $this->page->begin_block($template, "temple_markers_blue");
        $this->page->begin_block($template, "temple_blue");

        $this->page->begin_block($template, "temple_markers_red");
        $this->page->begin_block($template, "temple_red");

        $this->page->begin_block($template, "temple_markers_green");
        $this->page->begin_block($template, "temple_green");

        for ($i = 0; $i < 12; $i++) {

            $this->page->reset_subblocks('temple_markers_blue');
            $this->page->reset_subblocks('temple_markers_red');
            $this->page->reset_subblocks('temple_markers_green');

            foreach ($players as $player_id => $player) {
                $this->page->insert_block("temple_markers_blue",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ID" => $i
                    ));
                $this->page->insert_block("temple_markers_red",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ID" => $i
                    ));
                $this->page->insert_block("temple_markers_green",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ID" => $i
                    ));
            }

            $this->page->insert_block("temple_blue",
                array(
                    "ID" => $i
                ));
            $this->page->insert_block("temple_red",
                array(
                    "ID" => $i
                ));
            $this->page->insert_block("temple_green",
                array(
                    "ID" => $i
                ));
        }


        $this->page->begin_block($template, "avenue_markers");
        $this->page->begin_block($template, "avenue");

        for ($i = 0; $i < 10; $i++) {

            $this->page->reset_subblocks('avenue_markers');

            foreach ($players as $player_id => $player) {
                $this->page->insert_block("avenue_markers",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ID" => $i
                    ));
            }

            $this->page->insert_block("avenue",
                array(
                    "ID" => $i
                ));
        }

        $this->page->begin_block($template, "ascension");
        for ($j = 0; $j < 5; $j++) {
            $this->page->insert_block("ascension",
                array(
                    "ID" => $j
                ));
        }

        $this->page->begin_block($template, "buildings");
        for ($j = 0; $j < 11; $j++) {
            $this->page->insert_block("buildings",
                array(
                    "ID" => $j
                ));
        }

        $this->page->begin_block($template, "construction_level0");
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $this->page->insert_block("construction_level0",
                    array(
                        "row" => $i,
                        "column" => $j
                    ));
            }
        }

        $this->page->begin_block($template, "construction_level1");
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $this->page->insert_block("construction_level1",
                    array(
                        "row" => $i,
                        "column" => $j
                    ));
            }
        }
        $this->page->begin_block($template, "construction_level2");
        for ($i = 0; $i < 2; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $this->page->insert_block("construction_level2",
                    array(
                        "row" => $i,
                        "column" => $j
                    ));
            }
        }

        $this->page->begin_block($template, "pyramid_track_markers");
        $this->page->begin_block($template, "pyramid_track");

        for ($i = 0; $i < 11; $i++) {

            $this->page->reset_subblocks('pyramid_track_markers');

            foreach ($players as $player_id => $player) {
                $this->page->insert_block("pyramid_track_markers",
                    array(
                        "PLAYER_ID" => $player_id,
                        "ID" => $i
                    ));
            }

            $this->page->insert_block("pyramid_track",
                array(
                    "ID" => $i
                ));
        }

        $this->page->begin_block($template, "calendar_track");
        for ($i = 0; $i < 13; $i++) {
                $this->page->insert_block("calendar_track",
                    array(
                        "ID" => $i
                    ));
        }

        $this->page->begin_block($template, "pyramid_decoration_left");
        for ($i = 0; $i < 4; $i++) {
                $this->page->insert_block("pyramid_decoration_left",
                    array(
                        "LEVEL" => $i
                    ));
        }
        $this->page->begin_block($template, "pyramid_decoration_top");
        for ($i = 0; $i < 4; $i++) {
                $this->page->insert_block("pyramid_decoration_top",
                    array(
                        "LEVEL" => $i
                    ));
        }
        $this->page->begin_block($template, "pyramid_decoration_right");
        for ($i = 0; $i < 4; $i++) {
                $this->page->insert_block("pyramid_decoration_right",
                    array(
                        "LEVEL" => $i
                    ));
        }
        $this->page->begin_block($template, "pyramid_decoration_bottom");
        for ($i = 0; $i < 4; $i++) {
                $this->page->insert_block("pyramid_decoration_bottom",
                    array(
                        "LEVEL" => $i
                    ));
        }

            /*********** Do not change anything below this line  ************/
        }
    }
  

