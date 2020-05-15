{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- teotihuacan implementation : © Jochen Walther boardgamearena@waltherjochen.de
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    teotihuacan_teotihuacan.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->
<div id="game-wrapper">
    <div id="overlay">
        <div id="overlay-content"></div>
    </div>
    <div id="startingTiles-zone" class="whiteblock"></div>
    <div id="claimDiscovery-zone" class="whiteblock"></div>
    <div id="eclipse-zone" class="whiteblock"><h3 id="eclipse-title"></h3><p id="eclipse-subtitle"></p></div>
    <div class="bg-wrapper">
        <div id="bg">
            <div class="game-area">

                <div id="actionBoards">

                    <!-- BEGIN actionBoards -->
                    <div class="actionBoard" id="actionBoard_{ACTION_BOARD_POS}">
                        <div class="help-icon" id="help_actionBoard_{ACTION_BOARD_POS}">?</div>
                        <!-- BEGIN diceGroup -->
                        <div class="dice-group">
                            <div class="dice-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dGroup_{PLAYER_ID}_dice_0"></div>
                            <div class="dice-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dGroup_{PLAYER_ID}_dice_1"></div>
                            <div class="dice-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dGroup_{PLAYER_ID}_dice_2"></div>
                            <div class="dice-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dGroup_{PLAYER_ID}_dice_3"></div>
                        </div>
                        <!-- END diceGroup -->

                        <div class="worship-group">
                            <div class="dice-wrapper worship"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dice_worship_1"></div>
                            <div class="dice-wrapper worship"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dice_worship_2"></div>
                            <div class="dice-wrapper worship"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_dice_worship_3"></div>
                        </div>

                        <div class="discoveryTiles-group">
                            <div class="discoveryTiles-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_discoveryTile_0"></div>
                            <div class="discoveryTiles-wrapper"
                                 id="POS_aBoard_{ACTION_BOARD_POS}_discoveryTile_1"></div>
                        </div>

                        <div class="nobles-wrapper">
                            <!-- BEGIN nobles_row -->
                            <div class="row_{ROW}">
                                <div class="btnNobles" id="btnNoblesRow_{ROW}" data-id="{ROW}"></div>
                                <!-- BEGIN nobles_building -->
                                <div class="building" id="building_{ID}_row_{ROW}"></div>
                                <!-- END nobles_building -->
                            </div>
                            <!-- END nobles_row -->
                        </div>

                        <div class="technology-wrapper">
                            <div class="techTile-wrapper" id="r1_c1">
                                <div class="marker-group" id="techTiles_r1_c1_markers"></div>
                            </div>
                            <div class="techTile-wrapper" id="r1_c2">
                                <div class="marker-group" id="techTiles_r1_c2_markers"></div>
                            </div>
                            <div class="techTile-wrapper" id="r1_c3">
                                <div class="marker-group" id="techTiles_r1_c3_markers"></div>
                            </div>
                            <div class="techTile-wrapper" id="r2_c1">
                                <div class="marker-group" id="techTiles_r2_c1_markers"></div>
                            </div>
                            <div class="techTile-wrapper" id="r2_c2">
                                <div class="marker-group" id="techTiles_r2_c2_markers"></div>
                            </div>
                            <div class="techTile-wrapper" id="r2_c3">
                                <div class="marker-group" id="techTiles_r2_c3_markers"></div>
                            </div>
                        </div>

                        <div class="royal-wrapper">
                            <div class="royalTile-wrapper" id="royal_wrapper_0"></div>
                            <div class="royalTile-wrapper" id="royal_wrapper_1"></div>
                            <div class="royalTile-wrapper" id="royal_wrapper_2"></div>
                        </div>

                        <div class="decoration-wrapper">
                            <div class="decorationTile-wrapper deck" id="decoration_wrapper_deck"></div>
                            <div class="decorationTile-wrapper" id="decoration_wrapper_0"></div>
                            <div class="decorationTile-wrapper" id="decoration_wrapper_1"></div>
                            <div class="decorationTile-wrapper" id="decoration_wrapper_2"></div>
                            <div class="decorationTile-wrapper" id="decoration_wrapper_3"></div>
                        </div>

                        <div class="pyramid-wrapper">
                            <div class="pyramidTile-wrapper" id="pyramid_wrapper_0"></div>
                            <div class="pyramidTile-wrapper" id="pyramid_wrapper_1"></div>
                            <div class="pyramidTile-wrapper" id="pyramid_wrapper_2"></div>
                        </div>
                    </div>
                    <!-- END actionBoards -->
                </div>

                <div id="actionBoard_-1">
                    <div class="dices">
                        <div class="dice-group">
                            <!-- BEGIN players -->
                            <div class="dice-wrapper"
                                 id="POS_aBoard_-1_dGroup_{PLAYER_ID}_dice_0"></div>
                            <!-- END players -->
                        </div>
                    </div>
                </div>

                <div class="temple blue" id="temple_blue">
                    <div class="btnTemple" id="btnTemple_blue" data-temple="blue"></div>
                    <div class="templeBonusTile-wrapper" id="temple_blue_bonus"></div>
                    <div class="markers">

                        <!-- BEGIN temple_blue -->
                        <div class="marker-group" id="temple_blue_step_{ID}">
                            <!-- BEGIN temple_markers_blue -->
                            <div class="marker-wrapper" id="temple_blue_step_{ID}_marker_{PLAYER_ID}"></div>
                            <!-- END temple_markers_blue -->
                        </div>
                        <!-- END temple_blue -->
                    </div>

                    <div class="discoveryTiles-group">
                        <div class="discoveryTiles-wrapper" id="btnTemple_blue_discoveryTile_0"></div>
                    </div>
                </div>

                <div class="temple red" id="temple_red">
                    <div class="btnTemple" id="btnTemple_red" data-temple="red"></div>
                    <div class="templeBonusTile-wrapper" id="temple_red_bonus"></div>
                    <div class="markers">

                        <!-- BEGIN temple_red -->
                        <div class="marker-group" id="temple_red_step_{ID}">
                            <!-- BEGIN temple_markers_red -->
                            <div class="marker-wrapper" id="temple_red_step_{ID}_marker_{PLAYER_ID}"></div>
                            <!-- END temple_markers_red -->
                        </div>
                        <!-- END temple_red -->
                    </div>

                    <div class="discoveryTiles-group">
                        <div class="discoveryTiles-wrapper" id="btnTemple_red_discoveryTile_0"></div>
                    </div>
                </div>

                <div class="temple green" id="temple_green">
                    <div class="btnTemple" id="btnTemple_green" data-temple="green"></div>
                    <div class="templeBonusTile-wrapper" id="temple_green_bonus"></div>
                    <div class="markers">

                        <!-- BEGIN temple_green -->
                        <div class="marker-group" id="temple_green_step_{ID}">
                            <!-- BEGIN temple_markers_green -->
                            <div class="marker-wrapper" id="temple_green_step_{ID}_marker_{PLAYER_ID}"></div>
                            <!-- END temple_markers_green -->
                        </div>
                        <!-- END temple_green -->
                    </div>

                    <div class="discoveryTiles-group">
                        <div class="discoveryTiles-wrapper" id="btnTemple_green_discoveryTile_0"></div>
                        <div class="discoveryTiles-wrapper" id="btnTemple_green_discoveryTile_1"></div>
                    </div>
                </div>

                <div class="avenue" id="avenue_of_dead">
                    <div class="btnAvenue" id="btnTemple_avenue_of_dead"></div>
                    <div class="markers">

                        <!-- BEGIN avenue -->
                        <div class="marker-group" id="avenue_step_{ID}">
                            <!-- BEGIN avenue_markers -->
                            <div class="marker-wrapper" id="avenue_step_{ID}_marker_{PLAYER_ID}"></div>
                            <!-- END avenue_markers -->
                        </div>
                        <!-- END avenue -->
                    </div>

                    <div class="discoveryTiles-group">
                        <div class="discoveryTiles-wrapper" id="avenue_discoveryTile_0"></div>
                        <div class="discoveryTiles-wrapper" id="avenue_discoveryTile_1"></div>
                        <div class="discoveryTiles-wrapper" id="avenue_discoveryTile_2"></div>
                    </div>
                </div>

                <div id="game-elements">
                    <div id="workers"></div>
                    <div id="discoverytiles"></div>
                    <div class="ascension-wrapper">
                        <!-- BEGIN ascension -->
                        <div class="ascension" id="ascension_{ID}" data-id="{ID}"></div>
                        <!-- END ascension -->
                    </div>
                    <div class="buildings-wrapper">
                        <!-- BEGIN buildings -->
                        <div class="building" id="building_{ID}"></div>
                        <!-- END buildings -->
                    </div>
                </div>

                <div class="" id="construction">
                    <!-- BEGIN construction_level0 -->
                    <div class="construction-wrapper unlocked" id="construction_level_0_r_{row}_c_{column}" data-level="0"></div>
                    <!-- END construction_level0 -->
                    <!-- BEGIN construction_level1 -->
                    <div class="construction-wrapper level1" id="construction_level_1_r_{row}_c_{column}" data-level="1"></div>
                    <!-- END construction_level1 -->
                    <!-- BEGIN construction_level2 -->
                    <div class="construction-wrapper level2" id="construction_level_2_r_{row}_c_{column}" data-level="2"></div>
                    <!-- END construction_level2 -->
                    <div class="construction-wrapper level3" id="construction_level_3_r_0_c_0" data-level="3"></div>
                </div>

                <div class="pyramid_track" id="pyramid_track">
                    <div class="markers">

                        <!-- BEGIN pyramid_track -->
                        <div class="marker-group" id="pyramid_track_step_{ID}">
                            <!-- BEGIN pyramid_track_markers -->
                            <div class="marker-wrapper" id="pyramid_track_step_{ID}_marker_{PLAYER_ID}"></div>
                            <!-- END pyramid_track_markers -->
                        </div>
                        <!-- END pyramid_track -->
                    </div>
                </div>

                <div class="calendar_track" id="calendar_track">
                    <div class="markers">

                        <!-- BEGIN calendar_track -->
                        <div class="marker-group" id="calendar_track_step_{ID}"></div>
                        <!-- END calendar_track -->
                    </div>
                </div>

                <div class="" id="pyramid_decoration">
                    <!-- BEGIN pyramid_decoration_left -->
                    <div class="pyramid_decoration-wrapper level{LEVEL}" id="pyramid_decoration_left_{LEVEL}" data-level="{LEVEL}"></div>
                    <!-- END pyramid_decoration_left -->
                    <!-- BEGIN pyramid_decoration_top -->
                    <div class="pyramid_decoration-wrapper level{LEVEL}" id="pyramid_decoration_top_{LEVEL}" data-level="{LEVEL}"></div>
                    <!-- END pyramid_decoration_top -->
                    <!-- BEGIN pyramid_decoration_right -->
                    <div class="pyramid_decoration-wrapper level{LEVEL}" id="pyramid_decoration_right_{LEVEL}" data-level="{LEVEL}"></div>
                    <!-- END pyramid_decoration_right -->
                    <!-- BEGIN pyramid_decoration_bottom -->
                    <div class="pyramid_decoration-wrapper level{LEVEL}" id="pyramid_decoration_bottom_{LEVEL}" data-level="{LEVEL}"></div>
                    <!-- END pyramid_decoration_bottom -->
                </div>

                <div id="eclipse" class="eclipse"></div>

            </div>
        </div>
        <div id="preclassic">
            <div id="actionBoard_info" class="actionBoard">
                <div class="decoration-wrapper">
                    <div class="decorationTile-wrapper deck info" id="decoration_wrapper_deck"></div>
                    <div class="decorationTile-wrapper info" id="decoration_wrapper_0"></div>
                    <div class="decorationTile-wrapper info" id="decoration_wrapper_1"></div>
                    <div class="decorationTile-wrapper info" id="decoration_wrapper_2"></div>
                    <div class="decorationTile-wrapper info" id="decoration_wrapper_3"></div>
                </div>
                <div class="pyramid-wrapper">
                    <div class="pyramidTile-wrapper info" id="pyramid_wrapper_0"></div>
                    <div class="pyramidTile-wrapper info" id="pyramid_wrapper_1"></div>
                    <div class="pyramidTile-wrapper info" id="pyramid_wrapper_2"></div>
                </div>
            </div>
            <div id="temple_orange"></div>
        </div>
    </div>
    <div id="player_table"></div>
</div>

<script type="text/javascript">

    // Javascript HTML templates

    /*
    // Example:
    var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${id}"></div>';

*/
    var jstpl_playerTable = '<div class="table whiteblock" id="player_table_${id}">' +
        '            <h3 style="color: #${player_color}">${player_name}</h3>' +
        '            <div class="playerOrder player${player_order}"></div>' +
        '            <div id="player_${id}_startingTiles" class="player_startingTiles"></div>' +
        '            <div class="content">' +
        '                <div class="non-mask" id="other_${id}"></div>' +
        '                <div class="mask" id="mask_${id}">' +
        '                </div>' +
        '            </div>';

    var jstpl_player_side = '<div id="cc_player_board_${id}" class="cc_player_board">' +
        '<span class="cc_counter"><span id="player_money_${id}" data-binding="b.players[${id}].cocoa">${cocoa}</span><span id="cocoa_${id}_side" class="token24 tokentext cocoa tooltipenable"></span></span>' +
        '<span class="cc_counter"><span id="player_money_${id}" data-binding="b.players[${id}].wood">${wood}</span><span id="wood_${id}_side" class="token24 tokentext wood tooltipenable"></span></span>' +
        '<span class="cc_counter"><span id="player_money_${id}" data-binding="b.players[${id}].stone">${stone}</span><span id="stone_${id}_side" class="token24 tokentext stone tooltipenable"></span></span>' +
        '<span class="cc_counter"><span id="player_money_${id}" data-binding="b.players[${id}].gold">${gold}</span><span id="gold_${id}_side" class="token24 tokentext gold tooltipenable"></span></span>' +
        '</div>';

    var jstpl_actionBoardsOntable = '<div class="actionBoard-test" id="test_${id}" style="background-position:-${x}00% -${y}00%"></div>';
    var jstpl_diceOntable = '<div class="dice color_${player_color} ${clickable} ${selected} ${locked}" id="${id}" data-worker-id="${worker_id}" data-worker-power="${worker_power}" data-board-id="${board_id}" style="background-position-x:${x}%"></div>';
    var jstpl_markerOntable = '<div class="marker color_${player_color}" id="${id}"></div>';
    var jstpl_discoveryTiles = '<div class="discoveryTile" id="discoveryTile_${type_arg}" data-location="${location}"></div>';
    var jstpl_technologyTiles = '<div class="technologyTile" id="technologyTile_${type_arg}" data-location="${location}"></div>';
    var jstpl_royalTiles = '<div class="royalTile" id="royalTile_${type_arg}" data-location="${location}"><div class="number">${number}</div></div>';
    var jstpl_templeBonusTiles = '<div class="templeBonusTile" id="templeBonusTile_${type_arg}" data-location="${location}"></div>';
    var jstpl_decorationTiles = '<div class="decorationTile" id="decorationTile_${type_arg}" data-location="${location}"><span>></span></div>';
    var jstpl_pyramidTiles = '<div class="pyramidTile rotate_${rotate}" id="pyramidTile_${type_arg}" data-location="${location}" data-rotate="${rotate}"><div class="token24 rotate"></div></div>';
    var jstpl_calendarTrack = '<div class="calendarTrack ${color}" id="calendarTrack_${color}"></div>';
    var jstpl_startingTiles = '<div class="startingTile-wrapper" id="startingTile_${type_arg}-wrapper"><div class="startingTile" id="startingTile_${type_arg}"></div></div>';

</script>

{OVERALL_GAME_FOOTER}


