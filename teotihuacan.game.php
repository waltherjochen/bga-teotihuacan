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
 * teotihuacan.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */


require_once(APP_GAMEMODULE_PATH . 'module/table/table.game.php');

// actionBoards
define("ACTION_BOARD_PALACE", 1);
define("ACTION_BOARD_FOREST", 2);
define("ACTION_BOARD_STONE", 3);
define("ACTION_BOARD_GOLD", 4);
define("ACTION_BOARD_ALCHEMY", 5);
define("ACTION_BOARD_NOBLES", 6);
define("ACTION_BOARD_DECORATIONS", 7);
define("ACTION_BOARD_CONSTRUCTION", 8);

// state constants
define("STATE_CHOOSE_STARTING_TILES", 20);
define("STATE_PREPARE_STARTING_TILES_BONUS", 21);
define("STATE_STARTING_TILES_PLACE_WORKERS", 22);
define("STATE_GET_STARTING_TILES_BONUS_AUTO", 23);
define("STATE_CLAIM_STARTING_DISCOVERY_TILES", 24);
define("STATE_CALCULATE_NEXT_TILES_BONUS", 25);
define("STATE_ZOMBIE", 26);
define("STATE_CHOOSE_STARTING_TILES_DRAFT", 27);
define("STATE_STARTING_TILES_DRAFT_CALCULATE_NEXT", 28);

define("STATE_START_TURN", 29);
define("STATE_PLAYER_TURN", 30);
define("STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS", 31);
define("STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS", 32);
define("STATE_PLAYER_TURN_WORSHIP_ACTIONS", 33);
define("STATE_PLAYER_TURN_CHOOSE_TEMPLE_RESOURCES", 34);
define("STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS", 35);
define("STATE_PLAYER_TURN_USE_DISCOVERY_TILE", 36);
define("STATE_PLAYER_TURN_AVENUE_OF_DEAD", 39);
define("STATE_PLAYER_TURN_CHOOSE_AVENUE_BONUS", 40);
define("STATE_PLAYER_TURN_UPGRADE_WORKERS", 41);
define("STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS", 42);
define("STATE_PLAYER_TURN_BOARD_ACTION", 43);
define("STATE_PLAYER_TURN_NOBLES", 44);
define("STATE_PLAYER_TURN_NOBLES_BUILD", 45);
define("STATE_PLAYER_TURN_WORSHIP_TRADE", 46);
define("STATE_PLAYER_TURN_ALCHEMY", 47);
define("STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY", 48);
define("STATE_PLAYER_TURN_CONSTRUCTION", 49);
define("STATE_PLAYER_TURN_DECORATION", 50);

define("STATE_PLAYER_TURN_PASS", 55);
define("STATE_PLAYER_TURN_CHECK_END_TURN", 58);
define("STATE_PLAYER_END_TURN", 60);
define("STATE_PLAYER_TURN_UNDO", 61);


define("STATE_PAY_SALARY", 70);
define("STATE_CHECK_END_GAME", 80);

// temple
define("PYRAMID_TYPE_EMPTY", "-");
define("PYRAMID_TYPE_GREEN", "g");
define("PYRAMID_TYPE_GREEN_TEMPLE", "G");
define("PYRAMID_TYPE_RED", "r");
define("PYRAMID_TYPE_RED_TEMPLE", "R");
define("PYRAMID_TYPE_BLUE", "b");
define("PYRAMID_TYPE_BLUE_TEMPLE", "B");

class teotihuacan extends Table
{
    function __construct()
    {
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();

        self::initGameStateLabels(array(
            "selected_board_id_from" => 10,
            "selected_board_id_to" => 11,
            "selected_worker_id" => 12,
            "worship_actions_worship" => 13,
            "worship_actions_discovery" => 14,
            "choose_resources_max" => 15,
            "temple_bonus_cocoa" => 16,
            "temple_bonus_vp" => 17,
            "temple_bonus_resource" => 18,
            "last_temple_id" => 19,
            "previous_game_state" => 20,
            "useDiscovery" => 21,
            "useDiscoveryMoveWorkerAnywhere" => 22,
            "useDiscoveryMoveTwoWorkers" => 23,
            "selected_worker2_id" => 24,
            "upgradeWorkers" => 25,
            "ascension" => 26,
            "ascensionTempleSteps" => 27,
            "doMainAction" => 28,
            "useDiscoveryPowerUp" => 29,
            "ascensionBonusChoosed" => 30,
            "buildOnePyramidTile" => 31,
            "royalTileTradeId" => 32,
            "royalTileAction" => 33,
            "useDiscoveryId" => 34,
            "isNobles" => 35,
            "paidPowerUp" => 36,
            "canBuildPyramidTiles" => 37,
            "isConstruction" => 38,
            "eclipseDiscWhite" => 39,
            "eclipseDiscBlack" => 40,
            "isDecoration" => 41,
            "extraWorker" => 42,
            "aquiredTechnologyTile" => 43,
            "eclipse" => 44,
            "lastRound" => 45,
            "startingTileBonus" => 46,
            "progression" => 47,
            "draftReverse" => 48,
            "getTechnologyDiscount" => 49,
            "newGlobal1" => 50,
            "newGlobal2" => 51,
            "newGlobal3" => 52,
            "newGlobal4" => 53,
            "newGlobal5" => 54,
            "newGlobal6" => 55,
            "newGlobal7" => 56,
            "newGlobal8" => 57,
            "newGlobal9" => 58,
            "newGlobal10" => 59,
            "newGlobal11" => 60,

            "random_setup" => 100,
            "dark_eclipse" => 101,
            "draft_mode" => 102,
            "pyramidSetup" => 103,
        ));

        $this->cards = self::getNew("module.common.deck");
        $this->cards->init("card");
    }

    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "teotihuacan";
    }

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame($players, $options = array())
    {
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach ($players as $player_id => $player) {
            $color = array_shift($default_colors);
            $values[] = "('" . $player_id . "','$color','" . $player['player_canal'] . "','" . addslashes($player['player_name']) . "','" . addslashes($player['player_avatar']) . "')";
        }
        $sql .= implode($values, ',');
        self::DbQuery($sql);
        self::reattributeColorsBasedOnPreferences($players, $gameinfos['player_colors']);
        self::reloadPlayersBasicInfos();

        /************ Start the game initialization *****/

        self::setGameStateInitialValue('selected_board_id_to', 0);
        self::setGameStateInitialValue('selected_board_id_from', 0);
        self::setGameStateInitialValue('selected_worker_id', 0);
        self::setGameStateInitialValue('selected_worker2_id', 0);
        self::setGameStateInitialValue('worship_actions_worship', 0);
        self::setGameStateInitialValue('worship_actions_discovery', 0);
        self::setGameStateInitialValue('choose_resources_max', 0);
        self::setGameStateInitialValue('temple_bonus_cocoa', 0);
        self::setGameStateInitialValue('temple_bonus_vp', 0);
        self::setGameStateInitialValue('temple_bonus_resource', 0);
        self::setGameStateInitialValue('last_temple_id', 0);
        self::setGameStateInitialValue('previous_game_state', 0);
        self::setGameStateInitialValue('useDiscovery', 0);
        self::setGameStateInitialValue('useDiscoveryMoveWorkerAnywhere', 0);
        self::setGameStateInitialValue('useDiscoveryMoveTwoWorkers', 0);
        self::setGameStateInitialValue('upgradeWorkers', 0);
        self::setGameStateInitialValue('ascension', 0);
        self::setGameStateInitialValue('ascensionTempleSteps', 0);
        self::setGameStateInitialValue('doMainAction', 0);
        self::setGameStateInitialValue('useDiscoveryPowerUp', 0);
        self::setGameStateInitialValue('ascensionBonusChoosed', 0);
        self::setGameStateInitialValue('buildOnePyramidTile', 0);
        self::setGameStateInitialValue('royalTileTradeId', 0);
        self::setGameStateInitialValue('royalTileAction', 0);
        self::setGameStateInitialValue('useDiscoveryId', 0);
        self::setGameStateInitialValue('isNobles', 0);
        self::setGameStateInitialValue('paidPowerUp', 0);
        self::setGameStateInitialValue('canBuildPyramidTiles', 0);
        self::setGameStateInitialValue('isConstruction', 0);
        self::setGameStateInitialValue('eclipseDiscWhite', 0);
        self::setGameStateInitialValue('isDecoration', 0);
        self::setGameStateInitialValue('extraWorker', 0);
        self::setGameStateInitialValue('aquiredTechnologyTile', -1);
        self::setGameStateInitialValue('eclipse', 1);
        self::setGameStateInitialValue('lastRound', 0);
        self::setGameStateInitialValue('startingTileBonus', 0);
        self::setGameStateInitialValue('progression', 0);
        self::setGameStateInitialValue('draftReverse', 0);
        self::setGameStateInitialValue('getTechnologyDiscount', 0);

        // Create actionBoards
        $sql = "INSERT INTO `card`(`card_id`, `card_type`, `card_type_arg`, `card_location`, `card_location_arg`) VALUES";

        if ($this->isRandomSetup()) {
            $location = array(2, 3, 4, 6, 7, 8);
            shuffle($location);
            $location = $this->insert($location, 0, 1);
            $location = $this->insert($location, 7, 5);
        } else {
            $location = array(1, 4, 2, 8, 3, 7, 6, 5);
        }
        $values = array();
        for ($i = 0; $i < 8; $i++) {
            $values[] = "(" . ($i + 1) . ",'actionBoards','0','0'," . ($location[$i]) . ")";
        }
        $sql .= implode($values, ',');
        self::DbQuery($sql);

        $sql = "INSERT INTO `map`(`actionboard_id`, `player_id`, `worker_id`,`worker_power`, `locked`) VALUES";
        $values = array();
        foreach ($players as $player_id => $player) {
            $values[] = "(-1,'" . $player_id . "','4','3',1)";
        }
        $sql .= implode($values, ',');
        self::DbQuery($sql);

        $players_order = self::getObjectListFromDB("SELECT `player_no` FROM `player`");
        foreach ($players_order as $player) {
            $player_no = $player['player_no'];
            if ($player_no == 1) {
                self::DbQuery("UPDATE `player` SET cocoa  = 1 WHERE player_no = $player_no");
            } else if ($player_no == count($players)) {
                self::DbQuery("UPDATE `player` SET cocoa  = 3 WHERE player_no = $player_no");
            } else {
                self::DbQuery("UPDATE `player` SET cocoa  = 2 WHERE player_no = $player_no");
            }
        }

        // Create discovery tiles
        $cards = array();
        foreach ($this->discoveryTiles as $key => $item) {
            $cards[] = array('type' => "discoveryTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'discTiles_deck');

        $this->cards->shuffle('discTiles_deck');

        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b1');
        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b2');
        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b3');
        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b4');
        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b7');
        $this->cards->pickCardsForLocation(count($players), 'discTiles_deck', 'discTiles_tb0');
        $this->cards->pickCardsForLocation((count($players) + 1), 'discTiles_deck', 'discTiles_tr0');
        $this->cards->pickCardsForLocation((count($players) - 1), 'discTiles_deck', 'discTiles_tg0');
        $this->cards->pickCardsForLocation(count($players), 'discTiles_deck', 'discTiles_tg1');
        $this->cards->pickCardsForLocation(3, 'discTiles_deck', 'discTiles_a0');
        $this->cards->pickCardsForLocation(2, 'discTiles_deck', 'discTiles_a1');
        $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_a2');

        $sql = "INSERT INTO `nobles`(`row0`, `row1`, `row2`) VALUES (0,0,0)";
        self::DbQuery($sql);

        // Create technology tiles
        $cards = array();
        foreach ($this->technologyTiles as $key => $item) {
            $cards[] = array('type' => "technologyTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'techTiles_deck');

        if ($this->isRandomSetup()) {
            $this->cards->pickCardsForLocation(5, 'techTiles_deck', 'techTiles_row2');
            $this->cards->shuffle('techTiles_deck');
            $this->cards->shuffle('techTiles_row2');

            $exclude = random_int(0, 3);

            $row = 1;
            for ($i = 0; $i < 4; $i++) {
                if ($i != $exclude) {
                    self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r1_c$row' WHERE card_type = 'technologyTiles' AND card_type_arg = $i");
                    $row = $row + 1;
                }
            }

            $exclude1 = random_int(4, 8);

            do {
                $exclude2 = random_int(4, 8);
            } while ($exclude1 == $exclude2);

            $row = 1;
            for ($i = 4; $i < 9; $i++) {
                if ($i != $exclude1 && $i != $exclude2) {
                    self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r2_c$row' WHERE card_type = 'technologyTiles' AND card_type_arg = $i");
                    $row = $row + 1;
                }
            }
        } else {
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r1_c1' WHERE card_type = 'technologyTiles' AND card_type_arg = 0");
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r1_c2' WHERE card_type = 'technologyTiles' AND card_type_arg = 1");
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r1_c3' WHERE card_type = 'technologyTiles' AND card_type_arg = 2");
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r2_c1' WHERE card_type = 'technologyTiles' AND card_type_arg = 4");
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r2_c2' WHERE card_type = 'technologyTiles' AND card_type_arg = 7");
            self::DbQuery("UPDATE `card` SET card_location  = 'techTiles_r2_c3' WHERE card_type = 'technologyTiles' AND card_type_arg = 8");
        }

        // Create royal tiles
        $cards = array();
        foreach ($this->royalTiles as $key => $item) {
            $cards[] = array('type' => "royalTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'royalTiles_deck');

        if ($this->isRandomSetup()) {
            $a = array(3,4,8);
            $b = array(1,5,6);
            $c = array(0,2,7);
            $setA = $a[random_int(0, 2)];
            $setB = $b[random_int(0, 2)];
            $setC = $c[random_int(0, 2)];

            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles0' WHERE card_type = 'royalTiles' AND card_type_arg = $setA");
            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles1' WHERE card_type = 'royalTiles' AND card_type_arg = $setB");
            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles2' WHERE card_type = 'royalTiles' AND card_type_arg = $setC");
        } else {
            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles0' WHERE card_type = 'royalTiles' AND card_type_arg = 0");
            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles1' WHERE card_type = 'royalTiles' AND card_type_arg = 5");
            self::DbQuery("UPDATE `card` SET card_location  = 'royalTiles2' WHERE card_type = 'royalTiles' AND card_type_arg = 3");
        }

        // Create temple bonus tiles
        $cards = array();
        foreach ($this->templeBonusTiles as $key => $item) {
            $cards[] = array('type' => "templeBonusTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'tBonusTiles_deck');

        if ($this->isRandomSetup()) {
            $this->cards->shuffle('tBonusTiles_deck');

            $this->cards->pickCardsForLocation(1, 'tBonusTiles_deck', 'tblueTile');
            $this->cards->pickCardsForLocation(1, 'tBonusTiles_deck', 'tredTile');
            $this->cards->pickCardsForLocation(1, 'tBonusTiles_deck', 'tgreenTile');
        } else {
            self::DbQuery("UPDATE `card` SET card_location  = 'tblueTile' WHERE card_type = 'templeBonusTiles' AND card_type_arg = 2");
            self::DbQuery("UPDATE `card` SET card_location  = 'tredTile' WHERE card_type = 'templeBonusTiles' AND card_type_arg = 3");
            self::DbQuery("UPDATE `card` SET card_location  = 'tgreenTile' WHERE card_type = 'templeBonusTiles' AND card_type_arg = 6");
        }

        // Create decoration tiles
        $cards = array();
        foreach ($this->decorationTiles as $key => $item) {
            $cards[] = array('type' => "decorationTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'decoTiles_deck');
        $this->cards->shuffle('decoTiles_deck');

        $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_0');
        $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_1');
        $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_2');
        $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_3');

        // Create pyramid tiles
        $cards = array();
        foreach ($this->pyramidTiles as $key => $item) {
            $cards[] = array('type' => "pyramidTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'pyraTiles_deck');
        $this->cards->shuffle('pyraTiles_deck');

        $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_0');
        $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_1');
        $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_2');

        $sql = "INSERT INTO pyramid (column0, column1, column2, column3, column4, column5, column6, column7) VALUES ";
        $values = array();
        foreach ($this->pyramidBottom as $key => $item) {
            $values[] = "('$item[0]', '$item[1]', '$item[2]', '$item[3]', '$item[4]', '$item[5]', '$item[6]', '$item[7]')";
        }
        $sql .= implode($values, ',');
        self::DbQuery($sql);

        if (count($players) == 4) {
            self::setGameStateInitialValue('eclipseDiscBlack', 12);
            $startTiles = array(1, 2, 4, 7, 8, 11, 13, 14);
        } else if (count($players) == 3) {
            self::setGameStateInitialValue('eclipseDiscBlack', 11);
            $setup = random_int(0, 1);

            if (!$this->isRandomPyramidSetup()) {
                $setup = 0;
            }

            if ($setup == 0) {
                $startTiles = array(4, 5, 6, 7, 8, 9, 10, 11, 103, 105);
            } else {
                $startTiles = array(1, 2, 5, 6, 9, 10, 13, 14, 101, 107);
            }

        } else if (count($players) <= 2) {
            self::setGameStateInitialValue('eclipseDiscBlack', 10);
            $setup = random_int(0, 3);

            if (!$this->isRandomPyramidSetup()) {
                $setup = 0;
            }

            if ($setup == 0) {
                $startTiles = array(0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 100, 108);
            } else if ($setup == 1) {
                $startTiles = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 102, 106);
            } else if ($setup == 2) {
                $startTiles = array(0, 1, 2, 4, 5, 6, 9, 10, 11, 13, 14, 15, 100, 108);
            } else {
                $startTiles = array(1, 2, 3, 5, 6, 7, 8, 9, 10, 12, 13, 14, 102, 106);
            }
        }

        for ($i = 0; $i < count($startTiles); $i++) {
            $rotate = random_int(0, 3);
            $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', "pyra_rotate_$rotate", $startTiles[$i]);

            $pyramidTileCard = $this->cards->getCardsInLocation("pyra_rotate_$rotate", $startTiles[$i]);

            $pyramidTile = (int)(array_shift($pyramidTileCard)['type_arg']);

            $pyramidTile_values = $this->pyramidTiles[$pyramidTile]['values'];

            for ($j = 0; $j < $rotate; $j++) {
                $temp = array_pop($pyramidTile_values);
                array_unshift($pyramidTile_values, $temp);
            }

            $level = (int)($startTiles[$i] / 100);
            $maxRowInLevel = 4 - $level;
            $number = $startTiles[$i] - ($level * 100);
            $row = (int)($number / $maxRowInLevel);
            $column = $number % $maxRowInLevel;

            $row = $row * 2 + 1 + $level;
            $column = $column * 2 + $level;

            $newColumn = $column;
            $newRow = $row;
            self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[0]'  WHERE `id` = $newRow");
            $newColumn = $column + 1;
            $newRow = $row;
            self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[1]'  WHERE `id` = $newRow");
            $newColumn = $column + 1;
            $newRow = $row + 1;
            self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[2]'  WHERE `id` = $newRow");
            $newColumn = $column;
            $newRow = $row + 1;
            self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[3]'  WHERE `id` = $newRow");
        }

        $cards = array();
        foreach ($this->startingTiles as $key => $item) {
            $cards[] = array('type' => "startingTiles", 'type_arg' => $key, 'nbr' => 1);
        }
        $this->cards->createCards($cards, 'startTiles_deck');
        $this->cards->shuffle('startTiles_deck');

        if ($this->isDraftMode()) {
            $countTiles = count($players) * 2 + 2;
            $this->cards->pickCardsForLocation($countTiles, 'startTiles_deck', 'sChoose_all');

            $hasDiscoveryTile = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = 3 and `card_location` = 'sChoose_all'");
            if ($hasDiscoveryTile) {
                $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'sChoose_all', 3);
            }
            $hasDiscoveryTile = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = 13 and `card_location` = 'sChoose_all'");
            if ($hasDiscoveryTile) {
                $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'sChoose_all', 13);
            }
        } else {

            foreach ($players as $player_id => $player) {
                $this->cards->pickCardsForLocation(4, 'startTiles_deck', 'sChoose_' . $player_id);

                $hasDiscoveryTile = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = 3 and `card_location` = 'sChoose_$player_id'");
                if ($hasDiscoveryTile) {
                    $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'sChoose_' . $player_id, 3);
                }
                $hasDiscoveryTile = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = 13 and `card_location` = 'sChoose_$player_id'");
                if ($hasDiscoveryTile) {
                    $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'sChoose_' . $player_id, 13);
                }
            }
        }


        // Init game statistics`
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        self::initStat('player', 'temple_blue', 0);  // Init a player statistics (for all players)
        self::initStat('player', 'temple_red', 0);
        self::initStat('player', 'temple_green', 0);
        self::initStat('player', 'avenue', 0);
        self::initStat('player', 'steps_on_pyramid_track_round1', 0);
        self::initStat('player', 'steps_on_pyramid_track_round2', 0);
        self::initStat('player', 'steps_on_pyramid_track_round3', 0);
        self::initStat('player', 'place_nobles_building_round1', 0);
        self::initStat('player', 'place_nobles_building_round2', 0);
        self::initStat('player', 'place_nobles_building_round3', 0);
        self::initStat('player', 'aquiredTechnology_eclipse1', 0);
        self::initStat('player', 'aquiredTechnology_eclipse2', 0);
        self::initStat('player', 'aquiredTechnology_eclipse3', 0);
        self::initStat('player', 'build_decoration', 0);
        self::initStat('player', 'ascension', 0);
        self::initStat('player', 'ahead_on_pyramid_track_eclipse1', 0);
        self::initStat('player', 'ahead_on_pyramid_track_eclipse2', 0);
        self::initStat('player', 'ahead_on_pyramid_track_eclipse3', 0);
        self::initStat('player', 'pyramid_same_symbol', 0);
        self::initStat('player', 'decoration_same_symbol', 0);
        self::initStat('player', 'masks_eclipse1', 0);
        self::initStat('player', 'masks_eclipse2', 0);
        self::initStat('player', 'masks_eclipse3', 0);
        self::initStat('player', 'avenue_eclipse1', 0);
        self::initStat('player', 'avenue_eclipse2', 0);
        self::initStat('player', 'avenue_eclipse3', 0);
        self::initStat('player', 'summary_pyramid', 0);
        self::initStat('player', 'summary_decoration', 0);
        self::initStat('player', 'summary_nobles', 0);
        self::initStat('player', 'summary_avenue', 0);
        self::initStat('player', 'summary_score', 0);

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();

        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!

        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_no player_order, player_score score, player_color, player_name, enableAuto, enableUndo, startingTile0, startingTile1, startingDiscovery0, startingDiscovery1, cocoa, wood, stone, gold, temple_blue, temple_red, temple_green, avenue_of_dead, techTiles_r1_c1, techTiles_r1_c2, techTiles_r1_c3, techTiles_r2_c1, techTiles_r2_c2, techTiles_r2_c3, pyramid_track FROM player";
        $result['players'] = self::getCollectionFromDb($sql);
        $result['players_count'] = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `player`");

        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        foreach ($result['players'] as $player_id => $player) {
            if (($key = array_search($player['player_color'], $default_colors)) !== false) {
                unset($default_colors[$key]);
            }
        }
        $result['left_colors'] = array_values($default_colors);

        // TODO: Gather all information about current game situation (visible by player $current_player_id).

        $sql = "SELECT * FROM `card`";
        $result['actionBoards'] = self::getCollectionFromDb($sql);
        $result['actionBoards_data'] = $this->actionBoards;

        $map_sorted = array();
        foreach ($result['players'] as $player_id => $player) {
            $sql = "SELECT * FROM `map` WHERE `player_id` = " . $player_id;
            $map_player = self::getCollectionFromDb($sql);
            $map_sorted[$player_id] = $map_player;
        }
        for ($i = 0; $i < (4 - count($result['players'])); $i++) {
            $sql = "SELECT * FROM `map` WHERE `player_id` = " . $i;
            $map_player = self::getCollectionFromDb($sql);
            $map_sorted[$i] = $map_player;
        }
        $result['map'] = $map_sorted;

        $result['global'] = $this->getGlobalVariables()['global'];

        $discoveryTiles = array(
            "deck" => $this->cards->getCardsInLocation('discTiles_deck'),
            "b1" => $this->cards->getCardsInLocation('discTiles_b1'),
            "b2" => $this->cards->getCardsInLocation('discTiles_b2'),
            "b3" => $this->cards->getCardsInLocation('discTiles_b3'),
            "b4" => $this->cards->getCardsInLocation('discTiles_b4'),
            "b7" => $this->cards->getCardsInLocation('discTiles_b7'),
            "tb0" => $this->cards->getCardsInLocation('discTiles_tb0'),
            "tr0" => $this->cards->getCardsInLocation('discTiles_tr0'),
            "tg0" => $this->cards->getCardsInLocation('discTiles_tg0'),
            "tg1" => $this->cards->getCardsInLocation('discTiles_tg1'),
            "a0" => $this->cards->getCardsInLocation('discTiles_a0'),
            "a1" => $this->cards->getCardsInLocation('discTiles_a1'),
            "a2" => $this->cards->getCardsInLocation('discTiles_a2'),
        );

        $result['discoveryTiles'] = $discoveryTiles;
        $result['discoveryTiles_data'] = $this->discoveryTiles;

        $technologyTiles = array(
            "r1_c1" => $this->cards->getCardsInLocation('techTiles_r1_c1'),
            "r1_c2" => $this->cards->getCardsInLocation('techTiles_r1_c2'),
            "r1_c3" => $this->cards->getCardsInLocation('techTiles_r1_c3'),
            "r2_c1" => $this->cards->getCardsInLocation('techTiles_r2_c1'),
            "r2_c2" => $this->cards->getCardsInLocation('techTiles_r2_c2'),
            "r2_c3" => $this->cards->getCardsInLocation('techTiles_r2_c3'),
        );

        $result['technologyTiles'] = $technologyTiles;
        $result['technologyTiles_data'] = $this->technologyTiles;

        $royalTiles = array(
            "royalTiles0" => $this->cards->getCardsInLocation('royalTiles0'),
            "royalTiles1" => $this->cards->getCardsInLocation('royalTiles1'),
            "royalTiles2" => $this->cards->getCardsInLocation('royalTiles2'),
        );

        $result['royalTiles'] = $royalTiles;
        $result['royalTiles_data'] = $this->royalTiles;

        $templeBonusTiles = array(
            "tblueTile" => $this->cards->getCardsInLocation('tblueTile'),
            "tredTile" => $this->cards->getCardsInLocation('tredTile'),
            "tgreenTile" => $this->cards->getCardsInLocation('tgreenTile'),
        );

        $result['templeBonusTiles'] = $templeBonusTiles;
        $result['templeBonusTiles_data'] = $this->templeBonusTiles;

        $decorationTiles = array(
            "decoTiles_0" => $this->cards->getCardsInLocation('decoTiles_0'),
            "decoTiles_1" => $this->cards->getCardsInLocation('decoTiles_1'),
            "decoTiles_2" => $this->cards->getCardsInLocation('decoTiles_2'),
            "decoTiles_3" => $this->cards->getCardsInLocation('decoTiles_3'),
            "deco_p_left" => $this->cards->getCardsInLocation('deco_p_left'),
            "deco_p_top" => $this->cards->getCardsInLocation('deco_p_top'),
            "deco_p_right" => $this->cards->getCardsInLocation('deco_p_right'),
            "deco_p_bottom" => $this->cards->getCardsInLocation('deco_p_bottom'),
        );

        $result['decorationTiles'] = $decorationTiles;
        $result['decorationTiles_data'] = $this->decorationTiles;

        $pyramidTiles = array(
            "pyramidTiles_0" => $this->cards->getCardsInLocation('pyramidTiles_0'),
            "pyramidTiles_1" => $this->cards->getCardsInLocation('pyramidTiles_1'),
            "pyramidTiles_2" => $this->cards->getCardsInLocation('pyramidTiles_2'),
            "pyra_rotate_0" => $this->cards->getCardsInLocation('pyra_rotate_0'),
            "pyra_rotate_1" => $this->cards->getCardsInLocation('pyra_rotate_1'),
            "pyra_rotate_2" => $this->cards->getCardsInLocation('pyra_rotate_2'),
            "pyra_rotate_3" => $this->cards->getCardsInLocation('pyra_rotate_3'),
        );

        $result['pyramidTiles'] = $pyramidTiles;
        $result['pyramidTiles_data'] = $this->pyramidTiles;

        $player_hand = array();

        foreach ($result['players'] as $player_id => $player) {
            $player_hand[$player_id]['mask'][0] = array();
            $player_hand[$player_id]['mask'][1] = array();
            $player_hand[$player_id]['mask'][2] = array();
            $player_hand[$player_id]['other'] = array();
            $player_hand[$player_id]['used'] = array();
            foreach ($this->cards->getPlayerHand($player_id) as $i => $hand) {
                $id_mask = $this->discoveryTiles[$hand['type_arg']]['bonus']['mask'];
                if ($id_mask > 0) {
                    if (!$this->isMaskInArray($id_mask, $player_hand[$player_id]['mask'][0])) {
                        array_push($player_hand[$player_id]['mask'][0], $hand);
                    } else if (!$this->isMaskInArray($id_mask, $player_hand[$player_id]['mask'][1])) {
                        array_push($player_hand[$player_id]['mask'][1], $hand);
                    } else {
                        array_push($player_hand[$player_id]['mask'][2], $hand);
                    }
                } else {
                    array_push($player_hand[$player_id]['other'], $hand);
                }
            }

            foreach ($this->cards->getCardsInLocation("hand_used", $player_id) as $i => $hand) {
                array_push($player_hand[$player_id]['used'], $hand);
            }
        }
        $result['playersHand'] = $player_hand;

        if ($this->isDraftMode()) {
            $result['startingTiles'] = $this->cards->getCardsInLocation('sChoose_all');
        } else {
            $result['startingTiles'] = $this->cards->getCardsInLocation('sChoose_' . $current_player_id);
        }
        $result['startingTiles_data'] = $this->startingTiles;
        $result['ascensionInfo_data'] = $this->ascensionInfo;

        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        $prog = $this->getGameStateValue('progression');
        return $prog;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */

    function isRandomSetup()
    {
        return self::getGameStateValue('random_setup') == 1;
    }

    function isDarkEclipse()
    {
        return self::getGameStateValue('dark_eclipse') == 2;
    }

    function isDraftMode()
    {
        return self::getGameStateValue('draft_mode') == 2;
    }

    function isRandomPyramidSetup()
    {
        return self::getGameStateValue('pyramidSetup') == 2;
    }

    function stGameStart()
    {

        if ($this->isRandomSetup()) {
            if (!$this->isDraftMode()) {
                $this->activateMultiPlayer();
            } else {
                $this->activeNextPlayer();
                $this->gamestate->nextState("draft");
            }
        } else {
            self::DbQuery("UPDATE `player` SET cocoa = cocoa + 6, wood = 1, stone = 2, gold = 4, temple_green = 1 WHERE player_no = 1");
            self::DbQuery("UPDATE `player` SET cocoa = cocoa + 5, wood = 4, stone = 2, temple_red = 1, temple_blue = 1, player_score = 1 WHERE player_no = 2");

            $count_player = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `player`");

            if ($count_player >= 3) {
                self::DbQuery("UPDATE `player` SET cocoa = cocoa + 4, wood = 3, stone = 4, gold = 1, temple_blue = 1, avenue_of_dead = 1 WHERE player_no = 3");

                if ($count_player >= 4) {
                    self::DbQuery("UPDATE `player` SET cocoa = cocoa + 1, wood = 2, gold = 5, temple_green = 2, techTiles_r1_c3 = 1 WHERE player_no = 4");

                }
            }

            $player_id = self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE player_no = 1");
            $this->setPlayerWorkers($player_id, 7, 4, 5, 2, 1, 1);
            $player_id = self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE player_no = 2");
            $this->setPlayerWorkers($player_id, 6, 4, 2, 2, 1, 1);

            if ($count_player >= 3) {
                $player_id = self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE player_no = 3");
                $this->setPlayerWorkers($player_id, 1, 4, 6, 1, 1, 1);

                if ($count_player >= 4) {
                    $player_id = self::getUniqueValueFromDB("SELECT `player_id` FROM `player` WHERE player_no = 4");
                    $this->setPlayerWorkers($player_id, 2, 8, 3, 1, 1, 1);
                }
            }

            $this->setNonPlayerWorkers(false);
            $this->gamestate->nextState("playerTurn");
        }

    }

    function setPlayerWorkers($player_id, $board1, $board2, $board3, $power1, $power2, $power3)
    {
        $sql = "INSERT INTO `map`(`actionboard_id`, `player_id`, `worker_id`,`worker_power`, `locked`) VALUES";
        $values = array();
        $values[] = "($board1,'" . $player_id . "','1','$power1',0)";
        $values[] = "($board2,'" . $player_id . "','2','$power2',0)";
        $values[] = "($board3,'" . $player_id . "','3','$power3',0)";
        $sql .= implode($values, ',');
        self::DbQuery($sql);
    }

    function stCalculateNextDraftPlayer()
    {
        $player_id = self::getActivePlayerId();

        $player_no = (int)self::getUniqueValueFromDB("SELECT `player_no` FROM `player` WHERE `player_id` = $player_id");
        $count_player = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `player`");

        $prev = (int)self::getGameStateValue('draftReverse');

        if ($player_no == $count_player) {

            $secondTile = self::getUniqueValueFromDB("SELECT `startingTile1` FROM `player` WHERE `player_id` = $player_id");

            if ($secondTile == null) {
                self::giveExtraTime($player_id);
                $this->gamestate->nextState('draft');
            } else {
                self::setGameStateValue('draftReverse', 1);
                $player_id = self::activePrevPlayer();
                self::giveExtraTime($player_id);
                $this->gamestate->nextState('draft');
            }

        } else if ($player_no == 1 && $prev) {
            $player_id = self::activePrevPlayer();
            self::giveExtraTime($player_id);
            $this->gamestate->nextState('bonus');
        } else {
            if ($prev) {
                $player_id = self::activePrevPlayer();
                self::giveExtraTime($player_id);
                $this->gamestate->nextState('draft');
            } else {
                $player_id = self::activeNextPlayer();
                self::giveExtraTime($player_id);
                $this->gamestate->nextState('draft');
            }
        }
    }

    function stStartTurn()
    {
        self::trace("stStartTurn");

        $player_id = self::activeNextPlayer();
        self::giveExtraTime($player_id);

        $this->undoSavepoint();

        $this->gamestate->nextState();
    }

    function resetGameStateValues()
    {
        self::setGameStateValue('selected_board_id_to', 0);
        self::setGameStateValue('selected_board_id_from', 0);
        self::setGameStateValue('selected_worker_id', 0);
        self::setGameStateValue('selected_worker2_id', 0);
        self::setGameStateValue('worship_actions_worship', 0);
        self::setGameStateValue('worship_actions_discovery', 0);
        self::setGameStateValue('choose_resources_max', 0);
        self::setGameStateValue('temple_bonus_cocoa', 0);
        self::setGameStateValue('temple_bonus_vp', 0);
        self::setGameStateValue('temple_bonus_resource', 0);
        self::setGameStateValue('last_temple_id', 0);
        self::setGameStateValue('previous_game_state', 0);
        self::setGameStateValue('useDiscovery', 0);
        self::setGameStateValue('useDiscoveryMoveWorkerAnywhere', 0);
        self::setGameStateValue('useDiscoveryMoveTwoWorkers', 0);
        self::setGameStateValue('upgradeWorkers', 0);
        self::setGameStateValue('ascension', 0);
        self::setGameStateValue('ascensionTempleSteps', 0);
        self::setGameStateValue('doMainAction', 0);
        self::setGameStateValue('useDiscoveryPowerUp', 0);
        self::setGameStateValue('ascensionBonusChoosed', 0);
        self::setGameStateValue('buildOnePyramidTile', 0);
        self::setGameStateValue('royalTileTradeId', 0);
        self::setGameStateValue('royalTileAction', 0);
        self::setGameStateValue('useDiscoveryId', 0);
        self::setGameStateValue('isNobles', 0);
        self::setGameStateValue('paidPowerUp', 0);
        self::setGameStateValue('canBuildPyramidTiles', 0);
        self::setGameStateValue('isConstruction', 0);
        self::setGameStateValue('isDecoration', 0);
        self::setGameStateValue('extraWorker', 0);
        self::setGameStateValue('startingTileBonus', 0);
        self::setGameStateValue('getTechnologyDiscount', 0);
        self::setGameStateValue('aquiredTechnologyTile', -1);
        self::setGameStateValue('draftReverse', 0);

        self::DbQuery("TRUNCATE temple_queue");
        self::DbQuery("TRUNCATE discovery_queue");
    }

    function prepareNextPlayer()
    {
        $this->resetGameStateValues();
        $this->refillTiles();

        $eclipse = (int)self::getGameStateValue('eclipse');

        $player_id = self::getActivePlayerId();
        $sql = "SELECT `player_no` FROM `player` WHERE `player_id` = $player_id";
        $player_no = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT count(*) FROM `player`";
        $count_player = (int)self::getUniqueValueFromDB($sql);

        $black = (int)self::getGameStateValue('eclipseDiscBlack');
        $white = (int)self::getGameStateValue('eclipseDiscWhite');

        if ($white == $black && $this->isDarkEclipse()) {
            self::setGameStateValue('lastRound', 0);
            self::notifyAllPlayers("showEclipseBanner", '', array(
                'lastRound' => (int)self::getGameStateValue('lastRound'),
                'eclipseNumber' => $eclipse
            ));
            $this->eclipse();
        } else if ($player_no == $count_player) {
            $this->advanceCalenderTrack();

            $black = (int)self::getGameStateValue('eclipseDiscBlack');
            $white = (int)self::getGameStateValue('eclipseDiscWhite');

            $lastRound = (int)self::getGameStateValue('lastRound');

            if ($white >= $black) {
                if ($lastRound == 1 || $this->isDarkEclipse()) {
                    self::setGameStateValue('lastRound', 0);
                    self::notifyAllPlayers("showEclipseBanner", '', array(
                        'lastRound' => (int)self::getGameStateValue('lastRound'),
                        'eclipseNumber' => $eclipse
                    ));
                    $this->eclipse();
                } else {
                    self::setGameStateValue('lastRound', 1);
                    self::notifyAllPlayers("showEclipseBanner", clienttranslate('*** One round left ***'), array(
                        'lastRound' => (int)self::getGameStateValue('lastRound'),
                        'eclipseNumber' => $eclipse
                    ));
                    $this->gamestate->nextState("next_player");
                }
            } else {
                $this->gamestate->nextState("next_player");
            }
        } else {
            $this->gamestate->nextState("next_player");
        }

    }

    function eclipse()
    {
        $eclipse = (int)self::getGameStateValue('eclipse');

        $players = self::getObjectListFromDB("SELECT `player_id` FROM `player`");
        $playersData = self::getCollectionFromDB("SELECT player_id id, player_name, pyramid_track FROM `player`");

        // ----------- Avenue ---------------
        $row1 = (int)self::getUniqueValueFromDB("SELECT `row0` FROM `nobles`");
        $row2 = (int)self::getUniqueValueFromDB("SELECT `row1` FROM `nobles`");
        $row3 = (int)self::getUniqueValueFromDB("SELECT `row2` FROM `nobles`");
        $sum = $row1 + $row2 + $row3;
        $buildingVP = $this->buildings[$sum];

        foreach ($players as $player) {
            $player_id = $player['player_id'];
            $player_name = $playersData[$player_id]['player_name'];
            $step = (int)self::getUniqueValueFromDB("SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id");
            $amount = $buildingVP * $step;

            $this->updateVP($amount, $player_id);
            $playersData[$player_id]['scoring_avenue'] = $amount . " ($step)";
            self::incStat($amount, "summary_avenue", $player_id);

            if ($amount > 0) {
                self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on Avenue of Dead'), array(
                    'player_id' => $player_id,
                    'player_name' => $player_name,
                    'amount' => $amount,
                    'vp' => $amount,
                    'token_vp' => 'vp',
                ));
            }
        }

        // ----------- Pyramid Track ---------------
        $max = (int)self::getUniqueValueFromDB("SELECT max(pyramid_track) FROM `player`");
        $pyramidVP = 5 - $eclipse;

        foreach ($players as $player) {
            $player_id = $player['player_id'];
            $player_name = $playersData[$player_id]['player_name'];
            $pyramid_track = $playersData[$player_id]['pyramid_track'];

            if ($pyramid_track == $max && $max != 0) {
                $amount = 4;
                $this->updateVP($amount, $player_id);
                $playersData[$player_id]['scoring_pyramid_track_ahead'] = $amount;
                self::incStat($amount, "summary_pyramid", $player_id);
                self::incStat(1, "ahead_on_pyramid_track_eclipse$eclipse", $player_id);

                self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} for furthest ahead of pyramid track'), array(
                    'player_id' => $player_id,
                    'player_name' => $player_name,
                    'amount' => $amount,
                    'token_vp' => 'vp',
                ));
            } else {
                $playersData[$player_id]['scoring_pyramid_track_ahead'] = 0;
            }

            $step = (int)self::getUniqueValueFromDB("SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id");
            $amount = $pyramidVP * $step;
            $this->updateVP($amount, $player_id);
            $playersData[$player_id]['scoring_pyramid_track'] = $amount . " ($step)";
            self::incStat($amount, "summary_pyramid", $player_id);

            if ($amount > 0) {
                self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on pyramid track'), array(
                    'player_id' => $player_id,
                    'player_name' => $player_name,
                    'amount' => $amount,
                    'token_vp' => 'vp',
                ));
            }
        }

        // ----------- Masks ---------------
        $player_hand = $this->getAllDatas()['playersHand'];

        foreach ($players as $player) {
            $player_id = $player['player_id'];
            $player_name = $playersData[$player_id]['player_name'];

            $set1 = $this->scoringMask[count($player_hand[$player_id]['mask'][0])];
            $set2 = $this->scoringMask[count($player_hand[$player_id]['mask'][1])];
            $set3 = $this->scoringMask[count($player_hand[$player_id]['mask'][2])];

            $amount = $set1 + $set2 + $set3;

            $text = '';
            if ($set1 > 0) {
                $text = "($set1)";
            }
            if ($set2 > 0) {
                $text = $text . "($set2)";
            }
            if ($set3 > 0) {
                $text = $text . "($set3)";
            }

            $this->updateVP($amount, $player_id);
            $playersData[$player_id]['scoring_masks'] = $amount . " " . $text;

            if ($amount > 0) {
                self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on masks'), array(
                    'player_id' => $player_id,
                    'player_name' => $player_name,
                    'amount' => $amount,
                    'token_vp' => 'vp',
                ));
            }
        }

        // ----------- Summary ---------------
        $table = array(
            array(''),
            array(
                array('str' => clienttranslate('${amount} VP for each step on Avenue of Dead: (summary)'),
                    'args' => array('amount' => $buildingVP),
                )
            ),
            array(
                array('str' => clienttranslate('4 VP for furthest ahead on the Pyramid track'),
                    'args' => array(),
                )
            ),
            array(
                array('str' => clienttranslate('${amount} VP for each step on Pyramid track: (summary)'),
                    'args' => array('amount' => $pyramidVP),
                )
            ),
            array(
                array('str' => clienttranslate('Different masks sets'),
                    'args' => array(),
                )
            )
        );

        $table[] = array(array('str' => clienttranslate('TOTAL'),
            'args' => array(),
        ));

        foreach ($playersData as $player_id => $playerData) {
            $score = (int)explode(" ", $playerData["scoring_avenue"])[0]
                + $playerData["scoring_pyramid_track_ahead"]
                + (int)explode(" ", $playerData["scoring_pyramid_track"])[0]
                + (int)explode(" ", $playerData["scoring_masks"])[0];
            $playersData[$player_id]["scoring_total"] = $score;
        }

        foreach ($playersData as $player_id => $playerData) {
            $rowIndex = 0;
            $table[$rowIndex++][] = array('str' => '${player_name}',
                'args' => array('player_name' => $playerData['player_name']),
                'type' => 'header'
            );
            $table[$rowIndex++][] = $playerData["scoring_avenue"];
            $table[$rowIndex++][] = $playerData["scoring_pyramid_track_ahead"];
            $table[$rowIndex++][] = $playerData["scoring_pyramid_track"];
            $table[$rowIndex++][] = $playerData["scoring_masks"];
            $table[$rowIndex++][] = $playerData["scoring_total"];

            $this->notifyAllPlayers('calculateEndGameScoring', clienttranslate('${player_name} summary eclipse score: ${score}${token_vp}'), Array(
                'player_id' => $player_id,
                'player_name' => $playerData['player_name'],
                'score' => $playerData["scoring_total"],
                'token_vp' => 'vp',
            ));
        }

        $this->notifyAllPlayers('tableWindow', '', Array(
            'id' => 'eclipseScoring',
            'title' => clienttranslate('Eclipse scoring summary'),
            'table' => $table,
            'closing' => clienttranslate('Close summary')
        ));

        self::notifyAllPlayers("paySalary", clienttranslate('*** Pay Salary ***'), array());
        $this->gamestate->nextState("pay_salary");
    }


    function activateMultiPlayer()
    {
        $this->gamestate->setAllPlayersMultiactive();
    }

    function checkEndGame()
    {
        $eclipse = (int)self::getGameStateValue('eclipse');
        if ($eclipse < 3) {
            self::incGameStateValue('eclipse', 1);
        }

        $players = self::getObjectListFromDB("SELECT `player_id` FROM `player`");

        if ($eclipse < 3) {
            self::incGameStateValue('eclipseDiscBlack', -1);
            self::setGameStateValue('eclipseDiscWhite', 0);

            self::notifyAllPlayers("updateCalenderTrack", clienttranslate('Dark disc updated on the Calender track'), array(
                'step' => self::getGameStateValue('eclipseDiscBlack'),
                'color' => 'black',
            ));
            self::notifyAllPlayers("updateCalenderTrack", clienttranslate('Reset the light disc on the Calender track'), array(
                'step' => 0,
                'color' => 'white',
            ));
            foreach ($players as $player) {
                $player_id = $player['player_id'];

                self::DbQuery("UPDATE `player` SET `pyramid_track`  = 0 WHERE player_id = $player_id");

                self::notifyAllPlayers("stepPyramidTrack", '', array(
                    'player_id' => $player_id,
                    'step' => 0,
                ));
            }
            $this->setNonPlayerWorkers();
            $this->gamestate->nextState("next_player");
        } else {
            self::setGameStateValue('progression', 100);

            foreach ($players as $player) {
                $player_id = $player['player_id'];

                $cocoa = (int)self::getUniqueValueFromDB("SELECT `cocoa` FROM `player` WHERE `player_id` = $player_id");
                self::DbQuery("UPDATE `player` SET player_score_aux = $cocoa WHERE player_id = $player_id");
            }

            $ties = self::getObjectListFromDB("SELECT `player_score`,`player_score_aux` FROM `player` GROUP By `player_score`,`player_score_aux` HAVING  COUNT(*) > 1");

            foreach ($ties as $tie) {
                $player_score = $tie['player_score'];
                $player_score_aux = $tie['player_score_aux'];

                $players_tie = self::getObjectListFromDB("SELECT `player_no`,`player_id` FROM `player` WHERE `player_score` = $player_score AND `player_score_aux` = $player_score_aux");

                foreach ($players_tie as $player) {
                    $player_id = $player['player_id'];
                    $player_no = $player['player_no'];

                    if ($this->isDarkEclipse()) {
                        self::DbQuery("UPDATE `player` SET player_score_aux = $player_no WHERE player_id = $player_id");
                    } else {
                        self::DbQuery("UPDATE `player` SET player_score_aux = -$player_no WHERE player_id = $player_id");
                    }
                }
            }

            $table = array(
                array(''),
                array(
                    array('str' => clienttranslate('Temple Bonus blue'),
                        'args' => array(),
                    )
                ),
                array(
                    array('str' => clienttranslate('Temple Bonus red'),
                        'args' => array(),
                    )
                ),
                array(
                    array('str' => clienttranslate('Temple Bonus green'),
                        'args' => array(),
                    )
                )
            );

            $table[] = array(array('str' => clienttranslate('TOTAL'),
                'args' => array(),
            ));

            $playersData = self::getCollectionFromDB("SELECT player_id id, player_name FROM `player`");

            foreach ($players as $player) {
                $player_id = $player['player_id'];
                $player_name = $playersData[$player_id]['player_name'];
                $amount = $this->getTempleBonus('blue', $player_id);
                $this->updateVP($amount, $player_id);
                $playersData[$player_id]['scoring_temple_blue'] = $amount;
                if ($amount > 0) {
                    self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on temple blue'), array(
                        'player_id' => $player_id,
                        'player_name' => $player_name,
                        'amount' => $amount,
                        'token_vp' => 'vp',
                    ));
                }
            }
            foreach ($players as $player) {
                $player_id = $player['player_id'];
                $player_name = $playersData[$player_id]['player_name'];
                $amount = $this->getTempleBonus('red', $player_id);
                $this->updateVP($amount, $player_id);
                $playersData[$player_id]['scoring_temple_red'] = $amount;
                if ($amount > 0) {
                    self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on temple red'), array(
                        'player_id' => $player_id,
                        'player_name' => $player_name,
                        'amount' => $amount,
                        'token_vp' => 'vp',
                    ));
                }
            }
            foreach ($players as $player) {
                $player_id = $player['player_id'];
                $player_name = $playersData[$player_id]['player_name'];
                $amount = $this->getTempleBonus('green', $player_id);
                $this->updateVP($amount, $player_id);
                $playersData[$player_id]['scoring_temple_green'] = $amount;
                if ($amount > 0) {
                    self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} scored ${amount}${token_vp} on temple green'), array(
                        'player_id' => $player_id,
                        'player_name' => $player_name,
                        'amount' => $amount,
                        'token_vp' => 'vp',
                    ));
                }
            }

            $playersDataScore = self::getCollectionFromDB("SELECT player_id id,player_score FROM `player`");

            foreach ($players as $player) {
                $player_id = $player['player_id'];
                self::incStat($playersDataScore[$player_id]['player_score'], "summary_score", $player_id);
            }

            // ----------- Summary ---------------
            foreach ($playersData as $player_id => $playerData) {
                $score = $playerData["scoring_temple_blue"]
                    + $playerData["scoring_temple_red"]
                    + $playerData["scoring_temple_green"];
                $playersData[$player_id]["scoring_total"] = $score;
            }

            foreach ($playersData as $player_id => $playerData) {
                $rowIndex = 0;
                $table[$rowIndex++][] = array('str' => '${player_name}',
                    'args' => array('player_name' => $playerData['player_name']),
                    'type' => 'header'
                );
                $table[$rowIndex++][] = $playerData["scoring_temple_blue"];
                $table[$rowIndex++][] = $playerData["scoring_temple_red"];
                $table[$rowIndex++][] = $playerData["scoring_temple_green"];
                $table[$rowIndex++][] = $playerData["scoring_total"];

                $this->notifyAllPlayers('calculateEndGameScoring', clienttranslate('${player_name} summary temple score: ${score}${token_vp}'), Array(
                    'player_id' => $player_id,
                    'player_name' => $playerData['player_name'],
                    'score' => $playerData["scoring_total"],
                    'token_vp' => 'vp',
                ));
            }

            $this->notifyAllPlayers('tableWindow', '', Array(
                'id' => 'eclipseScoring',
                'title' => clienttranslate('Temple scoring summary'),
                'table' => $table,
                'closing' => clienttranslate('Close summary')
            ));

            $this->gamestate->nextState("game_end");
        }

    }

    function getTempleBonus($color, $player_id)
    {
        $sql = "SELECT `temple_$color` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        if ($step < 10) {
            return 0;
        }

        $location = "t" . $color . "Tile";
        $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'templeBonusTiles' AND `card_location` = '$location'";
        $type_arg = (int)self::getUniqueValueFromDB($sql);

        if ($type_arg == 0) {
            $player_hand = $this->getAllDatas()['playersHand'];

            $set1 = $this->scoringMask[count($player_hand[$player_id]['mask'][0])];
            $set2 = $this->scoringMask[count($player_hand[$player_id]['mask'][1])];
            $set3 = $this->scoringMask[count($player_hand[$player_id]['mask'][2])];

            return max($set1, $set2, $set3);
        } else if ($type_arg == 1) {

            $amount = 0;

            for ($i = 0; $i < 9; $i++) {
                if ($this->isTechAquired($i, $player_id)) {
                    $amount += 5;
                }
            }

            return $amount;
        } else if ($type_arg == 2) {

            return 15;
        } else if ($type_arg == 3) {
            $step = (int)self::getUniqueValueFromDB("SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id");
            return $step * 3;
        } else if ($type_arg == 4) {
            $amount = 0;

            $step = (int)self::getUniqueValueFromDB("SELECT `temple_blue` FROM `player` WHERE `player_id` = $player_id");
            if ($step >= 10) {
                $amount += 9;
            }
            $step = (int)self::getUniqueValueFromDB("SELECT `temple_red` FROM `player` WHERE `player_id` = $player_id");
            if ($step >= 10) {
                $amount += 9;
            }
            $step = (int)self::getUniqueValueFromDB("SELECT `temple_green` FROM `player` WHERE `player_id` = $player_id");
            if ($step >= 10) {
                $amount += 9;
            }
            return $amount;
        } else if ($type_arg == 5) {
            $player_hand = $this->getAllDatas()['playersHand'];

            $other = count($player_hand[$player_id]['other']);
            $used = count($player_hand[$player_id]['used']);
            return ($other + $used) * 2;
        } else if ($type_arg == 6) {
            $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `actionboard_id` != -1";
            $workers = self::getObjectListFromDB($sql);
            $amount = 0;
            foreach ($workers as $worker) {
                $worker_power = $worker['worker_power'];
                if ($worker_power < 4) {
                    $amount += 4;
                } else {
                    $amount += 9;
                }
            }

            return $amount;
        }

        return 0;

    }

    function getGlobalVariables()
    {
        $player_id = self::getActivePlayerId();

        $row1 = (int)self::getUniqueValueFromDB("SELECT `row0` FROM `nobles`");
        $row2 = (int)self::getUniqueValueFromDB("SELECT `row1` FROM `nobles`");
        $row3 = (int)self::getUniqueValueFromDB("SELECT `row2` FROM `nobles`");

        $global = array(
            "selected_board_id_to" => self::getGameStateValue('selected_board_id_to'),
            "selected_board_id_from" => self::getGameStateValue('selected_board_id_from'),
            "selected_worker_id" => self::getGameStateValue('selected_worker_id'),
            "selected_worker2_id" => self::getGameStateValue('selected_worker2_id'),
            "worship_actions_worship" => self::getGameStateValue('worship_actions_worship'),
            "worship_actions_discovery" => self::getGameStateValue('worship_actions_discovery'),
            "choose_resources_max" => self::getGameStateValue('choose_resources_max'),
            "temple_bonus_cocoa" => self::getGameStateValue('temple_bonus_cocoa'),
            "temple_bonus_vp" => self::getGameStateValue('temple_bonus_vp'),
            "temple_bonus_resource" => self::getGameStateValue('temple_bonus_resource'),
            "last_temple_id" => self::getGameStateValue('last_temple_id'),
            "useDiscoveryMoveWorkerAnywhere" => self::getGameStateValue('useDiscoveryMoveWorkerAnywhere'),
            "useDiscoveryMoveTwoWorkers" => self::getGameStateValue('useDiscoveryMoveTwoWorkers'),
            "eclipseDiscWhite" => self::getGameStateValue('eclipseDiscWhite'),
            "eclipseDiscBlack" => self::getGameStateValue('eclipseDiscBlack'),
            "eclipse" => self::getGameStateValue('eclipse'),
            "lastRound" => self::getGameStateValue('lastRound'),
            "row1" => $row1,
            "row2" => $row2,
            "row3" => $row3,
            "isDraftMode" => $this->isDraftMode(),
        );

        $lockedWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = true AND `worship_pos` > 0");

        $lockedWorkersText = '';
        if ($lockedWorkers > 0) {
            $lockedWorkersText = 'or';
        }

        $playerInfo = self::getCollectionFromDb("SELECT player_id id, player_score score, cocoa, wood, stone, gold FROM player ");
        $clickableWorkers = self::getObjectListFromDB("SELECT `worker_id` FROM `map` WHERE `player_id` = $player_id AND `locked` = false", true);

        return array(
            'global' => $global,
            'lockedWorkers' => $lockedWorkers,
            'lockedWorkersText' => $lockedWorkersText,
            'playerInfo' => $playerInfo,
            'clickableWorkers' => $clickableWorkers
        );
    }

    function getMaxResources()
    {
        return array(
            'max' => self::getGameStateValue('choose_resources_max')
        );
    }

    function getTempleBonusValue()
    {
        return array(
            'last_temple_id' => self::getGameStateValue('last_temple_id'),
            'temple_bonus_resource' => self::getGameStateValue('temple_bonus_resource'),
            'temple_bonus_vp' => self::getGameStateValue('temple_bonus_vp'),
            'temple_bonus_cocoa' => self::getGameStateValue('temple_bonus_cocoa')
        );
    }

    function getWorshipInfo()
    {
        $sql = "SELECT `queue` FROM `temple_queue` ORDER BY id DESC LIMIT 1";
        $queue = self::getUniqueValueFromDB($sql);

        $player_id = self::getActivePlayerId();
        $selected_worker_id = (int)self::getGameStateValue('selected_worker_id');
        $selected_board_id_to = (int)self::getGameStateValue('selected_board_id_to');
        $royalTileAction = (int)self::getGameStateValue('royalTileAction');

        $worship_pos = -1;
        if ($selected_board_id_to == 1 && $royalTileAction) {
            $sql = "SELECT `worship_pos` FROM `map` WHERE player_id = $player_id AND worker_id = $selected_worker_id";
            $worship_pos = (int)self::getUniqueValueFromDB($sql);
        }

        $queueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `temple_queue`");
        $worship_actions_discovery = (int)self::getGameStateValue('worship_actions_discovery');

        if ($worship_actions_discovery && $queueCount > 0) {
            $description = clienttranslate('have to step on the temple or may claim the discovery tile');
        } else if ($worship_actions_discovery && $queueCount <= 0) {
            $description = clienttranslate('may claim the discovery tile');
        } else if (!$worship_actions_discovery && $queueCount > 0) {
            $description = clienttranslate('have to step on the temple');
        } else {
            $description = clienttranslate('have to choose the worship action');
        }

        return array(
            'queue' => $queue,
            'worship_actions_discovery' => self::getGameStateValue('worship_actions_discovery'),
            'worship_pos' => $worship_pos,
            'description' => $description,
            'royalTileAction' => $royalTileAction
        );
    }

    function getTradeInfo()
    {
        $id = (int)self::getGameStateValue('royalTileTradeId');

        $player_id = self::getActivePlayerId();
        $selected_worker_id = self::getGameStateValue('selected_worker_id');

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $selected_worker_id";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        $max = $worker_power;

        $cocoa = (int)self::getUniqueValueFromDB("SELECT `cocoa` FROM `player` WHERE `player_id` = $player_id");
        $wood = (int)self::getUniqueValueFromDB("SELECT `wood` FROM `player` WHERE `player_id` = $player_id");
        $stone = (int)self::getUniqueValueFromDB("SELECT `stone` FROM `player` WHERE `player_id` = $player_id");
        $gold = (int)self::getUniqueValueFromDB("SELECT `gold` FROM `player` WHERE `player_id` = $player_id");

        $tradeInfo = $this->royalTilesTrade[$id];
        $r = $wood + $stone + $gold;

        if ($tradeInfo['id'] == 'trade_c_t') {
            $max--;
            $max = min($max, $cocoa);
        }

        if ($tradeInfo['id'] == 'trade_c_ws' || $tradeInfo['id'] == 'trade_c_sg') {
            $max = min($max, $cocoa);
        }

        if ($tradeInfo['id'] == 'trade_r_2c') {
            $max = min($max, $r);
        }

        return array(
            'pay' => $tradeInfo['pay'],
            'get' => $tradeInfo['get'],
            'max' => $max,
            'maxWood' => $wood,
            'maxStone' => $stone,
            'maxGold' => $gold,
        );
    }

    function checkEndTurn()
    {
        if ($this->canUseDiscoveryTiles()) {
            $this->gamestate->nextState("check_pass");
        } else {
            $player_id = self::getCurrentPlayerId();
            $enableUndo = (int)self::getUniqueValueFromDB("SELECT `enableUndo` FROM `player` WHERE `player_id` = $player_id");

            if($enableUndo > 0){
                $this->gamestate->nextState("undo");
            } else {
                $this->gamestate->nextState("next_player");
            }
        }
    }

    function checkStartingDiscoveryTiles()
    {
        $player_id = self::getCurrentPlayerId();
        if ($this->isDraftMode()) {
            $location = 'sChoose_all';
        } else {
            $location = 'sChoose_' . $player_id;
        }
        $discTilesLeft = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = '$location'");

        if ($discTilesLeft == 0 && !$this->canUseDiscoveryTiles()) {
            $this->pass();
        }
    }

    function canUseDiscoveryTiles()
    {
        $current_player_id = self::getCurrentPlayerId();
        $possibleDiscoveryTiles = [17, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41];
        $isInArray = false;
        $playerHand = $this->getAllDatas()['playersHand'][$current_player_id]['other'];

        for ($i = 0; $i < count($playerHand); $i++) {
            $type_arg = (int)$playerHand[$i]['type_arg'];
            if (in_array($type_arg, $possibleDiscoveryTiles)) {
                $isInArray = true;
                break;
            }
        }

        return $isInArray;
    }

    function refillTiles()
    {
        $newTiles = array();

        $pyramidTiles_0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'pyramidTiles' AND `card_location` = 'pyramidTiles_0'");
        $pyramidTiles_1 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'pyramidTiles' AND `card_location` = 'pyramidTiles_1'");
        $pyramidTiles_2 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'pyramidTiles' AND `card_location` = 'pyramidTiles_2'");

        if ($pyramidTiles_0 <= 0) {
            $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_0');
            array_push($newTiles, current($this->cards->getCardsInLocation('pyramidTiles_0')));
        }
        if ($pyramidTiles_1 <= 0) {
            $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_1');
            array_push($newTiles, current($this->cards->getCardsInLocation('pyramidTiles_1')));
        }
        if ($pyramidTiles_2 <= 0) {
            $this->cards->pickCardsForLocation(1, 'pyraTiles_deck', 'pyramidTiles_2');
            array_push($newTiles, current($this->cards->getCardsInLocation('pyramidTiles_2')));
        }

        if (count($newTiles) > 0) {
            $pyramidTiles = $this->getAllDatas()['pyramidTiles'];

            self::notifyAllPlayers("refillPyramidTileOffer", '', array(
                'newTiles' => $newTiles,
                'pyramidTiles' => $pyramidTiles,
            ));
        }

        $newTiles = array();

        $decorationTiles_0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'decorationTiles' AND `card_location` = 'decoTiles_0'");
        $decorationTiles_1 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'decorationTiles' AND `card_location` = 'decoTiles_1'");
        $decorationTiles_2 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'decorationTiles' AND `card_location` = 'decoTiles_2'");
        $decorationTiles_3 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'decorationTiles' AND `card_location` = 'decoTiles_3'");

        if ($decorationTiles_0 <= 0) {
            $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_0');
            array_push($newTiles, current($this->cards->getCardsInLocation('decoTiles_0')));
        }
        if ($decorationTiles_1 <= 0) {
            $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_1');
            array_push($newTiles, current($this->cards->getCardsInLocation('decoTiles_1')));
        }
        if ($decorationTiles_2 <= 0) {
            $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_2');
            array_push($newTiles, current($this->cards->getCardsInLocation('decoTiles_2')));
        }
        if ($decorationTiles_3 <= 0) {
            $this->cards->pickCardsForLocation(1, 'decoTiles_deck', 'decoTiles_3');
            array_push($newTiles, current($this->cards->getCardsInLocation('decoTiles_3')));
        }

        if (count($newTiles) > 0) {
            $decorationTiles = $this->getAllDatas()['decorationTiles'];

            self::notifyAllPlayers("refillDecorationTileOffer", '', array(
                'newTiles' => $newTiles,
                'decorationTiles' => $decorationTiles,
            ));
        }

        $newTiles = array();

        $discTiles_b1 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b1'");
        $discTiles_b2 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b2'");
        $discTiles_b3 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b3'");
        $discTiles_b4 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b4'");
        $discTiles_b7 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b7'");

        if ($discTiles_b1 <= 0) {
            $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b1');
            array_push($newTiles, current($this->cards->getCardsInLocation('discTiles_b1')));
        }
        if ($discTiles_b2 <= 0) {
            $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b2');
            array_push($newTiles, current($this->cards->getCardsInLocation('discTiles_b2')));
        }
        if ($discTiles_b3 <= 0) {
            $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b3');
            array_push($newTiles, current($this->cards->getCardsInLocation('discTiles_b3')));
        }
        if ($discTiles_b4 <= 0) {
            $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b4');
            array_push($newTiles, current($this->cards->getCardsInLocation('discTiles_b4')));
        }
        if ($discTiles_b7 <= 0) {
            $this->cards->pickCardsForLocation(1, 'discTiles_deck', 'discTiles_b7');
            array_push($newTiles, current($this->cards->getCardsInLocation('discTiles_b7')));
        }

        if (count($newTiles) > 0) {
            $discoveryTiles = $this->getAllDatas()['discoveryTiles'];

            self::notifyAllPlayers("refillDiscoveryTilesOffer", '', array(
                'newTiles' => $newTiles,
                'discoveryTiles' => $discoveryTiles,
            ));
        }
    }

    function areDiscoveryTilesLeft()
    {
        $player_id = self::getCurrentPlayerId();
        $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
        self::setGameStateValue('doMainAction', 0);

        $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
        if ($worker && count($worker) > 0) {
            $this->gamestate->nextState("ascension");
        } else if ($upgradeWorkers > 0) {
            $this->gamestate->nextState("upgrade_workers");
        } else {
            $current_player_id = self::getCurrentPlayerId();
            $possibleDiscoveryTiles = [17, 18, 19, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41];
            $isInArray = false;
            $playerHand = $this->getAllDatas()['playersHand'][$current_player_id]['other'];

            for ($i = 0; $i < count($playerHand); $i++) {
                $type_arg = (int)$playerHand[$i]['type_arg'];
                if (in_array($type_arg, $possibleDiscoveryTiles)) {
                    $isInArray = true;
                    break;
                }
            }
            if (!$isInArray) {
                $enableUndo = (int)self::getUniqueValueFromDB("SELECT `enableUndo` FROM `player` WHERE `player_id` = $player_id");

                if($enableUndo > 0){
                    $this->gamestate->nextState("undo");
                } else {
                    $this->gamestate->nextState("pass");
                }
            }
        }
    }

    function useDiscoveryTemple($temple, $steps = 1)
    {
        self::setGameStateValue('useDiscoveryId', -1);
        $this->setPreviousState();

        $gameStateValue = self::getGameStateValue('previous_game_state');
        for ($i = 0; $i < $steps; $i++) {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('$temple',$gameStateValue)";
            self::DbQuery($sql);
        }

        $this->gamestate->nextState("useDiscoveryTile");
        $this->gamestate->nextState("action");
    }

    function insert($array, $index, $val)
    {
        $size = count($array); //because I am going to use this more than one time
        if (!is_int($index) || $index < 0 || $index > $size) {
            return -1;
        } else {
            $temp = array_slice($array, 0, $index);
            $temp[] = $val;
            return array_merge($temp, array_slice($array, $index, $size));
        }
    }

    function isMaskInArray($id, $arrayDiscTiles)
    {
        foreach ($arrayDiscTiles as $disc) {
            $mask_id = $this->discoveryTiles[$disc['type_arg']]['bonus']['mask'];
            if ($mask_id == $id) {
                return true;
            }
        }
        return false;
    }

    function getTechnologyRow()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $row = 1;

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT MIN(`worker_power`) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        $extraWorker = (int)self::getGameStateValue('extraWorker');
        $countWorkers += $extraWorker;

        if ($countWorkers > 1 || ($countWorkers == 1 && ($worker_power == 4 || $worker_power == 5))) {
            $row = 2;
        }

        return array(
            'row' => $row,
        );
    }

    function isPalaceTechAquired()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_board_id_from = self::getGameStateValue('selected_board_id_from');
        $selected_worker_id = self::getGameStateValue('selected_worker_id');
        $selected_worker2_id = self::getGameStateValue('selected_worker2_id');
        $useDiscoveryMoveTwoWorkers = self::getGameStateValue('useDiscoveryMoveTwoWorkers');
        $map = $this->getAllDatas()['map'];

        $canBuyDiscoveryTile = false;
        $canBuyDiscoveryTileBoth = false;
        $card_id_actionBoards = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to");
        $discoveryTile_id = self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b$card_id_actionBoards'");

        if ($discoveryTile_id != null) {
            $discoveryTile_id = (int)$discoveryTile_id;
            $priceCocoa = $this->discoveryTiles[$discoveryTile_id]['price']['cocoa'];
            $priceWood = $this->discoveryTiles[$discoveryTile_id]['price']['wood'];
            $priceGold = $this->discoveryTiles[$discoveryTile_id]['price']['gold'];

            $cocoa = (int)self::getUniqueValueFromDB("SELECT `cocoa` FROM `player` WHERE `player_id` = $player_id");
            $wood = (int)self::getUniqueValueFromDB("SELECT `wood` FROM `player` WHERE `player_id` = $player_id");
            $gold = (int)self::getUniqueValueFromDB("SELECT `gold` FROM `player` WHERE `player_id` = $player_id");

            if ($cocoa >= $priceCocoa && $wood >= $priceWood && $gold >= $priceGold) {
                $canBuyDiscoveryTile = true;
            }
            if (($cocoa - 1) >= $priceCocoa && $wood >= $priceWood && $gold >= $priceGold) {
                $canBuyDiscoveryTileBoth = true;
            }
        }

        return array(
            'isPalaceTechAquired' => $this->isTechAquired(0),
            'selected_board_id_to' => $selected_board_id_to,
            'selected_board_id_from' => $selected_board_id_from,
            'selected_worker_id' => $selected_worker_id,
            'selected_worker2_id' => $selected_worker2_id,
            'useDiscoveryMoveTwoWorkers' => $useDiscoveryMoveTwoWorkers,
            'canBuyDiscoveryTile' => $canBuyDiscoveryTile,
            'canBuyDiscoveryTileBoth' => $canBuyDiscoveryTileBoth,
            'map' => $map
        );
    }

    function isConstructionWorkerTechAquired()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        if ($this->isTechAquired(7)) {
            $countWorkers++;
        }
        if (self::getGameStateValue('extraWorker')) {
            $countWorkers++;
        }

        $canBuildPyramidTiles = (int)self::getGameStateValue('canBuildPyramidTiles');
        $buildOnePyramidTile = (int)self::getGameStateValue('buildOnePyramidTile');

        return array(
            'isConstructionWorkerTechAquired' => $this->isTechAquired(7) && self::getGameStateValue('getTechnologyDiscount'),
            'canPass' => $buildOnePyramidTile > 0
        );
    }

    function getCountWorkers()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);
        return array(
            'countWorkersOnDecoration' => $countWorkers,
        );
    }

    function isTechAquired($type_arg, $player_id = null)
    {
        if ($player_id == null) {
            $player_id = self::getCurrentPlayerId();
        }
        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'technologyTiles' AND `card_type_arg` = $type_arg";
        $techTile = $this->cards->getCard((int)self::getUniqueValueFromDB($sql));
        $location = $techTile['location'];

        $aquiredTechnologyTile = self::getGameStateValue('aquiredTechnologyTile');

        if ($location == 'techTiles_deck' || $location == 'techTiles_row2' || $type_arg == $aquiredTechnologyTile) {
            return false;
        }
        $sql = "SELECT `$location` FROM `player` WHERE `player_id` = $player_id";
        return (int)self::getUniqueValueFromDB($sql);
    }

    function getNoblesRows()
    {
        $player_id = self::getActivePlayerId();

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $extraWorker = (int)self::getGameStateValue('extraWorker');

        $countWorkers += $extraWorker;

        return array(
            'row' => $countWorkers,
        );
    }

    function prePowerUp()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_worker_id = (int)self::getGameStateValue('selected_worker_id');
        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        $useStartingTile = (int)self::getGameStateValue('startingTileBonus') > 0;

        if ($countWorkers == 0 && !$discoveryQueueCount && !$useStartingTile) {
            $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
            self::setGameStateValue('upgradeWorkers', 0);

            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to";
            $card = self::getObjectFromDB($sql);

            $board_name_to = $this->actionBoards[$card["card_id"]]["name"];

            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} has no more workers on ${board_name_to} to power up (${amount}x)'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'board_name_to' => $board_name_to,
                'amount' => $upgradeWorkers,
            ));
            $this->cleanUpPowerUp();
        } else {
            $enableAuto = (int)self::getUniqueValueFromDB("SELECT `enableAuto` FROM `player` WHERE `player_id` = $player_id");
            $workersOnBoard = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to");
            $worker_power = (int)self::getUniqueValueFromDB("SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $selected_worker_id");
            $workerOnBoardId = (int)self::getUniqueValueFromDB("SELECT worker_id FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to");

            if($enableAuto > 0 && $worker_power < 5 && $workersOnBoard == 1 && $discoveryQueueCount == 0 && !$useStartingTile){
                $this->upgradeWorker($workerOnBoardId, $selected_board_id_to);
            }
        }
    }

    function upgradeOnBoardOnly()
    {
        $player_id = self::getActivePlayerId();

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        $useStartingTile = (int)self::getGameStateValue('startingTileBonus') > 0;

        $lockedWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = true AND `worship_pos` > 0");

        $undo = false;

        if ($discoveryQueueCount > 0 || $useStartingTile) {
            $clickableWorkers = self::getObjectListFromDB("SELECT `worker_id` FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `worker_power` < 6", true);
        } else {
            $clickableWorkers = self::getObjectListFromDB("SELECT `worker_id` FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `worker_power` < 6 AND `actionboard_id` = $selected_board_id_to", true);
            self::setGameStateValue('doMainAction', 0);
        }

        $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
        $useDiscoveryPowerUp = (int)self::getGameStateValue('useDiscoveryPowerUp');
        $amountPowerUpsDiscovery = '';
        $amountPowerUps = $upgradeWorkers;

        if ($useDiscoveryPowerUp) {
            $amountPowerUpsDiscovery = ' + (' . $useDiscoveryPowerUp . 'x ' . self::_("discovery tile") . ')';
            $amountPowerUps = $amountPowerUps - $useDiscoveryPowerUp;
        }

        return array(
            'lockedWorkers' => $lockedWorkers,
            'selected_board_id_to' => $selected_board_id_to,
            'clickableWorkers' => $clickableWorkers,
            'amount' => $amountPowerUps,
            'amountPowerUpsDiscovery' => $amountPowerUpsDiscovery,
        );
    }

    function getAscensionBonusAmount()
    {
        $ascension = (int)self::getGameStateValue('ascension');
        return array(
            'amount' => $ascension,
        );
    }

    function getMaxSalary()
    {
        $players = self::getObjectListFromDB("SELECT `player_id` FROM `player`");
        $playersData = self::getCollectionFromDB("SELECT player_id id FROM `player`");

        foreach ($players as $player) {
            $player_id = $player['player_id'];

            $smallWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `worker_power` <= 3 AND `actionboard_id` != -1");
            $bigWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `worker_power` >= 4 AND `actionboard_id` != -1");
            $max = $smallWorkers + $bigWorkers * 2;

            $cocoa = (int)self::getUniqueValueFromDB("SELECT `cocoa` FROM `player` WHERE `player_id` = $player_id");

            if ($cocoa > $max) {
                $cocoa = $max;
            }

            $playersData[$player_id]['max'] = $max;
            $playersData[$player_id]['cocoa'] = $cocoa;
        }


        return array(
            'playersData' => $playersData,
        );
    }

    function prepareStartingTilesBonus()
    {
        if (!$this->isDraftMode()) {
            $players = $this->getAllDatas()['players'];
            self::notifyAllPlayers("choosedStartingTiles", '', array(
                'players' => $players,
            ));
        }

        self::setGameStateValue('progression', 2);

        $this->activeNextPlayer();
        $this->gamestate->nextState("place_workers");
    }

    function setupPlayerWorkers()
    {
//        $workersLeft = count($this->getPossibleBoards());
//        if ($workersLeft == 3) {
//            for ($i = 0; $i < $workersLeft; $i++) {
//                $board_id = $this->getPossibleBoards()[0];
//                $board_pos = (int)self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_id` = $board_id");
//                $this->placeWorker($board_id, $board_pos);
//            }
//        }

    }

    function getPossibleBoards()
    {
        $player_id = self::getActivePlayerId();

        $startingTile0 = (int)self::getUniqueValueFromDB("SELECT `startingTile0` FROM `player` WHERE `player_id` = $player_id");
        $startingTile1 = (int)self::getUniqueValueFromDB("SELECT `startingTile1` FROM `player` WHERE `player_id` = $player_id");

        $boardsTile0 = $this->startingTiles[$startingTile0]['board'];
        $boardsTile1 = $this->startingTiles[$startingTile1]['board'];

        $boards = array_merge($boardsTile0, $boardsTile1);
        $boards = array_unique($boards);
        $boards = array_values($boards);

        $workers = self::getObjectListFromDB("SELECT `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `locked` = false", true);
        $ids = join("','", $workers);
        $card_id = self::getObjectListFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` in ('$ids')", true);
        $boards = array_diff($boards, $card_id);
        $boards = array_values($boards);

        return $boards;
    }

    function setStartingTilesBonus()
    {
        $player_id = self::getActivePlayerId();

        $startingTile0 = (int)self::getUniqueValueFromDB("SELECT `startingTile0` FROM `player` WHERE `player_id` = $player_id");
        $startingTile1 = (int)self::getUniqueValueFromDB("SELECT `startingTile1` FROM `player` WHERE `player_id` = $player_id");

        $this->setStartingBonusAuto($startingTile0);
        $this->setStartingBonusAuto($startingTile1);

        $temple_red0 = $this->startingTiles[$startingTile0]['bonus']['temple_red'];
        $temple_red1 = $this->startingTiles[$startingTile1]['bonus']['temple_red'];

        $temple_redMax = $temple_red0 + $temple_red1;
        if ($temple_redMax > 0) {
            if ($temple_redMax == 1) {
                $this->startingTileStepTemple($player_id, 'red');
            } else {
                $this->startingTileStepTemple($player_id, 'red', false);
                $this->startingTileStepTemple($player_id, 'red');
            }
        }

        $avenue0 = $this->startingTiles[$startingTile0]['bonus']['avenue'];
        $avenue1 = $this->startingTiles[$startingTile1]['bonus']['avenue'];

        $avenueMax = $avenue0 + $avenue1;
        if ($avenueMax > 0) {
            if ($avenueMax == 1) {
                $this->startingTileStepAvenue($player_id);
            } else {
                $this->startingTileStepAvenue($player_id, false);
                $this->startingTileStepAvenue($player_id);
            }
        }

        $temple_green0 = $this->startingTiles[$startingTile0]['bonus']['temple_green'];
        $temple_green1 = $this->startingTiles[$startingTile1]['bonus']['temple_green'];

        $temple_greenMax = $temple_green0 + $temple_green1;
        if ($temple_greenMax > 0) {
            if ($temple_greenMax == 1) {
                $this->startingTileStepTemple($player_id, 'green');
            } else {
                $this->startingTileStepTemple($player_id, 'green', false);
                $this->startingTileStepTemple($player_id, 'green');
            }
        }

        $resource0 = $this->startingTiles[$startingTile0]['bonus']['resource'];
        $resource1 = $this->startingTiles[$startingTile1]['bonus']['resource'];
        $resourceMax = $resource0 + $resource1;
        if ($resourceMax > 0) {
            $startingResourceWood = (int)self::getUniqueValueFromDB("SELECT `startingResourceWood` FROM `player` WHERE `player_id` = $player_id");
            $startingResourceStone = (int)self::getUniqueValueFromDB("SELECT `startingResourceStone` FROM `player` WHERE `player_id` = $player_id");
            $startingResourceGold = (int)self::getUniqueValueFromDB("SELECT `startingResourceGold` FROM `player` WHERE `player_id` = $player_id");
            $source = 'startingTile_' . $startingTile0;
            $this->collectResource($player_id, $startingResourceWood, 'wood', $source, clienttranslate('${player_name} choosed ${amount}${token_wood} as starting resource(s)'));
            $this->collectResource($player_id, $startingResourceStone, 'stone', $source, clienttranslate('${player_name} choosed ${amount}${token_stone} as starting resource(s)'));
            $this->collectResource($player_id, $startingResourceGold, 'gold', $source, clienttranslate('${player_name} choosed ${amount}${token_gold} as starting resource(s)'));
        }

        self::setGameStateValue('startingTileBonus', 1);
        self::setGameStateValue('worship_actions_discovery', 0);

        $maxResources = 0;

        if ($startingTile0 == 6 || $startingTile0 == 17) {
            $maxResources += 2;
        }
        if ($startingTile1 == 6 || $startingTile1 == 17) {
            $maxResources += 2;
        }

        if ($this->isDraftMode() && $maxResources > 0) {
            self::setGameStateValue('choose_resources_max', $maxResources);
            self::setGameStateValue('useDiscoveryId', -1);
            if ($startingTile0 == 6 || $startingTile0 == 17) {
                self::setGameStateValue('useDiscoveryId', 100 + $startingTile0);
            } else {
                self::setGameStateValue('useDiscoveryId', 100 + $startingTile1);
            }
            $this->setPreviousState();
            $this->gamestate->nextState("useDiscoveryTile");
            $this->gamestate->nextState("choose_resources");
        } else {
            $this->gamestate->nextState("calculate_next_bonus");
        }
    }

    function calculateNextBonus()
    {
        $player_id = self::getActivePlayerId();
        self::DbQuery("TRUNCATE discovery_queue");

        $startingTile0 = (int)self::getUniqueValueFromDB("SELECT `startingTile0` FROM `player` WHERE `player_id` = $player_id");
        $startingTile1 = (int)self::getUniqueValueFromDB("SELECT `startingTile1` FROM `player` WHERE `player_id` = $player_id");

        $startingTileBonus = (int)self::getGameStateValue('startingTileBonus');

        if ($startingTileBonus <= 1) {
            $upgrade0 = $this->startingTiles[$startingTile0]['bonus']['upgrade'];
            $upgrade1 = $this->startingTiles[$startingTile1]['bonus']['upgrade'];

            $upgradeMax = $upgrade0 + $upgrade1;
            if ($upgradeMax > 0) {
                self::incGameStateValue('upgradeWorkers', $upgradeMax);
                $this->gamestate->nextState("upgrade_workers");
                return false;
            }
        }

        if ($startingTileBonus <= 2) {
            $temple_blue0 = $this->startingTiles[$startingTile0]['bonus']['temple_blue'];
            $temple_blue1 = $this->startingTiles[$startingTile1]['bonus']['temple_blue'];

            $temple_blueMax = $temple_blue0 + $temple_blue1;
            if ($temple_blueMax > 0) {
                self::setGameStateValue('startingTileBonus', 3);

                for ($i = 0; $i < $temple_blueMax; $i++) {
                    self::DbQuery("INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_blue',1)");
                }
                $this->gamestate->nextState("action");
                return false;
            }
        }

        if ($startingTileBonus <= 3) {
            $technology0 = $this->startingTiles[$startingTile0]['bonus']['technology'];
            $technology1 = $this->startingTiles[$startingTile1]['bonus']['technology'];

            $technologyMax = $technology0 + $technology1;
            if ($technologyMax > 0) {
                self::setGameStateValue('startingTileBonus', 4);

                $sql = "SELECT `card_location` FROM `card` WHERE `card_type` = 'technologyTiles' AND `card_type_arg` = 0";
                $location = self::getUniqueValueFromDB($sql);

                if ($location == 'techTiles_deck' || $location == 'techTiles_row2') {
                    $sql = "SELECT `card_location` FROM `card` WHERE `card_type` = 'technologyTiles' AND `card_type_arg` = 1";
                    $location = self::getUniqueValueFromDB($sql);
                    $sql = "UPDATE `player` SET `$location`  = 1 WHERE player_id = $player_id";
                    self::DbQuery($sql);
                } else {
                    $sql = "UPDATE `player` SET `$location`  = 1 WHERE player_id = $player_id";
                    self::DbQuery($sql);
                }
                self::notifyAllPlayers("acquireTechnology", clienttranslate('${player_name} acquired a Technology'), array(
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'location' => $location,
                ));

                $eclipse = (int)self::getGameStateValue('eclipse');
                self::incStat(1, "aquiredTechnology_eclipse$eclipse", $player_id);

                if ($location == 'techTiles_r1_c1' || $location == 'techTiles_r2_c1') {
                    $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_blue',1)";
                } else if ($location == 'techTiles_r1_c2' || $location == 'techTiles_r2_c2') {
                    $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_red',1)";
                } else if ($location == 'techTiles_r1_c3' || $location == 'techTiles_r2_c3') {
                    $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_green',1)";
                }
                self::DbQuery($sql);

                $this->gamestate->nextState("action");
                return false;
            }
        }

        if ($startingTileBonus <= 5) {
            $discovery0 = $this->startingTiles[$startingTile0]['bonus']['discovery'];
            $discovery1 = $this->startingTiles[$startingTile1]['bonus']['discovery'];

            $discoveryMax = $discovery0 + $discovery1;
            if ($discoveryMax > 0) {
                if ($startingTileBonus < 5) {
                    self::setGameStateValue('worship_actions_discovery', $discoveryMax);
                }
                self::setGameStateValue('startingTileBonus', 5);

                $this->gamestate->nextState("claim_starting_Discovery");
                return false;
            }
        }

        $sql = "SELECT `player_no` FROM `player` WHERE `player_id` = $player_id";
        $player_no = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT count(*) FROM `player`";
        $count_player = (int)self::getUniqueValueFromDB($sql);

        $progression = 8 * $player_no / $count_player;
        self::setGameStateValue('progression', $progression);

        $this->resetGameStateValues();

        if ($player_no == $count_player) {
            $this->setNonPlayerWorkers();
            $this->gamestate->nextState("playerTurn");
        } else {
            $player_id = self::activeNextPlayer();
            self::giveExtraTime($player_id);
            $this->gamestate->nextState("place_workers");
        }
    }

    function setNonPlayerWorkers($notify = true)
    {
        $players = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `player`");
        if ($players < 4) {
            $nonPlayers = (4 - $players);
            self::DbQuery("DELETE FROM `map` WHERE `player_id` <= $nonPlayers");

            if ($notify) {
                self::notifyAllPlayers("removeNonPlayerWorkers", '', array(
                    'nonPlayers' => $nonPlayers,
                ));
            }

            for ($i = 0; $i < $nonPlayers; $i++) {

                $randomInt0 = random_int(0, 17);

                do {
                    $randomInt1 = random_int(0, 17);
                } while ($randomInt0 == $randomInt1);

                $boardsTile0 = $this->startingTiles[$randomInt0]['board'];
                $boardsTile1 = $this->startingTiles[$randomInt1]['board'];

                $boards = array_merge($boardsTile0, $boardsTile1);
                $boards = array_unique($boards);
                $boards = array_values($boards);

                for ($j = 1; $j <= 3; $j++) {
                    $board_id = $boards[($j - 1)];
                    $board_pos = (int)self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_id` = $board_id");

                    $sql = "INSERT INTO `map`(`actionboard_id`, `player_id`, `worker_id`,`worker_power`, `locked`, `worship_pos`) VALUES";
                    $values = array();
                    $values[] = "(" . $board_pos . ",'" . $i . "','$j','1',0,0)";
                    $sql .= implode($values, ',');
                    self::DbQuery($sql);

                    if ($notify) {
                        $board_name = $this->actionBoards[$board_id]["name"];
                        $map = $this->getAllDatas()['map'];

                        self::notifyAllPlayers("placeWorker", clienttranslate('Non Player Worker placed on ${board_name} (${board_id})'), array(
                            'player_id' => $i,
                            'board_name' => $board_name,
                            'board_id' => $board_id,
                            'board_pos' => $board_pos,
                            'map' => $map,
                            'worker_id' => $j,
                        ));
                    }

                }

            }
        }
    }

    function setStartingBonusAuto($id)
    {
        $player_id = self::getActivePlayerId();

        $statingTile_details = $this->startingTiles[$id]['bonus'];

        $cocoa = $statingTile_details['cocoa'];
        $wood = $statingTile_details['wood'];
        $stone = $statingTile_details['stone'];
        $gold = $statingTile_details['gold'];

        $source = 'startingTile_' . $id;

        if ($cocoa > 0) {
            $this->collectResource($player_id, $cocoa, 'cocoa', $source);
        }
        if ($wood > 0) {
            $this->collectResource($player_id, $wood, 'wood', $source);
        }
        if ($stone > 0) {
            $this->collectResource($player_id, $stone, 'stone', $source);
        }
        if ($gold > 0) {
            $this->collectResource($player_id, $gold, 'gold', $source);
        }

    }

    function startingTileStepAvenue($player_id, $notification = true)
    {
        $sql = "UPDATE `player` SET `avenue_of_dead`  = `avenue_of_dead` + 1 WHERE player_id = $player_id";
        self::DbQuery($sql);

        $sql = "SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        if ($notification) {
            self::notifyAllPlayers("stepAvenue", clienttranslate('${player_name} advanced one space on Avenue of Dead'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'step' => $step,
            ));
        } else {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} advanced one space on Avenue of Dead'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
            ));
        }

        self::incStat(1, "avenue", $player_id);
        $eclipse = (int)self::getGameStateValue('eclipse');
        self::incStat(1, "avenue_eclipse$eclipse", $player_id);
    }

    function startingTileStepTemple($player_id, $temple, $notification = true)
    {
        $sql = "UPDATE `player` SET `temple_$temple`  = `temple_$temple` + 1 WHERE player_id = $player_id";
        self::DbQuery($sql);

        $sql = "SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        $bonus = explode(":", $this->temples[$temple][1]);
        $source = "temple_" . $temple . "_step_" . $step;

        if ($bonus[1] == 'vp') {
            $this->collectResource($player_id, $bonus[0], 'vp', $source);
        } else if ($bonus[1] == 'c') {
            $this->collectResource($player_id, $bonus[0], 'cocoa', $source);
        }

        if ($notification) {
            self::notifyAllPlayers("stepTemple", clienttranslate('${player_name} advanced one space on temple ${temple}'), array(
                'i18n' => array('temple'),
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'temple' => $temple,
                'step' => $step,
                'bonus' => $bonus,
            ));
        } else {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} advanced one space on temple ${temple}'), array(
                'i18n' => array('temple'),
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'temple' => $temple,
            ));
        }

        self::incStat(1, "temple_$temple", $player_id);
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
////////////

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in teotihuacan.action.php)
    */

    /*

    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' );

        $player_id = self::getActivePlayerId();

        // Add your game logic to play a card there
        ...

        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );

    }

    */
    function cancelMoveToBoard()
    {
        self::checkAction('cancelMoveToBoard');
        $this->gamestate->nextState("canceled");
    }

    function showBoardActions($board_id_to, $board_id_from, $worker_id, $worker2_id)
    {
        self::checkAction('showBoardActions');

        $player_id = self::getActivePlayerId();

        $sql = "SELECT `actionboard_id`,`locked` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id";
        $worker = self::getObjectFromDB($sql);

        $valid = true;

        if ($worker["actionboard_id"] != $board_id_from) {
            $valid = false;
        }

        if ($worker["locked"] == true) {
            $valid = false;
        }

        if (self::getGameStateValue('useDiscoveryMoveTwoWorkers')) {
            $sql = "SELECT `actionboard_id`,`locked` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id";
            $worker2 = self::getObjectFromDB($sql);

            if ($worker["actionboard_id"] != $worker2["actionboard_id"]) {
                $valid = false;
            }

            if ($worker2["locked"] == true) {
                $valid = false;
            }
        }

        if (!self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
            $checkBoard = (int)$board_id_from;
            for ($i = 0; $i < 3; $i++) {
                $checkBoard++;
                if ($checkBoard > 8) {
                    $checkBoard = 1;
                }
                if ($checkBoard == $board_id_to) {
                    break;
                }
            }

            if ($checkBoard != $board_id_to) {
                $valid = false;
            }
        }

        if ($valid) {
            self::setGameStateValue('selected_board_id_to', $board_id_to);
            self::setGameStateValue('selected_board_id_from', $board_id_from);
            self::setGameStateValue('selected_worker_id', $worker_id);
            self::setGameStateValue('selected_worker2_id', $worker2_id);

            $this->gamestate->nextState("showBoardActions");
        } else {
            throw new BgaUserException(self::_("This move is not possible."));
        }
    }

    function doMainActionOnBoard($freeCocoa = false)
    {
        self::checkAction('doMainActionOnBoard');

        $player_id = self::getActivePlayerId();

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_board_id_from = self::getGameStateValue('selected_board_id_from');
        $selected_worker_id = self::getGameStateValue('selected_worker_id');
        $target = $player_id . '_worker_' . $selected_worker_id;
        $source = $player_id . '_worker_' . $selected_worker_id;

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to";
        $card_id = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        if ($card_id == 5) {
            $techTiles_r1_c1 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r1_c1` FROM `player` WHERE `player_id` = $player_id");
            $techTiles_r1_c2 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r1_c2` FROM `player` WHERE `player_id` = $player_id");
            $techTiles_r1_c3 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r1_c3` FROM `player` WHERE `player_id` = $player_id");
            $techTiles_r2_c1 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r2_c1` FROM `player` WHERE `player_id` = $player_id");
            $techTiles_r2_c2 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r2_c2` FROM `player` WHERE `player_id` = $player_id");
            $techTiles_r2_c3 = (int)self::getUniqueValueFromDB($sql = "SELECT `techTiles_r2_c3` FROM `player` WHERE `player_id` = $player_id");

            if ($techTiles_r1_c1 && $techTiles_r1_c2 && $techTiles_r1_c3 && $techTiles_r2_c1 && $techTiles_r2_c2 && $techTiles_r2_c3) {
                throw new BgaUserException(self::_("You already aquired all technologies."));
            } else if ($techTiles_r1_c1 && $techTiles_r1_c2 && $techTiles_r1_c3) {
                $this->updateGold(-2, false, null, clienttranslate("You do not have enough gold for the main action."));

                $worker_power = (int)self::getUniqueValueFromDB("SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id`=$selected_worker_id");

                $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (51,52,53) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
                $id = (int)self::getUniqueValueFromDB($sql);

                if (!($countWorkers > 0 || $worker_power >= 4 || $id)) {
                    throw new BgaUserException(self::_("This move is not possible."));
                }
            } else {
                $this->updateGold(-1, false, null, clienttranslate("You do not have enough gold for the main action."));
            }

        } else if ($card_id == 6) {
            $this->updateWood(-2, false, null, clienttranslate("You do not have enough wood for the main action."));
        } else if ($card_id == 7) {
            $gold = 4 - ($countWorkers + 1);
            if (self::getGameStateValue('useDiscoveryMoveTwoWorkers') && self::getGameStateValue('selected_worker2_id')) {
                $gold = $gold - 1;
            }
            if ($gold < 1) {
                $gold = 1;
            }
            $this->updateGold(-$gold, false, null, clienttranslate("You do not have enough gold for the main action."));

            $top = self::getObjectListFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_location` like 'deco_p_top'", true);
            $right = self::getObjectListFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_location` like 'deco_p_right'", true);
            $bottom = self::getObjectListFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_location` like 'deco_p_bottom'", true);
            $left = self::getObjectListFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_location` like 'deco_p_left'", true);

            if (count($top) > 0 && count($right) > 0 && count($bottom) > 0 && count($left) > 0) {
                $valid = false;

                if ($this->checkDecoration('top', count($top), false)) {
                    $valid = true;
                }
                if ($this->checkDecoration('right', count($right), false)) {
                    $valid = true;
                }
                if ($this->checkDecoration('bottom', count($bottom), false)) {
                    $valid = true;
                }
                if ($this->checkDecoration('left', count($left), false)) {
                    $valid = true;
                }
                if (!$valid) {
                    throw new BgaUserException(self::_("There is no space for decorations"));
                }
            }
        } else if ($card_id == 8) {
            $pyramid = self::getObjectListFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_type` = 'pyramidTiles' and `card_location` like 'pyra_rotate_%'", true);
            $bottom = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
            $level1 = array(100, 101, 102, 103, 104, 105, 106, 107, 108);
            $level2 = array(200, 201, 202, 203);
            $level3 = array(300);
            $finish = false;
            $wood = 0;

            for ($i = 0; $i < count($bottom); $i++) {
                if (!in_array($bottom[$i], $pyramid)) {
                    $finish = true;
                    $wood = 0;
                    break;
                }
            }

            if (!$finish) {
                for ($i = 0; $i < count($level1); $i++) {
                    if (!in_array($level1[$i], $pyramid)) {
                        $finish = true;
                        $wood = 1;
                        break;
                    }
                }
            }

            if (!$finish) {
                for ($i = 0; $i < count($level2); $i++) {
                    if (!in_array($level2[$i], $pyramid)) {
                        $finish = true;
                        $wood = 2;
                        break;
                    }
                }
            }

            if (!$finish) {
                if (!in_array($level3[0], $pyramid)) {
                    $wood = 3;
                } else {
                    throw new BgaUserException(self::_("This pyramid is already complete."));
                }
            }

            $stone = 2;

            if ($this->isTechAquired(7)) {
                $stonePlayer = (int)self::getUniqueValueFromDB("SELECT `stone` FROM `player` WHERE `player_id` = $player_id");
                $woodPlayer = (int)self::getUniqueValueFromDB("SELECT `wood` FROM `player` WHERE `player_id` = $player_id");
                if (!($stonePlayer >= $stone && ($woodPlayer + 1) >= $wood || ($stonePlayer + 1) >= $stone && $woodPlayer >= $wood)) {
                    throw new BgaUserException(self::_("You do not have enough resources for the main action."));
                }
            } else {

                $this->updateStone(-2, false, null, clienttranslate("You do not have enough stone for the main action."));
                $this->updateWood(-$wood, false, null, clienttranslate("You do not have enough wood for the main action."));
            }
        }

        if ($selected_board_id_to <= $selected_board_id_from && $selected_board_id_from != 1 && $this->isTechAquired(0) && !self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
            $actionBoard_1 = 'actionBoard_1';

            $selected_worker2_id = self::getGameStateValue('selected_worker2_id');

            if (self::getGameStateValue('useDiscoveryMoveTwoWorkers') && $selected_worker2_id != 0) {
                $this->collectResource($player_id, 2, 'cocoa', $actionBoard_1, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 1)'));
            } else {
                $this->collectResource($player_id, 1, 'cocoa', $actionBoard_1, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 1)'));
            }
        }

        if (!$freeCocoa) {

            $colors = $this->getDiffrentColorsOnBoard();
            $this->payResource($player_id, -$colors, 'cocoa', $target);
        } else {
            $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
            $id = (int)self::getUniqueValueFromDB($sql);
            if (!$id) {
                throw new BgaUserException(self::_("This move is not possible."));
            }

            $this->useDiscoveryTile($id, true);
        }

        $this->moveWorkerToBoard(0);

        self::incGameStateValue('doMainAction', 1);

        $sql = "SELECT MIN(`worker_power`) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $this->gamestate->nextState("board_action");

        if ($card_id == 1) {
            throw new BgaUserException(self::_("This move is not possible."));
        } else if ($card_id == 2) {
            $this->perfomMainActionOnBoardForest($player_id, $countWorkers, $worker_power, $source);
        } else if ($card_id == 3) {
            $this->perfomMainActionOnBoardStoneQuarry($player_id, $countWorkers, $worker_power, $source);
        } else if ($card_id == 4) {
            $this->perfomMainActionOnBoardGoldDeposit($player_id, $countWorkers, $worker_power, $source);
        } else if ($card_id == 5) {
            $this->gamestate->nextState("alchemy");
        } else if ($card_id == 6) {
            $this->perfomMainActionOnBoardNobles($countWorkers);
        } else if ($card_id == 7) {
            $this->gamestate->nextState("decoration");
        } else if ($card_id == 8) {
            if ($this->isTechAquired(7)) {
                $countWorkers++;
                self::setGameStateValue('getTechnologyDiscount', 1);
            }
            self::setGameStateValue('canBuildPyramidTiles', $countWorkers);
            $this->gamestate->nextState("construction");
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function perfomMainActionOnBoardForest($player_id, $countWorkers, $worker_power, $source)
    {
        if ($countWorkers == 1) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'cocoa', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 1, 'wood', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'wood', $source);
            }
            $this->checkExtraResource($player_id, 'wood',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers == 2) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'wood', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 2, 'wood', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'cocoa', $source);
                $this->collectResource($player_id, 3, 'wood', $source);
            }
            $this->checkExtraResource($player_id, 'wood',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers >= 3) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'cocoa', $source);
                $this->collectResource($player_id, 2, 'wood', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 3, 'cocoa', $source);
                $this->collectResource($player_id, 3, 'wood', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 5, 'cocoa', $source);
                $this->collectResource($player_id, 4, 'wood', $source);
            }
            $this->checkExtraResource($player_id, 'wood',$source);
            self::incGameStateValue('upgradeWorkers', 2);
            $this->gamestate->nextState("upgrade_workers");
        }
    }

    function checkExtraResource($player_id, $token, $source)
    {
        if ($this->isTechAquired(2)) {
            $this->collectResource($player_id, 1, $token, $source, clienttranslate('${player_name} got ${amount}${token_wood}${token_stone}${token_gold} extra (technology tile 5)'));
        }
        if ($this->isTechAquired(3)) {
            $this->collectResource($player_id, 1, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 7)'));
            $this->collectResource($player_id, 1, 'cocoa', $source, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 7)'));
        }
    }

    function perfomMainActionOnBoardStoneQuarry($player_id, $countWorkers, $worker_power, $source)
    {
        if ($countWorkers == 1) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'vp', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 1, 'stone', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'stone', $source);
            }
            $this->checkExtraResource($player_id, 'stone',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers == 2) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'stone', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 2, 'stone', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'vp', $source);
                $this->collectResource($player_id, 3, 'stone', $source);
            }
            $this->checkExtraResource($player_id, 'stone',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers >= 3) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'vp', $source);
                $this->collectResource($player_id, 2, 'stone', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 3, 'vp', $source);
                $this->collectResource($player_id, 3, 'stone', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 5, 'vp', $source);
                $this->collectResource($player_id, 4, 'stone', $source);
            }
            $this->checkExtraResource($player_id, 'stone',$source);
            self::incGameStateValue('upgradeWorkers', 2);
            $this->gamestate->nextState("upgrade_workers");
        }
    }

    function perfomMainActionOnBoardGoldDeposit($player_id, $countWorkers, $worker_power, $source)
    {
        if ($countWorkers == 1) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'vp', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 1, 'gold', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'gold', $source);
            }
            $this->checkExtraResource($player_id, 'gold',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers == 2) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'gold', $source);
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 2, 'gold', $source);
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'vp', $source);
                $this->collectResource($player_id, 3, 'gold', $source);
            }
            $this->checkExtraResource($player_id, 'gold',$source);
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers >= 3) {
            if ($worker_power == 1) {
                $this->collectResource($player_id, 1, 'cocoa', $source);
                $this->collectResource($player_id, 2, 'gold', $source);
                $this->checkExtraResource($player_id, 'gold',$source);
                self::incGameStateValue('upgradeWorkers', 2);
                $this->gamestate->nextState("upgrade_workers");
            } else if ($worker_power == 2 || $worker_power == 3) {
                $this->collectResource($player_id, 1, 'temple_choose', $source);
                $this->collectResource($player_id, 3, 'gold', $source);
                $this->checkExtraResource($player_id, 'gold',$source);
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);
                self::incGameStateValue('upgradeWorkers', 2);
                $this->gamestate->nextState("action");
            } else if ($worker_power == 4 || $worker_power == 5) {
                $this->collectResource($player_id, 2, 'temple_choose', $source);
                $this->collectResource($player_id, 4, 'gold', $source);
                $this->checkExtraResource($player_id, 'gold',$source);
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);
                self::DbQuery($sql);
                self::incGameStateValue('upgradeWorkers', 2);
                $this->gamestate->nextState("action");
            }
        }
    }

    function perfomMainActionOnBoardNobles($countWorkers)
    {
        $row1 = (int)self::getUniqueValueFromDB("SELECT `row0` FROM `nobles`");
        $row2 = (int)self::getUniqueValueFromDB("SELECT `row1` FROM `nobles`");
        $row3 = (int)self::getUniqueValueFromDB("SELECT `row2` FROM `nobles`");

        $player_id = self::getActivePlayerId();

        if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (51) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
            $countWorkers++;
        }
        if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (52) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
            $countWorkers++;
        }
        if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (53) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
            $countWorkers++;
        }

        if ($countWorkers == 1 && $row1 >= 5) {
            throw new BgaUserException(self::_("There is no space left"));
        } else if ($countWorkers == 2 && $row1 >= 5 && $row2 >= 4) {
            throw new BgaUserException(self::_("There is no space left"));
        } else if ($countWorkers == 3 && $row1 >= 5 && $row2 >= 4 && $row3 >= 3) {
            throw new BgaUserException(self::_("There is no space left"));
        }
        $this->gamestate->nextState("nobles");
    }

    function collectResource($player_id, $amount, $token, $source, $customMessage = '')
    {
        $token_cocoa = '';
        $token_wood = '';
        $token_stone = '';
        $token_gold = '';
        $token_vp = '';
        $token_temple_choose = '';
        $token_temple_blue = '';
        $token_temple_red = '';
        $token_temple_green = '';
        if ($token == 'cocoa') {
            $this->updateCocoa($amount, true, $player_id);
            $token_cocoa = $token;
        } else if ($token == 'wood') {
            $token_wood = $token;
            $this->updateWood($amount, true, $player_id);
        } else if ($token == 'stone') {
            $token_stone = $token;
            $this->updateStone($amount, true, $player_id);
        } else if ($token == 'gold') {
            $token_gold = $token;
            $this->updateGold($amount, true, $player_id);
        } else if ($token == 'vp') {
            $token_vp = $token;
            $this->updateVP($amount, $player_id);
        } else if ($token == 'temple_choose') {
            $token_temple_choose = $token;
        } else if ($token == 'temple_blue') {
            $token_temple_blue = $token;
        } else if ($token == 'temple_red') {
            $token_temple_red = $token;
        } else if ($token == 'temple_green') {
            $token_temple_green = $token;
        }

        $message = clienttranslate('${player_name} got ${amount}${token_cocoa}${token_wood}${token_stone}${token_gold}${token_vp}${token_temple_choose}${token_temple_blue}${token_temple_red}${token_temple_green}');

        if ($customMessage == ' ') {
            $message = '';
        } else if ($customMessage != '') {
            $message = $customMessage;
        }

        $player_name = self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE player_id = $player_id");
        if ($amount > 0) {
            self::notifyAllPlayers("collectResource", $message, array(
                'player_id' => $player_id,
                'player_name' => $player_name,
                'amount' => $amount,
                'token' => $token,
                'token_cocoa' => $token_cocoa,
                'token_wood' => $token_wood,
                'token_stone' => $token_stone,
                'token_gold' => $token_gold,
                'token_vp' => $token_vp,
                'token_temple_choose' => $token_temple_choose,
                'token_temple_blue' => $token_temple_blue,
                'token_temple_red' => $token_temple_red,
                'token_temple_green' => $token_temple_green,
                'source' => $source
            ));
        }
    }

    function payResource($player_id, $amount, $token, $target, $customMessage = '')
    {
        $token_cocoa = '';
        $token_wood = '';
        $token_stone = '';
        $token_gold = '';
        $token_vp = '';
        $token_temple_choose = '';
        if ($token == 'cocoa') {
            $this->updateCocoa($amount, true, $player_id);
            $token_cocoa = $token;
        } else if ($token == 'wood') {
            $token_wood = $token;
            $this->updateWood($amount, true, $player_id);
        } else if ($token == 'stone') {
            $token_stone = $token;
            $this->updateStone($amount, true, $player_id);
        } else if ($token == 'gold') {
            $token_gold = $token;
            $this->updateGold($amount, true, $player_id);
        } else if ($token == 'vp') {
            $token_vp = $token;

            $score = (int)self::getUniqueValueFromDB("SELECT `player_score` FROM `player` WHERE `player_id` = $player_id");
            if (($score + $amount) < 0) {
                $amount = -$score;
            }
            $this->updateVP($amount, $player_id);
        } else if ($token == 'temple_choose') {
            $token_temple_choose = $token;
        }

        $message = clienttranslate('${player_name} pays ${amount}${token_cocoa}${token_wood}${token_stone}${token_gold}${token_vp}${token_temple_choose}');

        if ($customMessage != '') {
            $message = $customMessage;
        }

        $player_name = self::getUniqueValueFromDB("SELECT `player_name` FROM `player` WHERE player_id = $player_id");

        if ($amount < 0) {
            $amount = -$amount;
            self::notifyAllPlayers("payResource", $message, array(
                'player_id' => $player_id,
                'player_name' => $player_name,
                'amount' => $amount,
                'token' => $token,
                'token_cocoa' => $token_cocoa,
                'token_wood' => $token_wood,
                'token_stone' => $token_stone,
                'token_gold' => $token_gold,
                'token_vp' => $token_vp,
                'token_temple_choose' => $token_temple_choose,
                'target' => $target
            ));
        }
    }

    function doWorshipOnBoard($worship_pos, $pay, $freeCocoa = false)
    {
        self::checkAction('doWorshipOnBoard');

        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_board_id_from = self::getGameStateValue('selected_board_id_from');

        $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
        $id = (int)self::getUniqueValueFromDB($sql);
        if ($freeCocoa && !$id) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        if ($selected_board_id_to <= $selected_board_id_from && $this->isTechAquired(0) && !self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
            $actionBoard_1 = 'actionBoard_1';
            $this->collectResource($player_id, 1, 'cocoa', $actionBoard_1, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 1)'));
        }

        if ($pay || $freeCocoa) {
            if (!$freeCocoa) {
                $this->updateCocoa(-1);
            }

            $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

            $sql = "SELECT `player_id`, `worker_id` FROM `map` WHERE `actionboard_id` = $selected_board_id_to AND locked = true AND worship_pos = $worship_pos";
            $otherPlayer = self::getObjectFromDB($sql);

            $sql = "UPDATE `map` SET
                 locked  = false,
                 worship_pos  = 0
                WHERE player_id = " . $otherPlayer['player_id'] . " AND `worker_id`= " . $otherPlayer['worker_id'];
            self::DbQuery($sql);

            self::notifyAllPlayers("unlockSingleWorker", clienttranslate('${player_name} unlocked Worship'), array(
                'player_id' => $otherPlayer['player_id'],
                'worker_id' => $otherPlayer['worker_id'],
                'player_name' => self::getActivePlayerName(),
                'worship_pos' => $worship_pos,
            ));
        }

        $this->moveWorkerToBoard($worship_pos);

        $this->gamestate->nextState("choose_worship");

        if ($freeCocoa) {
            $this->worshipAction(true, true, true);
        }
    }

    function updateCocoa($amount, $updateDB = true, $player_id = null)
    {
        if ($player_id == null) {
            $player_id = self::getActivePlayerId();
        }

        if ($amount < 0) {
            $amount = -$amount;
            $sql = "SELECT `cocoa` FROM `player` WHERE `player_id` = $player_id";
            $player = self::getObjectFromDB($sql);
            $cocoa = (int)$player['cocoa'];

            if ($cocoa < $amount) {
                throw new BgaUserException(self::_("You do not have enough cocoa."));
            }
            $sql = "UPDATE `player` SET cocoa  = cocoa - $amount WHERE player_id = $player_id";
        } else {
            $sql = "UPDATE `player` SET cocoa  = cocoa + $amount WHERE player_id = $player_id";
        }
        if ($updateDB) {
            self::DbQuery($sql);
        }
    }

    function updateWood($amount, $updateDB = true, $player_id = null, $message = '')
    {
        if ($player_id == null) {
            $player_id = self::getActivePlayerId();
        }

        if ($amount < 0) {
            $amount = -$amount;
            $sql = "SELECT `wood` FROM `player` WHERE `player_id` = $player_id";
            $player = self::getObjectFromDB($sql);
            $wood = (int)$player['wood'];

            if ($wood < $amount) {
                if ($message == '') {
                    throw new BgaUserException(self::_("You do not have enough wood."));
                } else {
                    throw new BgaUserException($message);
                }
            }
            $sql = "UPDATE `player` SET wood  = wood - $amount WHERE player_id = $player_id";
        } else {
            $sql = "UPDATE `player` SET wood  = wood + $amount WHERE player_id = $player_id";
        }
        if ($updateDB) {
            self::DbQuery($sql);
        }
    }

    function updateStone($amount, $updateDB = true, $player_id = null, $message = '')
    {
        if ($player_id == null) {
            $player_id = self::getActivePlayerId();
        }

        if ($amount < 0) {
            $amount = -$amount;
            $sql = "SELECT `stone` FROM `player` WHERE `player_id` = $player_id";
            $player = self::getObjectFromDB($sql);
            $stone = (int)$player['stone'];

            if ($stone < $amount) {
                if ($message == '') {
                    throw new BgaUserException(self::_("You do not have enough stone."));
                } else {
                    throw new BgaUserException($message);
                }
            }
            $sql = "UPDATE `player` SET stone  = stone - $amount WHERE player_id = $player_id";
        } else {
            $sql = "UPDATE `player` SET stone  = stone + $amount WHERE player_id = $player_id";
        }
        if ($updateDB) {
            self::DbQuery($sql);
        }
    }

    function updateGold($amount, $updateDB = true, $player_id = null, $message = '')
    {
        if ($player_id == null) {
            $player_id = self::getActivePlayerId();
        }

        if ($amount < 0) {
            $amount = -$amount;
            $sql = "SELECT `gold` FROM `player` WHERE `player_id` = $player_id";
            $player = self::getObjectFromDB($sql);
            $gold = (int)$player['gold'];

            if ($gold < $amount) {
                if ($message == '') {
                    throw new BgaUserException(self::_("You do not have enough gold."));
                } else {
                    throw new BgaUserException($message);
                }
            }
            $sql = "UPDATE `player` SET gold  = gold - $amount WHERE player_id = $player_id";
        } else {
            $sql = "UPDATE `player` SET gold  = gold + $amount WHERE player_id = $player_id";
        }
        if ($updateDB) {
            self::DbQuery($sql);
        }
    }

    function updateVP($amount, $player_id = null)
    {
        if ($player_id == null) {
            $player_id = self::getActivePlayerId();
        }

        if ($amount > 0) {
            $sql = "UPDATE `player`
                SET player_score  = player_score + $amount
                WHERE player_id = $player_id";
            self::DbQuery($sql);

            $score = (int)self::getUniqueValueFromDB("SELECT `player_score` FROM `player` WHERE `player_id` = $player_id");
            if ($score < 0) {
                self::DbQuery("UPDATE `player` SET player_score  = 0 WHERE player_id = $player_id");
            }
        } else {
            $amount = -$amount;
            $sql = "UPDATE `player` SET player_score  = player_score - $amount WHERE player_id = $player_id";
            self::DbQuery($sql);
        }
    }

    function unlockAllWorkers($pay, $freeCocoa)
    {
        self::checkAction('unlockAllWorkers');
        $player_id = self::getActivePlayerId();

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = true AND `worship_pos` > 0";
        $count = (int)self::getUniqueValueFromDB($sql);

        if ($count == 0) {
            throw new BgaUserException(self::_("You have no locked workers"));
        }

        $sql = "UPDATE `map` SET
         locked  = false,
         worship_pos  = 0
        WHERE player_id = $player_id AND actionboard_id > 0";
        self::DbQuery($sql);

        if ($pay) {
            if (!$freeCocoa) {

                $target = 'player_table_' . $player_id;
                $this->payResource($player_id, -3, 'cocoa', $target);

                $this->gamestate->nextState("playerTurn");
                self::notifyAllPlayers("unlockAllWorkers", clienttranslate('${player_name} unlocked all Workers and pay 3${token_cocoa} to do a normal turn'), array(
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'token_cocoa' => 'cocoa',
                    'pay' => $pay,
                ));
            } else {
                $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
                $id = (int)self::getUniqueValueFromDB($sql);
                if (!$id) {
                    throw new BgaUserException(self::_("This move is not possible."));
                }

                $this->useDiscoveryTile($id, true);

                self::notifyAllPlayers("unlockAllWorkers", clienttranslate('${player_name} unlocked all Workers and do a normal turn'), array(
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'token_cocoa' => 'cocoa',
                    'pay' => $pay,
                ));
                $this->gamestate->nextState("playerTurn");
            }


        } else {
            self::notifyAllPlayers("unlockAllWorkers", clienttranslate('${player_name} unlocked all Workers'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'pay' => $pay,
            ));
            $this->gamestate->nextState("check_end_turn");
        }

    }

    function collectCocoaOnBoard()
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction('collectCocoa');

        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_board_id_from = self::getGameStateValue('selected_board_id_from');

        if ($selected_board_id_to <= $selected_board_id_from && $this->isTechAquired(0) && !self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
            $actionBoard_1 = 'actionBoard_1';

            $selected_worker2_id = self::getGameStateValue('selected_worker2_id');

            if (self::getGameStateValue('useDiscoveryMoveTwoWorkers') && $selected_worker2_id != 0) {
                $this->collectResource($player_id, 2, 'cocoa', $actionBoard_1, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 1)'));
            } else {
                $this->collectResource($player_id, 1, 'cocoa', $actionBoard_1, clienttranslate('${player_name} got ${amount}${token_cocoa} extra (technology tile 1)'));
            }
        }

        $colors = $this->getDiffrentColorsOnBoard() + 1;

        $this->updateCocoa($colors);

        $this->moveWorkerToBoard(0);

        $board_id = self::getGameStateValue('selected_board_id_to');
        $worker_id = self::getGameStateValue('selected_worker_id');

        $source = $player_id . '_worker_' . $worker_id;
        self::notifyAllPlayers("collectResource", "", array(
            'player_id' => $player_id,
            'amount' => $colors,
            'token' => 'cocoa',
            'source' => $source
        ));

        self::notifyAllPlayers("collectCocoa", clienttranslate('${player_name} collects ${cocoa}${token_cocoa}'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'cocoa' => $colors,
            'token_cocoa' => 'cocoa',
        ));

        $this->gamestate->nextState("check_end_turn");
    }

    function getDiffrentColorsOnBoard()
    {
        $player_id = self::getActivePlayerId();
        $board_id = self::getGameStateValue('selected_board_id_to');
        $selected_worker_id = (int)self::getGameStateValue('selected_worker_id');

        if ($this->isTechAquired(0) && self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
            $sql = "SELECT COUNT(DISTINCT `player_id`) AS Count FROM `map` WHERE `actionboard_id` = $board_id and locked = false and not (`worker_id` = $selected_worker_id and `player_id` = $player_id)";
        } else {

            $sql = "SELECT COUNT(DISTINCT `player_id`) AS Count
                FROM `map` 
                WHERE `actionboard_id` = $board_id and locked = false
            ";
        }

        return (int)self::getUniqueValueFromDB($sql);
    }

    function moveWorkerToBoard($worship_pos, $worker_id = null, $board_id_from = null, $board_id_to = null)
    {
        $player_id = self::getActivePlayerId();

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $selected_worker_id = self::getGameStateValue('selected_worker_id');
        $selected_worker2_id = self::getGameStateValue('selected_worker2_id');
        $selected_board_id_from = self::getGameStateValue('selected_board_id_from');
        $useDiscoveryMoveTwoWorkers = self::getGameStateValue('useDiscoveryMoveTwoWorkers');

        if ($worker_id != null) {
            $selected_worker_id = $worker_id;
        }
        if ($board_id_to != null) {
            $selected_board_id_to = $board_id_to;
        }
        if ($board_id_from != null) {
            $selected_board_id_from = $board_id_from;
        }

        $worker_power = (int)self::getUniqueValueFromDB("SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id`=$selected_worker_id");

        $sql = "UPDATE `map` SET actionboard_id  = $selected_board_id_to WHERE player_id = $player_id AND worker_id = $selected_worker_id";
        self::DbQuery($sql);

        if (self::getGameStateValue('useDiscoveryMoveTwoWorkers')) {
            $sql = "UPDATE `map` SET actionboard_id  = $selected_board_id_to WHERE player_id = $player_id AND worker_id = $selected_worker2_id";
            self::DbQuery($sql);
        }

        if ($worship_pos > 0) {
            $sql = "UPDATE `map` SET
             locked  = true,
             worship_pos  = $worship_pos
            WHERE player_id = $player_id
            AND worker_id = $selected_worker_id
            ";
            self::DbQuery($sql);
        }

        $board_name_from = "Ascension";

        if ($selected_board_id_from != -1) {
            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_from";
            $card = self::getObjectFromDB($sql);

            $board_name_from = $this->actionBoards[$card["card_id"]]["name"];
        }

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to";
        $card = self::getObjectFromDB($sql);

        $board_name_to = $this->actionBoards[$card["card_id"]]["name"];

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $workersOnBoard = self::getObjectListFromDB($sql, true);
        $workersOnBoard = implode(',', $workersOnBoard);


        $card_id_to = (int)self::getUniqueValueFromDB($sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to");
        $card_id_from = (int)self::getUniqueValueFromDB($sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_from");

        $message = clienttranslate('${player_name} moved worker ${worker_power}${worker_power2} from ${board_name_from} (${card_id_from}) to ${board_name_to} (${card_id_to}) with workers (${workersOnBoard})');

        if($worker_power == 6){
            $message = clienttranslate('${player_name} moved worker ${worker_power} from ${board_name_from} to ${board_name_to} as a new worker 1');
        }

        $worker_power2 = '';
        if($selected_worker2_id){
            $worker_power2 = ',' . (int)self::getUniqueValueFromDB("SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id`=$selected_worker2_id");
        }

        self::notifyAllPlayers("moveWokerToBoard", $message, array(
            'i18n' => array('board_name_from'),
            'i18n' => array('board_name_to'),
            'board_name_from' => $board_name_from,
            'board_name_to' => $board_name_to,
            'card_id_to' => $card_id_to,
            'card_id_from' => $card_id_from,
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'selected_board_id_to' => $selected_board_id_to,
            'selected_board_id_from' => $selected_board_id_from,
            'worship_pos' => $worship_pos,
            'selected_worker_id' => $selected_worker_id,
            'selected_worker2_id' => $selected_worker2_id,
            'worker_power' => $worker_power,
            'worker_power2' => $worker_power2,
            'workersOnBoard' => $workersOnBoard,
            'moveTwoWorkers' => $useDiscoveryMoveTwoWorkers
        ));

        self::setGameStateValue('selected_worker2_id', 0);
        self::setGameStateValue('useDiscoveryMoveWorkerAnywhere', 0);
    }

    function worshipAction($worship, $discovery, $freeCocoa = false)
    {
        self::checkAction('worshipAction');

        $player_id = self::getActivePlayerId();

        $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
        $id = (int)self::getUniqueValueFromDB($sql);
        if ($freeCocoa && !$id) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $board_id = (int)self::getUniqueValueFromDB("SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to");

        if ($board_id == 2) {
            $temple = 'red';
        } else if ($board_id == 3) {
            $temple = 'green';
        } else if ($board_id == 4) {
            $temple = 'blue';
        }
        if ($worship && isset($temple)) {
            $sql = "SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);

            $sql = "SELECT count(*) FROM `player` WHERE `temple_$temple` >= 11";
            $used = (int)self::getUniqueValueFromDB($sql);

            if (($step >= 11 || $step == 10 && $used)) {
                throw new BgaUserException(self::_("You are already on top of the temple"));
            }
        }

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to";
        $board_id = (int)self::getUniqueValueFromDB($sql);
        if ($worship) {
            if ($board_id == 1) {
                self::setGameStateValue('royalTileAction', 1);
            } else if ($board_id == 2) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_red',1)";
                self::DbQuery($sql);
            } else if ($board_id == 3) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_green',1)";
                self::DbQuery($sql);
            } else if ($board_id == 4) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_blue',1)";
                self::DbQuery($sql);
            } else if ($board_id == 7) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);
            } else {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }

        self::setGameStateValue('worship_actions_discovery', (int)$discovery);

        if ($worship && $discovery) {

            if ($freeCocoa) {
                self::notifyAllPlayers("payCocoa", clienttranslate('${player_name} uses ${token_cocoa_free} for both worship actions'), array(
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'token_cocoa_free' => 'cocoa_free',
                    'amount' => 1,
                ));
                $this->useDiscoveryTile($id, true);
            } else {
                $this->updateCocoa(-1);
                self::notifyAllPlayers("payCocoa", clienttranslate('${player_name} pay 1${token_cocoa} for both worship actions'), array(
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'token_cocoa' => 'cocoa',
                    'amount' => 1,
                ));
            }
        }

        $this->gamestate->nextState("action");
    }

    function royalTileAction()
    {
        self::checkAction('royalTileAction');

        if (!self::getGameStateValue('royalTileAction')) {
            throw new BgaUserException(self::_("This move is not possible."));
        }
        self::setGameStateValue('royalTileAction', 0);

        $player_id = self::getActivePlayerId();
        $selected_worker_id = self::getGameStateValue('selected_worker_id');
        $source = $player_id . '_worker_' . $selected_worker_id;

        $sql = "SELECT `worship_pos` FROM `map` WHERE player_id = $player_id AND worker_id = $selected_worker_id";
        $worship_pos = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $selected_worker_id";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        $royalTilesPos = $worship_pos - 1;
        $royalTile = $this->cards->getCardsInLocation('royalTiles' . $royalTilesPos);
        $royalTile = array_shift($royalTile);
        $royalTiles_details = $this->royalTiles[$royalTile['type_arg']]['bonus'];

        $cocoa_locked = $royalTiles_details['cocoa_locked'];
        $trade_c_ws = $royalTiles_details['trade_c_ws'];
        $trade_r_2c = $royalTiles_details['trade_r_2c'];
        $vp_technology = $royalTiles_details['vp_technology'];
        $vp_pyramid = $royalTiles_details['vp_pyramid'];
        $trade_c_sg = $royalTiles_details['trade_c_sg'];
        $trade_cr_r = $royalTiles_details['trade_cr_r'];
        $trade_c_t = $royalTiles_details['trade_c_t'];
        $vp_ad = $royalTiles_details['vp_ad'];

        $amount = 0;

        $message = '${player_name} ${fulfilled_text}';// NOI18N
        $messageParts = array();
        $sendNotification = false;

        if ($cocoa_locked > 0) {
            $messageParts[] = ' ${amount}${token_cocoa}';// NOI18N
            $amount = $worker_power + 1;
            $this->updateCocoa($amount);
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $amount,
                'token' => 'cocoa',
                'source' => $source
            ));
            $sendNotification = true;
        } else if ($vp_pyramid > 0) {
            $messageParts[] = ' ${amount}${token_vp}';// NOI18N
            $sql = "SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);
            $amount = min($step, $worker_power) * 2;
            $this->updateVP($amount);
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $amount,
                'token' => 'vp',
                'source' => $source
            ));
            $sendNotification = true;
        } else if ($vp_ad > 0) {
            $messageParts[] = ' ${amount}${token_vp}';// NOI18N
            $sql = "SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);
            $amount = min($step, $worker_power + 1);
            $this->updateVP($amount);
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $amount,
                'token' => 'vp',
                'source' => $source
            ));
            $sendNotification = true;
        } else if ($vp_technology > 0) {
            $messageParts[] = ' ${amount}${token_vp}';// NOI18N
            $sum = 0;
            for ($i = 1; $i <= 2; $i++) {
                for ($j = 1; $j <= 3; $j++) {
                    $sql = "SELECT `techTiles_r" . $i . "_c" . $j . "` FROM `player` WHERE `player_id` = $player_id";
                    $archived = (int)self::getUniqueValueFromDB($sql);
                    $sum += $archived;
                }
            }
            $amount = min($sum, $worker_power) * 2;
            $this->updateVP($amount);
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $amount,
                'token' => 'vp',
                'source' => $source
            ));
            $sendNotification = true;
        } else if ($trade_c_ws > 0) {
            self::setGameStateValue('royalTileTradeId', 0);
            $this->gamestate->nextState("trade");
        } else if ($trade_r_2c > 0) {
            self::setGameStateValue('royalTileTradeId', 1);
            $this->gamestate->nextState("trade");
        } else if ($trade_c_sg > 0) {
            self::setGameStateValue('royalTileTradeId', 2);
            $this->gamestate->nextState("trade");
        } else if ($trade_cr_r > 0) {
            self::setGameStateValue('royalTileTradeId', 3);
            $this->gamestate->nextState("trade");
        } else if ($trade_c_t > 0) {
            self::setGameStateValue('royalTileTradeId', 4);
            $this->gamestate->nextState("trade");
        }

        if ($sendNotification) {
            self::notifyAllPlayers("usedRoyalTile", $message . implode(",", $messageParts), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'amount' => $amount,
                'token_vp' => 'vp',
                'token_r' => 'resource',
                'token_cocoa' => 'cocoa',
                'token_temple_choose' => 'temple_choose',
                'token_temple_blue' => 'temple_blue',
                'token_temple_red' => 'temple_blue',
                'token_temple_green' => 'temple_blue',
                'token_upgrade' => 'upgrade',
                'token_cocoa_free' => 'cocoa_free',
                'token_ad' => 'ad',
                'fulfilled_text' => clienttranslate('used a royal tile and got:'),
                'i18n' => array('fulfilled_text')
            ));

            $worship_actions_discovery = (int)self::getGameStateValue('worship_actions_discovery');

            if ($worship_actions_discovery > 0) {
                $this->gamestate->nextState("action");
            } else {
                $this->gamestate->nextState("check_end_turn");
            }
        }
    }

    function stepTemple($temple)
    {
        self::checkAction('stepTemple');

        $player_id = self::getActivePlayerId();
        $templeSteps = (int)self::getGameStateValue('ascensionTempleSteps');

        $sql = "SELECT count(*) FROM `temple_queue`";
        $queueCount = (int)self::getUniqueValueFromDB($sql);

        if ($queueCount <= 0) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $sql = "SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT count(*) FROM `player` WHERE `temple_$temple` >= 11";
        $used = (int)self::getUniqueValueFromDB($sql);

        // all checks done
        $sql = "SELECT `referrer` FROM `temple_queue` ORDER BY id DESC LIMIT 1";
        $referrer = self::getUniqueValueFromDB($sql);

        $sql = "SELECT * FROM `temple_queue` ORDER BY id DESC LIMIT 1";
        $temple_queue = self::getObjectFromDB($sql);

        if (self::getGameStateValue('ascensionTempleSteps')) {
            self::incGameStateValue('ascensionTempleSteps', -1);
        }

        if (substr($temple_queue['queue'], 0, 4) === "deco") {
            $temple1 = explode("_", $temple_queue['queue'])[1];
            $temple2 = explode("_", $temple_queue['queue'])[2];
            $id = $temple_queue['id'];

            if ($temple1 === $temple) {
                self::DbQuery("UPDATE `temple_queue` SET `queue`='temple_$temple2' WHERE id = $id");
            } else {
                self::DbQuery("UPDATE `temple_queue` SET `queue`='temple_$temple1' WHERE id = $id");
            }
        } else {
            $sql = "DELETE FROM `temple_queue` ORDER BY id DESC limit 1";
            self::DbQuery($sql);
        }

        if (!($step >= 11 || $step == 10 && $used)) {
            if ($temple == 'blue') {
                self::setGameStateValue('last_temple_id', 1);
            } else if ($temple == 'red') {
                self::setGameStateValue('last_temple_id', 2);
            } else {
                self::setGameStateValue('last_temple_id', 3);
            }

            $sql = "UPDATE `player` SET `temple_$temple`  = `temple_$temple` + 1 WHERE player_id = $player_id";
            self::DbQuery($sql);

            self::incStat(1, "temple_$temple", $player_id);

            $sql = "SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);

            $bonus = explode(":", $this->temples[$temple][$step]);

            self::setGameStateValue('temple_bonus_resource', 0);
            self::setGameStateValue('temple_bonus_vp', 0);
            self::setGameStateValue('temple_bonus_cocoa', 0);

            if ($temple == 'blue' && $step == 4 ||
                $temple == 'red' && $step == 5 ||
                $temple == 'green' && $step == 3 ||
                $temple == 'green' && $step == 6) {

                self::notifyAllPlayers("stepTemple", clienttranslate('${player_name} advanced one space on temple ${temple}'), array(
                    'i18n' => array('temple'),
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'temple' => $temple,
                    'step' => $step,
                    'bonus' => $bonus,
                    'last_temple_id' => self::getGameStateValue('last_temple_id'),
                ));

                if ($temple == 'blue') {
                    self::setGameStateValue('temple_bonus_resource', (int)$bonus[0]);
                } else if ($temple == 'red') {
                    self::setGameStateValue('temple_bonus_vp', (int)$bonus[0]);
                } else {
                    self::setGameStateValue('temple_bonus_cocoa', (int)$bonus[0]);
                }

                $this->gamestate->nextState("choose_bonus");

                $discTiles_tb0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_tb0'");
                $discTiles_tr0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_tr0'");
                $discTiles_tg0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_tg0'");
                $discTiles_tg1 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_tg1'");

                if ($temple == 'blue' && $step == 4 && $discTiles_tb0 == 0) {
                    $this->temple_bonus();
                } else if ($temple == 'red' && $step == 5 && $discTiles_tr0 == 0) {
                    $this->temple_bonus();
                } else if ($temple == 'green' && $step == 3 && $discTiles_tg0 == 0) {
                    $this->temple_bonus();
                } else if ($temple == 'green' && $step == 6 && $discTiles_tg1 == 0) {
                    $this->temple_bonus();
                }
            } else {
                $source = "temple_" . $temple . "_step_" . $step;
                if ($bonus[1] == 'vp') {
                    $this->collectResource($player_id, $bonus[0], 'vp', $source);
                } else if ($bonus[1] == 'c') {
                    $this->collectResource($player_id, $bonus[0], 'cocoa', $source);
                }

                self::notifyAllPlayers("stepTemple", clienttranslate('${player_name} advanced one space on temple ${temple}'), array(
                    'i18n' => array('temple'),
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'temple' => $temple,
                    'step' => $step,
                    'bonus' => $bonus,
                ));

                if ($bonus[1] == 'r') {
                    self::setGameStateValue('choose_resources_max', (int)$bonus[0]);
                    $this->gamestate->nextState("choose_resources");
                } else {
                    $this->goToNextState();
                }
            }
        } else {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} stays on top of temple ${temple} and gain no further benefit'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'temple' => $temple,
            ));
            $this->goToNextState();
        }
    }

    function pass($notification = true)
    {
        self::checkAction('pass');

        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        if ($notification) {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} passed'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
            ));
        }

        if ($this->gamestate->state()['name'] == 'claim_starting_discovery_tiles') {
            $startingDiscovery0 = self::getUniqueValueFromDB("SELECT `startingDiscovery0` FROM `player` WHERE `player_id` = $player_id");
            $startingDiscovery1 = self::getUniqueValueFromDB("SELECT `startingDiscovery1` FROM `player` WHERE `player_id` = $player_id");
            self::DbQuery("UPDATE `player` SET startingDiscovery0 = NULL WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingDiscovery1 = NULL WHERE player_id = $player_id");

            $this->notifyAllPlayers('removeLeftDiscoveryTiles', '', Array(
                'startingDiscovery0' => $startingDiscovery0,
                'startingDiscovery1' => $startingDiscovery1,
            ));

            self::setGameStateValue('startingTileBonus', 6);

            $this->gamestate->nextState("calculate_next_bonus");
        } else if ($this->gamestate->state()['name'] == 'playerTurn_upgrade_workers_buy') {
            if (self::getGameStateValue('ascension')) {
                $this->gamestate->nextState("avenue_of_dead");
            } else if ($discoveryQueueCount > 0) {
                $this->goToPreviousState();
            } else {
                $this->gamestate->nextState("check_end_turn");
            }
        } else if (self::getGameStateValue('isNobles')) {
            self::setGameStateValue('isNobles', 0);
            $extraWorker = (int)self::getGameStateValue('extraWorker');
            $countWorkers += $extraWorker;
            $this->boardgetUpgrades($countWorkers);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_construction') {
            self::setGameStateValue('isConstruction', 0);
            self::setGameStateValue('canBuildPyramidTiles', 0);

            if ($this->isTechAquired(7)) {
                $countWorkers++;
            }

            $extraWorker = (int)self::getGameStateValue('extraWorker');
            $countWorkers += $extraWorker;

            $source = 'actionBoard_5';
            if ($this->isTechAquired(5)) {
                $this->collectResource($player_id, 3, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 11)'));
            }
            if ($this->isTechAquired(8)) {
                $this->collectResource($player_id, 1, 'temple_choose', $source, clienttranslate('${player_name} got ${amount}${token_temple_choose} extra (technology tile 17)'));
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);

                if ($countWorkers == 1) {
                    self::incGameStateValue('upgradeWorkers', 1);
                } else if ($countWorkers == 2) {
                    self::incGameStateValue('upgradeWorkers', 1);
                } else if ($countWorkers >= 3) {
                    self::incGameStateValue('upgradeWorkers', 2);
                }

                $this->gamestate->nextState("action");
            } else {
                $this->boardgetUpgrades($countWorkers);
            }
        } else if ($this->gamestate->state()['name'] == 'playerTurn_choose_worship_actions') {
            $this->gamestate->nextState("pass");
        } else if ($this->gamestate->state()['name'] == 'playerTurn_nobles' || $this->gamestate->state()['name'] == 'playerTurn_alchemy') {
            $this->gamestate->nextState("check_end_turn");
        } else if (self::getGameStateValue('ascension')) {
            $this->gamestate->nextState("ascension");
        } else if (self::getGameStateValue('upgradeWorkers')) {
            $this->gamestate->nextState("upgrade_workers");
        } else if ($discoveryQueueCount > 0) {
            $this->goToPreviousState();
        } else {
            $enableUndo = (int)self::getUniqueValueFromDB("SELECT `enableUndo` FROM `player` WHERE `player_id` = $player_id");

            if($enableUndo > 0){
                $this->gamestate->nextState("undo");
            } else {
                $this->gamestate->nextState("pass");
            }
        }
    }

    function boardgetUpgrades($countWorkers)
    {
        if ($countWorkers == 1) {
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers == 2) {
            self::incGameStateValue('upgradeWorkers', 1);
            $this->gamestate->nextState("upgrade_workers");
        } else if ($countWorkers >= 3) {
            self::incGameStateValue('upgradeWorkers', 2);
            $this->gamestate->nextState("upgrade_workers");
        }
    }

    function temple_bonus()
    {
        self::checkAction('temple_bonus');

        $player_id = self::getActivePlayerId();
        $bonus = array();


        if (self::getGameStateValue('temple_bonus_cocoa') > 0) {
            $bonus = self::getGameStateValue('temple_bonus_cocoa');
            $temple = 'green';

            $step = (int)self::getUniqueValueFromDB("SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id");

            $source = "temple_" . $temple . "_step_" . $step;
            $this->collectResource($player_id, $bonus, 'cocoa', $source);
        } else if (self::getGameStateValue('temple_bonus_vp') > 0) {
            $bonus = self::getGameStateValue('temple_bonus_vp');
            $temple = 'red';

            $step = (int)self::getUniqueValueFromDB("SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id");

            $source = "temple_" . $temple . "_step_" . $step;
            $this->collectResource($player_id, $bonus, 'vp', $source);
        } else if (self::getGameStateValue('temple_bonus_resource') > 0) {
            $bonus = self::getGameStateValue('temple_bonus_resource');
            self::setGameStateValue('choose_resources_max', $bonus);
            $temple = 'blue';
            $this->gamestate->nextState("choose_resources");
        } else {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        self::setGameStateValue('temple_bonus_resource', 0);
        self::setGameStateValue('temple_bonus_vp', 0);
        self::setGameStateValue('temple_bonus_cocoa', 0);
        self::setGameStateValue('last_temple_id', 0);

        if ($temple != 'blue') {
            $this->goToNextState();
        }
    }

    function choosed_resource($wood, $stone, $gold)
    {
        self::checkAction('choose_resource');

        $player_id = self::getActivePlayerId();

        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        $amount = $wood + $stone + $gold;
        $max = self::getGameStateValue('choose_resources_max');

        if ($amount > $max) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $sql = "SELECT `temple_blue` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        if ($discoveryQueueCount > 0) {
            $id = self::getGameStateValue('useDiscoveryId');
            if ($id > 100) {
                $id = $id - 100;
                $source = 'startingTile_' . $id;
                $this->collectResource($player_id, $wood, 'wood', $source, clienttranslate('${player_name} choosed ${amount}${token_wood} as starting resource(s)'));
                $this->collectResource($player_id, $stone, 'stone', $source, clienttranslate('${player_name} choosed ${amount}${token_stone} as starting resource(s)'));
                $this->collectResource($player_id, $gold, 'gold', $source, clienttranslate('${player_name} choosed ${amount}${token_gold} as starting resource(s)'));
            } else {
                $source = 'discoveryTile_' . $id;
                $this->collectResource($player_id, $wood, 'wood', $source);
                $this->collectResource($player_id, $stone, 'stone', $source);
                $this->collectResource($player_id, $gold, 'gold', $source);
            }
        } else {
            $source = 'temple_blue_step_' . $step;
            $this->collectResource($player_id, $wood, 'wood', $source);
            $this->collectResource($player_id, $stone, 'stone', $source);
            $this->collectResource($player_id, $gold, 'gold', $source);
        }

        $this->goToNextState();
    }

    function setPreviousState()
    {
        $name = '';
        if ($this->gamestate->state()['name'] == 'playerTurn') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_show_board_actions') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_board_action') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_BOARD_ACTION);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_choose_worship_actions') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_worship_actions') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_WORSHIP_ACTIONS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_choose_temple_bonus') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS);
            $name = self::getGameStateValue('last_temple_id');
        } else if ($this->gamestate->state()['name'] == 'playerTurn_check_pass') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_PASS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_worship_actions_trade') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_WORSHIP_TRADE);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_alchemy') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_ALCHEMY);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_upgrade_workers_buy') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY);
        } else if ($this->gamestate->state()['name'] == 'claim_starting_discovery_tiles') {
            self::setGameStateValue('previous_game_state', STATE_CLAIM_STARTING_DISCOVERY_TILES);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_construction') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_CONSTRUCTION);
        } else if ($this->gamestate->state()['name'] == 'get_starting_tiles_bonus_auto') {
            self::setGameStateValue('previous_game_state', STATE_CALCULATE_NEXT_TILES_BONUS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_ascension_choose_bonus') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_avenue_of_dead_choose_bonus') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_CHOOSE_AVENUE_BONUS);
        } else if ($this->gamestate->state()['name'] == 'pay_salary') {
            self::setGameStateValue('previous_game_state', STATE_PAY_SALARY);
        } else if ($this->gamestate->state()['name'] == 'playerTurn_nobles_choose_row') {
            self::setGameStateValue('previous_game_state', STATE_PLAYER_TURN_NOBLES_BUILD);
        }
        $gameStateValue = self::getGameStateValue('previous_game_state');
        if ($name == '') {
            $gameStateName = self::getGameStateValue('useDiscoveryId');
        } else {
            $gameStateName = $name;
        }

        self::DbQuery("INSERT INTO `discovery_queue`(`queue`, `referrer`) VALUES ('$gameStateName',$gameStateValue)");
    }

    function goToPreviousState()
    {
        $player_id = self::getCurrentPlayerId();
        $referrer = (int)self::getUniqueValueFromDB("SELECT `referrer` FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        $queue = self::getUniqueValueFromDB("SELECT `queue` FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        $useDiscoveryPowerUp = (int)self::getGameStateValue('useDiscoveryPowerUp');
        self::setGameStateValue('previous_game_state', $referrer);

        if (!(($queue == '39' || $queue == '40' || $queue == '41') && $useDiscoveryPowerUp > 0)) {
            self::DbQuery("DELETE FROM `discovery_queue` ORDER BY id DESC limit 1");

            if ($referrer == STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS) {
                $last_temple_id = (int)$queue;
                self::setGameStateValue('last_temple_id', $last_temple_id);

                if ($last_temple_id == 1) {
                    $temple = 'blue';
                } else if ($last_temple_id == 2) {
                    $temple = 'red';
                } else if ($last_temple_id == 3) {
                    $temple = 'green';
                }
                $step = (int)self::getUniqueValueFromDB("SELECT `temple_$temple` FROM `player` WHERE `player_id` = $player_id");
                $bonus = explode(":", $this->temples[$temple][$step]);

                self::setGameStateValue('temple_bonus_resource', 0);
                self::setGameStateValue('temple_bonus_vp', 0);
                self::setGameStateValue('temple_bonus_cocoa', 0);

                if ($temple == 'blue') {
                    self::setGameStateValue('temple_bonus_resource', (int)$bonus[0]);
                } else if ($temple == 'red') {
                    self::setGameStateValue('temple_bonus_vp', (int)$bonus[0]);
                } else {
                    self::setGameStateValue('temple_bonus_cocoa', (int)$bonus[0]);
                }
            }

            $this->gamestate->nextState("useDiscoveryTile");
            if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN) {
                $this->gamestate->nextState("player_turn");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS) {
                $this->gamestate->nextState("showBoardActions");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_BOARD_ACTION) {
                $this->gamestate->nextState("board_action");
                $this->gamestate->nextState("check_end_turn");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS) {
                $this->gamestate->nextState("choose_worship");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_WORSHIP_ACTIONS) {
                $this->gamestate->nextState("action");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS) {
                $this->gamestate->nextState("choose_bonus");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_PASS) {
                $this->gamestate->nextState("check_pass");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_WORSHIP_TRADE) {
                $this->gamestate->nextState("trade");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_ALCHEMY) {
                $this->gamestate->nextState("alchemy");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY) {
                $this->gamestate->nextState("buy");
            } else if (self::getGameStateValue('previous_game_state') == STATE_CLAIM_STARTING_DISCOVERY_TILES) {
                $this->gamestate->nextState("claim_starting_Discovery");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_CONSTRUCTION) {
                $this->gamestate->nextState("construction");
            } else if (self::getGameStateValue('previous_game_state') == STATE_CALCULATE_NEXT_TILES_BONUS) {
                $this->gamestate->nextState("calculate_next_bonus");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS) {
                $this->gamestate->nextState("ascension");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_CHOOSE_AVENUE_BONUS) {
                $this->gamestate->nextState("choose_avenue_bonus");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PAY_SALARY) {
                $this->gamestate->nextState("pay_salary");
            } else if (self::getGameStateValue('previous_game_state') == STATE_PLAYER_TURN_NOBLES_BUILD) {
                $this->gamestate->nextState("choose_row");
            }
            self::setGameStateValue('previous_game_state', 0);
        } else {
            $this->gamestate->nextState("upgrade_workers");
        }
    }

    function claimDiscovery($id)
    {
        self::checkAction('claimDiscovery');

        $player_id = self::getCurrentPlayerId();
        $worker_id = self::getGameStateValue('selected_worker_id');
        $last_temple_id = (int)self::getGameStateValue('last_temple_id');

        $this->updateCocoa(-$this->discoveryTiles[$id]['price']['cocoa'], false);
        $this->updateWood(-$this->discoveryTiles[$id]['price']['wood'], false);
        $this->updateGold(-$this->discoveryTiles[$id]['price']['gold'], false);

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` = $id";
        $card_id_discoveryTiles = (int)self::getUniqueValueFromDB($sql);
        $discTile = $this->cards->getCard($card_id_discoveryTiles);

        if ((int)self::getGameStateValue('startingTileBonus')) {
            if ($this->isDraftMode()) {
                if ($discTile['location'] != 'sChoose_all') {
                    throw new BgaUserException(self::_("This move is not possible."));
                }
            } else {
                if ($discTile['location'] != 'sChoose_' . $player_id) {
                    throw new BgaUserException(self::_("This move is not possible."));
                }
            }

            $startingDiscovery0 = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery0` FROM `player` WHERE `player_id` = $player_id");
            $startingDiscovery1 = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery1` FROM `player` WHERE `player_id` = $player_id");

            if ($startingDiscovery0 == $id) {
                self::DbQuery("UPDATE `player` SET startingDiscovery0 = NULL WHERE player_id = $player_id");
            } else if ($startingDiscovery1 == $id) {
                self::DbQuery("UPDATE `player` SET startingDiscovery1 = NULL WHERE player_id = $player_id");
            }

        } else if (strpos($discTile['location'], 'discTiles_a') === 0) {
            $sql = "SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);

            if ($step == 3 && $discTile['location'] != 'discTiles_a0') {
                throw new BgaUserException(self::_("This move is not possible."));
            } else if ($step == 6 && $discTile['location'] != 'discTiles_a1') {
                throw new BgaUserException(self::_("This move is not possible."));
            } else if ($step == 8 && $discTile['location'] != 'discTiles_a2') {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        } else {
            if (!(self::getGameStateValue('worship_actions_discovery') || $last_temple_id > 0)) {
                throw new BgaUserException(self::_("This move is not possible."));
            }

            if ($last_temple_id == 0) {
                $sql = "SELECT `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id";
                $board_id = (int)self::getUniqueValueFromDB($sql);

                $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $board_id";
                $card_id_actionBoards = (int)self::getUniqueValueFromDB($sql);

                $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_b$card_id_actionBoards'";
                $card_type = (int)self::getUniqueValueFromDB($sql);

                if ($id != $card_type) {
                    throw new BgaUserException(self::_("This move is not possible."));
                }
            } else {
                if ($last_temple_id == 1) {
                    if (!$discTile['location'] == 'discTiles_tb0') {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                } else if ($last_temple_id == 2) {
                    if (!$discTile['location'] == 'discTiles_tr0') {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                } else if ($last_temple_id == 3) {
                    $sql = "SELECT `temple_green` FROM `player` WHERE `player_id` = $player_id";
                    $step = (int)self::getUniqueValueFromDB($sql);
                    if (!$discTile['location'] == 'discTiles_tg0' || ($step == 6 && !$discTile['location'] == 'discTiles_tg1')) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                }
            }
        }

        //All checks done
        $target = 'discoveryTile_' . $id;
        $this->payResource($player_id, -$this->discoveryTiles[$id]['price']['cocoa'], 'cocoa', $target);
        $this->payResource($player_id, -$this->discoveryTiles[$id]['price']['wood'], 'wood', $target);
        $this->payResource($player_id, -$this->discoveryTiles[$id]['price']['gold'], 'gold', $target);

        $player_hand = $this->getAllDatas()['playersHand'];
        $bonus_id = $this->discoveryTiles[$discTile['type_arg']]['bonus']['mask'];

        $row = -1;
        if ($bonus_id > 0) {
            $eclipse = (int)self::getGameStateValue('eclipse');
            self::incStat(1, "masks_eclipse$eclipse", $player_id);
            if (!$this->isMaskInArray($bonus_id, $player_hand[$player_id]['mask'][0])) {
                $row = 0;
            } else if (!$this->isMaskInArray($bonus_id, $player_hand[$player_id]['mask'][1])) {
                $row = 1;
            } else {
                $row = 2;
            }
        }

        $this->cards->moveCard($card_id_discoveryTiles, 'hand', $player_id);

        $discoveryTiles = $this->getAllDatas()['discoveryTiles'];
        $player_hand = $this->getAllDatas()['playersHand'];

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        self::notifyAllPlayers("claimDiscovery", clienttranslate('${player_name} claim Discovery tile'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'cocoa' => $this->discoveryTiles[$id]['price']['cocoa'],
            'wood' => $this->discoveryTiles[$id]['price']['wood'],
            'gold' => $this->discoveryTiles[$id]['price']['gold'],
            'discoveryTiles' => $discoveryTiles,
            'discTile' => $discTile,
            'row' => $row,
            'selected_board_id_to' => $selected_board_id_to,
            'player_hand' => $player_hand,
        ));

        if (self::getGameStateValue('worship_actions_discovery') && $last_temple_id == 0) {
            self::incGameStateValue('worship_actions_discovery', -1);
        }

        $this->goToNextState();
    }

    function preStepAvenue()
    {
        $player_id = self::getActivePlayerId();
        $enableAuto = (int)self::getUniqueValueFromDB("SELECT `enableAuto` FROM `player` WHERE `player_id` = $player_id");

        if($enableAuto > 0){
            $this->stepAvenue();
        }
    }

    function preWorshipActions()
    {
        $queueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `temple_queue`");
        $worship_actions_discovery = (int)self::getGameStateValue('worship_actions_discovery');
        $royalTileAction = (int)self::getGameStateValue('royalTileAction');

        if (!($queueCount > 0 || $worship_actions_discovery > 0 || $royalTileAction > 0)) {
            $this->goToNextState();
        } else {
            $player_id = self::getActivePlayerId();
            $enableAuto = (int)self::getUniqueValueFromDB("SELECT `enableAuto` FROM `player` WHERE `player_id` = $player_id");

            if($enableAuto > 0 && ($queueCount > 0 && $worship_actions_discovery == 0 && $royalTileAction == 0)){
                $temple_queue = self::getUniqueValueFromDB("SELECT queue FROM `temple_queue` ORDER BY id DESC LIMIT 1");

                if ($temple_queue == 'temple_blue') {
                    $this->stepTemple('blue');
                } else if ($temple_queue == 'temple_red') {
                    $this->stepTemple('red');
                } else if ($temple_queue == 'temple_green') {
                    $this->stepTemple('green');
                }
            }
        }
    }

    function goToNextState()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $sql = "SELECT count(*) FROM `temple_queue`";
        $queueCount = (int)self::getUniqueValueFromDB($sql);
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        $worship_actions_discovery = (int)self::getGameStateValue('worship_actions_discovery');
        $royalTileAction = (int)self::getGameStateValue('royalTileAction');
        $canBuildPyramidTiles = (int)self::getGameStateValue('canBuildPyramidTiles');
        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);
        self::setGameStateValue('last_temple_id', 0);

        if ($discoveryQueueCount > 0) {
            $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
            if ($worker && count($worker) > 0) {
                $this->gamestate->nextState("ascension");
            } else if (self::getGameStateValue('ascensionTempleSteps')) {
                $this->gamestate->nextState("action");
            } else {
                $this->goToPreviousState();
            }
        } else if ((int)self::getGameStateValue('startingTileBonus') > 0 && !$queueCount) {
            $this->gamestate->nextState("calculate_next_bonus");
        } else if ($queueCount > 0 || $worship_actions_discovery > 0 || $royalTileAction > 0) {
            $this->gamestate->nextState("action");
        } else if (self::getGameStateValue('isNobles')) {
            self::setGameStateValue('isNobles', 0);
            $extraWorker = (int)self::getGameStateValue('extraWorker');
            $countWorkers += $extraWorker;
            $this->boardgetUpgrades($countWorkers);
        } else if (self::getGameStateValue('ascensionBonusChoosed')) {
            $this->ascensionCleanUp();
        } else if (self::getGameStateValue('ascension')) {
            $this->gamestate->nextState("ascension");
        } else if (self::getGameStateValue('upgradeWorkers')) {
            $this->gamestate->nextState("upgrade_workers");
        } else if (self::getGameStateValue('isConstruction')) {
            if ($canBuildPyramidTiles > 0) {
                $this->gamestate->nextState("construction");
            } else {
                self::setGameStateValue('isConstruction', 0);

                if ($this->isTechAquired(7)) {
                    $countWorkers++;
                }

                $extraWorker = (int)self::getGameStateValue('extraWorker');
                $countWorkers += $extraWorker;

                $source = 'actionBoard_5';
                if ($this->isTechAquired(5)) {
                    $this->collectResource($player_id, 3, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 11)'));
                }
                if ($this->isTechAquired(8)) {
                    $this->collectResource($player_id, 1, 'temple_choose', $source, clienttranslate('${player_name} got ${amount}${token_temple_choose} extra (technology tile 17)'));
                    $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                    self::DbQuery($sql);

                    if ($countWorkers == 1) {
                        self::incGameStateValue('upgradeWorkers', 1);
                    } else if ($countWorkers == 2) {
                        self::incGameStateValue('upgradeWorkers', 1);
                    } else if ($countWorkers >= 3) {
                        self::incGameStateValue('upgradeWorkers', 2);
                    }

                    $this->gamestate->nextState("action");
                } else {
                    $this->boardgetUpgrades($countWorkers);
                }
            }
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function useDiscoveryTile($id, $force = false)
    {
        self::checkAction('useDiscoveryTile');

        $player_id = self::getCurrentPlayerId();

        if ($id < 0) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` = $id";
        $card_id_discoveryTile = (int)self::getUniqueValueFromDB($sql);
        $discTile = $this->cards->getCard($card_id_discoveryTile);

        if ($discTile['location'] != 'hand') {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $discTile_details = $this->discoveryTiles[$id]['bonus'];

        $vp = $discTile_details['vp'];
        $r = $discTile_details['r'];
        $cocoa = $discTile_details['cocoa'];
        $ad = $discTile_details['ad'];
        $temple_choose = $discTile_details['temple_choose'];
        $temple_blue = $discTile_details['temple_blue'];
        $temple_red = $discTile_details['temple_red'];
        $temple_green = $discTile_details['temple_green'];
        $upgrade = $discTile_details['upgrade'];
        $move_double = $discTile_details['move_double'];
        $free_cocoa = $discTile_details['free_cocoa'];
        $move_choose = $discTile_details['move_choose'];
        $extra_worker = $discTile_details['extra_worker'];

        $message = '${player_name} ${fulfilled_text}';// NOI18N
        $messageParts = array();

        if ($this->gamestate->state()['name'] == 'pay_salary' && ($cocoa <= 0 && $free_cocoa <= 0)) {
            throw new BgaUserException(self::_("You cannot use this Discovery Tile right now"));
        }

        //All checks done
        self::setGameStateValue('useDiscoveryId', $id);
        $this->cards->moveCard($card_id_discoveryTile, 'hand_used', $player_id);

        if ($vp > 0) {
            $messageParts[] = ' ${vp}${token_vp}';// NOI18N
            $this->updateVP($vp, $player_id);
            $source = 'discoveryTile_' . $id;
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $vp,
                'token' => 'vp',
                'source' => $source
            ));
            $this->setPreviousState();
            $this->goToPreviousState();
        } else if ($cocoa > 0) {
            $messageParts[] = ' ${cocoa}${token_cocoa}';// NOI18N
            $this->updateCocoa($cocoa, true, $player_id);
            $source = 'discoveryTile_' . $id;
            self::notifyAllPlayers("collectResource", "", array(
                'player_id' => $player_id,
                'amount' => $cocoa,
                'token' => 'cocoa',
                'source' => $source
            ));
            if ($this->gamestate->state()['name'] != 'pay_salary'){
                $this->setPreviousState();
                $this->goToPreviousState();
            }
        } else if ($r > 0) {
            $messageParts[] = ' ${r}${token_r}';// NOI18N
            self::setGameStateValue('choose_resources_max', $r);
            $this->setPreviousState();
            $this->gamestate->nextState("useDiscoveryTile");
            $this->gamestate->nextState("choose_resources");
        } else if ($temple_choose > 0) {
            $messageParts[] = ' ${temple_choose}${token_temple_choose}';// NOI18N
            $this->useDiscoveryTemple('temple_choose');
        } else if ($temple_blue > 0) {
            $messageParts[] = ' ${temple_blue}${token_temple_blue}';// NOI18N
            $this->useDiscoveryTemple('temple_blue');
        } else if ($temple_red > 0) {
            $messageParts[] = ' ${temple_red}${token_temple_red}';// NOI18N
            $this->useDiscoveryTemple('temple_red');
        } else if ($temple_green > 0) {
            $messageParts[] = ' ${temple_green}${token_temple_green}';// NOI18N
            $this->useDiscoveryTemple('temple_green');
        } else if ($ad > 0) {
            $messageParts[] = ' ${ad}${token_ad}';// NOI18N
            $this->setPreviousState();
            $this->gamestate->nextState("useDiscoveryTile");
            $this->gamestate->nextState("avenue_of_dead");
        } else if ($move_choose > 0) {
            if ($this->gamestate->state()['name'] != 'playerTurn' || self::getGameStateValue('useDiscoveryMoveWorkerAnywhere')) {
                throw new BgaUserException(self::_("You cannot use this Discovery Tile right now"));
            }
            $messageParts[] = clienttranslate(' ability to move worker anywhere.');
            self::setGameStateValue('useDiscoveryMoveWorkerAnywhere', 1);
        } else if ($move_double > 0) {
            $AreTwoWorkersAvailable = self::getUniqueValueFromDB("SELECT `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `locked` = false group by actionboard_id having count(*) > 1");
            if ($this->gamestate->state()['name'] != 'playerTurn' || !$AreTwoWorkersAvailable || self::getGameStateValue('useDiscoveryMoveTwoWorkers')) {
                throw new BgaUserException(self::_("You cannot use this Discovery Tile right now"));
            }
            $messageParts[] = clienttranslate(' ability to move two workers');
            self::setGameStateValue('useDiscoveryMoveTwoWorkers', 1);
        } else if ($upgrade > 0) {
            $countWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false");
            if ($this->gamestate->state()['name'] == 'playerTurn_show_board_actions' || $countWorkers == 0) {
                throw new BgaUserException(self::_("You cannot use this Discovery Tile right now"));
            }
            $messageParts[] = ' ${upgrade}${token_upgrade}';// NOI18N
            self::incGameStateValue('useDiscoveryPowerUp', 2);
            self::incGameStateValue('upgradeWorkers', $upgrade);
            $this->setPreviousState();
            $this->gamestate->nextState("useDiscoveryTile");
            $this->gamestate->nextState("upgrade_workers");
        } else if ($free_cocoa > 0) {
            if (!$force) {
                throw new BgaUserException(self::_("You use this discovery tile in the action bar only"));
            }
            $messageParts[] = ' ${token_cocoa_free}';// NOI18N
        } else if ($extra_worker > 0) {
            if ($this->gamestate->state()['name'] == 'playerTurn_alchemy') {
                $messageParts[] = ' ${token_extra_worker}';// NOI18N
                self::incGameStateValue('extraWorker', 1);
                $this->gamestate->nextState("useDiscoveryTile");
                $this->gamestate->nextState("alchemy");
            } else if ($this->gamestate->state()['name'] == 'playerTurn_construction') {
                $messageParts[] = ' ${token_extra_worker}';// NOI18N
                self::incGameStateValue('canBuildPyramidTiles', 1);
                self::incGameStateValue('extraWorker', 1);
            } else if ($this->gamestate->state()['name'] == 'playerTurn_nobles_choose_row') {
                $messageParts[] = ' ${token_extra_worker}';// NOI18N
                self::incGameStateValue('extraWorker', 1);
                $this->gamestate->nextState("useDiscoveryTile");
                $this->gamestate->nextState("choose_row");
            } else {
                throw new BgaUserException(self::_("You cannot use this Discovery Tile right now"));
            }
        }

        $player_hand = $this->getAllDatas()['playersHand'];

        self::notifyAllPlayers("useDiscoveryTile", $message . implode(",", $messageParts), array(
            'player_id' => $player_id,
            'player_name' => self::getCurrentPlayerName(),
            'id' => $id,
            'vp' => $vp,
            'r' => $r,
            'cocoa' => $cocoa,
            'player_hand' => $player_hand,
            'temple_choose' => $temple_choose,
            'temple_blue' => $temple_blue,
            'temple_red' => $temple_red,
            'temple_green' => $temple_green,
            'ad' => $ad,
            'upgrade' => $upgrade,
            'token_vp' => 'vp',
            'token_r' => 'resource',
            'token_cocoa' => 'cocoa',
            'token_temple_choose' => 'temple_choose',
            'token_temple_blue' => 'temple_blue',
            'token_temple_red' => 'temple_red',
            'token_temple_green' => 'temple_green',
            'token_upgrade' => 'upgrade',
            'token_cocoa_free' => 'cocoa_free',
            'token_extra_worker' => 'extra_worker',
            'token_ad' => 'ad',
            'fulfilled_text' => clienttranslate('used a discovery tile and got:'),
            'i18n' => array('fulfilled_text')
        ));
    }

    function stepAvenue()
    {
        self::checkAction('stepAvenue');

        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        if (!$discoveryQueueCount && !self::getGameStateValue('ascension')) {

            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $selected_board_id_to";
            $board_id = (int)self::getUniqueValueFromDB($sql);

            if ($board_id != 6) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }

        $sql = "SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);


        // all checks done
        if ($step < 9) {
            $sql = "UPDATE `player` SET `avenue_of_dead`  = `avenue_of_dead` + 1 WHERE player_id = $player_id";
            self::DbQuery($sql);

            self::incStat(1, "avenue", $player_id);

            $sql = "SELECT `avenue_of_dead` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);

            $eclipse = (int)self::getGameStateValue('eclipse');
            self::incStat(1, "avenue_eclipse$eclipse", $player_id);

            if ($step == 3 || $step == 6 || $step == 8) {

                self::notifyAllPlayers("stepAvenue", clienttranslate('${player_name} advanced one space on Avenue of Dead'), array(
                    'i18n' => array('temple'),
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'step' => $step,
                ));

                $this->gamestate->nextState("choose_bonus");

                $discTiles_a0 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_a0'");
                $discTiles_a1 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_a1'");
                $discTiles_a2 = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_location` = 'discTiles_a2'");


                if ($step == 3 && $discTiles_a0 == 0) {
                    $this->pass(false);
                } else if ($step == 6 && $discTiles_a1 == 0) {
                    $this->pass(false);
                } else if ($step == 8 && $discTiles_a2 == 0) {
                    $this->pass(false);
                }
            } else {

                self::notifyAllPlayers("stepAvenue", clienttranslate('${player_name} advanced one space on Avenue of Dead'), array(
                    'i18n' => array('temple'),
                    'player_id' => $player_id,
                    'player_name' => self::getActivePlayerName(),
                    'step' => $step,
                ));

                $this->stepAvenueCleanup();
            }
        } else {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} stays on top of avenue of dead'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
            ));
            $this->stepAvenueCleanup();
        }
    }

    function stepAvenueCleanup()
    {
        $player_id = self::getActivePlayerId();
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        if ($discoveryQueueCount) {
            $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
            if ($worker && count($worker) > 0) {
                $this->gamestate->nextState("ascension");
            } else if (self::getGameStateValue('ascensionTempleSteps')) {
                $this->gamestate->nextState("action");
            } else {
                $this->goToPreviousState();
            }
        } else if (self::getGameStateValue('ascension')) {
            $this->gamestate->nextState("ascension");
        } else if (self::getGameStateValue('isNobles')) {
            self::setGameStateValue('isNobles', 0);
            $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
            $countWorkers = (int)self::getUniqueValueFromDB($sql);

            $extraWorker = (int)self::getGameStateValue('extraWorker');
            $countWorkers += $extraWorker;

            if ($countWorkers == 1) {
                self::incGameStateValue('upgradeWorkers', 1);
                $this->gamestate->nextState("upgrade_workers");
            } else if ($countWorkers == 2) {
                self::incGameStateValue('upgradeWorkers', 1);
                $this->gamestate->nextState("upgrade_workers");
            } else if ($countWorkers >= 3) {
                self::incGameStateValue('upgradeWorkers', 2);
                $this->gamestate->nextState("upgrade_workers");
            }
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function upgradeWorker($worker_id, $board_id_from)
    {
        self::checkAction('upgradeWorker');

        $player_id = self::getActivePlayerId();

        $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
        $selected_worker_id = (int)self::getGameStateValue('selected_worker_id');
        $selected_worker2_id = (int)self::getGameStateValue('selected_worker2_id');
        $selected_board_id_to = (int)self::getGameStateValue('selected_board_id_to');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        $worker_power = (int)self::getUniqueValueFromDB("SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id");

        if ($upgradeWorkers <= 0 || $worker_power > 5) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        $sql = "SELECT `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id AND `locked` = false";
        $board_id = (int)self::getUniqueValueFromDB($sql);

        $useDiscoveryPowerUp = (int)self::getGameStateValue('useDiscoveryPowerUp') > 0;

        if (!$discoveryQueueCount && !self::getGameStateValue('startingTileBonus') && !$useDiscoveryPowerUp) {
            if ($board_id == null || $board_id_from != $board_id) {
                throw new BgaUserException(self::_("This move is not possible."));
            }

            if ($board_id_from != $selected_board_id_to) {
                throw new BgaUserException(self::_("You cannot power up this worker"));
            }
        }

        if (((int)self::getGameStateValue('doMainAction') > 0) && ($selected_worker_id == $worker_id || $selected_worker2_id == $worker_id)) {
            throw new BgaUserException(self::_("You must finish the main action before power up the worker you just moved"));
        }

        //done all checks
        $upgradeWorkers = $upgradeWorkers - 1;
        self::incGameStateValue('upgradeWorkers', -1);

        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");
        $useStartingTile = (int)self::getGameStateValue('startingTileBonus') > 0;

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        if ($worker_power >= 5) {
            self::incGameStateValue('ascension', 1);
        }
        $sql = "UPDATE `map` SET worker_power  = worker_power + 1 WHERE player_id = $player_id AND `worker_id` = $worker_id";
        self::DbQuery($sql);

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $worker_id";
        $worker_power_after = (int)self::getUniqueValueFromDB($sql);

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $board_id_from";
        $card = self::getObjectFromDB($sql);

        $board_name_to = $this->actionBoards[$card["card_id"]]["name"];

        $card_id_to = (int)self::getUniqueValueFromDB($sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_location_arg` = $board_id_from");


        self::notifyAllPlayers("upgradeWorker", clienttranslate('${player_name} upgrades a worker on ${board_name_to} (${card_id_to}) from ${worker_power_previous} to ${worker_power}'), array(
            'i18n' => array('temple'),
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'worker_id' => $worker_id,
            'worker_power' => $worker_power_after,
            'worker_power_previous' => $worker_power,
            'board_name_to' => $board_name_to,
            'card_id_to' => $card_id_to,
        ));

        if (self::getGameStateValue('useDiscoveryPowerUp')) {
            self::incGameStateValue('useDiscoveryPowerUp', -1);
        }

        $this->cleanUpPowerUp();
    }

    function cleanUpPowerUp()
    {
        $player_id = self::getActivePlayerId();
        $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
        if (self::getGameStateValue('ascension') && $worker && count($worker) > 0) {
            $this->gamestate->nextState("avenue_of_dead");
        } else if ($discoveryQueueCount > 0) {
            if (self::getGameStateValue('useDiscoveryPowerUp')) {
                $this->gamestate->nextState("upgrade_workers");
            } else {
                $this->goToPreviousState();
            }
        } else if ($upgradeWorkers > 0) {
            $this->gamestate->nextState("upgrade_workers");
        } else if ((int)self::getGameStateValue('startingTileBonus') > 0) {
            self::setGameStateValue('startingTileBonus', 2);
            $this->gamestate->nextState("calculate_next_bonus");
        } else if ($this->isTechAquired(6) && !self::getGameStateValue('paidPowerUp')) {
            self::setGameStateValue('paidPowerUp', 1);
            $this->gamestate->nextState("buy");
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function buyPowerUp()
    {
        self::checkAction('buyPowerUp');

        $player_id = self::getActivePlayerId();
        $worker_id = self::getGameStateValue('selected_worker_id');
        $target = $player_id . '_worker_' . $worker_id;

        $this->payResource($player_id, -1, 'cocoa', $target, clienttranslate('${player_name} pays ${amount}${token_cocoa} for additional power up (technology tile 13)'));

        self::incGameStateValue('upgradeWorkers', 1);
        $this->gamestate->nextState("upgrade_workers");
    }

    function ascension($id, $freeCocoa)
    {
        self::checkAction('ascension');

        $player_id = self::getActivePlayerId();

        $message = '${player_name} ${fulfilled_text}';// NOI18N
        $messageParts = array();

        self::incStat(1, "ascension", $player_id);

        self::incGameStateValue('ascension', -1);

        $target = 'ascension_' . $id;
        $source = 'ascension_' . $id;

        if ($id == 0) {
            $messageParts[] = ' ${vp}${token_vp}';// NOI18N
            $this->collectResource($player_id, 5, 'vp', $source, ' ');
        } else if ($id == 1) {
            $messageParts[] = ' 2 ${token_temple_choose}';// NOI18N

            if (!$freeCocoa) {

                $this->payResource($player_id, -3, 'cocoa', $target);
            } else {
                $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
                $id = (int)self::getUniqueValueFromDB($sql);
                if (!$id) {
                    throw new BgaUserException(self::_("This move is not possible."));
                }

                $this->useDiscoveryTile($id, true);
            }

            for ($i = 0; $i < 2; $i++) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',2)";
                self::DbQuery($sql);
            }
            self::setGameStateValue('ascensionTempleSteps', 2);
            $this->gamestate->nextState("action");
        } else if ($id == 2) {
            $actionboard_id = (int)self::getUniqueValueFromDB("SELECT `actionboard_id` FROM `map` WHERE player_id = $player_id AND `worker_id` = 4");
            if ($actionboard_id != -1) {
                throw new BgaUserException(self::_("You already have your 4. Worker."));
            }
            $messageParts[] = ' 2${token_cocoa} and 4. Worker';// NOI18N
            $this->collectResource($player_id, 2, 'cocoa', $source);

            $sql = "UPDATE `map` SET locked = false WHERE player_id = $player_id AND `worker_id` = 4";
            self::DbQuery($sql);
            $this->moveWorkerToBoard(0, 4, -1, 1);
        } else if ($id == 3) {
            $messageParts[] = ' 1 ${token_temple_choose}';// NOI18N
            self::setGameStateValue('ascensionTempleSteps', 1);

            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',2)";
            self::DbQuery($sql);

            $this->gamestate->nextState("action");
        } else if ($id == 4) {
            $messageParts[] = ' ${cocoa}${token_cocoa}';// NOI18N
            $this->collectResource($player_id, 5, 'cocoa', $source, ' ');
        } else {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        self::setGameStateValue('ascensionBonusChoosed', 1);

        self::notifyAllPlayers("useAscension", $message . implode(",", $messageParts), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'vp' => 5,
            'cocoa' => 5,
            'token_vp' => 'vp',
            'token_cocoa' => 'cocoa',
            'token_temple_choose' => 'temple_choose',
            'fulfilled_text' => clienttranslate('takes Ascension bonus and got:'),
            'i18n' => array('fulfilled_text')
        ));

        if ($id == 0 || $id == 2 || $id == 4) {
            $this->ascensionCleanUp();
        }
    }

    function preAscension()
    {
        $player_id = self::getActivePlayerId();

        $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
        if ($worker && count($worker) > 0) {
            $this->advanceCalenderTrack();

            $worker_id = $worker[0]['worker_id'];
            $board_id_from = $worker[0]['actionboard_id'];

            self::notifyAllPlayers("upgradeWorker", '', array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'worker_id' => $worker_id,
                'worker_power' => 1,
            ));

            $this->moveWorkerToBoard(0, $worker_id, $board_id_from, 1);

            $sql = "UPDATE `map` SET worker_power  = 1 WHERE player_id = $player_id AND `worker_id` = $worker_id";
            self::DbQuery($sql);
        }
        $ascension = (int)self::getGameStateValue('ascension');
        if ($ascension <= 0) {
            $this->ascensionCleanUp();
        }
    }

    function ascensionCleanUp()
    {
        $player_id = self::getActivePlayerId();
        $upgradeWorkers = (int)self::getGameStateValue('upgradeWorkers');
        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');
        $discoveryQueueCount = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `discovery_queue` ORDER BY id DESC LIMIT 1");

        self::setGameStateValue('ascensionBonusChoosed', 0);

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        if ($discoveryQueueCount > 0) {
            $worker = self::getObjectListFromDB("SELECT `worker_id`, `actionboard_id` FROM `map` WHERE `player_id` = $player_id AND `worker_power` = 6 Limit 1");
            if ($worker && count($worker) > 0) {
                $this->gamestate->nextState("ascension");
            } else if (self::getGameStateValue('ascensionTempleSteps')) {
                $this->gamestate->nextState("action");
            } else {
                $this->goToPreviousState();
            }
        } else if (self::getGameStateValue('ascension')) {
            $this->gamestate->nextState("ascension");
        } else if ($upgradeWorkers > 0) {
            $this->gamestate->nextState("upgrade_workers");
        } else if ($this->isTechAquired(6) && !self::getGameStateValue('paidPowerUp') && $countWorkers > 0) {
            self::setGameStateValue('paidPowerUp', 1);
            $this->gamestate->nextState("buy");
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function advanceCalenderTrack($message = '')
    {
        $player_id = self::getActivePlayerId();

        $black = (int)self::getGameStateValue('eclipseDiscBlack');
        $white = (int)self::getGameStateValue('eclipseDiscWhite');

        if ($white < $black) {
            $white = $white + 1;
            self::incGameStateValue('eclipseDiscWhite', 1);
            if ($message == '') {
                $message = clienttranslate('The light disc advanced one step on the Calender track');
            }
            self::notifyAllPlayers("updateCalenderTrack", $message, array(
                'step' => (int)self::getGameStateValue('eclipseDiscWhite'),
                'color' => 'white',
            ));

            $eclipse = (int)self::getGameStateValue('eclipse');
            $count_player = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `player`");
            $initBlack = 12 - (4 - $count_player);
            $maxSteps = $initBlack + ($initBlack - 1) + ($initBlack - 2);
            $currentStep = $white;
            if ($eclipse == 2) {
                $currentStep = $currentStep + $initBlack;
            } else if ($eclipse == 3) {
                $currentStep = $currentStep + $initBlack + $initBlack - 1;
            }

            $progression = 10 + 85 * $currentStep / $maxSteps;
            self::setGameStateValue('progression', $progression);

            if ($white >= $black) {
                if ($this->isDarkEclipse()) {
                    self::setGameStateValue('lastRound', 3);
                    self::notifyAllPlayers("showEclipseBanner", clienttranslate('*** Eclipse is triggered ***'), array(
                        'lastRound' => (int)self::getGameStateValue('lastRound'),
                        'eclipseNumber' => $eclipse
                    ));
                } else {
                    self::setGameStateValue('lastRound', 2);
                    self::notifyAllPlayers("showEclipseBanner", clienttranslate('*** Eclipse is triggered ***'), array(
                        'lastRound' => (int)self::getGameStateValue('lastRound'),
                        'eclipseNumber' => $eclipse
                    ));
                }
            }
        }
    }

    function nobles()
    {
        self::checkAction('nobles');

        $player_id = self::getActivePlayerId();

        $this->updateWood(-2);

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $target = 'actionBoard_' . $selected_board_id_to;
        self::notifyAllPlayers("payResource", clienttranslate('${player_name} pays ${amount}${token_wood}'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'amount' => 2,
            'token_wood' => 'wood',
            'token' => 'wood',
            'target' => $target
        ));

        $this->gamestate->nextState("choose_row");
    }

    function placeBuilding($row)
    {
        self::checkAction('placeBuilding');

        $player_id = self::getActivePlayerId();

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $extraWorker = (int)self::getGameStateValue('extraWorker');

        $countWorkers += $extraWorker;

        if ($row < 0 || $row > 2) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        if (($row + 1) > $countWorkers) {
            throw new BgaUserException(self::_("You cannot build in this row"));
        }

        $rowDB = (int)self::getUniqueValueFromDB("SELECT `row$row` FROM `nobles`");

        if ($row == 0 && $rowDB >= 5) {
            if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (51,52,53) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
                throw new BgaUserException(self::_("You cannot build in this row. You may use your discovery tile to get an extra worker for a different row"));
            } else {
                throw new BgaUserException(self::_("You cannot build in this row"));
            }
        } else if ($row == 1 && $rowDB >= 4) {
            if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (51,52,53) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
                throw new BgaUserException(self::_("You cannot build in this row. You may use your discovery tile to get an extra worker for a different row"));
            } else {
                throw new BgaUserException(self::_("You cannot build in this row"));
            }
        } else if ($row == 2 && $rowDB >= 3) {
            if ((int)self::getUniqueValueFromDB("SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (51,52,53) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1")) {
                throw new BgaUserException(self::_("You cannot build in this row. You may use your discovery tile to get an extra worker for a different row"));
            } else {
                throw new BgaUserException(self::_("You cannot build in this row"));
            }
        }

        $sql = "UPDATE `nobles` SET `row$row`  = `row$row` + 1";
        self::DbQuery($sql);

        $row1 = (int)self::getUniqueValueFromDB("SELECT `row0` FROM `nobles`");
        $row2 = (int)self::getUniqueValueFromDB("SELECT `row1` FROM `nobles`");
        $row3 = (int)self::getUniqueValueFromDB("SELECT `row2` FROM `nobles`");

        $sum = $row1 + $row2 + $row3;

        $vp = $this->nobles['row' . $row][$rowDB];

        $source = 'building_' . $rowDB . '_row_' . $row;

        $this->collectResource($player_id, $vp, 'vp', $source);

        self::incStat($vp, "summary_nobles", $player_id);

        if ($this->isTechAquired(1)) {
            $this->collectResource($player_id, 3, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 03)'));
        }

        self::notifyAllPlayers("placeBuilding", clienttranslate('${player_name} placed a building'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'sum' => $sum,
            'row' => $row,
            'column' => $rowDB
        ));

        $eclipse = (int)self::getGameStateValue('eclipse');
        self::incStat(1, "place_nobles_building_round$eclipse", $player_id);

        self::setGameStateValue('isNobles', 1);
        $this->gamestate->nextState("avenue_of_dead");
    }

    function trade($get_cocoa, $get_wood, $get_stone, $get_gold, $pay_cocoa, $pay_wood, $pay_stone, $pay_gold, $get_temple, $freeCocoa)
    {
        self::checkAction('trade');

        $player_id = self::getActivePlayerId();
        $selected_worker_id = self::getGameStateValue('selected_worker_id');

        $sql = "SELECT `worker_power` FROM `map` WHERE `player_id` = $player_id AND `worker_id` = $selected_worker_id";
        $worker_power = (int)self::getUniqueValueFromDB($sql);

        if (!$freeCocoa) {
            $this->updateCocoa(-$pay_cocoa, false);
        }
        $this->updateWood(-$pay_wood, false);
        $this->updateStone(-$pay_stone, false);
        $this->updateGold(-$pay_gold, false);

        $id = (int)self::getGameStateValue('royalTileTradeId');
        $tradeInfo = $this->royalTilesTrade[$id];

        if ($tradeInfo['pay']['cocoa'] > 0 && $tradeInfo['pay']['resource'] == 0 || $tradeInfo['pay']['cocoa'] > 0 && $tradeInfo['pay']['resource'] > 0) {
            if ($pay_cocoa > $worker_power) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        } else if ($tradeInfo['pay']['cocoa'] == 0 && $tradeInfo['pay']['resource'] > 0) {
            if (($pay_wood + $pay_stone + $pay_gold) > $worker_power) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }

        if ($tradeInfo['id'] == 'trade_c_ws') {
            if ($get_wood > $worker_power || $get_stone > $worker_power || ($get_cocoa + $get_gold) > 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($get_wood != $pay_cocoa || $get_stone != $pay_cocoa) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($freeCocoa) {
                $get_wood = $worker_power;
                $get_stone = $worker_power;
            }
        } else if ($tradeInfo['id'] == 'trade_r_2c') {
            if ($get_cocoa > ($worker_power * 2) || ($get_wood + $get_stone + $get_gold) > 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($get_cocoa != (($pay_wood + $pay_stone + $pay_gold) * 2)) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        } else if ($tradeInfo['id'] == 'trade_c_sg') {
            if ($get_stone > $worker_power || $get_gold > $worker_power || ($get_cocoa + $get_wood) > 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($get_gold != $pay_cocoa || $get_stone != $pay_cocoa) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($freeCocoa) {
                $get_stone = $worker_power;
                $get_gold = $worker_power;
            }
        } else if ($tradeInfo['id'] == 'trade_cr_r') {
            if (($get_wood + $get_stone + $get_gold) > $worker_power || $get_cocoa > 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if (($pay_wood + $pay_stone + $pay_gold) != $pay_cocoa || $pay_cocoa != 1) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        } else if ($tradeInfo['id'] == 'trade_c_t') {
            if ($get_temple > ($worker_power - 1) || ($get_cocoa + $get_wood + $get_stone + $get_gold) > 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            if ($freeCocoa) {
                $get_temple = ($worker_power - 1);
            }
            for ($i = 0; $i < $get_temple; $i++) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);
            }
        }

        $worker_id = self::getGameStateValue('selected_worker_id');
        $source = $player_id . '_worker_' . $worker_id;
        $target = $player_id . '_worker_' . $worker_id;

        if (!$freeCocoa) {
            $this->payResource($player_id, -$pay_cocoa, 'cocoa', $target);
        } else {
            $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
            $id = (int)self::getUniqueValueFromDB($sql);
            if (!$id) {
                throw new BgaUserException(self::_("This move is not possible."));
            }

            $this->useDiscoveryTile($id, true);
        }

        $this->payResource($player_id, -$pay_wood, 'wood', $target);
        $this->payResource($player_id, -$pay_stone, 'stone', $target);
        $this->payResource($player_id, -$pay_gold, 'gold', $target);

        $this->collectResource($player_id, $get_cocoa, 'cocoa', $source);
        $this->collectResource($player_id, $get_wood, 'wood', $source);
        $this->collectResource($player_id, $get_stone, 'stone', $source);
        $this->collectResource($player_id, $get_gold, 'gold', $source);

        $worship_actions_discovery = (int)self::getGameStateValue('worship_actions_discovery');

        if ($worship_actions_discovery > 0 || $get_temple > 0) {
            $this->gamestate->nextState("action");
        } else {
            $this->gamestate->nextState("check_end_turn");
        }
    }

    function acquireTechnology($id)
    {
        self::checkAction('acquireTechnology');

        $player_id = self::getActivePlayerId();

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'technologyTiles' AND `card_type_arg` = $id";
        $card_id_technologyTiles = (int)self::getUniqueValueFromDB($sql);
        $techTile = $this->cards->getCard($card_id_technologyTiles);
        $location = $techTile['location'];

        $source = 'technologyTile_' . $id;
        $target = 'technologyTile_' . $id;

        $sql = "SELECT `$location` FROM `player` WHERE `player_id` = $player_id";

        if ((int)self::getUniqueValueFromDB($sql) > 0) {
            throw new BgaUserException(self::_("You already acquired this technology."));
        }

        //all checks done
        self::setGameStateValue('aquiredTechnologyTile', $id);

        if ($location == 'techTiles_r2_c1' || $location == 'techTiles_r2_c2' || $location == 'techTiles_r2_c3') {
            $this->payResource($player_id, -2, 'gold', $target);
        } else {
            $this->payResource($player_id, -1, 'gold', $target);
        }

        $sql = "UPDATE `player` SET `$location`  = 1 WHERE player_id = $player_id";
        self::DbQuery($sql);

        $sql = "SELECT `player_id` FROM `player` WHERE `$location` = 1 AND player_id != $player_id";
        $otherPlayers = self::getObjectListFromDB($sql);

        foreach ($otherPlayers as $player) {
            $this->collectResource($player['player_id'], 3, 'vp', $source);
        }

        $selected_board_id_to = self::getGameStateValue('selected_board_id_to');

        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);

        $extraWorker = (int)self::getGameStateValue('extraWorker');
        $countWorkers += $extraWorker;

        if (!($countWorkers == 1 && ($location == 'techTiles_r2_c1' || $location == 'techTiles_r2_c2' || $location == 'techTiles_r2_c3'))) {
            self::incGameStateValue('upgradeWorkers', 1);
        }

        self::notifyAllPlayers("acquireTechnology", clienttranslate('${player_name} acquired a Technology'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'location' => $location,
        ));

        $eclipse = (int)self::getGameStateValue('eclipse');
        self::incStat(1, "aquiredTechnology_eclipse$eclipse", $player_id);

        if ($this->isTechAquired(1)) {
            $this->collectResource($player_id, 3, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 03)'));
        }

        if ($location == 'techTiles_r1_c1' || $location == 'techTiles_r2_c1') {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_blue',1)";
        } else if ($location == 'techTiles_r1_c2' || $location == 'techTiles_r2_c2') {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_red',1)";
        } else if ($location == 'techTiles_r1_c3' || $location == 'techTiles_r2_c3') {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_green',1)";
        }
        self::DbQuery($sql);

        $this->gamestate->nextState("action");
    }

    function buildPyramid($constructionWrapper, $pyramidTile, $rotate, $wood, $stone)
    {
        self::checkAction('buildPyramid');

        $player_id = self::getActivePlayerId();
        $source = $constructionWrapper;
        $target = 'actionBoard_5';

        $level = (int)explode("_", $constructionWrapper)[2];
        $row = (int)explode("_", $constructionWrapper)[4];
        $column = (int)explode("_", $constructionWrapper)[6];

        $maxRowInLevel = 4 - $level;
        $location_arg = ($level * 100) + ($row * $maxRowInLevel) + $column;

        $isAlreadyUsed = self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = $location_arg");

        if ($isAlreadyUsed) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        if ($level > 0) {

            $maxRowInLevel = 5 - $level;
            $topLeft = $row * $maxRowInLevel + $column + (($level - 1) * 100);
            $topRight = $topLeft + 1;
            $bottomLeft = $row * $maxRowInLevel + $column + $maxRowInLevel + (($level - 1) * 100);
            $bottomRight = $bottomLeft + 1;

            $topLeftCard = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = $topLeft");
            $topRightCard = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = $topRight");
            $bottomLeftCard = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = $bottomLeft");
            $bottomRightCard = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = $bottomRight");

            if (!($topLeftCard && $topRightCard && $bottomLeftCard && $bottomRightCard)) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }

        $stonePrice = 2;
        $woodPrice = $level;

        if ($this->isTechAquired(7) && self::getGameStateValue('getTechnologyDiscount')) {

            if ($stonePrice != $stone || $woodPrice != $wood) {
                if (!($stonePrice == $stone && ($woodPrice - 1) == $wood || ($stonePrice - 1) == $stone && $woodPrice == $wood)) {
                    throw new BgaUserException(self::_("You can reduce one resource only (technology tile 15)"));
                }
                self::setGameStateValue('getTechnologyDiscount', 0);
                $stonePrice = $stone;
                $woodPrice = $wood;
            }
        }

        $this->updateStone(-$stonePrice, false);
        $this->updateWood(-$woodPrice, false);

        //all checks done

        self::setGameStateValue('buildOnePyramidTile', 1);

        $this->payResource($player_id, -$stonePrice, 'stone', $target);
        $this->payResource($player_id, -$woodPrice, 'wood', $target);

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'pyramidTiles' AND `card_type_arg` = $pyramidTile";
        $card_id_pyramidTiles = (int)self::getUniqueValueFromDB($sql);
        $pyramidTileCard = $this->cards->getCard($card_id_pyramidTiles);

        $this->cards->pickCardsForLocation(1, $pyramidTileCard['location'], "pyra_rotate_$rotate", $location_arg);

        $pyramidTiles = $this->getAllDatas()['pyramidTiles'];

        self::notifyAllPlayers("buildPyramid", clienttranslate('${player_name} add a pyramid tile to the pyramid'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'pyramidTile' => $pyramidTile,
            'constructionWrapper' => $constructionWrapper,
            'rotate' => $rotate,
            'pyramidTiles' => $pyramidTiles,
        ));

        $row = $row * 2 + 1 + $level;
        $column = $column * 2 + $level;

        $pyramidTile_values = $this->pyramidTiles[$pyramidTile]['values'];

        for ($i = 0; $i < $rotate; $i++) {
            $temp = array_pop($pyramidTile_values);
            array_unshift($pyramidTile_values, $temp);
        }

        $vp = 0;

        $newColumn = $column;
        $newRow = $row;
        $sql = "SELECT column$newColumn FROM `pyramid` WHERE `id` = $newRow";
        if ($this->checkPyramidField($player_id, $source, self::getUniqueValueFromDB($sql), $pyramidTile_values[0])) {
            $vp++;
        }
        self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[0]'  WHERE `id` = $newRow");
        $newColumn = $column + 1;
        $newRow = $row;
        $sql = "SELECT column$newColumn FROM `pyramid` WHERE `id` = $newRow";
        if ($this->checkPyramidField($player_id, $source, self::getUniqueValueFromDB($sql), $pyramidTile_values[1])) {
            $vp++;
        }
        self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[1]'  WHERE `id` = $newRow");
        $newColumn = $column + 1;
        $newRow = $row + 1;
        $sql = "SELECT column$newColumn FROM `pyramid` WHERE `id` = $newRow";
        if ($this->checkPyramidField($player_id, $source, self::getUniqueValueFromDB($sql), $pyramidTile_values[2])) {
            $vp++;
        }
        self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[2]'  WHERE `id` = $newRow");
        $newColumn = $column;
        $newRow = $row + 1;
        $sql = "SELECT column$newColumn FROM `pyramid` WHERE `id` = $newRow";
        if ($this->checkPyramidField($player_id, $source, self::getUniqueValueFromDB($sql), $pyramidTile_values[3])) {
            $vp++;
        }
        self::DbQuery("UPDATE `pyramid` SET column$newColumn = '$pyramidTile_values[3]'  WHERE `id` = $newRow");

        $this->collectResource($player_id, $vp, 'vp', $source);

        self::incStat($vp, "pyramid_same_symbol", $player_id);
        self::incStat($vp, "summary_pyramid", $player_id);

        $vp = ($level * 2) + 1;
        $this->collectResource($player_id, $vp, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (pyramid level)'));

        self::incStat($vp, "summary_pyramid", $player_id);

        $sql = "SELECT count(*) FROM `temple_queue`";
        $queueCount = (int)self::getUniqueValueFromDB($sql);

        $selected_board_id_to = (int)self::getGameStateValue('selected_board_id_to');
        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);
        $extraWorker = (int)self::getGameStateValue('extraWorker');
        $countWorkers += $extraWorker;

        if ($this->isTechAquired(7)) {
            $countWorkers++;
        }

        $sql = "SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        if ($step < 9) {
            $sql = "UPDATE `player` SET `pyramid_track`  = `pyramid_track` + 1 WHERE player_id = $player_id";
            self::DbQuery($sql);

            $sql = "SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id";
            $step = (int)self::getUniqueValueFromDB($sql);

            self::notifyAllPlayers("stepPyramidTrack", clienttranslate('${player_name} advanced one space on Pyramid track'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
                'step' => $step,
            ));

            $eclipse = (int)self::getGameStateValue('eclipse');
            self::incStat(1, "steps_on_pyramid_track_round$eclipse", $player_id);
        } else {
            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} stays on top of pyramid track'), array(
                'player_id' => $player_id,
                'player_name' => self::getActivePlayerName(),
            ));
        }

        if ($level == 3) {

            $black = (int)self::getGameStateValue('eclipseDiscBlack');
            $white = (int)self::getGameStateValue('eclipseDiscWhite');

            self::setGameStateValue('canBuildPyramidTiles', 0);
            self::setGameStateInitialValue('eclipse', 3);

            if ($white < $black) {
                self::setGameStateValue('eclipseDiscWhite', (int)self::getGameStateValue('eclipseDiscBlack'));
                self::incGameStateValue('eclipseDiscWhite', -1);
                $this->advanceCalenderTrack(clienttranslate('The light disc moved directly to the position of the black disc'));
            }
        } else {
            self::incGameStateValue('canBuildPyramidTiles', -1);
        }
        $canBuildPyramidTiles = (int)self::getGameStateValue('canBuildPyramidTiles');

        self::setGameStateValue('isConstruction', 1);

        if ($queueCount > 0) {
            $this->gamestate->nextState("action");
        } else if ($canBuildPyramidTiles > 0) {
            $this->gamestate->nextState("construction");
        } else {
            self::setGameStateValue('isConstruction', 0);

            if ($this->isTechAquired(5)) {
                $this->collectResource($player_id, 3, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 11)'));
            }
            if ($this->isTechAquired(8)) {
                $this->collectResource($player_id, 1, 'temple_choose', $source, clienttranslate('${player_name} got ${amount}${token_temple_choose} extra (technology tile 17)'));
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_choose',1)";
                self::DbQuery($sql);

                if ($countWorkers == 1) {
                    self::incGameStateValue('upgradeWorkers', 1);
                } else if ($countWorkers == 2) {
                    self::incGameStateValue('upgradeWorkers', 1);
                } else if ($countWorkers >= 3) {
                    self::incGameStateValue('upgradeWorkers', 2);
                }

                $this->gamestate->nextState("action");
            } else {
                $this->boardgetUpgrades($countWorkers);
            }
        }
    }

    function checkPyramidField($player_id, $source, $type, $pyramidTileType)
    {
        if (strcasecmp($type, $pyramidTileType) == 0) {
            if (strcmp(PYRAMID_TYPE_BLUE_TEMPLE, $pyramidTileType) == 0) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_blue',1)";
                self::DbQuery($sql);
                $this->collectResource($player_id, 1, 'temple_blue', $source);
            } else if (strcmp(PYRAMID_TYPE_RED_TEMPLE, $pyramidTileType) == 0) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_red',1)";
                self::DbQuery($sql);
                $this->collectResource($player_id, 1, 'temple_red', $source);
            } else if (strcmp(PYRAMID_TYPE_GREEN_TEMPLE, $pyramidTileType) == 0) {
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('temple_green',1)";
                self::DbQuery($sql);
                $this->collectResource($player_id, 1, 'temple_green', $source);
            }
            return true;
        }
        return false;
    }

    function checkDecoration($direction, $level, $exception = true)
    {
        if ($direction == "left") {

            if ($level == 1) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 4");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 8");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 2) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 103");

                if (!($pyramidTaken0)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 3) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 200");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 202");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            }
        } else if ($direction == "top") {

            if ($level == 1) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 1");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 2");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 2) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 101");

                if (!($pyramidTaken0)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 3) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 200");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 201");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            }
        } else if ($direction == "right") {

            if ($level == 1) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 7");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 11");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 2) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 105");

                if (!($pyramidTaken0)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 3) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 201");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 203");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            }
        } else if ($direction == "bottom") {

            if ($level == 1) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 13");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 14");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 2) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 107");

                if (!($pyramidTaken0)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            } else if ($level == 3) {
                $pyramidTaken0 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 202");
                $pyramidTaken1 = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'pyra_rotate_%' AND `card_location_arg` = 203");

                if (!($pyramidTaken0 && $pyramidTaken1)) {
                    if ($exception) {
                        throw new BgaUserException(self::_("This move is not possible."));
                    }
                    return false;
                }
            }
        }
        return true;
    }

    function buildDecoration($decorationWrapper, $decorationTile)
    {
        self::checkAction('buildDecoration');

        $player_id = self::getActivePlayerId();
        $source = $decorationWrapper;
        $selected_board_id_to = (int)self::getGameStateValue('selected_board_id_to');
        $sql = "SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `locked` = false AND `actionboard_id`=$selected_board_id_to";
        $countWorkers = (int)self::getUniqueValueFromDB($sql);
        $target = 'actionBoard_' . $selected_board_id_to;

        $direction = explode("_", $decorationWrapper)[2];
        $level = (int)explode("_", $decorationWrapper)[3];

        $isAlreadyUsed = self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'deco_p_$direction' AND `card_location_arg` = $level");

        if ($isAlreadyUsed) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        if ($level > 0) {
            $before = $level - 1;
            $isBefore = (int)self::getUniqueValueFromDB("SELECT * FROM `card` WHERE `card_location` like 'deco_p_$direction' AND `card_location_arg` = $before");

            if ($isBefore) {
                $this->checkDecoration($direction, $level);
            } else {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }

        //all checks done
        $gold = 4 - $countWorkers;
        if ($gold < 1) {
            $gold = 1;
        }
        $this->payResource($player_id, -$gold, 'gold', $target);

        $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'decorationTiles' AND `card_type_arg` = $decorationTile";
        $card_id_decorationTiles = (int)self::getUniqueValueFromDB($sql);
        $decorationTileCard = $this->cards->getCard($card_id_decorationTiles);

        $this->cards->pickCardsForLocation(1, $decorationTileCard['location'], "deco_p_$direction", $level);

        self::notifyAllPlayers("buildDecoration", clienttranslate('${player_name} adds a decoration tile to the pyramid'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'decorationTile' => $decorationTile,
            'decorationWrapper' => $decorationWrapper,
        ));

        self::incStat(1, "build_decoration", $player_id);

        $decorationTile_values = $this->decorationTiles[$decorationTile]['values'];

        if ($direction == "left" || $direction == "bottom") {
            $temp = array_pop($decorationTile_values);
            array_unshift($decorationTile_values, $temp);
        }

        $vp = 0;

        $values = array();
        $values[0] = "";
        $values[1] = "";

        if ($level == 0) {
            $pyramidBottomDecoration_values = $this->pyramidBottomDecoration[$direction]['values'];
            $values[0] = $pyramidBottomDecoration_values[0];
            $values[1] = $pyramidBottomDecoration_values[1];
        } else {
            if ($direction == "left") {
                $column = $level - 1;
                $values[0] = self::getUniqueValueFromDB("SELECT column$column FROM `pyramid` WHERE `id` = 4");
                $values[1] = self::getUniqueValueFromDB("SELECT column$column FROM `pyramid` WHERE `id` = 5");
            } else if ($direction == "top") {
                $row = $level;
                $values[0] = self::getUniqueValueFromDB("SELECT column3 FROM `pyramid` WHERE `id` = $row");
                $values[1] = self::getUniqueValueFromDB("SELECT column4 FROM `pyramid` WHERE `id` = $row");
            } else if ($direction == "right") {
                $column = 8 - $level;
                $values[0] = self::getUniqueValueFromDB("SELECT column$column FROM `pyramid` WHERE `id` = 4");
                $values[1] = self::getUniqueValueFromDB("SELECT column$column FROM `pyramid` WHERE `id` = 5");
            } else if ($direction == "bottom") {
                $row = 9 - $level;
                $values[0] = self::getUniqueValueFromDB("SELECT column3 FROM `pyramid` WHERE `id` = $row");
                $values[1] = self::getUniqueValueFromDB("SELECT column4 FROM `pyramid` WHERE `id` = $row");
            }
        }

        $this->collectResource($player_id, 3, 'vp', $source);
        self::incStat(3, "summary_decoration", $player_id);

        $temple = array();
        $temple[0] = "";
        $temple[1] = "";

        for ($i = 0; $i < 2; $i++) {
            if (strcasecmp($values[$i], $decorationTile_values[$i]) == 0) {
                $vp++;
                if (strcmp(PYRAMID_TYPE_BLUE_TEMPLE, $decorationTile_values[$i]) == 0) {
                    $temple[$i] = 'temple_blue';
                } else if (strcmp(PYRAMID_TYPE_RED_TEMPLE, $decorationTile_values[$i]) == 0) {
                    $temple[$i] = 'temple_red';
                } else if (strcmp(PYRAMID_TYPE_GREEN_TEMPLE, $decorationTile_values[$i]) == 0) {
                    $temple[$i] = 'temple_green';
                }
            }
        }

        if ($temple[0] != "" && $temple[1] == "") {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('$temple[0]',1)";
            self::DbQuery($sql);
            $this->collectResource($player_id, 1, $temple[0], $source);
        } else if ($temple[0] == "" && $temple[1] != "") {
            $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('$temple[1]',1)";
            self::DbQuery($sql);
            $this->collectResource($player_id, 1, $temple[1], $source);
        } else if ($temple[0] != "" && $temple[1] != "") {

            if ($temple[0] == $temple[1]) {
                for ($i = 0; $i < 2; $i++) {
                    $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('$temple[0]',1)";
                    self::DbQuery($sql);
                }
                $this->collectResource($player_id, 2, $temple[0], $source);
            } else {
                $short1 = explode("_", $temple[0])[1];
                $short2 = explode("_", $temple[1])[1];
                $value = "deco_" . $short1 . "_" . $short2;
                $sql = "INSERT INTO `temple_queue`(`queue`, `referrer`) VALUES ('$value',1)";
                self::DbQuery($sql);
                $this->collectResource($player_id, 1, $temple[0], $source);
                $this->collectResource($player_id, 1, $temple[1], $source);
            }
        }

        $this->collectResource($player_id, $vp, 'vp', $source);
        self::incStat($vp, "summary_decoration", $player_id);
        self::incStat($vp, "decoration_same_symbol", $player_id);

        if ($this->isTechAquired(4)) {
            $this->collectResource($player_id, 4, 'vp', $source, clienttranslate('${player_name} got ${amount}${token_vp} extra (technology tile 9)'));
        }

        $sql = "SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        if ($step < 9) {
            $sql = "UPDATE `player` SET `pyramid_track`  = `pyramid_track` + 1 WHERE player_id = $player_id";
            self::DbQuery($sql);
        }

        $sql = "SELECT `pyramid_track` FROM `player` WHERE `player_id` = $player_id";
        $step = (int)self::getUniqueValueFromDB($sql);

        self::notifyAllPlayers("stepPyramidTrack", clienttranslate('${player_name} advanced one space on Pyramid track'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'step' => $step,
        ));

        $eclipse = (int)self::getGameStateValue('eclipse');
        self::incStat(1, "steps_on_pyramid_track_round$eclipse", $player_id);

        self::setGameStateValue('isDecoration', 1);
        self::incGameStateValue('upgradeWorkers', 1);

        $sql = "SELECT count(*) FROM `temple_queue`";
        $queueCount = (int)self::getUniqueValueFromDB($sql);

        if ($queueCount > 0) {
            $this->gamestate->nextState("action");
        } else {
            $this->gamestate->nextState("upgrade_workers");
        }
    }

    function paySalary($cocoa, $freeCocoa)
    {
        self::checkAction('paySalary');
        $player_id = $this->getCurrentPlayerId();

        if ($freeCocoa) {
            $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'discoveryTiles' AND `card_type_arg` in (45,46,47) and `card_location_arg`= $player_id and `card_location` = 'hand' limit 1";
            $id = (int)self::getUniqueValueFromDB($sql);
            if (!$id) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
            $this->useDiscoveryTile($id, true);
        } else {
            $target = 'player_table_' . $player_id;
            $this->payResource($player_id, -$cocoa, 'cocoa', $target);

            $smallWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `worker_power` <= 3 AND `actionboard_id` != -1");
            $bigWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id AND `worker_power` >= 4 AND `actionboard_id` != -1");
            $max = $smallWorkers + $bigWorkers * 2;

            $vp = 3 * ($max - $cocoa);
            $this->payResource($player_id, -$vp, 'vp', $target);
        }

        $this->gamestate->setPlayerNonMultiactive($player_id, 'next');
        self::giveExtraTime($player_id);
    }

    function chooseStartingTile($startingTile0, $startingTile1, $wood, $stone, $gold)
    {
        self::checkAction('chooseStartingTile');
        $player_id = $this->getCurrentPlayerId();

        if ($this->isDraftMode()) {
            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = $startingTile0 AND `card_location` = 'sChoose_all'";
            $isTile0Valid = (int)self::getUniqueValueFromDB($sql);

            if (!$isTile0Valid || $startingTile1 != 0) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        } else {
            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = $startingTile0 AND `card_location` = 'sChoose_$player_id'";
            $isTile0Valid = (int)self::getUniqueValueFromDB($sql);
            $sql = "SELECT `card_id` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_type_arg` = $startingTile1 AND `card_location` = 'sChoose_$player_id'";
            $isTile1Valid = (int)self::getUniqueValueFromDB($sql);

            if (!$isTile0Valid || !$isTile1Valid || $startingTile0 == $startingTile1) {
                throw new BgaUserException(self::_("This move is not possible."));
            }
        }


        $maxResources = 0;

        if ($startingTile0 == 6 || $startingTile0 == 17) {
            $maxResources += 2;
        }
        if ($startingTile1 == 6 || $startingTile1 == 17) {
            $maxResources += 2;
        }

        $max = $wood + $stone + $gold;

        if ($max > $maxResources) {
            throw new BgaUserException(self::_("This move is not possible."));
        }

        // all checks done

        if ($this->isDraftMode()) {

            $firstTile = self::getUniqueValueFromDB("SELECT `startingTile0` FROM `player` WHERE `player_id` = $player_id");

            if ($firstTile != null) {

                self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile0 WHERE player_id = $player_id");
            } else {

                self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
            }

            if ($startingTile0 == 3 || $startingTile0 == 13) {
                $discoveryCard = $this->cards->getCardsInLocation('sChoose_all', $startingTile0);
                $discoveryTile = (int)(array_shift($discoveryCard)['type_arg']);

                if ($firstTile != null) {
                    self::DbQuery("UPDATE `player` SET startingDiscovery1 = $discoveryTile WHERE player_id = $player_id");
                } else {
                    self::DbQuery("UPDATE `player` SET startingDiscovery0 = $discoveryTile WHERE player_id = $player_id");
                }
            }
            self::DbQuery("UPDATE `player` SET startingResourceWood = startingResourceWood + $wood WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingResourceStone = startingResourceStone + $stone WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingResourceGold = startingResourceGold + $gold WHERE player_id = $player_id");

            if ($firstTile != null) {
                self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile0 WHERE player_id = $player_id");
                $discoveryTile = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery1` FROM `player` WHERE `player_id` = $player_id");
            } else {
                self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
                $discoveryTile = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery0` FROM `player` WHERE `player_id` = $player_id");
            }

            self::DbQuery("UPDATE `card` SET card_location  = 'startTiles_deck' WHERE card_type = 'startingTiles' AND card_type_arg = $startingTile0");

            self::notifyAllPlayers("choosedStartingTilesDraft", clienttranslate('${player_name} chose one starting tile'), array(
                'player_id' => $player_id,
                'player_name' => self::getCurrentPlayerName(),
                'startingTile0' => $startingTile0,
                'discoveryTile' => $discoveryTile,
            ));

            $this->gamestate->nextState("next_player");

        } else {

            self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile1 WHERE player_id = $player_id");

            if ($startingTile0 == 3 || $startingTile0 == 13) {
                $discoveryCard = $this->cards->getCardsInLocation('sChoose_' . $player_id, $startingTile0);
                $discoveryTile = (int)(array_shift($discoveryCard)['type_arg']);
                self::DbQuery("UPDATE `player` SET startingDiscovery0 = $discoveryTile WHERE player_id = $player_id");
            }
            if ($startingTile1 == 3 || $startingTile1 == 13) {
                $discoveryCard = $this->cards->getCardsInLocation('sChoose_' . $player_id, $startingTile1);
                $discoveryTile = (int)(array_shift($discoveryCard)['type_arg']);
                self::DbQuery("UPDATE `player` SET startingDiscovery1 = $discoveryTile WHERE player_id = $player_id");
            }
            self::DbQuery("UPDATE `player` SET startingResourceWood = $wood WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingResourceStone = $stone WHERE player_id = $player_id");
            self::DbQuery("UPDATE `player` SET startingResourceGold = $gold WHERE player_id = $player_id");

            self::notifyAllPlayers("messageOnly", clienttranslate('${player_name} chose the starting tiles'), array(
                'player_id' => $player_id,
                'player_name' => self::getCurrentPlayerName(),
            ));

            $this->gamestate->setPlayerNonMultiactive($player_id, 'next');
            self::giveExtraTime($player_id);
        }
    }

    function undo()
    {
        self::checkAction('undo');
        $this->undoRestorePoint();
    }

    function noUndo()
    {
        self::checkAction('noUndo');
        $this->gamestate->nextState("pass");
    }

    function enableUndo($checked)
    {
        $player_id = $this->getCurrentPlayerId();
        $checked = (int) $checked;
        self::DbQuery("UPDATE `player` SET enableUndo = $checked WHERE player_id = $player_id");
        self::notifyAllPlayers("enableUndo", '', array());
    }

    function enableAuto($checked)
    {
        $player_id = $this->getCurrentPlayerId();
        $checked = (int) $checked;
        self::DbQuery("UPDATE `player` SET enableAuto = $checked WHERE player_id = $player_id");
        self::notifyAllPlayers("enableAuto", '', array());
    }

    function placeWorker($board_id, $board_pos)
    {
        self::checkAction('placeWorker');
        $player_id = $this->getActivePlayerId();

        if (!in_array($board_id, $this->getPossibleBoards())) {
            throw new BgaUserException(self::_("This move is not possible."));
        }
        $countWorkers = (int)self::getUniqueValueFromDB("SELECT count(*) FROM `map` WHERE `player_id` = $player_id");

        $sql = "INSERT INTO `map`(`actionboard_id`, `player_id`, `worker_id`,`worker_power`, `locked`, `worship_pos`) VALUES";
        $values = array();
        $values[] = "($board_pos,'" . $player_id . "','$countWorkers','1',0,0)";
        $sql .= implode($values, ',');
        self::DbQuery($sql);

        $board_name = $this->actionBoards[$board_id]["name"];
        $map = $this->getAllDatas()['map'];

        self::notifyAllPlayers("placeWorker", clienttranslate('${player_name} placed a worker on ${board_name} (${board_id})'), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'board_name' => $board_name,
            'board_id' => $board_id,
            'board_pos' => $board_pos,
            'map' => $map,
            'worker_id' => $countWorkers,
        ));

        if ($countWorkers >= 3) {
            $this->gamestate->nextState("get_bonus");
        }

    }


//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*

    Example for game state "MyGameState":

    function argMyGameState()
    {
        // Get some values from the current game situation in database...

        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */

    /*

    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...

        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:

        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
    */

    function zombieTurn($state, $active_player)
    {
        $statename = $state['name'];
        $player_id = $active_player;

        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                case 'claim_starting_discovery_tiles':
                    $this->pass();
                    return;
                case 'choose_starting_tiles_draft':
                    $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_location` = 'sChoose_all' limit 1";
                    $startingTile0 = (int)self::getUniqueValueFromDB($sql);

                    $firstTile = self::getUniqueValueFromDB("SELECT `startingTile0` FROM `player` WHERE `player_id` = $player_id");

                    if ($firstTile != null) {

                        self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile0 WHERE player_id = $player_id");
                    } else {

                        self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
                    }

                    if ($startingTile0 == 3 || $startingTile0 == 13) {
                        $discoveryCard = $this->cards->getCardsInLocation('sChoose_all', $startingTile0);
                        $discoveryTile = (int)(array_shift($discoveryCard)['type_arg']);
                        self::DbQuery("UPDATE `player` SET startingDiscovery0 = $discoveryTile WHERE player_id = $player_id");
                    }
                    self::DbQuery("UPDATE `player` SET startingResourceWood = startingResourceWood + 0 WHERE player_id = $player_id");
                    self::DbQuery("UPDATE `player` SET startingResourceStone = startingResourceStone + 0 WHERE player_id = $player_id");
                    self::DbQuery("UPDATE `player` SET startingResourceGold = startingResourceGold + 0 WHERE player_id = $player_id");

                    if ($firstTile != null) {
                        self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile0 WHERE player_id = $player_id");
                        $discoveryTile = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery1` FROM `player` WHERE `player_id` = $player_id");
                    } else {
                        self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
                        $discoveryTile = (int)self::getUniqueValueFromDB("SELECT `startingDiscovery0` FROM `player` WHERE `player_id` = $player_id");
                    }

                    self::DbQuery("UPDATE `card` SET card_location  = 'startTiles_deck' WHERE card_type = 'startingTiles' AND card_type_arg = $startingTile0");

                    self::notifyAllPlayers("choosedStartingTilesDraft", clienttranslate('${player_name} chose one starting tile'), array(
                        'player_id' => $player_id,
                        'player_name' => self::getActivePlayerName(),
                        'startingTile0' => $startingTile0,
                        'discoveryTile' => $discoveryTile,
                    ));

                    $this->gamestate->nextState("next_player");

                    return;
                case 'starting_tiles_place_workers':
                    $workersLeft = count($this->getPossibleBoards());
                    if ($workersLeft > 3) {
                        $workersLeft = 3;
                    }

                    for ($i = 0; $i < $workersLeft; $i++) {
                        $board_id = $this->getPossibleBoards()[0];
                        $board_pos = (int)self::getUniqueValueFromDB("SELECT `card_location_arg` FROM `card` WHERE `card_type` = 'actionBoards' AND `card_id` = $board_id");
                        $sql = "INSERT INTO `map`(`actionboard_id`, `player_id`, `worker_id`,`worker_power`, `locked`, `worship_pos`) VALUES";
                        $values = array();
                        $values[] = "($board_pos,'" . $player_id . "','$i','1',0,0)";
                        $sql .= implode($values, ',');
                        self::DbQuery($sql);

                        $board_name = $this->actionBoards[$board_id]["name"];
                        $map = $this->getAllDatas()['map'];

                        self::notifyAllPlayers("placeWorker", clienttranslate('${player_name} placed a worker on ${board_name} (${board_id})'), array(
                            'player_id' => $player_id,
                            'player_name' => self::getActivePlayerName(),
                            'board_name' => $board_name,
                            'board_id' => $board_id,
                            'board_pos' => $board_pos,
                            'map' => $map,
                            'worker_id' => $i,
                        ));
                    }
                    $sql = "SELECT `player_no` FROM `player` WHERE `player_id` = $player_id";
                    $player_no = (int)self::getUniqueValueFromDB($sql);

                    $sql = "SELECT count(*) FROM `player`";
                    $count_player = (int)self::getUniqueValueFromDB($sql);

                    if ($player_no == $count_player) {
                        $this->setNonPlayerWorkers();
                        $this->gamestate->nextState("zombiePass");
                        $this->activeNextPlayer();
                        $this->gamestate->nextState("playerTurn");
                    } else {
                        $this->gamestate->nextState("zombiePass");
                        $this->activeNextPlayer();
                        $this->gamestate->nextState("place_workers");
                    }
                    return;
                default:
                    $this->gamestate->nextState("zombiePass");
                    break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            switch ($statename) {
                case 'choose_starting_tiles':
                    $sql = "SELECT `card_type_arg` FROM `card` WHERE `card_type` = 'startingTiles' AND `card_location` = 'sChoose_$player_id'";
                    $startingTiles = self::getObjectListFromDB($sql, true);

                    $startingTile0 = $startingTiles[0];
                    $startingTile1 = $startingTiles[1];

                    self::DbQuery("UPDATE `player` SET startingTile0 = $startingTile0 WHERE player_id = $player_id");
                    self::DbQuery("UPDATE `player` SET startingTile1 = $startingTile1 WHERE player_id = $player_id");
                    break;
            }

            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive($active_player, 'next');

            return;
        }

        throw new feException("Zombie mode not supported at this game state: " . $statename);
    }

///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:

        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.

    */

    function upgradeTableDb($from_version)
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345

        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
        if ($from_version <= 2005071758) {
            // ! important ! Use DBPREFIX_<table_name> for all tables
            $sql = "CREATE TABLE IF NOT EXISTS `DBPREFIX_discovery_queue` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `queue` varchar(16) NOT NULL, `referrer` INT NOT NULL DEFAULT '0', PRIMARY KEY (`id`))";
            self::applyDbUpgradeToAllDB($sql);
        }
        if ($from_version <= 2005251328) {
            // ! important ! Use DBPREFIX_<table_name> for all tables
            $sql = "ALTER TABLE DBPREFIX_player ADD `enableUndo` INT UNSIGNED NOT NULL DEFAULT '0'";
            self::applyDbUpgradeToAllDB($sql);
            $sql = "ALTER TABLE DBPREFIX_player ADD `enableAuto` INT UNSIGNED NOT NULL DEFAULT '1'";
            self::applyDbUpgradeToAllDB($sql);
        }
    }
}
