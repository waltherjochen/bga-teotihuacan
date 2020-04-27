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
 * material.inc.php
 *
 * teotihuacan game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


$this->ascensionInfo = array(
    0 => array("tooltip" => clienttranslate("Score 5 Victory Points")),
    1 => array("tooltip" => clienttranslate("Pay 3 cocoa to advance your marker twice on temples (either once on two different temples, or twice on the same temple)")),
    2 => array("tooltip" => clienttranslate("If you have only 3 workers in play, gain your 4th worker (with a starting power of 3), placing it in the general area of the Palace (1) Action Board. Additionally, gain 2 cocoa.")),
    3 => array("tooltip" => clienttranslate("Advance your marker one step on one of the temples")),
    4 => array("tooltip" => clienttranslate("Gain 5 cocoa")),
);

$this->actionBoards = array(
    ACTION_BOARD_PALACE => array(
        "id" => ACTION_BOARD_PALACE,
        "name" => clienttranslate("Palace"),
        "tooltip" => clienttranslate("The Palace (1) Action Board does NOT have a Main action. When moving a worker here, you may only place it on a Royal tile to take a Worship action or in the general area to take a Collect Cocoa action.")
    ),
    ACTION_BOARD_FOREST => array(
        "id" => ACTION_BOARD_FOREST,
        "name" => clienttranslate("Forest"),
        "tooltip" => clienttranslate("Count the number of your unlocked workers in the general area of the Action Board in question (including the worker you just moved) to select a row on the Action Board. Use the power of your lowest value worker to select a column on the Action Board. Gain the rewards (wood, stone, gold, cocoa, Victory Points, or temple advances) shown in the corresponding cell.")
    ),
    ACTION_BOARD_STONE => array(
        "id" => ACTION_BOARD_STONE,
        "name" => clienttranslate("Stone Quarry"),
        "tooltip" => clienttranslate("Count the number of your unlocked workers in the general area of the Action Board in question (including the worker you just moved) to select a row on the Action Board. Use the power of your lowest value worker to select a column on the Action Board. Gain the rewards (wood, stone, gold, cocoa, Victory Points, or temple advances) shown in the corresponding cell.")
    ),
    ACTION_BOARD_GOLD => array(
        "id" => ACTION_BOARD_GOLD,
        "name" => clienttranslate("Gold Deposits"),
        "tooltip" => clienttranslate("Count the number of your unlocked workers in the general area of the Action Board in question (including the worker you just moved) to select a row on the Action Board. Use the power of your lowest value worker to select a column on the Action Board. Gain the rewards (wood, stone, gold, cocoa, Victory Points, or temple advances) shown in the corresponding cell.")
    ),
    ACTION_BOARD_ALCHEMY => array(
        "id" => ACTION_BOARD_ALCHEMY,
        "name" => clienttranslate("Alchemy"),
        "tooltip" => clienttranslate("Select one Technology tile on the Alchemy (5) Action Board observing the following restrictions:<br><br>- If you have only one worker on this Action Board, you must select from the first row.<br> - If you have two or more workers on this Action Board, you may select from either row.<br> - EXCEPTION: If you have only one worker on this Action Board but its power is 4 or 5, you may still choose from the second row, but doing this will forfeit the power-up you would gain from this action.<br><br> Pay the cost in gold as shown on the selected tile and place one of your Technology markers (wooden discs) on it to mark that you have acquired this Technology. Refer to the Appendix for an explanation of the ongoing benefit of each Technology tile. Any other player who already had a disc on the tile you develop immediately scores 3 Victory Points. You may never develop a Technology you have previously acquired (you may not place a Technology marker on a Technology tile with one of your markers present). <br><br>After placing the Technology marker, advance once on one temple, depending on which column the selected Technology belongs to:{token_temple_blue} for the left column,{token_temple_red} for the centre column,{token_temple_green} for the right column.")
    ),
    ACTION_BOARD_NOBLES => array(
        "id" => ACTION_BOARD_NOBLES,
        "name" => clienttranslate("Nobles"),
        "tooltip" => clienttranslate("Pay 2 wood to take the first available Building from the left side of the Buildings row on the Main Board (it is important that these Buildings are always taken from left to right), and place it in the leftmost available space of one of the Building rows of the Nobles (6) Action Board:<br><br>- If you have 1 worker on this Action Board: Place the Building in the leftmost empty space of the top row. If there are no empty spaces, you may not take this action.<br>- If you have 2 workers on this Action Board: Place it in the leftmost empty space of the centre row. If there are no empty spaces on the second row, place it in the row above, if possible.<br>-If you have 3 workers on this Action Board: Place it in the leftmost empty space of the bottom row. If there are no empty spaces on the third row, place it in one of the rows above, if possible.<br><br>Then, score a number of Victory Points equal to the printed value of the space you covered with the Building, and advance your marker one step on the Avenue of the Dead track, to a maximum of 9.")
    ),
    ACTION_BOARD_DECORATIONS => array(
        "id" => ACTION_BOARD_DECORATIONS,
        "name" => clienttranslate("Decorations"),
        "tooltip" => clienttranslate("Pay 3 gold to select one of the 4 available Decoration tiles. For each additional worker you have on this Action Board you receive a 1 gold discount to the cost (to a minimum of 1 gold). Add the selected Decoration tile to the Pyramid using the following restrictions:<br><br>- It must be placed in one of the marked spaces.<br>- The tile must be placed so that its arrow points towards the centre of the Pyramid.<br>- You can only place on the spaces if there is a layer of Pyramid tiles under both of its squares AND there is a Decoration one step lower<br><br> When placing the tile, check the icons you are covering. For each icon being covered by an icon of the same type on the Decoration tile, score 1 Victory Point. If you scored 1 Victory Point for an icon that is red {token_temple_red}, green {token_temple_green}, or blue {token_temple_blue} on the Decoration tile (the colour of the icon that is being covered does not matter), also advance on the corresponding temple. Then score an additional 3 Victory Points and advance your marker one step on the Pyramid track.")
    ),
    ACTION_BOARD_CONSTRUCTION => array(
        "id" => ACTION_BOARD_CONSTRUCTION,
        "name" => clienttranslate("Construction"),
        "tooltip" => clienttranslate("You may select one of the available Pyramid tiles and add it to the Pyramid for each worker you have on the Construction (8) Action Board. You must add at least one tile.<br><br>- You can place a Pyramid tile on the first (bottom) level, into one of the empty squares, by paying 2 stone. Score 1 Victory Point for each tile added to the first level.<br>-You can place a Pyramid tile on the second level, over the intersection of four tiles of the first level, by paying 2 stone and 1 wood. Score 3 Victory Points for each tile added to the second level.<br>- Same with other levels.<br>-If a tile was placed on the fourth level of the Pyramid, the Pyramid is considered finished. The active player must immediately move the white Calendar disc to the position of the black Calendar disc. This will trigger a final Eclipse, as described in the Eclipse & End of Game section, and end the game.<br><br>You may rotate the tile any way you choose. When placing the tile, check the icons you are covering. For each icon being covered by an icon of the same type on the newly placed Pyramid tile, score 1 Victory Point.<br><br>If you scored 1 Victory Point for an icon that is red {token_temple_red}, green {token_temple_green}, or blue {token_temple_blue} on the newly placed Pyramid tile (the colour of the icon that is being covered does not matter), also advance on the corresponding temple.<br>Then advance your marker one step on the Pyramid track.<br><br>If adding more than one tile to the Pyramid, always fully resolve all effects before adding the next tile, including the scoring of Victory Points, advancement on the Pyramid track, as well as temple advancements, if any.")
    ),
);

$this->temples = array(
    "blue" => array('0:0', '1:r', '1:r', '1:r', '1:r', '2:r', '2:r', '2:vp', '2:r', '4:vp', '0:0', '6:vp'),
    "red" => array('0:0', '1:vp', '1:vp', '2:vp', '2:vp', '2:vp', '3:vp', '3:vp', '4:vp', '5:vp', '0:0', '7:vp'),
    "green" => array('0:0', '1:c', '1:c', '1:c', '2:c', '2:c', '2:c', '3:c', '2:c', '4:c', '0:0', '5:vp')
);

$this->nobles = array(
    "row0" => array(3, 2, 2, 1, 1),
    "row1" => array(5, 4, 3, 3),
    "row2" => array(7, 6, 5)
);
$this->buildings = array(5, 5, 4, 4, 3, 3, 3, 2, 2, 2, 1, 1);
$this->scoringMask = array(0,1,3,6,10,15,21,28);

$this->pyramidBottom = array(
    0 => array("-", "-", "-", "b", "b", "-", "-", "-"),
    1 => array("g", "g", "g", "b", "b", "-", "-", "-"),
    2 => array("-", "-", "-", "b", "-", "r", "r", "r"),
    3 => array("g", "g", "r", "-", "-", "b", "b", "b"),
    4 => array("b", "b", "r", "-", "b", "r", "r", "r"),
    5 => array("-", "-", "r", "-", "b", "g", "g", "g"),
    6 => array("b", "b", "b", "r", "r", "-", "-", "-"),
    7 => array("g", "g", "g", "r", "r", "-", "-", "-"),
);

$this->pyramidTiles = array(
    0 => array("id" => 0, "rotate" => 0, "values" => array("b", "-", "R", "g")),
    1 => array("id" => 1, "rotate" => 0, "values" => array("-", "b", "r", "G")),
    2 => array("id" => 2, "rotate" => 0, "values" => array("-", "r", "b", "G")),
    3 => array("id" => 3, "rotate" => 0, "values" => array("g", "-", "b", "R")),
    4 => array("id" => 4, "rotate" => 0, "values" => array("g", "-", "b", "r")),
    5 => array("id" => 5, "rotate" => 0, "values" => array("g", "-", "r", "b")),
    6 => array("id" => 6, "rotate" => 0, "values" => array("g", "-", "r", "B")),
    7 => array("id" => 7, "rotate" => 0, "values" => array("g", "b", "r", "-")),
    8 => array("id" => 8, "rotate" => 0, "values" => array("g", "B", "r", "-")),
    9 => array("id" => 9, "rotate" => 0, "values" => array("b", "-", "r", "-")),
    10 => array("id" => 10, "rotate" => 0, "values" => array("B", "-", "r", "-")),
    11 => array("id" => 11, "rotate" => 0, "values" => array("g", "-", "r", "-")),
    12 => array("id" => 12, "rotate" => 0, "values" => array("g", "-", "R", "-")),
    13 => array("id" => 13, "rotate" => 0, "values" => array("b", "-", "G", "-")),
    14 => array("id" => 14, "rotate" => 0, "values" => array("b", "-", "g", "-")),
    15 => array("id" => 15, "rotate" => 0, "values" => array("b", "-", "g", "r")),
    16 => array("id" => 16, "rotate" => 0, "values" => array("b", "-", "g", "R")),
    17 => array("id" => 17, "rotate" => 0, "values" => array("g", "-", "b", "r")),
    18 => array("id" => 18, "rotate" => 0, "values" => array("-", "-", "-", "r")),
    19 => array("id" => 19, "rotate" => 0, "values" => array("b", "-", "-", "-")),
    20 => array("id" => 20, "rotate" => 0, "values" => array("-", "-", "-", "g")),
    21 => array("id" => 21, "rotate" => 0, "values" => array("b", "-", "-", "g")),
    22 => array("id" => 22, "rotate" => 0, "values" => array("g", "-", "-", "b")),
    23 => array("id" => 23, "rotate" => 0, "values" => array("-", "-", "g", "r")),
    24 => array("id" => 24, "rotate" => 0, "values" => array("G", "-", "-", "r")),
    25 => array("id" => 25, "rotate" => 0, "values" => array("b", "-", "-", "R")),
    26 => array("id" => 26, "rotate" => 0, "values" => array("b", "-", "-", "r")),
    27 => array("id" => 27, "rotate" => 0, "values" => array("g", "b", "G", "b")),
    28 => array("id" => 28, "rotate" => 0, "values" => array("r", "B", "r", "b")),
    29 => array("id" => 29, "rotate" => 0, "values" => array("g", "r", "g", "R")),
    30 => array("id" => 30, "rotate" => 0, "values" => array("-", "-", "-", "-")),
    31 => array("id" => 31, "rotate" => 0, "values" => array("-", "-", "-", "-")),
);
$this->pyramidBottomDecoration = array(
    'left' => array("id" => 0, "values" => array("b", "r")),
    'top' => array("id" => 1, "values" => array("r", "g")),
    'right' => array("id" => 2, "values" => array("-", "g")),
    'bottom' => array("id" => 3, "values" => array("b", "-")),
);
$this->decorationTiles = array(
    0 => array("id" => 0, "values" => array("B", "R")),
    1 => array("id" => 1, "values" => array("-", "B")),
    2 => array("id" => 2, "values" => array("G", "G")),
    3 => array("id" => 3, "values" => array("B", "G")),
    4 => array("id" => 4, "values" => array("R", "B")),
    5 => array("id" => 5, "values" => array("B", "-")),
    6 => array("id" => 6, "values" => array("R", "R")),
    7 => array("id" => 7, "values" => array("G", "B")),
    8 => array("id" => 8, "values" => array("R", "G")),
    9 => array("id" => 9, "values" => array("G", "-")),
    10 => array("id" => 10, "values" => array("-", "R")),
    11 => array("id" => 11, "values" => array("B", "B")),
    12 => array("id" => 12, "values" => array("G", "R")),
    13 => array("id" => 13, "values" => array("R", "-")),
    14 => array("id" => 14, "values" => array("-", "G")),
);

$this->templeBonusTiles = array(
    0 => array(
        "id" => 0,
        "tooltip" => clienttranslate("Score your highest scoring mask set one more time."),
        "bonus" => array("mask" => 1, "technology" => 0, "vp15" => 0, "vp_ad" => 0, "vp_bonus" => 0, "vp_discovery" => 0, "vp_workers" => 0)),
    1 => array(
        "id" => 1,
        "tooltip" => clienttranslate("Score 5 Victory Points for each Technology tile with one of your markers."),
        "bonus" => array("mask" => 0, "technology" => 1, "vp15" => 0, "vp_ad" => 0, "vp_bonus" => 0, "vp_discovery" => 0, "vp_workers" => 0)),
    2 => array(
        "id" => 2,
        "tooltip" => clienttranslate("Score 15 Victory Points"),
        "bonus" => array("mask" => 0, "technology" => 0, "vp15" => 1, "vp_ad" => 0, "vp_bonus" => 0, "vp_discovery" => 0, "vp_workers" => 0)),
    3 => array(
        "id" => 3,
        "tooltip" => clienttranslate("Score 3 Victory Points for each step you progressed on the Avenue of the Dead track."),
        "bonus" => array("mask" => 0, "technology" => 0, "vp15" => 0, "vp_ad" => 1, "vp_bonus" => 0, "vp_discovery" => 0, "vp_workers" => 0)),
    4 => array(
        "id" => 4,
        "tooltip" => clienttranslate("Score 9 Victory Points for each Bonus tile you reached, including this tile."),
        "bonus" => array("mask" => 0, "technology" => 0, "vp15" => 0, "vp_ad" => 0, "vp_bonus" => 1, "vp_discovery" => 0, "vp_workers" => 0)),
    5 => array(
        "id" => 5,
        "tooltip" => clienttranslate("Score 2 Victory Points for each nonmask Discovery tile you have (used or unused)."),
        "bonus" => array("mask" => 0, "technology" => 0, "vp15" => 0, "vp_ad" => 0, "vp_bonus" => 0, "vp_discovery" => 1, "vp_workers" => 0)),
    6 => array(
        "id" => 6,
        "tooltip" => clienttranslate("Score for your workers: for each worker with 1-3 power, score 4 Victory Points; for each worker with 4-5 power, score 9 Victory Points."),
        "bonus" => array("mask" => 0, "technology" => 0, "vp15" => 0, "vp_ad" => 0, "vp_bonus" => 0, "vp_discovery" => 0, "vp_workers" => 1)),
);

$this->royalTiles = array(
    0 => array(
        "id" => 0,
        "tooltip" => clienttranslate("Gain 1 more cocoa than the power of the locked worker."),
        "bonus" => array("cocoa_locked" => 1, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    1 => array(
        "id" => 1,
        "tooltip" => clienttranslate("Pay 1 cocoa to receive 1 wood and 1 stone. You may do this up to as many times as the power of the locked worker"),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 1, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    2 => array(
        "id" => 2,
        "tooltip" => clienttranslate("Pay 1 resource to receive 2 cocoa. You may do this up to as many times as the power of the locked worker."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 1, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    3 => array(
        "id" => 3,
        "tooltip" => clienttranslate("Score 2 Victory Points each for whichever is lower: the number of Technologies with your marker, or the power of the locked worker."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 1, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    4 => array(
        "id" => 4,
        "tooltip" => clienttranslate("Score 2 Victory Points each for whichever is lower: your position on the Pyramid track, or the power of the locked worker."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 1, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    5 => array(
        "id" => 5,
        "tooltip" => clienttranslate("Pay 1 cocoa to receive 1 gold and 1 stone. You may do this up to as many times as the power of the locked worker."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 1, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 0)),
    6 => array(
        "id" => 6,
        "tooltip" => clienttranslate("Spend 1 cocoa and 1 resource to receive as many resources as the power of the locked worker (any combination)."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 1, "trade_c_t" => 0, "vp_ad" => 0)),
    7 => array(
        "id" => 7,
        "tooltip" => clienttranslate("Spend 1 cocoa to advance on any temple by one. You may do this up to as many times as the power of the locked worker minus one. Note: locking a worker with a power of 1 has no effect here."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 1, "vp_ad" => 0)),
    8 => array(
        "id" => 8,
        "tooltip" => clienttranslate("Score 1 Victory Point each for whichever is lower: your position on the Avenue of the Dead track, or the power of the locked worker plus one."),
        "bonus" => array("cocoa_locked" => 0, "trade_c_ws" => 0, "trade_r_2c" => 0, "vp_technology" => 0, "vp_pyramid" => 0, "trade_c_sg" => 0, "trade_cr_r" => 0, "trade_c_t" => 0, "vp_ad" => 1)),
);
$this->royalTilesTrade = array(
    0 => array(
        "id" => 'trade_c_ws',
        "pay" => array("cocoa" => 1, "resource" => 0),
        "get" => array("cocoa" => 0, "wood" => 1, "stone" => 1, "gold" => 0, "resource" => 0, "temple" => 0)),
    1 => array(
        "id" => 'trade_r_2c',
        "pay" => array("cocoa" => 0, "resource" => 1),
        "get" => array("cocoa" => 2, "wood" => 0, "stone" => 0, "gold" => 0, "resource" => 0, "temple" => 0)),
    2 => array(
        "id" => 'trade_c_sg',
        "pay" => array("cocoa" => 1, "resource" => 0),
        "get" => array("cocoa" => 0, "wood" => 0, "stone" => 1, "gold" => 1, "resource" => 0, "temple" => 0)),
    3 => array(
        "id" => 'trade_cr_r',
        "pay" => array("cocoa" => 1, "resource" => 1),
        "get" => array("cocoa" => 0, "wood" => 0, "stone" => 0, "gold" => 0, "resource" => 1, "temple" => 0)),
    4 => array(
        "id" => 'trade_c_t',
        "pay" => array("cocoa" => 1, "resource" => 0),
        "get" => array("cocoa" => 0, "wood" => 0, "stone" => 0, "gold" => 0, "resource" => 0, "temple" => 1)),
);

$this->technologyTiles = array(
    0 => array(
        "id" => 0,
        "tooltip" => clienttranslate("Each time you move a worker onto or past the Palace (1) Action Board, you gain 1 cocoa. (Nr. 01) (Price: 1 {token_gold})"),
        "price" => array("gold" => 1),
        "bonus" => array("cocoa_palace" => 1, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    1 => array(
        "id" => 1,
        "tooltip" => clienttranslate("After performing the Main action of Alchemy (5) or Nobles (6) Action Boards, gain 3 Victory Points. (Nr. 03) (Price: 1 {token_gold})"),
        "price" => array("gold" => 1),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 1, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    2 => array(
        "id" => 2,
        "tooltip" => clienttranslate("After performing the Main action of Forest (2), Stone Quarry (3), Gold Deposits (4), you get one (additional) wood, stone, gold, respectively. (Nr. 05) (Price: 1 {token_gold})"),
        "price" => array("gold" => 1),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 1, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    3 => array(
        "id" => 3,
        "tooltip" => clienttranslate("After performing the Main action of the Forest (2), Stone Quarry (3), or Gold Deposits (4) Action Boards, gain 1 cocoa and 1 Victory Point. (Nr. 07) (Price: 1 {token_gold})"),
        "price" => array("gold" => 1),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 1, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    4 => array(
        "id" => 4,
        "tooltip" => clienttranslate("After performing the Main action of the Decorations (7) Action Board, gain 4 Victory Points. (Nr. 09) (Price: 2 {token_gold})"),
        "price" => array("gold" => 2),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 1, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    5 => array(
        "id" => 5,
        "tooltip" => clienttranslate("After performing the Main action of the Construction (8) Action Board, gain 3 Victory Points (regardless of the number of tiles placed). (Nr. 11) (Price: 2 {token_gold})"),
        "price" => array("gold" => 2),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 1, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 0)),
    6 => array(
        "id" => 6,
        "tooltip" => clienttranslate("After resolving one or more powerups gained from performing a Main action, you may pay 1 cocoa to gain an additional power-up (same Action Board). (Nr. 13) (Price: 2 {token_gold})"),
        "price" => array("gold" => 2),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 1, "construction_extra" => 0, "construction_temple" => 0)),
    7 => array(
        "id" => 7,
        "tooltip" => clienttranslate("When performing the Main action of the Construction (8) Action Board, resolve it as if you had an additional worker that also granted a discount of 1 resource. (Nr. 15) (Price: 2 {token_gold})"),
        "price" => array("gold" => 2),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 1, "construction_temple" => 0)),
    8 => array(
        "id" => 8,
        "tooltip" => clienttranslate("After performing the Main action of the Construction (8) Action Board, advance your marker once on one temple (regardless of the number of tiles placed). (Nr. 17) (Price: 2 {token_gold})"),
        "price" => array("gold" => 2),
        "bonus" => array("cocoa_palace" => 0, "alchemy" => 0, "resources" => 0, "resources_c_vp" => 0, "decorations_vp" => 0, "construction_vp" => 0, "power_up" => 0, "construction_extra" => 0, "construction_temple" => 1))
);

$this->startingTiles = array(
    0 => array("id" => 0, "tooltip" => clienttranslate("Gain 3 cocoa and 5 wood."),
        "board" => array(3,5), "bonus" => array("cocoa" => 3,"wood" => 5,"stone" => 0,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    1 => array("id" => 1, "tooltip" => clienttranslate("Gain 3 cocoa, 2 wood, and 3 stone."),
        "board" => array(3,8), "bonus" => array("cocoa" => 3,"wood" => 2,"stone" => 3,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    2 => array("id" => 2, "tooltip" => clienttranslate("Gain 1 wood, 2 stone, and 3 gold."),
        "board" => array(2,6), "bonus" => array("cocoa" => 0,"wood" => 1,"stone" => 2,"gold" => 3,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    3 => array("id" => 3, "tooltip" => clienttranslate("Gain 2 cocoa and 3 gold. You may claim a random Discovery tile (by paying its cost). You may look at the Discovery tile before deciding to pick this tile."),
        "board" => array(4,8), "bonus" => array("cocoa" => 2,"wood" => 0,"stone" => 0,"gold" => 3,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 1,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    4 => array("id" => 4, "tooltip" => clienttranslate("Advance on the Avenue of the Dead track and gain 1 wood and 2 stone."),
        "board" => array(2,7), "bonus" => array("cocoa" => 0,"wood" => 1,"stone" => 2,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 1,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    5 => array("id" => 5, "tooltip" => clienttranslate("Advance on the green temple (gaining its reward) and gain 2 stone and 3 gold."),
        "board" => array(4,3), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 2,"gold" => 3,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 1,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    6 => array("id" => 6, "tooltip" => clienttranslate("Select the lowest numbered Technology tile on the Alchemy (5) Action Board, and place your marker on it for free. Gain the associated temple advance (and its reward) plus any 2 resources."),
        "board" => array(5,8), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 0,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 1,"upgrade" => 0,"resource" => 2)),
    7 => array("id" => 7, "tooltip" => clienttranslate("Select the lowest numbered Technology tile on the Alchemy (5) Action Board, and place your marker on it for free. Gain the associated temple advance (and its reward) plus 2 gold."),
        "board" => array(4,5), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 0,"gold" => 2,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 1,"upgrade" => 0,"resource" => 0)),
    8 => array("id" => 8, "tooltip" => clienttranslate("Increase the power of one of your starting workers. Gain 3 wood and 2 gold."),
        "board" => array(2,4), "bonus" => array("cocoa" => 0,"wood" => 3,"stone" => 0,"gold" => 2,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 1,"resource" => 0)),
    9 => array("id" => 9, "tooltip" => clienttranslate("Advance on the red temple (gaining its reward) and gain 5 cocoa and 2 gold."),
        "board" => array(1,4), "bonus" => array("cocoa" => 5,"wood" => 0,"stone" => 0,"gold" => 2,"temple_blue" => 0,"temple_red" => 1,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    10 => array("id" => 10, "tooltip" => clienttranslate("Advance on the blue temple (gaining its reward) and gain 2 cocoa and 4 stone."),
        "board" => array(6,7), "bonus" => array("cocoa" => 2,"wood" => 0,"stone" => 4,"gold" => 0,"temple_blue" => 1,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    11 => array("id" => 11, "tooltip" => clienttranslate("Advance on each of the three temples (gaining rewards)."),
        "board" => array(5,6), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 0,"gold" => 0,"temple_blue" => 1,"temple_red" => 1,"temple_green" => 1,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    12 => array("id" => 12, "tooltip" => clienttranslate("Advance on the Avenue of the Dead track and gain 2 cocoa and 3 wood."),
        "board" => array(1,2), "bonus" => array("cocoa" => 2,"wood" => 3,"stone" => 0,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 1,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    13 => array("id" => 13, "tooltip" => clienttranslate("Gain 3 stone and 1 gold. You may claim a random Discovery tile (by paying its cost). You may look at the Discovery tile before deciding to pick this tile."),
        "board" => array(3,7), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 3,"gold" => 1,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 0,"discovery" => 1,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    14 => array("id" => 14, "tooltip" => clienttranslate("Advance on the blue temple (gaining its reward) and gain 4 wood and 1 stone."),
        "board" => array(2,3), "bonus" => array("cocoa" => 0,"wood" => 4,"stone" => 1,"gold" => 0,"temple_blue" => 1,"temple_red" => 0,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 0)),
    15 => array("id" => 15, "tooltip" => clienttranslate("Advance on the green temple (gaining its reward), increase the power of one of your starting workers, and gain 5 cocoa."),
        "board" => array(6,8), "bonus" => array("cocoa" => 5,"wood" => 0,"stone" => 0,"gold" => 0,"temple_blue" => 0,"temple_red" => 0,"temple_green" => 1,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 1,"resource" => 0)),
    16 => array("id" => 16, "tooltip" => clienttranslate("Advance on the red temple (gaining its reward), increase the power of one of your starting workers, and gain 5 cocoa."),
        "board" => array(7,8), "bonus" => array("cocoa" => 5,"wood" => 0,"stone" => 0,"gold" => 0,"temple_blue" => 0,"temple_red" => 1,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 1,"resource" => 0)),
    17 => array("id" => 17, "tooltip" => clienttranslate("Advance on the blue and red temples (gaining rewards), and gain any 2 resources."),
        "board" => array(5,7), "bonus" => array("cocoa" => 0,"wood" => 0,"stone" => 0,"gold" => 0,"temple_blue" => 1,"temple_red" => 1,"temple_green" => 0,"discovery" => 0,"avenue" => 0,"technology" => 0,"upgrade" => 0,"resource" => 2)),
);

$this->discoveryTiles = array(
    0 => array("id" => 0, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 2, "wood" => 0, "gold" => 1), "bonus" => array("mask" => 1, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    1 => array("id" => 1, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 1, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 2, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    2 => array("id" => 2, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 1, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 2, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    3 => array("id" => 3, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 1, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 3, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    4 => array("id" => 4, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 1, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 3, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    5 => array("id" => 5, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 4, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    6 => array("id" => 6, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 4, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    7 => array("id" => 7, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 4, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    8 => array("id" => 8, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 5, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    9 => array("id" => 9, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 5, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    10 => array("id" => 10, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 5, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    11 => array("id" => 11, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 6, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    12 => array("id" => 12, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 6, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    13 => array("id" => 13, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 6, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    14 => array("id" => 14, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 7, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    15 => array("id" => 15, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 7, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    16 => array("id" => 16, "tooltip" => clienttranslate("These are masks, used to score during Eclipse. The small number in the bottom right corner shows how many copies of that mask exist. Lower numbered masks are less common, which makes them more valuable."), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 7, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    17 => array("id" => 17, "tooltip" => clienttranslate("Score 4 Victory Points"), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 4, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    18 => array("id" => 18, "tooltip" => clienttranslate("Score 4 Victory Points"), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 4, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    19 => array("id" => 19, "tooltip" => clienttranslate("Score 4 Victory Points"), "price" => array("cocoa" => 0, "wood" => 1, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 4, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    20 => array("id" => 20, "tooltip" => clienttranslate("Gain 3 resources"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 3, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    21 => array("id" => 21, "tooltip" => clienttranslate("Gain 3 resources"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 3, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    22 => array("id" => 22, "tooltip" => clienttranslate("Gain 2 resources"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 2, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    23 => array("id" => 23, "tooltip" => clienttranslate("Gain 2 resources"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 2, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    24 => array("id" => 24, "tooltip" => clienttranslate("Advance once on the Avenue of the Dead track (to a maximum of 9)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 1, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    25 => array("id" => 25, "tooltip" => clienttranslate("Advance once on the Avenue of the Dead track (to a maximum of 9)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 1, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    26 => array("id" => 26, "tooltip" => clienttranslate("Advance once on the Avenue of the Dead track (to a maximum of 9)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 1, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    27 => array("id" => 27, "tooltip" => clienttranslate("Advance once on any temple"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 1, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    28 => array("id" => 28, "tooltip" => clienttranslate("Advance once on any temple"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 1, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    29 => array("id" => 29, "tooltip" => clienttranslate("Advance once on any temple"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 1, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    30 => array("id" => 30, "tooltip" => clienttranslate("Advance on the blue temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 1, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    31 => array("id" => 31, "tooltip" => clienttranslate("Advance on the blue temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 1, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    32 => array("id" => 32, "tooltip" => clienttranslate("Advance on the red temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 1, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    33 => array("id" => 33, "tooltip" => clienttranslate("Advance on the red temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 1, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    34 => array("id" => 34, "tooltip" => clienttranslate("Advance on the green temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 1, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    35 => array("id" => 35, "tooltip" => clienttranslate("Advance on the green temple"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 1, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    36 => array("id" => 36, "tooltip" => clienttranslate("Gain 4 cocoa"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 1), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 4, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    37 => array("id" => 37, "tooltip" => clienttranslate("Gain 4 cocoa"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 1), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 4, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    38 => array("id" => 38, "tooltip" => clienttranslate("Gain 4 cocoa"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 1), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 4, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    39 => array("id" => 39, "tooltip" => clienttranslate("Power up 2 of your unlocked workers (or the same worker twice)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 2, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    40 => array("id" => 40, "tooltip" => clienttranslate("Power up 2 of your unlocked workers (or the same worker twice)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 2, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    41 => array("id" => 41, "tooltip" => clienttranslate("Power up 2 of your unlocked workers (or the same worker twice)"), "price" => array("cocoa" => 1, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 2, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    42 => array("id" => 42, "tooltip" => clienttranslate("Use when moving a worker to move a second worker from the same Action Board to the same Action Board"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 1, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    43 => array("id" => 43, "tooltip" => clienttranslate("Use when moving a worker to move a second worker from the same Action Board to the same Action Board"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 1, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    44 => array("id" => 44, "tooltip" => clienttranslate("Use when moving a worker to move a second worker from the same Action Board to the same Action Board"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 1, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 0)),
    45 => array("id" => 45, "tooltip" => clienttranslate("Use to ignore paying cocoa for one transaction:<br>-Payment for a Main action.<br>-Payment for a Worship Action (including cost of unlocking another player’s worker).<br>-Payment for salary during Eclipse"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 1, "move_choose" => 0, "extra_worker" => 0)),
    46 => array("id" => 46, "tooltip" => clienttranslate("Use to ignore paying cocoa for one transaction:<br>-Payment for a Main action.<br>-Payment for a Worship Action (including cost of unlocking another player’s worker).<br>-Payment for salary during Eclipse"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 1, "move_choose" => 0, "extra_worker" => 0)),
    47 => array("id" => 47, "tooltip" => clienttranslate("Use to ignore paying cocoa for one transaction:<br>-Payment for a Main action.<br>-Payment for a Worship Action (including cost of unlocking another player’s worker).<br>-Payment for salary during Eclipse"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 1, "move_choose" => 0, "extra_worker" => 0)),
    48 => array("id" => 48, "tooltip" => clienttranslate("Use in place of your normal movement to move one of your workers an unlimited distance. (May be combined with the doublemove tile’s effect.)"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 1, "extra_worker" => 0)),
    49 => array("id" => 49, "tooltip" => clienttranslate("Use in place of your normal movement to move one of your workers an unlimited distance. (May be combined with the doublemove tile’s effect.)"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 1, "extra_worker" => 0)),
    50 => array("id" => 50, "tooltip" => clienttranslate("Use in place of your normal movement to move one of your workers an unlimited distance. (May be combined with the doublemove tile’s effect.)"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 1, "extra_worker" => 0)),
    51 => array("id" => 51, "tooltip" => clienttranslate("Use when resolving the Main action on the Alchemy (5), Nobles (6), or Construction (8) Action Board to treat the action as if you had an additional worker present"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 1)),
    52 => array("id" => 52, "tooltip" => clienttranslate("Use when resolving the Main action on the Alchemy (5), Nobles (6), or Construction (8) Action Board to treat the action as if you had an additional worker present"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 1)),
    53 => array("id" => 53, "tooltip" => clienttranslate("Use when resolving the Main action on the Alchemy (5), Nobles (6), or Construction (8) Action Board to treat the action as if you had an additional worker present"), "price" => array("cocoa" => 0, "wood" => 0, "gold" => 0), "bonus" => array("mask" => 0, "vp" => 0, "r" => 0, "ad" => 0, "temple_choose" => 0, "temple_blue" => 0, "temple_red" => 0, "temple_green" => 0, "cocoa" => 0, "upgrade" => 0, "move_double" => 0, "free_cocoa" => 0, "move_choose" => 0, "extra_worker" => 1)),
);



