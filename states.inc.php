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
 * states.inc.php
 *
 * teotihuacan game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!


$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array("" => STATE_CHOOSE_STARTING_TILES)
    ),

    //////////////////////////////////////// GAME TURN /////////////////////////////////////////

    STATE_CHOOSE_STARTING_TILES => array(
        "name" => "choose_starting_tiles",
        "description" => clienttranslate('All Players must choose their starting tiles'),
        "descriptionmyturn" => clienttranslate('${you} must select two starting tiles'),
        "type" => "multipleactiveplayer",
        'action' => 'stGameStart',
        "possibleactions" => array(
            "chooseStartingTile",
            "choose_resource",
        ),
        "transitions" => array("next" => STATE_PREPARE_STARTING_TILES_BONUS, "playerTurn" => STATE_START_TURN, "draft" => STATE_CHOOSE_STARTING_TILES_DRAFT)
    ),

    STATE_CHOOSE_STARTING_TILES_DRAFT => array(
        "name" => "choose_starting_tiles_draft",
        "description" => clienttranslate('${actplayer} must choose one starting tile'),
        "descriptionmyturn" => clienttranslate('${you} must select one starting tile'),
        "type" => "activeplayer",
        "possibleactions" => array(
            "chooseStartingTile",
        ),
        "transitions" => array("next_player" => STATE_STARTING_TILES_DRAFT_CALCULATE_NEXT, "zombiePass" => STATE_STARTING_TILES_DRAFT_CALCULATE_NEXT)
    ),

    STATE_STARTING_TILES_DRAFT_CALCULATE_NEXT => array(
        "name" => "starting_tiles_draft_calculate_next",
        "description" => '',
        "type" => "game",
        'action' => 'stCalculateNextDraftPlayer',
        "transitions" => array("bonus" => STATE_PREPARE_STARTING_TILES_BONUS, "playerTurn" => STATE_START_TURN, "draft" => STATE_CHOOSE_STARTING_TILES_DRAFT)
    ),

    STATE_PREPARE_STARTING_TILES_BONUS => array(
        "name" => "prepare_starting_tiles_bonus",
        "description" => '',
        "type" => "game",
        'action' => 'prepareStartingTilesBonus',
        "updateGameProgression" => true,
        "transitions" => array("place_workers" => STATE_STARTING_TILES_PLACE_WORKERS)
    ),

    STATE_STARTING_TILES_PLACE_WORKERS => array(
        "name" => "starting_tiles_place_workers",
        "description" => clienttranslate('${actplayer} must place the workers'),
        "descriptionmyturn" => clienttranslate('${you} must place the workers'),
        "type" => "activeplayer",
        'args' => 'getPossibleBoards',
        "possibleactions" => array(
            "placeWorker",
        ),
        "transitions" => array("get_bonus" => STATE_GET_STARTING_TILES_BONUS_AUTO, "zombiePass" => STATE_ZOMBIE, "playerTurn" => STATE_PLAYER_TURN)
    ),

    STATE_ZOMBIE => array(
        "name" => "zombie",
        "description" => '',
        "type" => "game",
        "transitions" => array("place_workers" => STATE_STARTING_TILES_PLACE_WORKERS, "playerTurn" => STATE_PLAYER_TURN)
    ),

    STATE_GET_STARTING_TILES_BONUS_AUTO => array(
        "name" => "get_starting_tiles_bonus_auto",
        "description" => '',
        "type" => "game",
        'action' => 'setStartingTilesBonus',
        "transitions" => array("calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE)
    ),

    STATE_CALCULATE_NEXT_TILES_BONUS => array(
        "name" => "calculate_next_tiles_bonus",
        "description" => '',
        "type" => "game",
        'action' => 'calculateNextBonus',
        "updateGameProgression" => true,
        "transitions" => array("playerTurn" => STATE_START_TURN, "claim_starting_Discovery" => STATE_CLAIM_STARTING_DISCOVERY_TILES, "place_workers" => STATE_STARTING_TILES_PLACE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS)
    ),

    STATE_CLAIM_STARTING_DISCOVERY_TILES => array(
        "name" => "claim_starting_discovery_tiles",
        "description" => clienttranslate('${actplayer} can claim their discovery tile from starting tile'),
        "descriptionmyturn" => clienttranslate('${you} can claim your discovery tile from starting tile'),
        "type" => "activeplayer",
        "args" => "getWorshipInfo",
        "possibleactions" => array(
            "useDiscoveryTile",
            "claimDiscovery",
            "pass",
        ),
        "transitions" => array("calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE)
    ),

    //////////////////////////////////////// GAME TURN /////////////////////////////////////////

    STATE_START_TURN => array(
        "name" => "startTurn",
        "description" => '',
        "type" => "game",
        "action" => "stStartTurn",
        "transitions" => array("playerTurn" => STATE_PLAYER_TURN)
    ),

    STATE_PLAYER_TURN => array(
        "name" => "playerTurn",
        "description" => clienttranslate('${actplayer} must move a worker'),
        "descriptionmyturn" => clienttranslate('${you} must move one worker ${lockedWorkersText}'),
        "type" => "activeplayer",
        "args" => "getGlobalVariables",
        "updateGameProgression" => true,
        "possibleactions" => array(
            "useDiscoveryTile",
            "unlockAllWorkers",
            "selectDice",
            "selectBoard",
            "showBoardActions"
        ),
        "transitions" => array("showBoardActions" => STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "playerTurn" => STATE_PLAYER_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE)
    ),

    STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS => array(
        "name" => "playerTurn_show_board_actions",
        "description" => clienttranslate('${actplayer} must move a worker'),
        "descriptionmyturn" => clienttranslate('${you} must choose an action'),
        "type" => "activeplayer",
        "args" => "isPalaceTechAquired",
        "possibleactions" => array(
            "useDiscoveryTile",
            "doWorshipOnBoard",
            "doMainActionOnBoard",
            "collectCocoa",
            "cancelMoveToBoard"
        ),
        "transitions" => array("choose_worship" => STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS, "board_action" => STATE_PLAYER_TURN_BOARD_ACTION, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "canceled" => STATE_PLAYER_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_BOARD_ACTION => array(
        "name" => "playerTurn_board_action",
        "description" => "",
        "type" => "game",
        "possibleactions" => array(
            "useDiscoveryTile",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "decoration" => STATE_PLAYER_TURN_DECORATION, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "nobles" => STATE_PLAYER_TURN_NOBLES, "alchemy" => STATE_PLAYER_TURN_ALCHEMY, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_DECORATION => array(
        "name" => "playerTurn_decoration",
        "description" => clienttranslate('${actplayer} must build decoration tiles'),
        "descriptionmyturn" => clienttranslate('${you} must build decoration tiles'),
        "type" => "activeplayer",
        "args" => "getCountWorkers",
        "possibleactions" => array(
            "useDiscoveryTile",
            "buildDecoration",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_CONSTRUCTION => array(
        "name" => "playerTurn_construction",
        "description" => clienttranslate('${actplayer} can build pyramid tiles'),
        "descriptionmyturn" => clienttranslate('${you} can build pyramid tiles'),
        "type" => "activeplayer",
        "args" => "isConstructionWorkerTechAquired",
        "possibleactions" => array(
            "useDiscoveryTile",
            "buildPyramid",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_ALCHEMY => array(
        "name" => "playerTurn_alchemy",
        "description" => clienttranslate('${actplayer} must acquire a technology'),
        "descriptionmyturn" => clienttranslate('${you} must acquire a technology'),
        "type" => "activeplayer",
        "args" => "getTechnologyRow",
        "possibleactions" => array(
            "useDiscoveryTile",
            "acquireTechnology",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_NOBLES => array(
        "name" => "playerTurn_nobles",
        "description" => clienttranslate('${actplayer} must place a Building'),
        "descriptionmyturn" => '',
        "type" => "activeplayer",
        "possibleactions" => array(
            "useDiscoveryTile",
            "nobles",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "choose_row" => STATE_PLAYER_TURN_NOBLES_BUILD, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_NOBLES_BUILD => array(
        "name" => "playerTurn_nobles_choose_row",
        "description" => clienttranslate('${actplayer} must choose a row'),
        "descriptionmyturn" => clienttranslate('${you} must choose a row'),
        "type" => "activeplayer",
        "args" => "getNoblesRows",
        "possibleactions" => array(
            "useDiscoveryTile",
            "placeBuilding",
        ),
        "transitions" => array("avenue_of_dead" => STATE_PLAYER_TURN_AVENUE_OF_DEAD, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS => array(
        "name" => "playerTurn_choose_worship_actions",
        "description" => clienttranslate('${actplayer} must choose a worship action'),
        "descriptionmyturn" => clienttranslate('${you} must choose an action'),
        "type" => "activeplayer",
        "args" => "isPalaceTechAquired",
        "possibleactions" => array(
            "useDiscoveryTile",
            "worshipAction",
        ),
        "transitions" => array("action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_WORSHIP_ACTIONS => array(
        "name" => "playerTurn_worship_actions",
        "description" => clienttranslate('${actplayer} ${description}'),
        "descriptionmyturn" => clienttranslate('${you} ${description}'),
        "type" => "activeplayer",
        "args" => "getWorshipInfo",
        "possibleactions" => array(
            "useDiscoveryTile",
            "royalTileAction",
            "stepTemple",
            "claimDiscovery",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "trade" => STATE_PLAYER_TURN_WORSHIP_TRADE, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "pass" => STATE_PLAYER_TURN_CHECK_END_TURN, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "choose_bonus" => STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS, "choose_resources" => STATE_PLAYER_TURN_CHOOSE_TEMPLE_RESOURCES, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_WORSHIP_TRADE => array(
        "name" => "playerTurn_worship_actions_trade",
        "description" => clienttranslate('${actplayer} must trade for royal tile'),
        "descriptionmyturn" => '',
        "type" => "activeplayer",
        "args" => "getTradeInfo",
        "possibleactions" => array(
            "useDiscoveryTile",
            "trade",
        ),
        "transitions" => array("useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS => array(
        "name" => "playerTurn_choose_temple_bonus",
        "description" => clienttranslate('${actplayer} must choose the temple bonus or'),
        "descriptionmyturn" => clienttranslate('${you} must choose the temple bonus or'),
        "type" => "activeplayer",
        "possibleactions" => array(
            "useDiscoveryTile",
            "temple_bonus",
            "claimDiscovery",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "choose_resources" => STATE_PLAYER_TURN_CHOOSE_TEMPLE_RESOURCES, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),
    STATE_PLAYER_TURN_CHOOSE_TEMPLE_RESOURCES => array(
        "name" => "playerTurn_choose_temple_resources",
        "description" => clienttranslate('${actplayer} must choose the temple resource(s)'),
        "descriptionmyturn" => clienttranslate('${you} must choose the temple resource(s)'),
        "type" => "activeplayer",
        "args" => "getMaxResources",
        "possibleactions" => array(
            "choose_resource",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    STATE_PLAYER_TURN_AVENUE_OF_DEAD => array(
        "name" => "playerTurn_avenue_of_dead",
        "description" => clienttranslate('${actplayer} must step on Avenue of Dead'),
        "descriptionmyturn" => clienttranslate('${you} must step on Avenue of Dead'),
        "type" => "activeplayer",
        "possibleactions" => array(
            "stepAvenue",
            "pass",
        ),
        "transitions" => array("check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "pass" => STATE_PLAYER_TURN_CHECK_END_TURN, "choose_bonus" => STATE_PLAYER_TURN_CHOOSE_AVENUE_BONUS, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),
    STATE_PLAYER_TURN_CHOOSE_AVENUE_BONUS => array(
        "name" => "playerTurn_avenue_of_dead_choose_bonus",
        "description" => clienttranslate('${actplayer} can take discovery Tile or pass'),
        "descriptionmyturn" => clienttranslate('${you} can take discovery Tile or'),
        "type" => "activeplayer",
        "possibleactions" => array(
            "useDiscoveryTile",
            "claimDiscovery",
            "pass",
        ),
        "transitions" => array("pass" => STATE_PLAYER_TURN_CHECK_END_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),
    STATE_PLAYER_TURN_UPGRADE_WORKERS => array(
        "name" => "playerTurn_upgrade_workers",
        "description" => clienttranslate('${actplayer} must power up workers (${amount}x)'),
        "descriptionmyturn" => clienttranslate('${you} must power up your workers (${amount}x)'),
        "type" => "activeplayer",
        "args" => "upgradeOnBoardOnly",
        "possibleactions" => array(
            "unlockAllWorkers",
            "upgradeWorker",
        ),
        "transitions" => array("useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "avenue_of_dead" => STATE_PLAYER_TURN_AVENUE_OF_DEAD, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "playerTurn" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),
    STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY => array(
        "name" => "playerTurn_upgrade_workers_buy",
        "description" => clienttranslate('${actplayer} can buy additional power up'),
        "descriptionmyturn" => clienttranslate('do ${you} want additional power up?'),
        "type" => "activeplayer",
        "possibleactions" => array(
            "buyPowerUp",
            "pass",
        ),
        "transitions" => array("useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "avenue_of_dead" => STATE_PLAYER_TURN_AVENUE_OF_DEAD, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),
    STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS => array(
        "name" => "playerTurn_ascension_choose_bonus",
        "description" => clienttranslate('${actplayer} chooses a ascension bonus'),
        "descriptionmyturn" => clienttranslate('${you} must choose a ascension bonus'),
        "type" => "activeplayer",
        "action" => "preAscension",
        "possibleactions" => array(
            "useDiscoveryTile",
            "ascension",
        ),
        "transitions" => array("useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "check_end_turn" => STATE_PLAYER_TURN_CHECK_END_TURN, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    //////////////////////////////////////// DISCOVERY TILE /////////////////////////////////////////

    STATE_PLAYER_TURN_USE_DISCOVERY_TILE => array(
        "name" => "playerTurn_use_discovery_tile",
        "description" => '',
        "type" => "game",
        "possibleactions" => array(
            "choose_resource",
        ),
        "transitions" => array("player_turn" => STATE_PLAYER_TURN, "ascension" => STATE_PLAYER_TURN_ASCENSION_CHOOSE_BONUS, "calculate_next_bonus" => STATE_CALCULATE_NEXT_TILES_BONUS, "construction" => STATE_PLAYER_TURN_CONSTRUCTION, "claim_starting_Discovery" => STATE_CLAIM_STARTING_DISCOVERY_TILES, "pay_salary" => STATE_PAY_SALARY, "choose_row" => STATE_PLAYER_TURN_NOBLES_BUILD, "alchemy" => STATE_PLAYER_TURN_ALCHEMY, "buy" => STATE_PLAYER_TURN_UPGRADE_WORKERS_BUY, "trade" => STATE_PLAYER_TURN_WORSHIP_TRADE, "board_action" => STATE_PLAYER_TURN_BOARD_ACTION, "choose_resources" => STATE_PLAYER_TURN_CHOOSE_TEMPLE_RESOURCES, "upgrade_workers" => STATE_PLAYER_TURN_UPGRADE_WORKERS, "avenue_of_dead" => STATE_PLAYER_TURN_AVENUE_OF_DEAD, "showBoardActions" => STATE_PLAYER_TURN_SHOW_BOARD_ACTIONS, "choose_worship" => STATE_PLAYER_TURN_CHOOSE_WORSHIP_ACTIONS, "action" => STATE_PLAYER_TURN_WORSHIP_ACTIONS, "choose_bonus" => STATE_PLAYER_TURN_CHOOSE_TEMPLE_BONUS, "check_pass" => STATE_PLAYER_TURN_PASS, "zombiePass" => STATE_PLAYER_TURN_CHECK_END_TURN)
    ),

    //////////////////////////////////////// CHECK END TURN /////////////////////////////////////////


    STATE_PLAYER_TURN_CHECK_END_TURN => array(
        "name" => "playerTurn_check_end_turn",
        "description" => '',
        "type" => "game",
        "possibleactions" => array(),
        "action" => "checkEndTurn",
        "transitions" => array("next_player" => STATE_PLAYER_END_TURN, "check_pass" => STATE_PLAYER_TURN_PASS, "zombiePass" => STATE_PLAYER_END_TURN)
    ),
    STATE_PLAYER_TURN_PASS => array(
        "name" => "playerTurn_check_pass",
        "description" => clienttranslate('${actplayer} can end turn or use discovery Tiles'),
        "descriptionmyturn" => clienttranslate('${you} can end your turn or use discovery Tiles'),
        "type" => "activeplayer",
        "action" => "areDiscoveryTilesLeft",
        "possibleactions" => array(
            "useDiscoveryTile",
            "pass",
        ),
        "transitions" => array("pass" => STATE_PLAYER_END_TURN, "useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "zombiePass" => STATE_PLAYER_END_TURN)
    ),
    STATE_PLAYER_END_TURN => array(
        "name" => "playerTurn_end_turn",
        "description" => '',
        "type" => "game",
        "action" => "prepareNextPlayer",
        "possibleactions" => array(),
        "transitions" => array("next_player" => STATE_START_TURN, "pay_salary" => STATE_PAY_SALARY)
    ),

    //////////////////////////////////////// ECLIPSE /////////////////////////////////////////

    STATE_PAY_SALARY => array(
        "name" => "pay_salary",
        "description" => '',
        'description' => clienttranslate('All Players have to pay salary for their workers'),
        'descriptionmyturn' => '',
        "type" => "multipleactiveplayer",
        'action' => 'activateMultiPlayer',
        "args" => "getMaxSalary",
        "possibleactions" => array(
            "useDiscoveryTile",
            'paySalary'
        ),
        "transitions" => array("useDiscoveryTile" => STATE_PLAYER_TURN_USE_DISCOVERY_TILE, "next" => STATE_CHECK_END_GAME)
    ),

    STATE_CHECK_END_GAME => array(
        "name" => "check_end_game",
        "description" => '',
        "type" => "game",
        "possibleactions" => array(),
        "action" => "checkEndGame",
        "updateGameProgression" => true,
        "transitions" => array("next_player" => STATE_START_TURN, "game_end" => 99)
    ),

    // Final state.
    // Please do not modify.
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "updateGameProgression" => true,
        "args" => "argGameEnd"
    )

);



