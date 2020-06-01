/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * teotihuacan implementation : © Jochen Walther boardgamearena@waltherjochen.de
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * teotihuacan.js
 *
 * teotihuacan user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
        "dojo", "dojo/_base/declare",
        "ebg/core/gamegui",
        "ebg/counter",
        "ebg/stock"
    ],
    function (dojo, declare) {
        return declare("bgagame.teotihuacan", ebg.core.gamegui, {
            constructor: function () {
                console.log('teotihuacan constructor');
            },

            /*
                setup:

                This method must set up the game user interface according to current game situation specified
                in parameters.

                The method is called each time the game interface is displayed to a player, ie:
                _ when the game starts
                _ when a player refreshes the game page (F5)

                "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
            */

            setup: function (gamedatas) {
                console.log("Starting game setup");
                console.log("gamedatas: ", gamedatas);

                this.gamedatas_local = dojo.clone(gamedatas);

                // Setting up player boards
                for (var player_id in gamedatas.players) {
                    var player = gamedatas.players[player_id];

                    var player_board_div = $('player_board_' + player.id);
                    dojo.place(this.format_block('jstpl_player_side', player), player_board_div);
                }

                this.setupGlobalVariables();
                this.setupPlayerTable();
                this.setupActionBoards();
                this.setupWorkers();
                this.setupTemples();
                this.setupMarkers();
                this.setupDiscoveryTiles();
                this.setupPlayerHand(false);
                this.setupPyramid(true);
                this.setupDecoration(true);
                this.setupOthers();

                this.setupClickEvents();
                this.setupNotifications();

                this.bindData(this.gamedatas_local);

                window.addEventListener('resize', this.resizeGame);
                this.resizeGame();

                console.log("Ending game setup");
            },

            setupPlayerTable: function () {
                var current_player = this.gamedatas_local.players[this.getThisPlayerId()];
                dojo.place(this.format_block('jstpl_playerTable', current_player), 'player_table', 'first');
                var allStartingTilesChoosed = true;

                for (var i = 1; i <= this.gamedatas_local.players_count; i++ ) {
                    var next = parseInt(current_player.player_order) + i;
                    if(next > this.gamedatas_local.players_count){
                        next -= this.gamedatas_local.players_count;
                    }

                    for (var player_id in this.gamedatas_local.players) {
                        var player = this.gamedatas_local.players[player_id];

                        if (player.id != current_player.id && player.player_order == next) {
                            dojo.place(this.format_block('jstpl_playerTable', player), 'player_table', 'last');
                        }
                    }
                }
                for (var player_id in this.gamedatas_local.players) {
                    var player = this.gamedatas_local.players[player_id];

                    if (player.startingTile0 == null && player.startingTile1 == null) {
                        allStartingTilesChoosed = false;
                    }
                }
                if (allStartingTilesChoosed || this.global_isDraftMode) {
                    for (var player_id in this.gamedatas_local.players) {
                        var player = this.gamedatas_local.players[player_id];
                        this.setupStartingTilesOnTable(player);
                    }
                } else {
                    this.setupStartingTilesOnTable(current_player);
                }
            },

            setupStartingTilesOnTable: function (player) {
                if (player.startingTile0 != null) {
                    var target = "player_" + player.id + "_startingTiles";
                    dojo.place(this.format_block('jstpl_startingTiles', {
                        type_arg: player.startingTile0
                    }), target, "last");
                    this.addTooltipHtml("startingTile_" + player.startingTile0, this.getStartingTileTooltip(player.startingTile0));


                    if (player.startingDiscovery0 != null && (player.startingTile0 == "3" || player.startingTile0 == "13")) {
                        var target_disc = "startingTile_" + player.startingTile0 + "-wrapper";
                        dojo.place(this.format_block('jstpl_discoveryTiles', {
                            type_arg: player.startingDiscovery0,
                            location: ''
                        }), target_disc);
                        this.addTooltipHtml("discoveryTile_" + player.startingDiscovery0, this.getDiscoveryTileTooltip(player.startingDiscovery0));
                    }
                }

                if (player.startingTile1 != null) {
                    dojo.place(this.format_block('jstpl_startingTiles', {
                        type_arg: player.startingTile1
                    }), target, "last");
                    this.addTooltipHtml("startingTile_" + player.startingTile1, this.getStartingTileTooltip(player.startingTile1));

                    if (player.startingDiscovery1 != null && (player.startingTile1 == "3" || player.startingTile1 == "13")) {
                        var target_disc = "startingTile_" + player.startingTile1 + "-wrapper";
                        dojo.place(this.format_block('jstpl_discoveryTiles', {
                            type_arg: player.startingDiscovery1,
                            location: ''
                        }), target_disc);
                        this.addTooltipHtml("discoveryTile_" + player.startingDiscovery1, this.getDiscoveryTileTooltip(player.startingDiscovery1));
                    }
                }
                this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
                this.resizeGame();
            },

            setupGlobalVariables: function () {
                this.selected_board_id_from = parseInt(this.gamedatas_local.global.selected_board_id_from);
                this.selected_board_id_to = parseInt(this.gamedatas_local.global.selected_board_id_to);
                this.selected_worker_id = parseInt(this.gamedatas_local.global.selected_worker_id);
                this.selected_worker2_id = parseInt(this.gamedatas_local.global.selected_worker2_id);
                this.global_temple_bonus_cocoa = parseInt(this.gamedatas_local.global.temple_bonus_cocoa);
                this.global_temple_bonus_vp = parseInt(this.gamedatas_local.global.temple_bonus_vp);
                this.global_temple_bonus_resource = parseInt(this.gamedatas_local.global.temple_bonus_resource);
                this.global_last_temple_id = parseInt(this.gamedatas_local.global.last_temple_id);
                this.global_moveAnywhere = parseInt(this.gamedatas_local.global.useDiscoveryMoveWorkerAnywhere);
                this.global_moveTwoWorkers = parseInt(this.gamedatas_local.global.useDiscoveryMoveTwoWorkers);
                this.global_eclipseDiscWhite = parseInt(this.gamedatas_local.global.eclipseDiscWhite);
                this.global_eclipseNumber = parseInt(this.gamedatas_local.global.eclipse);
                this.global_eclipseDiscBlack = parseInt(this.gamedatas_local.global.eclipseDiscBlack);
                this.global_lastRound = parseInt(this.gamedatas_local.global.lastRound);
                this.global_isDraftMode = this.gamedatas_local.global.isDraftMode;
            },
            resizeGame: function () {
                var player_panel = 240;
                var window_width = document.documentElement.clientWidth;
                var game_max_with = window_width - player_panel;
                var bg_width = game_max_with * 0.99;
                if (window_width < 1150) {
                    bg_width = window_width;
                } else if (game_max_with > 1232) {
                    bg_width = 1232;
                }
                if (bg_width < 900.9) {
                    bg_width = 900.9;
                }

                var player_table = (game_max_with - bg_width) * 0.97;
                if (player_table > 370) {
                    dojo.style('player_table', 'max-width', player_table + "px");
                } else {
                    dojo.style('player_table', 'max-width', bg_width + "px");
                }
                var bg_height = bg_width / 1.54;
                var board_width = bg_width / 3.62;
                var board_height = board_width / 1.94;
                dojo.style('bg', 'width', bg_width + "px");
                dojo.style('bg', 'height', bg_height + "px");
                dojo.style('bg', 'background-size', bg_width + "px " + bg_height + "px");
                for (var i = 1; i <= 8; i++) {
                    dojo.style('actionBoard_' + i, 'width', board_width + "px");
                    dojo.style('actionBoard_' + i, 'height', board_height + "px");
                }

                var dice_width = bg_width / 41.06;

                var queueEntries = dojo.query('.dice');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', dice_width + "px");
                    dojo.style(queueEntries[i], 'height', dice_width + "px");
                }

                var queueEntries = dojo.query('.dice-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', dice_width + "px");
                    dojo.style(queueEntries[i], 'height', dice_width + "px");
                }

                var marker_width = bg_width / 102.7;
                var queueEntries = dojo.query('.marker');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', marker_width + "px");
                    dojo.style(queueEntries[i], 'height', marker_width + "px");
                }
                var queueEntries = dojo.query('.marker-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', marker_width + "px");
                    dojo.style(queueEntries[i], 'height', marker_width + "px");
                }

                var marker_calender_width = bg_width / 49.28;
                var queueEntries = dojo.query('.calendarTrack');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', marker_calender_width + "px");
                    dojo.style(queueEntries[i], 'height', marker_calender_width + "px");
                }

                var discoveryTile_width = bg_width / 20.53;
                var discoveryTile_height = bg_width / 30.05;
                var queueEntries = dojo.query('.discoveryTile');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', discoveryTile_width + "px");
                    dojo.style(queueEntries[i], 'height', discoveryTile_height + "px");
                }
                var queueEntries = dojo.query('.discoveryTiles-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', discoveryTile_width + "px");
                    dojo.style(queueEntries[i], 'height', discoveryTile_height + "px");
                }

                var pyramidTile_width = bg_width / 25;
                var queueEntries = dojo.query('.pyramidTile');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', pyramidTile_width + "px");
                    dojo.style(queueEntries[i], 'height', pyramidTile_width + "px");
                }
                var queueEntries = dojo.query('.pyramidTile-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', pyramidTile_width + "px");
                    dojo.style(queueEntries[i], 'height', pyramidTile_width + "px");
                }
                var queueEntries = dojo.query('.construction-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', pyramidTile_width + "px");
                    dojo.style(queueEntries[i], 'height', pyramidTile_width + "px");
                }

                var decorationTile_width = pyramidTile_width / 2;
                var decorationTile_height = pyramidTile_width;
                var queueEntries = dojo.query('.decorationTile');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', decorationTile_width + "px");
                    dojo.style(queueEntries[i], 'height', decorationTile_height + "px");
                }
                var queueEntries = dojo.query('.decorationTile-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', decorationTile_width + "px");
                    dojo.style(queueEntries[i], 'height', decorationTile_height + "px");
                }
                var queueEntries = dojo.query('.pyramid_decoration-wrapper');
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], 'width', decorationTile_width + "px");
                    dojo.style(queueEntries[i], 'height', decorationTile_height + "px");
                }
            },

            setupActionBoards: function () {
                var gamedatas = this.gamedatas_local;

                for (var i = 1; i <= 8; i++) {
                    var board = this.gamedatas.actionBoards[i];
                    if (board.card_id != "1") {
                        var children = $('actionBoard_' + board.card_location_arg).childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].className == "royal-wrapper") {
                                children[j].remove();
                                break;
                            }
                        }
                    }
                    if (board.card_id != "5") {
                        var children = $('actionBoard_' + board.card_location_arg).childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].className == "technology-wrapper") {
                                children[j].remove();
                                break;
                            }
                        }
                    }
                    if (board.card_id != "6") {
                        var children = $('actionBoard_' + board.card_location_arg).childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].className == "nobles-wrapper") {
                                children[j].remove();
                                break;
                            }
                        }
                    }
                    if (board.card_id != "7") {
                        var children = $('actionBoard_' + board.card_location_arg).childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].className == "decoration-wrapper") {
                                children[j].remove();
                                break;
                            }
                        }
                    }
                    if (board.card_id != "8") {
                        var children = $('actionBoard_' + board.card_location_arg).childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].className == "pyramid-wrapper") {
                                children[j].remove();
                                break;
                            }
                        }
                    }
                }

                for (var i = 1; i <= 8; i++) {
                    var board = this.gamedatas.actionBoards[i];
                    var x = -((board.card_id - 1) % 2) * 100;
                    var y = -Math.floor((board.card_id - 1) / 2) * 100;
                    dojo.style('actionBoard_' + board.card_location_arg, 'background-position', x + '% ' + y + '%');
                    $('actionBoard_' + board.card_location_arg).dataset.id = board.card_id;
                    dojo.attr('actionBoard_' + board.card_location_arg, "data-pos", board.card_location_arg);

                    this.addTooltipHtml("help_actionBoard_" + board.card_location_arg, this.getActionBoardTooltip(i));

                    if (board.card_location_arg == this.selected_board_id_to && this.isCurrentPlayerActive()) {
                        if (this.checkPossibleActions("selectBoard")) {
                            dojo.addClass('actionBoard_' + board.card_location_arg, 'selected');
                        }
                    }

                    if (board.card_id == "1") {
                        for (var j in this.gamedatas_local.discoveryTiles.b1) {
                            var discoveryTile = this.gamedatas_local.discoveryTiles.b1[j];
                            var target = "POS_aBoard_" + board.card_location_arg + "_discoveryTile_1";
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                        var royalTiles = this.gamedatas_local.royalTiles.royalTiles0;
                        for (var j in royalTiles) {
                            dojo.place(this.format_block('jstpl_royalTiles', {
                                type_arg: royalTiles[j].type_arg,
                                location: royalTiles[j].location,
                                number: 1
                            }), 'royal_wrapper_0');
                            this.addTooltipHtml("royalTile_" + royalTiles[j].type_arg, this.getRoyalTileTooltip(royalTiles[j].type_arg));
                        }
                        royalTiles = this.gamedatas_local.royalTiles.royalTiles1;
                        for (var j in royalTiles) {
                            dojo.place(this.format_block('jstpl_royalTiles', {
                                type_arg: royalTiles[j].type_arg,
                                location: royalTiles[j].location,
                                number: 2
                            }), 'royal_wrapper_1');
                            this.addTooltipHtml("royalTile_" + royalTiles[j].type_arg, this.getRoyalTileTooltip(royalTiles[j].type_arg));
                        }
                        royalTiles = this.gamedatas_local.royalTiles.royalTiles2;
                        for (var j in royalTiles) {
                            dojo.place(this.format_block('jstpl_royalTiles', {
                                type_arg: royalTiles[j].type_arg,
                                location: royalTiles[j].location,
                                number: 3
                            }), 'royal_wrapper_2');
                            this.addTooltipHtml("royalTile_" + royalTiles[j].type_arg, this.getRoyalTileTooltip(royalTiles[j].type_arg));
                        }
                    } else if (board.card_id == "2") {
                        for (var j in this.gamedatas_local.discoveryTiles.b2) {
                            var discoveryTile = this.gamedatas_local.discoveryTiles.b2[j];
                            var target = "POS_aBoard_" + board.card_location_arg + "_discoveryTile_0";
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                    } else if (board.card_id == "3") {
                        for (var j in this.gamedatas_local.discoveryTiles.b3) {
                            var discoveryTile = this.gamedatas_local.discoveryTiles.b3[j];
                            var target = "POS_aBoard_" + board.card_location_arg + "_discoveryTile_0";
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                    } else if (board.card_id == "4") {
                        for (var j in this.gamedatas_local.discoveryTiles.b4) {
                            var discoveryTile = this.gamedatas_local.discoveryTiles.b4[j];
                            var target = "POS_aBoard_" + board.card_location_arg + "_discoveryTile_0";
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                    } else if (board.card_id == "5") {
                        var technologyTiles = this.gamedatas_local.technologyTiles.r1_c1;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r1_c1');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                        technologyTiles = this.gamedatas_local.technologyTiles.r1_c2;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r1_c2');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                        technologyTiles = this.gamedatas_local.technologyTiles.r1_c3;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r1_c3');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                        technologyTiles = this.gamedatas_local.technologyTiles.r2_c1;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r2_c1');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                        technologyTiles = this.gamedatas_local.technologyTiles.r2_c2;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r2_c2');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                        technologyTiles = this.gamedatas_local.technologyTiles.r2_c3;
                        for (var j in technologyTiles) {
                            dojo.place(this.format_block('jstpl_technologyTiles', technologyTiles[j]), 'r2_c3');
                            this.addTooltipHtml("technologyTile_" + technologyTiles[j].type_arg, this.getTechnologyTileTooltip(technologyTiles[j].type_arg));
                        }
                    } else if (board.card_id == "6") {
                        var row1 = parseInt(this.gamedatas_local.global.row1);
                        var row2 = parseInt(this.gamedatas_local.global.row2);
                        var row3 = parseInt(this.gamedatas_local.global.row3);
                        for (var j = 0; j < row1; j++) {
                            var target = 'building_' + j + '_row_0';
                            dojo.style(target, 'display', "block");
                        }
                        for (var j = 0; j < row2; j++) {
                            var target = 'building_' + j + '_row_1';
                            dojo.style(target, 'display', "block");
                        }
                        for (var j = 0; j < row3; j++) {
                            var target = 'building_' + j + '_row_2';
                            dojo.style(target, 'display', "block");
                        }
                        var sum = row1 + row2 + row3;
                        for (var j = 0; j < sum; j++) {
                            var target = 'building_' + j;
                            dojo.style(target, 'display', "none");
                        }
                    } else if (board.card_id == "7") {
                        for (var j in this.gamedatas_local.discoveryTiles.b7) {
                            var discoveryTile = this.gamedatas_local.discoveryTiles.b7[j];
                            var target = "POS_aBoard_" + board.card_location_arg + "_discoveryTile_0";
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                        pyramidTiles = this.gamedatas_local.decorationTiles.decoTiles_0;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_decorationTiles', pyramidTiles[j]), 'decoration_wrapper_0');
                        }
                        pyramidTiles = this.gamedatas_local.decorationTiles.decoTiles_1;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_decorationTiles', pyramidTiles[j]), 'decoration_wrapper_1');
                        }
                        pyramidTiles = this.gamedatas_local.decorationTiles.decoTiles_2;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_decorationTiles', pyramidTiles[j]), 'decoration_wrapper_2');
                        }
                        pyramidTiles = this.gamedatas_local.decorationTiles.decoTiles_3;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_decorationTiles', pyramidTiles[j]), 'decoration_wrapper_3');
                        }
                        dojo.place(this.format_block('jstpl_decorationTiles', {
                            type_arg: '15',
                            location: '',
                        }), 'decoration_wrapper_deck');
                    } else if (board.card_id == "8") {
                        var pyramidTiles = this.gamedatas_local.pyramidTiles.pyramidTiles_0;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_pyramidTiles', {
                                type_arg: pyramidTiles[j].type_arg,
                                location: pyramidTiles[j].location,
                                rotate: 0
                            }), 'pyramid_wrapper_0');
                        }

                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyramidTiles_1;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_pyramidTiles', {
                                type_arg: pyramidTiles[j].type_arg,
                                location: pyramidTiles[j].location,
                                rotate: 0
                            }), 'pyramid_wrapper_1');
                        }
                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyramidTiles_2;
                        for (var j in pyramidTiles) {
                            dojo.place(this.format_block('jstpl_pyramidTiles', {
                                type_arg: pyramidTiles[j].type_arg,
                                location: pyramidTiles[j].location,
                                rotate: 0
                            }), 'pyramid_wrapper_2');
                        }

                    }

                }

                this.queryAndAddEvent(".actionBoard", 'onclick', 'onActionBoardsSelectionChanged');
            },

            setupWorkers: function () {
                var gamedatas = this.gamedatas_local;

                // dojo.query(".dice").forEach(dojo.destroy);

                for (i in this.gamedatas.map) {
                    var map_player = this.gamedatas.map[i];
                    var workerOnBoard = [];

                    for (j in map_player) {
                        var map = map_player[j];
                        var board_id = map.actionboard_id;
                        if (typeof workerOnBoard[board_id] === 'undefined') {
                            if (map.locked == false){
                                workerOnBoard[board_id] = 0;
                            }
                            this.createWorker(map.player_id, map.worker_id, map.worker_power, map.locked, map.worship_pos, board_id, 0);
                        } else {
                            if (map.locked == false){
                                workerOnBoard[board_id]++;
                            }
                            this.createWorker(map.player_id, map.worker_id, map.worker_power, map.locked, map.worship_pos, board_id, workerOnBoard[board_id]);
                        }
                    }
                }
            },

            createWorker: function (player_id, worker_id, worker_power, locked, worship_pos, board_id, workerOnBoard) {
                var player_color = "";
                if (this.gamedatas_local.players[player_id] != null) {
                    player_color = this.gamedatas_local.players[player_id].player_color;
                } else {
                    player_color = this.gamedatas_local.left_colors[player_id];
                }
                var dice_board = 'aBoard_' + board_id;
                var dice_group = dice_board + '_dGroup_' + player_id + '';
                var target = 'POS_' + dice_group + '_dice_' + workerOnBoard;
                var wokerName = player_id + '_worker_' + worker_id;
                var x = -((worker_power - 1) * 100);

                var dice_clickable = "";
                var dice_selected = "";
                var dice_locked = "";
                if (locked == false && player_id === this.getActivePlayerId() && this.isCurrentPlayerActive()) {
                    if(this.clickableWorkers && this.clickableWorkers.includes(worker_id.toString())){
                        dice_clickable = 'clickable';
                    }
                    if (this.checkPossibleActions("selectDice")) {
                        if (worker_id == this.selected_worker_id || worker_id == this.selected_worker2_id) {
                            dice_selected = 'selected';
                        }
                    }
                }
                if (locked == true) {
                    dice_locked = 'locked';
                    if (worship_pos > 0) {
                        target = 'POS_' + dice_board + '_dice_worship_' + worship_pos;
                    }

                }

                dojo.place(this.format_block('jstpl_diceOntable', {
                    id: wokerName,
                    worker_id: worker_id,
                    worker_power: worker_power,
                    board_id: board_id,
                    player_color: player_color,
                    x: x,
                    clickable: dice_clickable,
                    selected: dice_selected,
                    locked: dice_locked
                }), target);


                this.queryAndAddEvent('.dice', 'onclick', 'onDiceSelectionChanged');
                this.resizeGame();
            },

            setupTemples: function () {
                var templeBonusTiles = this.gamedatas_local.templeBonusTiles.tblueTile;
                for (var j in templeBonusTiles) {
                    dojo.place(this.format_block('jstpl_templeBonusTiles', templeBonusTiles[j]), 'temple_blue_bonus');
                    this.addTooltipHtml("templeBonusTile_" + templeBonusTiles[j].type_arg, this.getTempleBonusTileTooltip(templeBonusTiles[j].type_arg));
                }

                templeBonusTiles = this.gamedatas_local.templeBonusTiles.tredTile;
                for (var j in templeBonusTiles) {
                    dojo.place(this.format_block('jstpl_templeBonusTiles', templeBonusTiles[j]), 'temple_red_bonus');
                    this.addTooltipHtml("templeBonusTile_" + templeBonusTiles[j].type_arg, this.getTempleBonusTileTooltip(templeBonusTiles[j].type_arg));
                }

                templeBonusTiles = this.gamedatas_local.templeBonusTiles.tgreenTile;
                for (var j in templeBonusTiles) {
                    dojo.place(this.format_block('jstpl_templeBonusTiles', templeBonusTiles[j]), 'temple_green_bonus');
                    this.addTooltipHtml("templeBonusTile_" + templeBonusTiles[j].type_arg, this.getTempleBonusTileTooltip(templeBonusTiles[j].type_arg));
                }
            },

            setupMarkers: function () {
                for (var player_id in this.gamedatas_local.players) {
                    var player = this.gamedatas_local.players[player_id];
                    var step = player.temple_blue;
                    var temple = "blue";
                    var target = "temple_" + temple + "_step_" + step + "_marker_" + player_id;
                    var id = "temple_" + temple + "_marker_" + player_id;
                    dojo.place(this.format_block('jstpl_markerOntable', {
                        id: id,
                        player_color: player.player_color,
                    }), target);

                    var step = player.temple_red;
                    var temple = "red";
                    var target = "temple_" + temple + "_step_" + step + "_marker_" + player_id;
                    var id = "temple_" + temple + "_marker_" + player_id;
                    dojo.place(this.format_block('jstpl_markerOntable', {
                        id: id,
                        player_color: player.player_color,
                    }), target);

                    var step = player.temple_green;
                    var temple = "green";
                    var target = "temple_" + temple + "_step_" + step + "_marker_" + player_id;
                    var id = "temple_" + temple + "_marker_" + player_id;
                    dojo.place(this.format_block('jstpl_markerOntable', {
                        id: id,
                        player_color: player.player_color,
                    }), target);

                    // AVENUE OF DEAD

                    var step = player.avenue_of_dead;
                    var target = "avenue_step_" + step + "_marker_" + player_id;
                    var id = "avenue_marker_" + player_id;
                    dojo.place(this.format_block('jstpl_markerOntable', {
                        id: id,
                        player_color: player.player_color,
                    }), target);

                    // TECHNOLOGY TILES
                    if (player.techTiles_r1_c1 != "0") {
                        var location = 'techTiles_r1_c1';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }
                    if (player.techTiles_r1_c2 != "0") {
                        var location = 'techTiles_r1_c2';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }
                    if (player.techTiles_r1_c3 != "0") {
                        var location = 'techTiles_r1_c3';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }
                    if (player.techTiles_r2_c1 != "0") {
                        var location = 'techTiles_r2_c1';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }
                    if (player.techTiles_r2_c2 != "0") {
                        var location = 'techTiles_r2_c2';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }
                    if (player.techTiles_r2_c3 != "0") {
                        var location = 'techTiles_r2_c3';
                        var id = location + "_marker_" + player_id;
                        dojo.place(this.format_block('jstpl_markerOntable', {
                            id: id,
                            player_color: player.player_color,
                        }), location + '_markers');
                    }

                    // PYRAMID TRACK

                    var step = player.pyramid_track;
                    var target = "pyramid_track_step_" + step + "_marker_" + player_id;
                    var id = "pyramid_track_marker_" + player_id;
                    dojo.place(this.format_block('jstpl_markerOntable', {
                        id: id,
                        player_color: player.player_color,
                    }), target);
                }

                // Calendar TRACK

                var color = "white";
                var step = this.global_eclipseDiscWhite;
                var target = "calendar_track_step_" + step;
                var id = "calendar_track_marker_" + color;
                dojo.place(this.format_block('jstpl_calendarTrack', {
                    color: color,
                }), target);

                color = "black";
                step = this.global_eclipseDiscBlack;
                target = "calendar_track_step_" + step;
                id = "calendar_track_marker_" + color;
                dojo.place(this.format_block('jstpl_calendarTrack', {
                    color: color,
                }), target);

            },

            setupDiscoveryTiles: function () {

                for (var j in this.gamedatas_local.discoveryTiles.tb0) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.tb0[j];
                    var target = "btnTemple_blue_discoveryTile_0";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.tr0) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.tr0[j];
                    var target = "btnTemple_red_discoveryTile_0";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.tg0) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.tg0[j];
                    var target = "btnTemple_green_discoveryTile_0";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.tg1) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.tg1[j];
                    var target = "btnTemple_green_discoveryTile_1";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.a0) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.a0[j];
                    var target = "avenue_discoveryTile_0";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.a1) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.a1[j];
                    var target = "avenue_discoveryTile_1";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }
                for (var j in this.gamedatas_local.discoveryTiles.a2) {
                    var discoveryTile = this.gamedatas_local.discoveryTiles.a2[j];
                    var target = "avenue_discoveryTile_2";
                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                }

                this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
            },

            setupPlayerHand: function (refresh) {
                for (var i = 0; i < 3; i++) {
                    for (var player_id in this.gamedatas_local.players) {
                        var discoveryTiles_mask = this.gamedatas_local.playersHand[player_id]['mask'][i];
                        var row = 'mask_' + player_id + '_row_' + i;
                        var target = "mask_" + player_id;

                        if (discoveryTiles_mask.length > 0 && !$(row)) {
                            dojo.place('<div class="row" id="' + row + '"></div>', target);
                        }
                        for (var discoveryTile_index in discoveryTiles_mask) {
                            var discoveryTile = discoveryTiles_mask[discoveryTile_index];
                            if(!refresh || !$("discoveryTile_" + discoveryTile.type_arg)){
                                dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), row);
                                this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                            }
                        }
                    }
                }

                for (var player_id in this.gamedatas_local.players) {
                    var discoveryTiles_other = this.gamedatas_local.playersHand[player_id]['other'];
                    var target = "other_" + player_id;

                    for (var discoveryTile_index in discoveryTiles_other) {
                        var discoveryTile = discoveryTiles_other[discoveryTile_index];
                        if(!refresh || !$("discoveryTile_" + discoveryTile.type_arg)){
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                            if (refresh && this.isCurrentPlayerActive() && this.checkPossibleActions("useDiscoveryTile")) {
                                dojo.query("discoveryTile_" + discoveryTile.type_arg).addClass('clickable');
                            }
                        }
                    }

                    var discoveryTiles_used = this.gamedatas_local.playersHand[player_id]['used'];

                    for (var discoveryTile_index in discoveryTiles_used) {
                        var discoveryTile = discoveryTiles_used[discoveryTile_index];
                        if(!refresh || !$("discoveryTile_" + discoveryTile.type_arg)){
                            dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                            dojo.query('#discoveryTile_' + discoveryTile['type_arg']).addClass('used');
                            this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                        }
                    }
                }

                this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
            },

            setupPyramid: function (place) {
                var pyramidTiles;
                this.pyramidTaken = [];
                this.pyramidTaken[0] = [];
                this.pyramidTaken[1] = [];
                this.pyramidTaken[2] = [];
                this.pyramidTaken[3] = [];
                for (var i = 0; i < 4; i++) {
                    if (i == 0)
                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyra_rotate_0;
                    else if (i == 1)
                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyra_rotate_1;
                    else if (i == 2)
                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyra_rotate_2;
                    else if (i == 3)
                        pyramidTiles = this.gamedatas_local.pyramidTiles.pyra_rotate_3;
                    for (var j in pyramidTiles) {
                        var location_arg = parseInt(pyramidTiles[j].location_arg);
                        var level = parseInt(location_arg / 100);
                        this.pyramidTaken[level].push(location_arg);
                        var maxRowInLevel = 4 - level;
                        var number = location_arg - (level * 100);
                        var row = parseInt(number / maxRowInLevel);
                        var column = number % maxRowInLevel;
                        var target = 'construction_level_' + level + '_r_' + row + '_c_' + column;
                        if (place) {
                            dojo.place(this.format_block('jstpl_pyramidTiles', {
                                type_arg: pyramidTiles[j].type_arg,
                                location: pyramidTiles[j].location,
                                rotate: i
                            }), target);
                        }
                        dojo.query('#' + target).addClass('disabled');
                    }
                }
                for (var level = 1; level <= 3; level++) {
                    var maxRow = 4 - level;
                    var maxColumn = 4 - level;
                    for (var i = 0; i < maxRow; i++) {
                        for (var j = 0; j < maxColumn; j++) {
                            var maxRowInLevel = 5 - level;
                            var topLeft = i * maxRowInLevel + j + ((level - 1) * 100);
                            var topRight = topLeft + 1;
                            var bottomLeft = i * maxRowInLevel + j + maxRowInLevel + ((level - 1) * 100);
                            var bottomRight = bottomLeft + 1;
                            if (this.pyramidTaken[level - 1].includes(topLeft) && this.pyramidTaken[level - 1].includes(topRight) && this.pyramidTaken[level - 1].includes(bottomLeft) && this.pyramidTaken[level - 1].includes(bottomRight)) {
                                var target = 'construction_level_' + level + '_r_' + i + '_c_' + j;
                                dojo.query('#' + target).addClass('unlocked');
                            }
                        }
                    }
                }

            },

            setupDecoration: function (place) {
                var decorationTiles;
                this.decorationTaken = [];
                this.decorationTaken['left'] = [];
                this.decorationTaken['top'] = [];
                this.decorationTaken['right'] = [];
                this.decorationTaken['bottom'] = [];
                for (var i = 0; i < 4; i++) {
                    var direction = '';
                    if (i == 0) {
                        decorationTiles = this.gamedatas_local.decorationTiles.deco_p_left;
                        direction = 'left';
                    } else if (i == 1) {
                        decorationTiles = this.gamedatas_local.decorationTiles.deco_p_top;
                        direction = 'top';
                    } else if (i == 2) {
                        decorationTiles = this.gamedatas_local.decorationTiles.deco_p_right;
                        direction = 'right';
                    } else if (i == 3) {
                        decorationTiles = this.gamedatas_local.decorationTiles.deco_p_bottom;
                        direction = 'bottom';
                    }
                    for (var j in decorationTiles) {
                        var level = parseInt(decorationTiles[j].location_arg);
                        target = 'pyramid_decoration_' + direction + '_' + level;
                        this.decorationTaken[direction].push(level);
                        if (place) {
                            dojo.place(this.format_block('jstpl_decorationTiles', {
                                type_arg: decorationTiles[j].type_arg,
                                location: decorationTiles[j].location,
                                rotate: i
                            }), target);
                        }
                        dojo.query('#' + target).addClass('disabled');
                    }
                }
                dojo.query('.pyramid_decoration-wrapper.level0').addClass('unlocked');

                var direction = 'left';
                if (this.pyramidTaken[0].includes(4) && this.pyramidTaken[0].includes(8) && this.decorationTaken[direction].includes(0)) {
                    var target = 'pyramid_decoration_' + direction + '_1';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[1].includes(103) && this.decorationTaken[direction].includes(1)) {
                    var target = 'pyramid_decoration_' + direction + '_2';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[2].includes(200) && this.pyramidTaken[2].includes(202) && this.decorationTaken[direction].includes(2)) {
                    var target = 'pyramid_decoration_' + direction + '_3';
                    dojo.query('#' + target).addClass('unlocked');
                }
                direction = 'top';
                if (this.pyramidTaken[0].includes(1) && this.pyramidTaken[0].includes(2) && this.decorationTaken[direction].includes(0)) {
                    var target = 'pyramid_decoration_' + direction + '_1';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[1].includes(101) && this.decorationTaken[direction].includes(1)) {
                    var target = 'pyramid_decoration_' + direction + '_2';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[2].includes(200) && this.pyramidTaken[2].includes(201) && this.decorationTaken[direction].includes(2)) {
                    var target = 'pyramid_decoration_' + direction + '_3';
                    dojo.query('#' + target).addClass('unlocked');
                }
                direction = 'right';
                if (this.pyramidTaken[0].includes(7) && this.pyramidTaken[0].includes(11) && this.decorationTaken[direction].includes(0)) {
                    var target = 'pyramid_decoration_' + direction + '_1';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[1].includes(105) && this.decorationTaken[direction].includes(1)) {
                    var target = 'pyramid_decoration_' + direction + '_2';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[2].includes(201) && this.pyramidTaken[2].includes(203) && this.decorationTaken[direction].includes(2)) {
                    var target = 'pyramid_decoration_' + direction + '_3';
                    dojo.query('#' + target).addClass('unlocked');
                }
                direction = 'bottom';
                if (this.pyramidTaken[0].includes(13) && this.pyramidTaken[0].includes(14) && this.decorationTaken[direction].includes(0)) {
                    var target = 'pyramid_decoration_' + direction + '_1';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[1].includes(107) && this.decorationTaken[direction].includes(1)) {
                    var target = 'pyramid_decoration_' + direction + '_2';
                    dojo.query('#' + target).addClass('unlocked');
                }
                if (this.pyramidTaken[2].includes(202) && this.pyramidTaken[2].includes(203) && this.decorationTaken[direction].includes(2)) {
                    var target = 'pyramid_decoration_' + direction + '_3';
                    dojo.query('#' + target).addClass('unlocked');
                }

            },

            setupStartingTiles: function () {
                for (var j in this.gamedatas_local.startingTiles) {
                    var startingTile = this.gamedatas_local.startingTiles[j];
                    if (startingTile.type == "startingTiles") {
                        var target = "startingTiles-zone";
                        dojo.place(this.format_block('jstpl_startingTiles', startingTile), target, "last");
                        this.addTooltipHtml("startingTile_" + startingTile.type_arg, this.getStartingTileTooltip(startingTile.type_arg));

                        if (startingTile.type_arg == "3" || startingTile.type_arg == "13") {
                            for (var k in this.gamedatas_local.startingTiles) {
                                var discoveryTile = this.gamedatas_local.startingTiles[k];
                                if (discoveryTile.type == "discoveryTiles" && discoveryTile.location_arg == startingTile.type_arg) {
                                    var target = "startingTile_" + discoveryTile.location_arg + "-wrapper";
                                    dojo.place(this.format_block('jstpl_discoveryTiles', discoveryTile), target);
                                    this.addTooltipHtml("discoveryTile_" + discoveryTile.type_arg, this.getDiscoveryTileTooltip(discoveryTile.type_arg));
                                    break;
                                }
                            }
                            this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
                        }
                    }
                }
                dojo.query('.startingTile').addClass('clickable');
                dojo.query('.startingTile').addClass('unselected');
                this.queryAndAddEvent('.startingTile.clickable', 'onclick', 'onStartingTileChanged');
            },

            setupOthers: function () {
                for (var i = 0; i < 5; i++) {
                    this.addTooltipHtml("ascension_" + i, this.gamedatas_local.ascensionInfo_data[i].tooltip);
                }
                this.showEclipseBanner();
                this.setPyramidZoom();
                this.setDecorationZoom();

                for (var player_id in this.gamedatas_local.players) {
                    if (player_id != this.getThisPlayerId() || this.isSpectator) {
                        $('enableUndo_' + player_id).remove();
                    }
                }

                if(!this.isSpectator){
                    this.queryAndAddEvent('#enableUndo_'+this.getThisPlayerId(), 'onclick', 'enableUndoChanged');
                    $('enableUndo_'+this.getThisPlayerId()+'_text').innerHTML = _('Ask for undo after my turn');
                    this.addTooltipHtml('enableUndo_'+this.getThisPlayerId()+'_text', _('Enable, when you want to undo your turn'));

                    var enableUndo = this.gamedatas_local.players[this.getThisPlayerId()].enableUndo;
                    if(enableUndo > 0){
                        $('enableUndo_' + this.getThisPlayerId()).innerHTML = 'X';
                    } else {
                        $('enableUndo_' + this.getThisPlayerId()).innerHTML = '';
                    }
                }
                $('player_side_order_2').remove();
                if($('player_side_order_3')){
                    $('player_side_order_3').remove();
                    if($('player_side_order_4')){
                        $('player_side_order_4').remove();
                    }
                }
            },

            setupClickEvents: function () {
                this.queryAndAddEvent('#overlay', 'onclick', 'hideOverlay');
                this.queryAndAddEvent('.btnTemple', 'onclick', 'onTempleStepChanged');
                this.queryAndAddEvent('.btnAvenue', 'onclick', 'onAvenueStepChanged');
                this.queryAndAddEvent('.btnNobles', 'onclick', 'onNoblesChanged');
                this.queryAndAddEvent('.ascension', 'onclick', 'onAscensionChanged');
                this.queryAndAddEvent('.royalTile', 'onclick', 'onRoyalTileChanged');
                this.queryAndAddEvent('.technologyTile', 'onclick', 'onTechnologyTileChanged');
                this.queryAndAddEvent('.pyramidTile', 'onclick', 'onPyramidTileChanged');
                this.queryAndAddEvent('.construction-wrapper', 'onclick', 'onPyramidChanged');
                this.queryAndAddEvent('.decorationTile', 'onclick', 'onDecorationTileChanged');
                this.queryAndAddEvent('.decorationTile span', 'onclick', 'onDecorationTileChanged');
                this.queryAndAddEvent('.pyramid_decoration-wrapper', 'onclick', 'onPyramidDecorationChanged');
                this.queryAndAddEvent('#eclipse', 'onclick', 'onEclipseChanged');
                this.addTooltipHtml('eclipse', _("Click to zoom"));
            },


            ///////////////////////////////////////////////////
            //// Game & client states

            // onEnteringState: this method is called each time we are entering into a new game state.
            //                  You can use this method to perform some user interface changes at this moment.
            //
            onEnteringState: function (stateName, args) {
                console.log('Entering state: ' + stateName);
                console.log('Entering state: ', args);

                this.deselectAll();
                var player_id = this.getThisPlayerId();

                if (this.isCurrentPlayerActive()) {
                    if (this.checkPossibleActions("useDiscoveryTile")) {
                        dojo.query('#other_' + player_id + ' .discoveryTile').addClass('clickable');
                    }

                    switch (stateName) {

                        case 'playerTurn':
                            this.clickableWorkers = args.args.clickableWorkers;
                            this.isGamePreparation = false;
                            this.gamedatas_local.global = args.args.global;
                            this.setupGlobalVariables();
                            this.setAllWorkersClickable(args.args.clickableWorkers);
                            break;
                        case 'playerTurn_show_board_actions':
                            dojo.addClass('actionBoard_' + args.args.selected_board_id_to, 'selected');
                            dojo.addClass(player_id + '_worker_' + args.args.selected_worker_id, 'selected');

                            if (args.args.global_moveTwoWorkers == true) {
                                if ($(player_id + '_worker_' + args.args.selected_worker_id) && $(player_id + '_worker_' + args.args.selected_worker2_id)) {
                                    dojo.addClass(player_id + '_worker_' + args.args.selected_worker_id, 'firstWorker');
                                    dojo.addClass(player_id + '_worker_' + args.args.selected_worker2_id, 'secondWorker');
                                }
                            }
                            break;
                        case 'playerTurn_worship_actions':
                            this.clientStateArgs = {};
                            this.clientStateArgs.templeQueue = args.args.queue;
                            this.clientStateArgs.royalTileAction = args.args.royalTileAction;
                            this.gamedatas_local.global.worship_actions_discovery = parseInt(args.args.worship_actions_discovery);
                            if (args.args.worship_pos != -1 && (args.args.royalTileAction || args.args.queue > 0)) {
                                dojo.query('#royal_wrapper_' + (args.args.worship_pos - 1) + ' .royalTile').addClass('clickable');
                            }
                            this.checkWorshipActions(args.args.queue);
                            break;
                        case 'client_playerTurn_doWorshipOnBoard_choose':
                            dojo.query('.royalTile .number').addClass('show');
                            break;
                        case 'playerTurn_choose_temple_resources':
                            this.clientStateArgs = {};
                            this.clientStateArgs.max = args.args.max;
                            this.clientStateArgs.wood = 0;
                            this.clientStateArgs.stone = 0;
                            this.clientStateArgs.gold = 0;
                            this.templeResouceConfirm();
                            break;
                        case 'playerTurn_choose_temple_bonus':
                            this.global_last_temple_id = args.args.last_temple_id;
                            this.setTempleDiscoveryTilesClickable();
                            break;
                        case 'playerTurn_avenue_of_dead':
                            dojo.query('.btnAvenue').addClass('clickable');
                            break;
                        case 'playerTurn_avenue_of_dead_choose_bonus':
                            var query = '';
                            var step = this.gamedatas_local.players[player_id].avenue_of_dead;
                            if (step == 3) {
                                query = '#avenue_discoveryTile_0 .discoveryTile';
                            } else if (step == 6) {
                                query = '#avenue_discoveryTile_1 .discoveryTile';
                            } else if (step == 8) {
                                query = '#avenue_discoveryTile_2 .discoveryTile';
                            }
                            if (query != '') {
                                dojo.query(query).addClass('clickable');
                            }
                            break;
                        case 'playerTurn_upgrade_workers':
                            this.clickableWorkers = args.args.clickableWorkers;
                            this.setAllWorkersClickable(args.args.clickableWorkers);
                            break;
                        case 'playerTurn_ascension_choose_bonus':
                            dojo.query('.ascension').addClass('clickable');
                            break;
                        case 'playerTurn_nobles_choose_row':

                            if (args.args.row <= 1) {
                                dojo.query('#btnNoblesRow_0').addClass('clickable');
                            } else if (args.args.row == 2) {
                                dojo.query('#btnNoblesRow_0').addClass('clickable');
                                dojo.query('#btnNoblesRow_1').addClass('clickable');
                            } else if (args.args.row >= 3) {
                                dojo.query('#btnNoblesRow_0').addClass('clickable');
                                dojo.query('#btnNoblesRow_1').addClass('clickable');
                                dojo.query('#btnNoblesRow_2').addClass('clickable');
                            }
                            break;
                        case 'playerTurn_worship_actions_trade':
                            this.clientStateArgs = {};
                            this.clientStateArgs.max = args.args.max;
                            this.clientStateArgs.maxWood = args.args.maxWood;
                            this.clientStateArgs.maxStone = args.args.maxStone;
                            this.clientStateArgs.maxGold = args.args.maxGold;
                            this.clientStateArgs.multiplier = 0;
                            this.clientStateArgs.isPayCocoa = args.args.pay.cocoa;
                            this.clientStateArgs.isPayResource = args.args.pay.resource;
                            this.clientStateArgs.getInfo = args.args.get;
                            this.clientStateArgs.pay = {};
                            this.clientStateArgs.get = {};
                            this.clientStateArgs.pay.cocoa = 0;
                            this.clientStateArgs.pay.wood = 0;
                            this.clientStateArgs.pay.stone = 0;
                            this.clientStateArgs.pay.gold = 0;
                            this.clientStateArgs.get.cocoa = 0;
                            this.clientStateArgs.get.wood = 0;
                            this.clientStateArgs.get.stone = 0;
                            this.clientStateArgs.get.gold = 0;
                            this.clientStateArgs.get.temple = 0;

                            this.tradeConfirm();
                            break;
                        case 'playerTurn_alchemy':
                            dojo.query('#r1_c1 .technologyTile').addClass('clickable');
                            dojo.query('#r1_c2 .technologyTile').addClass('clickable');
                            dojo.query('#r1_c3 .technologyTile').addClass('clickable');
                            if (args.args.row == 2) {
                                dojo.query('#r2_c1 .technologyTile').addClass('clickable');
                                dojo.query('#r2_c2 .technologyTile').addClass('clickable');
                                dojo.query('#r2_c3 .technologyTile').addClass('clickable');
                            }
                            break;
                        case 'playerTurn_construction':
                            this.isConstructionWorkerTechAquired = args.args.isConstructionWorkerTechAquired;
                            dojo.query('.actionBoard .pyramidTile').addClass('clickable');
                            break;
                        case 'client_playerTurn_buildPyramid_confirm':
                            if ($(this.pyramidTile.id) && $(this.constructionWrapper.id)) {
                                dojo.addClass(this.pyramidTile.id, 'selected');
                                dojo.addClass(this.constructionWrapper.id, 'selected');
                            }
                            break;
                        case 'playerTurn_decoration':
                            this.countWorkersOnDecoration = parseInt(args.args.countWorkersOnDecoration);
                            dojo.query('.actionBoard .decorationTile-wrapper:not(.deck) .decorationTile').addClass('clickable');
                            break;
                        case 'client_playerTurn_buildDecoration_confirm':
                            if ($(this.decorationTile.id) && $(this.decorationWrapper.id)) {
                                dojo.addClass(this.decorationTile.id, 'selected');
                                dojo.addClass(this.decorationWrapper.id, 'selected');
                            }
                            break;
                        case 'starting_tiles_place_workers':
                            this.isGamePreparation = true;
                            for (var i = 1; i <= 8; i++) {
                                var board = this.gamedatas.actionBoards[i];
                                for (var j = 0; j < args.args.length; j++) {
                                    if (board.card_id == args.args[j]) {
                                        dojo.addClass('actionBoard_' + board.card_location_arg, 'clickable');
                                    }
                                }
                            }
                            break;
                        case 'claim_starting_discovery_tiles':
                            this.gamedatas_local.global.worship_actions_discovery = parseInt(args.args.worship_actions_discovery);
                            dojo.query('#player_' + player_id + '_startingTiles .discoveryTile').addClass('clickable');
                            break;
                        case 'choose_starting_tiles_draft':
                            dojo.query('.startingTile').addClass('clickable');
                            dojo.query('.startingTile').addClass('unselected');
                            break;
                        case 'client_playerTurn_claimDiscovery_confirm':
                            dojo.query('#claimDiscovery-zone').addClass('show');
                            this.resizeGame();
                            break;
                    }
                }

                switch (stateName) {
                    case 'check_end_game':
                    case 'playerTurn':
                        var _this = this;
                        setTimeout(function () {
                            _this.checkIsMapComplete();
                        }, 3900);
                        for (var player_id in args.args.playerInfo) {
                            var info = args.args.playerInfo[player_id];
                            this.gamedatas_local.players[player_id].cocoa = info.cocoa;
                            this.gamedatas_local.players[player_id].wood = info.wood;
                            this.gamedatas_local.players[player_id].stone = info.stone;
                            this.gamedatas_local.players[player_id].gold = info.gold;
                            this.gamedatas_local.players[player_id].score = info.score;

                            $('player_score_' + player_id).innerHTML = this.gamedatas_local.players[player_id].score;
                            this.bindData(this.gamedatas_local);
                        }
                        break;
                    case 'client_playerTurn_paySalary_confirm':
                        dojo.query('#other_' + player_id + ' .discoveryTile').addClass('clickable');
                        break;
                    case 'pay_salary':
                        this.clientStateArgs = {};
                        this.clientStateArgs.max = args.args.playersData[player_id]['max'];
                        this.clientStateArgs.cocoa = args.args.playersData[player_id]['cocoa'];
                        console.log("this.clientStateArgs",this.clientStateArgs);
                        this.paySalaryConfirm();
                        break;
                    case 'choose_starting_tiles':
                    case 'choose_starting_tiles_draft':
                        this.isChoosingStartingTiles = true;
                        this.clientStateArgs = {};
                        this.clientStateArgs.max = 0;
                        this.clientStateArgs.wood = 0;
                        this.clientStateArgs.stone = 0;
                        this.clientStateArgs.gold = 0;
                        if (this.gamedatas_local.players[player_id].startingTile1 == null && dojo.getStyle("startingTiles-zone", "display") == "none") {
                            dojo.style("startingTiles-zone", 'display', "block");
                            this.setupStartingTiles();
                        }
                        break;
                    case 'starting_tiles_place_workers':
                        this.isChoosingStartingTiles = false;
                        if ($("startingTiles-zone") && dojo.getStyle("startingTiles-zone", "display") == "block") {
                            $("startingTiles-zone").remove();
                        }
                        break;
                }
            },

            // onLeavingState: this method is called each time we are leaving a game state.
            //                 You can use this method to perform some user interface changes at this moment.
            //
            onLeavingState: function (stateName) {
                console.log('Leaving state: ' + stateName);

                switch (stateName) {

                    /* Example:

                    case 'myGameState':

                        // Hide the HTML block we are displaying only during this game state
                        dojo.style( 'my_html_block_id', 'display', 'none' );

                        break;
                   */


                    case 'dummmy':
                        break;
                }
            },

            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //
            onUpdateActionButtons: function (stateName, args) {
                this.stateName = stateName;
                console.log('onUpdateActionButtons: ' + stateName);

                if (this.isCurrentPlayerActive()) {
                    var cancelConfirm = false;
                    var cancelMessage = _('Cancel');

                    switch (stateName) {
                        case 'playerTurn':
                            if (args.lockedWorkers > 0) {
                                this.addActionButton('button_1_id', _('Unlock all workers'), 'unlockAllWorkersClick', null, false, 'gray');
                            }
                            break;
                        case 'playerTurn_show_board_actions':
                            this.isPalaceTechAquired = args.isPalaceTechAquired;
                            this.gamedatas_local.map = args.map;

                            var board_id = dojo.attr($('actionBoard_' + args.selected_board_id_to), "data-id");

                            if (board_id != 1) {
                                var amount = -this.getDiffrentColorsOnBoard(args.selected_board_id_to);

                                this.addActionButton('button_1_id', _('Main Action') + " (" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardMainActionClick', null, false, 'gray');
                            }
                            var amount = (this.getDiffrentColorsOnBoard(args.selected_board_id_to) + 1);
                            this.addActionButton('button_2_id', _('Collect Cocoa') + " (+" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardCollectCocoaClick', null, false, 'gray');

                            if (board_id == 2 || board_id == 3 || board_id == 4 || board_id == 7) {
                                if (this.isWorshipPossible(1)) {
                                    var amount = 0;
                                    if (this.isWorshipFromOthersTaken(1)) {
                                        amount--;
                                    }
                                    this.addActionButton('button_3_id', _('Worship') + " (" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardWorshipClick', null, false, 'gray');
                                }
                            } else if (board_id == 1) {
                                if (this.isWorshipPossible(1) || this.isWorshipPossible(2) || this.isWorshipPossible(3)) {
                                    this.addActionButton('button_3_id', _('Worship'), 'doBoardWorshipClick', null, false, 'gray');
                                }
                            }
                            this.addActionButton('button_4_id', _('Cancel'), 'cancelMoveToBoard', null, false, "red");
                            break;
                        case 'playerTurn_nobles':
                            var actionConfirm = 'client_playerTurn_nobles_confirm';
                            this.clientStateArgs = {};
                            this.clientStateArgs.action = 'playerTurn_nobles';
                            this.clientStateArgs.price = 2;
                            var translated = _("Place a building") + " " + this.moneyPreview("wood");
                            this.setClientStateAction(actionConfirm, translated);
                            break;
                        case 'client_playerTurn_nobles_confirm':
                            this.addActionButton('button_1_id', _('Build'), 'doNoblesClick', null, false, 'gray');
                            break;
                        case 'playerTurn_choose_worship_actions':
                            this.canBuyDiscoveryTile = args.canBuyDiscoveryTile;
                            this.canBuyDiscoveryTileBoth = args.canBuyDiscoveryTileBoth;
                            this.addActionButton('button_3_id', _('do both') + " ( -1" + this.getTokenSymbol('cocoa', true) + " )", 'doWorshipBothClick', null, false, 'gray');
                            this.addActionButton('button_1_id', _('Worship only'), 'doWorshipClick', null, false, 'gray');

                            board_id = dojo.attr($('actionBoard_' + args.selected_board_id_to), "data-id");

                            if (board_id != 1) {
                                this.addActionButton('button_2_id', _('Discovery only'), 'doWorshipDiscoveryClick', null, false, 'gray');
                            }
                            if (this.isFreeCocoa()) {
                                this.addActionButton('cocoa_free', _('do both') + " ( " + this.getTokenSymbol('cocoa_free', true) + " )", 'doWorshipBothClickFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            break;
                        case 'playerTurn_check_pass':
                            this.addActionButton('button_1_id', _('end turn'), 'onPassClick', null, false, "red");
                            break;
                        case 'playerTurn_worship_actions':
                            if(!args.queue && !args.royalTileAction && args.worship_actions_discovery){
                                this.addActionButton('button_1_id', _('Skip action and pass'), 'onPassClick', null, false, "red");
                            }
                            break;
                        case 'playerTurn_avenue_of_dead_choose_bonus':
                        case 'playerTurn_avenue_of_dead':
                            this.addActionButton('button_1_id', _('Skip action and pass'), 'onPassClick', null, false, "red");
                            break;
                        case 'playerTurn_choose_temple_bonus':
                            var amount = 0;
                            var symbol = '';
                            if (args.temple_bonus_resource > 0) {
                                amount = args.temple_bonus_resource;
                                symbol = 'resource';
                            } else if (args.temple_bonus_vp > 0) {
                                amount = args.temple_bonus_vp;
                                symbol = 'vp';
                            } else if (args.temple_bonus_cocoa > 0) {
                                amount = args.temple_bonus_cocoa;
                                symbol = 'cocoa';
                            }
                            this.addActionButton('button_1_id', amount + this.getTokenSymbol(symbol, true), 'takeNormalBonus', null, false, "gray");
                            break;
                        case 'client_playerTurn_choose_temple_resources_confirm':
                            var isMax = (this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) == this.clientStateArgs.max;
                            if (this.clientStateArgs && isMax) {
                                this.addActionButton('done', _('Done'), 'doChooseTempleResourceConfirmed', null, false, 'gray');
                                dojo.query('#done').removeClass('disabled');
                            } else {
                                this.addActionButton('done', _('Done'), 'doChooseTempleResourceConfirmed', null, false, "gray");
                                dojo.query('#done').addClass('disabled');
                            }
                            this.addActionButton('incrementWood', "+1" + this.getTokenSymbol('wood', true), 'incrementTempleWood', null, false, 'gray');
                            this.addTooltipHtml('incrementWood', _("Increment number of wood"));
                            this.addActionButton('incrementStone', "+1" + this.getTokenSymbol('stone', true), 'incrementTempleStone', null, false, 'gray');
                            this.addTooltipHtml('incrementStone', _("Increment number of stone"));
                            this.addActionButton('incrementGold', "+1" + this.getTokenSymbol('gold', true), 'incrementTempleGold', null, false, 'gray');
                            this.addTooltipHtml('incrementGold', _("Increment number of gold"));

                            this.checkIfButtonsAreEnabled(this.clientStateArgs.wood, this.clientStateArgs.stone, this.clientStateArgs.gold, isMax);
                            cancelConfirm = true;
                            cancelMessage = _('reset');
                            break;
                        case 'client_playerTurn_mainAction_confirm':
                            this.addActionButton('button_1_id', _('Pay'), 'doBoardMainActionClickConfirmed', null, false, 'gray');
                            if (this.isFreeCocoa()) {
                                this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'doBoardMainActionClickConfirmedFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_collectCocoa_confirm':
                            this.addActionButton('button_1_id', _('Collect'), 'doBoardCollectCocoaClickConfirmed', null, false, 'gray');
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_unlockAllWorkers_and_move_confirm':
                            this.addActionButton('button_1_id', _('Pay') + " (-3" + this.getTokenSymbol('cocoa', true) + ")", 'unlockWorkersAndPayConfirmed', null, false, 'gray');
                            this.addActionButton('button_2_id', _('Just unlock and end turn'), 'unlockWorkersFreeConfirmed', null, false, 'gray');
                            if (this.isFreeCocoa()) {
                                this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'unlockWorkersAndPayConfirmedFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_doWorshipOnBoard_confirm':
                            this.addActionButton('button_2_id', _('Unlock'), 'doBoardWorshipClickConfirmed', null, false, 'gray');
                            if (this.isFreeCocoa()) {
                                this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'doBoardWorshipClickConfirmedFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_doWorshipOnBoard_choose':
                            if (this.isWorshipPossible(1)) {
                                var amount = 0;
                                if (this.isWorshipFromOthersTaken(1)) {
                                    amount--;
                                }
                                this.addActionButton('button_1_id', _('Worship 1') + " (" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardWorshipClick1', null, false, 'gray');
                            }
                            if (this.isWorshipPossible(2)) {
                                var amount = 0;
                                if (this.isWorshipFromOthersTaken(2)) {
                                    amount--;
                                }
                                this.addActionButton('button_2_id', _('Worship 2') + " (" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardWorshipClick2', null, false, 'gray');
                            }
                            if (this.isWorshipPossible(3)) {
                                var amount = 0;
                                if (this.isWorshipFromOthersTaken(3)) {
                                    amount--;
                                }
                                this.addActionButton('button_3_id', _('Worship 3') + " (" + amount + this.getTokenSymbol('cocoa', true) + ")", 'doBoardWorshipClick3', null, false, 'gray');
                            }
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_claimDiscovery_confirm':
                            this.addActionButton('button_1_id', _('Claim'), 'doClaimDiscoveryConfirmed', null, false, 'gray');
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_worship_actions_trade_confirm':
                            this.addActionButton('button_0_id', _('Done'), 'doTradeConfirmed', null, false, 'gray');

                            if (this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource == 0) {
                                this.addActionButton('incrementCocoa', "-1" + this.getTokenSymbol('cocoa', true), 'incrementTradeCocoa', null, false, 'gray');
                                this.addTooltipHtml('incrementCocoa', _("Decrement number of cocoa"));

                                var isMax = this.clientStateArgs.multiplier == this.clientStateArgs.max;
                                if (this.clientStateArgs.pay.cocoa == 0) {
                                    dojo.query('#decrementCocoa').addClass('disabled');
                                } else {
                                    dojo.query('#decrementCocoa').removeClass('disabled');
                                }
                                if (isMax) {
                                    dojo.query('#incrementCocoa').addClass('disabled');
                                } else {
                                    dojo.query('#incrementCocoa').removeClass('disabled');
                                }
                            }

                            if (this.clientStateArgs.isPayResource > 0) {
                                this.addActionButton('incrementWood', "-1" + this.getTokenSymbol('wood', true), 'incrementTradeWood', null, false, 'gray');
                                this.addTooltipHtml('incrementWood', _("Decrement number of wood"));
                                this.addActionButton('incrementStone', "-1" + this.getTokenSymbol('stone', true), 'incrementTradeStone', null, false, 'gray');
                                this.addTooltipHtml('incrementStone', _("Decrement number of stone"));
                                this.addActionButton('incrementGold', "-1" + this.getTokenSymbol('gold', true), 'incrementTradeGold', null, false, 'gray');
                                this.addTooltipHtml('incrementGold', _("Decrement number of gold"));

                                var isMax = false;
                                if (!((this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < 1) ||
                                    (this.clientStateArgs.isPayCocoa == 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < this.clientStateArgs.max))) {
                                     isMax = true;
                                }

                                this.checkIfButtonsAreEnabled(this.clientStateArgs.pay.wood, this.clientStateArgs.pay.stone, this.clientStateArgs.pay.gold, isMax);
                            }
                            if (this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0) {
                                this.addActionButton('incrementGetWood', "+1" + this.getTokenSymbol('wood', true), 'incrementTradeGetWood', null, false, 'gray');
                                this.addTooltipHtml('incrementGetWood', _("Increment number of wood"));
                                this.addActionButton('incrementGetStone', "+1" + this.getTokenSymbol('stone', true), 'incrementTradeGetStone', null, false, 'gray');
                                this.addTooltipHtml('incrementGetStone', _("Increment number of stone"));
                                this.addActionButton('incrementGetGold', "+1" + this.getTokenSymbol('gold', true), 'incrementTradeGetGold', null, false, 'gray');
                                this.addTooltipHtml('incrementGetGold', _("Increment number of gold"));

                                var isMax = (this.clientStateArgs.get.wood + this.clientStateArgs.get.stone + this.clientStateArgs.get.gold) == this.clientStateArgs.max;
                                if (isMax || this.clientStateArgs.pay.cocoa == 0) {
                                    dojo.query('#incrementGetWood').addClass('disabled');
                                    dojo.query('#incrementGetStone').addClass('disabled');
                                    dojo.query('#incrementGetGold').addClass('disabled');
                                } else {
                                    dojo.query('#incrementGetWood').removeClass('disabled');
                                    dojo.query('#incrementGetStone').removeClass('disabled');
                                    dojo.query('#incrementGetGold').removeClass('disabled');
                                }
                            }
                            if (this.isFreeCocoa() && this.clientStateArgs.isPayCocoa > 0) {
                                this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'doTradeConfirmedFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            cancelConfirm = true;
                            cancelMessage = _('reset');
                            break;
                        case 'playerTurn_upgrade_workers':
                            if (args.lockedWorkers > 0) {
                                this.addActionButton('button_1_id', _('Unlock all Workers') + "(-3" + this.getTokenSymbol('cocoa', true) + ")", 'unlockWorkersAndPayConfirmed', null, false, 'gray');

                                if (this.isFreeCocoa()) {
                                    this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'unlockWorkersAndPayConfirmedFree', null, false, 'gray');
                                    this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                                }
                            }
                            break;
                        case 'playerTurn_upgrade_workers_buy':
                            this.addActionButton('button_1_id', _('Buy') + "(-1" + this.getTokenSymbol('cocoa', true) + ")", 'buyPowerUpsConfirmed', null, false, 'gray');
                            this.addActionButton('button_pass_id', _('Skip action and pass'), 'onPassClick', null, false, "red");
                            break;
                        case 'playerTurn_construction':
                            if(args.canPass){
                                this.addActionButton('button_pass_id', _('Skip action and pass'), 'onPassClick', null, false, "red");
                            }
                            break;
                        case 'client_playerTurn_buildPyramid_confirm':

                            if (this.isConstructionWorkerTechAquired) {
                                this.addActionButton('decrementGetWood', "-1" + this.getTokenSymbol('wood', true), 'decrementConstuctionGetWood', null, false, 'gray');
                                this.addTooltipHtml('decrementGetWood', _("Decrement number of wood"));
                                this.addActionButton('decrementStone', "-1" + this.getTokenSymbol('stone', true), 'decrementConstuctionGetStone', null, false, 'gray');
                                this.addTooltipHtml('decrementStone', _("Decrement number of stone"));

                                var level = parseInt(dojo.attr(this.constructionWrapper, "data-level"));

                                if (this.clientStateArgs.wood > 0 && this.clientStateArgs.wood == level && this.clientStateArgs.stone == 2) {
                                    dojo.query('#decrementGetWood').removeClass('disabled');
                                } else {
                                    dojo.query('#decrementGetWood').addClass('disabled');
                                }
                                if (this.clientStateArgs.wood == level && this.clientStateArgs.stone == 2) {
                                    dojo.query('#decrementStone').removeClass('disabled');
                                } else {
                                    dojo.query('#decrementStone').addClass('disabled');
                                }
                                if (this.clientStateArgs.wood < level && this.clientStateArgs.stone == 2) {
                                    dojo.query('#incrementGetWood').removeClass('disabled');
                                } else {
                                    dojo.query('#incrementGetWood').addClass('disabled');
                                }
                                if (this.clientStateArgs.wood == level && this.clientStateArgs.stone == 1) {
                                    dojo.query('#incrementStone').removeClass('disabled');
                                } else {
                                    dojo.query('#incrementStone').addClass('disabled');
                                }
                            }
                            this.addActionButton('button_build', _('Build'), 'doBuildPyramidConfirmed', null, false, 'gray');
                            cancelConfirm = true;
                            cancelMessage = _('reset');
                            break;
                        case 'client_playerTurn_buildDecoration_confirm':
                            this.addActionButton('button_build', _('Build'), 'doBuildDecorationConfirmed', null, false, 'gray');
                            cancelConfirm = true;
                            break;
                        case 'client_playerTurn_paySalary_confirm':
                            this.gamedatas_local.playersHand = args.player_hand;
                            if (this.clientStateArgs) {
                                this.addActionButton('decrementCocoa', "-1" + this.getTokenSymbol('cocoa', true), 'decrementSalaryCocoa', null, false, 'gray');
                                this.addTooltipHtml('decrementCocoa', _("Decrement number of cocoa"));
                                this.addActionButton('incrementCocoa', "+1" + this.getTokenSymbol('cocoa', true), 'incrementSalaryCocoa', null, false, 'gray');
                                this.addTooltipHtml('incrementCocoa', _("Increment number of cocoa"));

                                var isMax = this.clientStateArgs.cocoa == this.clientStateArgs.max;
                                if (this.clientStateArgs.cocoa == 0) {
                                    dojo.query('#decrementCocoa').addClass('disabled');
                                } else {
                                    dojo.query('#decrementCocoa').removeClass('disabled');
                                }
                                console.log('cocoa', this.gamedatas_local.players[this.getThisPlayerId()].cocoa);
                                if (isMax || this.clientStateArgs.cocoa == this.gamedatas_local.players[this.getThisPlayerId()].cocoa ) {
                                    dojo.query('#incrementCocoa').addClass('disabled');
                                } else {
                                    dojo.query('#incrementCocoa').removeClass('disabled');
                                }
                            }
                            if (this.isFreeCocoa()) {
                                this.addActionButton('cocoa_free', this.getTokenSymbol('cocoa_free', true), 'doSalaryConfirmedFree', null, false, 'gray');
                                this.addTooltipHtml('cocoa_free', _("Ignore paying cocoa"));
                            }
                            this.addActionButton('button_build', _('Pay'), 'doSalaryConfirmed', null, false, 'gray');
                            break;
                        case 'choose_starting_tiles':
                            this.addActionButton('button_build', _('Choose'), 'doStartingTilesConfirmed', null, false, 'gray');
                            break;
                        case 'choose_starting_tiles_draft':
                            this.addActionButton('button_build', _('Choose'), 'doStartingTilesDraftConfirmed', null, false, 'gray');
                            break;
                        case 'choose_starting_tiles_choose_resources_confirm':
                            var isMax = (this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) == this.clientStateArgs.max;
                            if (this.clientStateArgs && isMax) {
                                this.addActionButton('done', _('Done'), 'doChooseStartResourceConfirmed', null, false, 'gray');
                                dojo.query('#done').removeClass('disabled');
                            } else {
                                this.addActionButton('done', _('Done'), 'doChooseStartResourceConfirmed', null, false, "gray");
                                dojo.query('#done').addClass('disabled');
                            }
                            this.addActionButton('incrementWood', "+1" + this.getTokenSymbol('wood', true), 'incrementStartWood', null, false, 'gray');
                            this.addTooltipHtml('incrementWood', _("Increment number of wood"));
                            this.addActionButton('incrementStone', "+1" + this.getTokenSymbol('stone', true), 'incrementStartStone', null, false, 'gray');
                            this.addTooltipHtml('incrementStone', _("Increment number of stone"));
                            this.addActionButton('incrementGold', "+1" + this.getTokenSymbol('gold', true), 'incrementStartGold', null, false, 'gray');
                            this.addTooltipHtml('incrementGold', _("Increment number of gold"));

                            this.checkIfButtonsAreEnabled(this.clientStateArgs.wood, this.clientStateArgs.stone, this.clientStateArgs.gold, isMax);
                            break;
                        case 'claim_starting_discovery_tiles':
                            this.addActionButton('button_1_id', _('Done'), 'onPassClick', null, false, 'gray');
                            break;
                        case 'client_playerTurn_ascension_cocoaFree_confirm':
                            this.addActionButton('button_1_id', _('Yes'), 'onAscensionFreeCocoaAccepted', null, false, 'gray');
                            this.addActionButton('button_2_id', _('No'), 'onAscensionFreeCocoaDeclined', null, false, 'red');
                            break;
                        case 'playerTurn_check_undo':
                            this.addActionButton('button_1_id', _('End turn'), 'noUndo', null, false, 'gray');
                            this.addActionButton('button_2_id', _('Undo complete turn'), 'undo', null, false, 'red');
                            break;
                    }

                    if (cancelConfirm) {
                        this.addActionButton('button_cancel', cancelMessage, dojo.hitch(this, function () {
                            this.restoreServerGameState();
                        }), null, false, "red");
                    }
                }
            },

            ///////////////////////////////////////////////////
            //// Utility methods

            /*

                Here, you can defines some utility methods that you can use everywhere in your javascript
                script.

            */

            /** Override this function to inject html for log items  */

            /* @Override */
            format_string_recursive: function (log, args) {
                try {
                    if (log && args && !args.processed) {
                        args.processed = true;

                        if (!this.isSpectator)
                            args.You = this.divYou(); // will replace ${You} with colored version

                        for (var key in args) {
                            if (args[key] && typeof args[key] == 'string' && key.indexOf('token_') == 0) {
                                args[key] = this.getTokenDiv(key, args);
                            }
                        }
                    }
                } catch (e) {
                    console.error(log, args, "Exception thrown", e.stack);
                }
                return this.inherited(arguments);
            },

            checkIfButtonsAreEnabled: function (wood, stone, gold, isMax) {
                if (isMax || (this.clientStateArgs && wood == this.clientStateArgs.maxWood)) {
                    dojo.query('#incrementWood').addClass('disabled');
                } else {
                    dojo.query('#incrementWood').removeClass('disabled');
                }
                if (isMax || (this.clientStateArgs && stone == this.clientStateArgs.maxStone)) {
                    dojo.query('#incrementStone').addClass('disabled');
                } else {
                    dojo.query('#incrementStone').removeClass('disabled');
                }
                if (isMax || (this.clientStateArgs && gold == this.clientStateArgs.maxGold)) {
                    dojo.query('#incrementGold').addClass('disabled');
                } else {
                    dojo.query('#incrementGold').removeClass('disabled');
                }
            },

            getTokenDiv: function (key, args) {
                var token_id = args[key];
                var tokenDiv = this.getTokenSymbol(token_id, true);
                return tokenDiv;
            },

            getDiscoveryIconDiv: function (key, args) {
                var token_id = args[key];
                var tokenDiv = this.getTokenSymbol(token_id, true);
                return tokenDiv;
            },

            /* Implementation of proper colored You with background in case of white or light colors  */

            divYou: function () {
                var color_bg = "";
                if (this.player_color_back) {
                    color_bg = "background-color:#" + this.player_color_back + ";";
                }
                var you = "<span style=\"font-weight:bold;color:#" + this.player_color + ";" + color_bg + "\">" + __("lang_mainsite", "You") + "</span>";
                return you;
            },

            queryAndStyle: function (query, style, value) {
                var queueEntries = dojo.query(query);
                for (var i = 0; i < queueEntries.length; i++) {
                    dojo.style(queueEntries[i], style, value);
                }
            },

            queryAndAddEvent: function (query, event, callback) {
                var queueEntries = dojo.query(query);
                for (var i = 0; i < queueEntries.length; i++) {
                    this.disconnect(queueEntries[i], event); //disconnect same event
                    this.connect(queueEntries[i], event, callback);
                }
            },
            queryAndAddEventThis: function (_this, query, event, callback) {
                var queueEntries = dojo.query(query);
                for (var i = 0; i < queueEntries.length; i++) {
                    _this.disconnect(queueEntries[i], event); //disconnect same event
                    _this.connect(queueEntries[i], event, callback);
                }
            },

            /**
             * function wrapper for this.player_id,
             * it checks if spectator
             */
            getThisPlayerId: function(){
                if (!this.isSpectator){
                    return this.player_id;
                }
                if (!this.cocFirstPlayerId){
                    this.cocFirstPlayerId = Object.keys(this.gamedatas_local.players)[0];
                }
                return this.cocFirstPlayerId;
            },

            bindData: function (object) {
                var queueEntries = dojo.query("*[data-binding]");
                for (var i = 0; i < queueEntries.length; i++) {
                    var context = object;
                    var js = queueEntries[i].getAttribute("data-binding");
                    var _me = this;
                    try {
                        var value = function () {
                            var me = _me;
                            var b = this;
                            return eval(js);
                        }.call(context);
                        if (queueEntries[i].innerHTML !== value) {
                            dojo.removeClass(queueEntries[i], "change_value");
                            dojo.addClass(queueEntries[i], "change_value");
                            queueEntries[i].innerHTML = value;
                        }
                    } catch (error) {
                        //nothing
                    }
                }
            },

            getPlayerData: function () {
                return this.gamedatas_local.players[this.getActivePlayerId()];
            },

            getCountWorkersOnBoardFromPlayer: function (board_id, player_id) {
                var result = 0;
                for (i in this.gamedatas_local.map) {
                    if (player_id == i) {
                        var map_player = this.gamedatas_local.map[i];
                        for (j in map_player) {
                            var map = map_player[j];
                            if (board_id == map.actionboard_id && map.locked == false) {
                                result++;
                            }
                        }
                    }
                }
                return result;
            },

            checkIsMapComplete: function () {
                $("workers").innerHTML = '';
                for (player_id in this.gamedatas_local.map) {
                    for (var index in this.gamedatas_local.map[player_id]) {
                        var map = this.gamedatas_local.map[player_id][index];
                        var worker_id = player_id + '_worker_' + map.worker_id;
                        if (!$(worker_id)) {
                            var nextBoard = map.actionboard_id;
                            var dice_board = 'aBoard_' + nextBoard;
                            var dice_group = dice_board + '_dGroup_' + player_id + '';
                            var workersAlreadyonBoard = 0;

                            for (var i = 0; i < 4; i++) {
                                target = 'POS_' + dice_group + '_dice_' + i;
                                if (!$(target).hasChildNodes()) {
                                    workersAlreadyonBoard = i;
                                    break;
                                }
                            }
                            this.createWorker(map.player_id, map.worker_id, map.worker_power, map.locked, map.worship_pos, nextBoard, workersAlreadyonBoard);
                            break;
                        }
                    }
                }
                this.setupPlayerHand(true);
                this.resizeGame();
            },

            getDiffrentColorsOnBoard: function (board_id) {
                var result = 0;
                for (i in this.gamedatas_local.map) {
                    var map_player = this.gamedatas_local.map[i];
                    var workers = 0;
                    for (j in map_player) {
                        var map = map_player[j];
                        if (board_id == map.actionboard_id && map.locked == false) {
                            var selectedWorker = $(this.getActivePlayerId() + '_worker_' + this.selected_worker_id);
                            if($(selectedWorker)){
                                var selectedWorkerBoard = dojo.attr(selectedWorker, "data-board-id");
                                if(!(this.isPalaceTechAquired && board_id == selectedWorkerBoard && map.worker_id == this.selected_worker_id && map.player_id == this.getActivePlayerId())){
                                    workers++;
                                }
                            }
                        }
                    }
                    if (workers > 0) {
                        result++;
                    }
                }
                return result;
            },

            setAllWorkersClickable: function (clickableWorkers) {
                dojo.query('.dice.clickable').removeClass('clickable');
                for (var i = 0; i < clickableWorkers.length; i++) {
                    var workerName = this.getActivePlayerId() + '_worker_' + clickableWorkers[i];
                    if ($(workerName)) {
                        dojo.addClass(workerName, 'clickable');
                    }
                }
            },

            deselectAll: function () {
                dojo.query('.dice.selected').removeClass('selected');
                dojo.query('.dice.clickable').removeClass('clickable');
                dojo.query('.actionBoard.selected').removeClass('selected');
                dojo.query('.actionBoard.clickable').removeClass('clickable');
                dojo.query('.btnTemple.clickable').removeClass('clickable');
                dojo.query('.discoveryTile.clickable').removeClass('clickable');
                dojo.query('.btnAvenue.clickable').removeClass('clickable');
                dojo.query('.ascension.clickable').removeClass('clickable');
                dojo.query('.btnNobles.clickable').removeClass('clickable');
                dojo.query('.royalTile.clickable').removeClass('clickable');
                dojo.query('.technologyTile.clickable').removeClass('clickable');
                dojo.query('.actionBoard .pyramidTile.clickable').removeClass('clickable');
                dojo.query('.actionBoard .pyramidTile.selected').removeClass('selected');
                dojo.query('.construction-wrapper.clickable').removeClass('clickable');
                dojo.query('.construction-wrapper.selected').removeClass('selected');
                dojo.query('.actionBoard .decorationTile.clickable').removeClass('clickable');
                dojo.query('.actionBoard .decorationTile.selected').removeClass('selected');
                dojo.query('.pyramid_decoration-wrapper.clickable').removeClass('clickable');
                dojo.query('.pyramid_decoration-wrapper.selected').removeClass('selected');
                dojo.query('.royalTile .number.show').removeClass('show');
                dojo.query('#claimDiscovery-zone.show').removeClass('show');

                this.clickableWorkers = [];
            },

            moneyPreview: function (moneySymbol) {
                var money = 0;
                if (moneySymbol == 'cocoa') {
                    money = Number(this.getPlayerData().cocoa);
                } else if (moneySymbol == 'wood') {
                    money = Number(this.getPlayerData().wood);
                } else if (moneySymbol == 'stone') {
                    money = Number(this.getPlayerData().stone);
                } else if (moneySymbol == 'gold') {
                    money = Number(this.getPlayerData().gold);
                }
                if (this.isPalaceTechAquired && this.selected_board_id_to <= this.selected_board_id_from && this.selected_board_id_from != 1) {
                    money++;
                    if(this.global_moveTwoWorkers && this.selected_worker2_id){
                        money++;
                    }
                }
                return dojo.string.substitute(_("${price}${moneySymbol}"), {
                    money: money,
                    price: this.formatNumberWithSign(-this.clientStateArgs.price),
                    result: money - this.clientStateArgs.price,
                    moneySymbol: this.getTokenSymbol(moneySymbol)
                });
            },

            getMoneySymbol: function (type, smaller) {
                return this.getTokenSymbol(type, smaller);
            },

            getTokenSymbol: function (type, smaller) {
                if (smaller) {
                    return '<span class="token24 tokentext ' + type + '" style="zoom:70%"> </span>';
                }
                return '<span class="token24 tokentext ' + type + '"> </span>';
            },

            formatNumberWithSign: function (numberValue) {
                if (numberValue >= 0) return "+" + numberValue;
                return "" + numberValue;
            },

            setClientStateAction: function (stateName, desc) {
                var args = dojo.clone(this.gamedatas.gamestate.args);
                if (args == null) {
                    args = {};
                }
                args.actname = this.getTr(this.clientStateArgs.action);
                this.setClientState(stateName, {
                    descriptionmyturn: this.getTr(desc),
                    args: args
                });
            },

            getTr: function (name) {
                if (typeof name.log != 'undefined') {
                    name = this.format_string_recursive(name.log, name.args);
                } else {
                    name = this.clienttranslate_string(name);
                }
                return name;
            },

            /** More convenient version of ajaxcall, do not to specify game name, and any of the handlers */
            ajaxAction: function (action, args, func, err, lock) {
                if (!args) {
                    args = [];
                }
                delete args.action;
                if (!args.hasOwnProperty('lock') || args.lock) {
                    args.lock = true;
                } else {
                    delete args.lock;
                }
                if (typeof func == "undefined" || func == null) {
                    var self = this;
                    func = function (result) {
                    };
                }

                // restore server server if error happened
                if (typeof err == "undefined") {
                    var self = this;
                    err = function (iserr, message) {
                        if (iserr) {
                            self.cancelLocalStateEffects();
                        }
                    };
                }
                var name = this.game_name;
                this.ajaxcall("/" + name + "/" + name + "/" + action + ".html", args, this, func, err);
            },

            cancelLocalStateEffects: function () {
                if (this.on_client_state) {
                    this.clientStateArgs = {
                        action: 'none',
                    };
                    this.gamedatas_local = dojo.clone(this.gamedatas);
                    if (this.restoreList) {
                        var restoreList = this.restoreList;
                        this.restoreList = [];
                        for (var i = 0; i < restoreList.length; i++) {
                            //var token = restoreList[i];
                            //var tokenInfo = this.gamedatas.tokens[token];
                            //this.placeTokenWithTips(token, tokenInfo, true);
                        }
                    }
                }
                //workaround for problem restoreServerGameState and error calculating reflexion times...
                try {
                    if (this.last_server_state && this.last_server_state && this.last_server_state.reflexion && !this.last_server_state.reflexion.initial_ts) {
                        this.last_server_state.reflexion.initial_ts = dojo.clone(this.gamedatas.gamestate.reflexion.initial_ts);
                    }
                    if (this.last_server_state && this.last_server_state && this.last_server_state.reflexion && !this.last_server_state.reflexion.initial) {
                        this.last_server_state.reflexion.initial = dojo.clone(this.gamedatas.gamestate.reflexion.initial);
                    }
                } catch (err) {
                    //nothing
                }
                this.restoreServerGameState();
            },

            isWorshipPossible: function (worship_pos) {
                var player_id = this.getActivePlayerId();
                for (var index in this.gamedatas_local.map[player_id]) {
                    var map = this.gamedatas_local.map[player_id][index];
                    if (map.actionboard_id == this.selected_board_id_to &&
                        map.locked == true &&
                        map.worship_pos == worship_pos) {
                        return false;
                    }
                }

                return true;
            },

            isFreeCocoa: function () {
                var player_id = this.getThisPlayerId();
                var discoveryTiles_other = this.gamedatas_local.playersHand[player_id]['other'];

                for (var discoveryTile_index in discoveryTiles_other) {
                    var discoveryTile = discoveryTiles_other[discoveryTile_index];
                    var freeCocoa = parseInt(this.gamedatas_local.discoveryTiles_data[discoveryTile['type_arg']].bonus.free_cocoa);
                    if (freeCocoa > 0) {
                        return true;
                    }
                }
                return false;
            },

            isWorshipFromOthersTaken: function (worship_pos) {
                var player_id = this.getActivePlayerId();
                for (var player in this.gamedatas_local.map) {
                    for (var index in this.gamedatas_local.map[player]) {
                        var map_player = this.gamedatas_local.map[player][index];
                        if (map_player.player_id != player_id &&
                            map_player.actionboard_id == this.selected_board_id_to &&
                            map_player.locked == true &&
                            map_player.worship_pos == worship_pos) {
                            return true;
                        }
                    }
                }

                return false;
            },

            animateWorker: function (player_id, worship_pos, worker_id, nextBoard, workersAlreadyonBoard) {
                var worker = player_id + '_worker_' + worker_id;
                var dice_board = 'aBoard_' + nextBoard;
                var dice_group = dice_board + '_dGroup_' + player_id + '';
                var board_id = parseInt(dojo.attr($(worker), "data-board-id"));
                var target = '';
                var workersAlreadyonBoardTemp = 0;

                for (var i = 0; i < 4; i++) {
                    target = 'POS_' + dice_group + '_dice_' + i;
                    if (!$(target).hasChildNodes()) {
                        workersAlreadyonBoardTemp = i;
                        break;
                    }
                }
                if(workersAlreadyonBoard != null){
                    workersAlreadyonBoard += workersAlreadyonBoardTemp;
                } else {
                    workersAlreadyonBoard = workersAlreadyonBoardTemp;
                }
                if (board_id == nextBoard) {
                    target = $(worker).parentElement;
                }
                if (worship_pos > 0) {
                    target = 'POS_' + dice_board + '_dice_worship_' + worship_pos;
                }
                var source = $(worker).parentElement;

                for (var index in this.gamedatas_local.map[player_id]) {
                    var map = this.gamedatas_local.map[player_id][index];
                    if (map.worker_id == worker_id) {
                        map.actionboard_id = nextBoard;
                        if (worship_pos > 0) {
                            map.locked = true;
                            map.worship_pos = worship_pos;
                        }
                        break;
                    }
                }

                this.slideTemporaryObject($(worker), 'workers', source, target);

                var _this = this;

                dojo.attr(worker, "data-board-id", nextBoard);

                setTimeout(function () {
                    for (var index in _this.gamedatas_local.map[player_id]) {
                        var map = _this.gamedatas_local.map[player_id][index];
                        if (map.worker_id == worker_id) {
                            _this.createWorker(map.player_id, map.worker_id, map.worker_power, map.locked, map.worship_pos, nextBoard, workersAlreadyonBoard);
                            break;
                        }
                    }

                }, 500);
            },

            animateStep: function (marker, player_id, next_step) {
                var source = $(marker).parentElement;
                var target = $(next_step);

                this.slideTemporaryObject($(marker), 'workers', source, target);

                var _this = this;

                setTimeout(function () {
                    dojo.place(_this.format_block('jstpl_markerOntable', {
                        id: marker,
                        player_color: _this.gamedatas_local.players[player_id].player_color,
                    }), target);

                    _this.resizeGame();
                }, 500);
            },

            animateResource: function (player_id, amount, token, source, target, delay) {
                var token_symbol = this.getTokenSymbol(token, false);
                var _this = this;

                if (token == 'cocoa') {
                    this.gamedatas_local.players[player_id].cocoa = parseInt(this.gamedatas_local.players[player_id].cocoa) + amount;
                    if(this.gamedatas_local.players[player_id].cocoa < 0){
                        this.gamedatas_local.players[player_id].cocoa = 0;
                    }
                    if(this.clientStateArgs && this.clientStateArgs.action && this.clientStateArgs.action == 'paySalary'){
                        this.clientStateArgs.cocoa = this.gamedatas_local.players[player_id].cocoa;
                        if(this.clientStateArgs.cocoa > this.clientStateArgs.max){
                            this.clientStateArgs.cocoa = this.clientStateArgs.max;
                            this.paySalaryConfirm();
                        }
                    }
                } else if (token == 'wood') {
                    this.gamedatas_local.players[player_id].wood = parseInt(this.gamedatas_local.players[player_id].wood) + amount;
                    if(this.gamedatas_local.players[player_id].wood < 0){
                        this.gamedatas_local.players[player_id].wood = 0;
                    }
                } else if (token == 'stone') {
                    this.gamedatas_local.players[player_id].stone = parseInt(this.gamedatas_local.players[player_id].stone) + amount;
                    if(this.gamedatas_local.players[player_id].stone < 0){
                        this.gamedatas_local.players[player_id].stone = 0;
                    }
                } else if (token == 'gold') {
                    this.gamedatas_local.players[player_id].gold = parseInt(this.gamedatas_local.players[player_id].gold) + amount;
                    if(this.gamedatas_local.players[player_id].gold < 0){
                        this.gamedatas_local.players[player_id].gold = 0;
                    }
                }

                if (amount < 0) {
                    amount = -amount;
                }

                setTimeout(function () {
                    for (var i = 0; i < amount; i++) {
                        _this.slideTemporaryObject(token_symbol, 'workers', source, target, 2000, 200 * i);
                    }
                }, delay);


                setTimeout(function () {
                    _this.bindData(_this.gamedatas_local);
                }, delay + 2000 + amount * 200);
            },

            animateVP: function (player_id, amount, token, source, target, delay) {
                var token_symbol = this.getTokenSymbol(token, false);
                var _this = this;
                setTimeout(function () {
                    for (var i = 0; i < amount; i++) {
                        if ($('workers') != null && $(source) != null && $(target) != null) {
                            _this.slideTemporaryObject(token_symbol, 'workers', source, target, 2000, 200 * i);
                        }
                    }
                }, delay);

                this.gamedatas_local.players[player_id].score = parseInt(this.gamedatas_local.players[player_id].score) + amount;
                setTimeout(function () {
                    _this.bindData(_this.gamedatas_local);
                    $('player_score_' + player_id).innerHTML = _this.gamedatas_local.players[player_id].score;
                }, delay + 2000 + amount * 200);
            },

            animateClaimDiscovery: function (discTile, target) {
                var source = $("discoveryTile_" + discTile.type_arg).parentElement;

                dojo.place(this.format_block('jstpl_discoveryTiles', {
                    type_arg: "-1",
                    location: ""
                }), target);

                this.resizeGame();

                this.slideTemporaryObject($("discoveryTile_" + discTile.type_arg), 'workers', source, 'discoveryTile_-1', 2000);

                var _this = this;

                var tooltip = this.getDiscoveryTileTooltip(discTile.type_arg);

                setTimeout(function () {
                    $("discoveryTile_-1").remove();
                    if($("discoveryTile_" + discTile.type_arg)){
                        $("discoveryTile_" + discTile.type_arg).remove();
                    }

                    dojo.place(_this.format_block('jstpl_discoveryTiles', {
                        type_arg: discTile.type_arg,
                        location: "hand"
                    }), target);

                    _this.queryAndAddEventThis(_this, '.discoveryTile', 'onclick', 'onDiscoveryClick');

                    if (_this.isCurrentPlayerActive()) {
                        dojo.query('#other_' + _this.getActivePlayerId() + ' .discoveryTile').addClass('clickable');
                    }

                    _this.resizeGame();
                }, 2000);

                setTimeout(function () {
                    _this.addTooltipHtml("discoveryTile_" + discTile.type_arg, tooltip);
                }, 2100);

            },
            animateBuilding: function (source, target) {
                dojo.style(target, 'display', 'block');
                dojo.style(target, 'opacity', 0);
                var parent = $(source);
                this.slideToObjectAndDestroy($(source), target, 2000);

                var _this = this;

                setTimeout(function () {
                    dojo.style(target, 'opacity', 1);
                }, 2000);

            },
            setTempleDiscoveryTilesClickable: function () {
                var player_id = this.getActivePlayerId();
                var temple = this.global_last_temple_id;
                var query = '';
                if (temple == 1) {
                    temple = 'blue';
                    query = '#btnTemple_' + temple + '_discoveryTile_0 .discoveryTile';
                } else if (temple == 2) {
                    temple = 'red';
                    query = '#btnTemple_' + temple + '_discoveryTile_0 .discoveryTile';
                } else if (temple == 3) {
                    temple = 'green';
                    query = '#btnTemple_' + temple + '_discoveryTile_0 .discoveryTile';
                    var step = this.gamedatas_local.players[player_id].temple_green;
                    if (step == 6) {
                        query = '#btnTemple_' + temple + '_discoveryTile_1 .discoveryTile';
                    }
                }
                if (query != '') {
                    dojo.query(query).addClass('clickable');
                }
            },

            ///////////////////////////////////////////////////
            //// Player's action

            /*

                Here, you are defining methods to handle player's action (ex: results of mouse click on
                game objects).

                Most of the time, these methods:
                _ check the action is possible at this game state.
                _ make a call to the game server

            */

            onActionBoardsSelectionChanged: function (event) {
                dojo.stopEvent(event);
                var target = event.target;
                while (!dojo.hasClass(target, 'actionBoard')) {
                    target = target.parentElement;
                }
                if (dojo.hasClass(target, 'clickable')) {
                    if (this.isGamePreparation) {
                        if (this.checkAction("placeWorker")) {
                            var board_pos = dojo.attr(target, "id").split('_')[1];
                            var board_id = dojo.attr(target, "data-id");
                            dojo.query('#actionBoard_' + board_pos).removeClass('clickable');
                            this.ajaxAction('placeWorker', {
                                board_id: board_id,
                                board_pos: board_pos
                            });
                        }
                    } else if (this.checkAction("selectBoard")) {
                        dojo.query('.actionBoard.clickable').removeClass('clickable');
                        dojo.query('.dice.clickable').removeClass('clickable');
                        dojo.addClass(target, 'selected');

                        var action = 'showBoardActions';
                        var id = dojo.attr(target, "data-pos");
                        this.selected_board_id_to = id;
                        this.ajaxAction(action, {
                            board_id_to: id,
                            board_id_from: this.selected_board_id_from,
                            worker_id: this.selected_worker_id,
                            worker2_id: this.selected_worker2_id
                        });
                    }

                }
            },

            onDiceSelectionChanged: function (event) {
                dojo.stopEvent(event);
                if (dojo.hasClass(event.target, 'clickable')) {
                    if (this.checkPossibleActions("selectDice")) {
                        dojo.query('.dice.firstWorker').removeClass('firstWorker');
                        dojo.query('.dice.secondWorker').removeClass('secondWorker');
                        var board_id = parseInt(dojo.attr(event.target, "data-board-id"));
                        var worker_id = dojo.attr(event.target, "data-worker-id");
                        this.selected_worker2_id = 0;

                        if (this.global_moveTwoWorkers == true) {
                            if (dojo.query('.dice.clickable.selected').length == 0) {
                                this.selected_worker_id = worker_id;
                            } else if (dojo.query('.dice.clickable.selected').length == 1) {
                                if (board_id != this.selected_board_id_from) {
                                    dojo.query('.dice.clickable.selected').removeClass('selected');
                                    this.selected_worker_id = worker_id;
                                } else {
                                    if (worker_id != this.selected_worker_id) {
                                        this.selected_worker2_id = worker_id;
                                    }
                                }
                            } else if (dojo.query('.dice.clickable.selected').length >= 2) {
                                dojo.query('.dice.clickable.selected').removeClass('selected');
                                this.selected_worker_id = worker_id;
                            }
                        } else {
                            dojo.query('.dice.clickable.selected').removeClass('selected');
                            this.selected_worker_id = worker_id;
                        }

                        dojo.query('.actionBoard.clickable').removeClass('clickable');
                        dojo.addClass(event.target, 'selected');

                        if (this.global_moveTwoWorkers == true) {
                            dojo.addClass(this.getActivePlayerId() + '_worker_' + this.selected_worker_id, 'firstWorker');
                            if($(this.getActivePlayerId() + '_worker_' + this.selected_worker2_id)){
                                dojo.addClass(this.getActivePlayerId() + '_worker_' + this.selected_worker2_id, 'secondWorker');
                            }
                        }

                        this.selected_board_id_from = board_id;

                        if (board_id > 0) {
                            var next_board = board_id + 1;
                            var max = 3;
                            if (this.global_moveAnywhere == true) {
                                max = 8;
                            }
                            for (var i = 0; i < max; i++) {
                                if (next_board > 8) {
                                    next_board = 1;
                                }
                                dojo.addClass('actionBoard_' + next_board, 'clickable');
                                next_board++;
                            }
                        }
                    } else if (this.checkPossibleActions("upgradeWorker")) {
                        var worker_id = dojo.attr(event.target, "data-worker-id");
                        var board_id_from = parseInt(dojo.attr(event.target, "data-board-id"));
                        var action = 'upgradeWorker';
                        this.ajaxAction(action, {
                            id: worker_id,
                            board_id_from: board_id_from
                        });
                    }
                }
            },

            onTempleStepChanged: function (event) {
                dojo.stopEvent(event);
                if (dojo.hasClass(event.target, 'clickable')) {
                    var action = 'stepTemple';
                    if (this.checkAction(action)) {
                        var temple = dojo.attr(event.target, "data-temple");
                        this.ajaxAction(action, {
                            temple: temple
                        });
                    }
                }
            },
            onAvenueStepChanged: function (event) {
                dojo.stopEvent(event);
                if (dojo.hasClass(event.target, 'clickable')) {
                    var action = 'stepAvenue';
                    if (this.checkAction(action)) {
                        this.ajaxAction(action);
                    }
                }
            },

            cancelMoveToBoard: function (event) {
                var action = 'cancelMoveToBoard';

                if (!this.checkAction(action)) {
                    return;
                }

                this.ajaxAction(action);
            },

            doBoardMainActionClick: function (event) {
                dojo.stopEvent(event);

                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'doMainActionOnBoard';
                actionConfirm = 'client_playerTurn_mainAction_confirm';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }
                    this.clientStateArgs = {};

                    this.clientStateArgs.action = action;
                    this.clientStateArgs.price = this.getDiffrentColorsOnBoard(this.selected_board_id_to);
                    this.clientStateArgs.price_token = 0;

                    if (this.clientStateArgs.price != 0) {
                        var translated = _("Perform Main Action?") + " " + this.moneyPreview("cocoa");
                        this.setClientStateAction(actionConfirm, translated);
                    } else {
                        if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                            this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                                this.ajaxAction(action);
                            }));
                            return;
                        } else {
                            this.ajaxAction(action);
                        }
                    }
                }
            },

            doBoardMainActionClickConfirmed: function (event) {
                var action = 'doMainActionOnBoard';

                if (!this.checkAction(action)) {
                    return;
                }

                if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                    this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                        this.ajaxAction(action);
                    }));
                    return;
                } else {
                    this.ajaxAction(action);
                }
            },
            doBoardMainActionClickConfirmedFree: function (event) {
                var action = 'doMainActionOnBoard';

                if (!this.checkAction(action)) {
                    return;
                }
                this.ajaxAction(action, {
                    freeCocoa: true,
                });
            },
            unlockWorkersAndPayConfirmed: function (event) {
                var action = 'unlockAllWorkers';

                if (!this.checkAction(action)) {
                    return;
                }
                this.ajaxAction(action, {
                    pay: true,
                    freeCocoa: false
                });
            },
            unlockWorkersAndPayConfirmedFree: function (event) {
                var action = 'unlockAllWorkers';

                if (!this.checkAction(action)) {
                    return;
                }
                this.ajaxAction(action, {
                    pay: true,
                    freeCocoa: true
                });
            },
            buyPowerUpsConfirmed: function (event) {
                var action = 'buyPowerUp';

                if (!this.checkAction(action)) {
                    return;
                }
                this.ajaxAction(action);
            },

            unlockWorkersFreeConfirmed: function (event) {
                var action = 'unlockAllWorkers';

                if (!this.checkAction(action)) {
                    return;
                }

                this.ajaxAction(action, {
                    pay: false,
                });
            },

            undo: function (event) {
                var action = 'undo';
                this.ajaxAction(action);
            },

            onPassClick: function (event) {
                var action = 'pass';

                if (!this.checkAction(action)) {
                    return;
                }
                if ((this.stateName == 'playerTurn_worship_actions' || this.stateName == 'claim_starting_discovery_tiles') &&
                    (this.gamedatas_local.global.worship_actions_discovery || this.clientStateArgs && (this.clientStateArgs.templeQueue || this.clientStateArgs.royalTileAction))) {
                    var message = '';

                    if (this.clientStateArgs && this.clientStateArgs.templeQueue) {
                        message = message + _('<br>- step on Temple');
                    }
                    if (this.clientStateArgs && this.clientStateArgs.royalTileAction) {
                        message = message + _('<br>- royal tile');
                    }
                    if (this.gamedatas_local.global.worship_actions_discovery) {
                        message = message + _('<br>- claim discovery tile');
                    }
                    this.confirmationDialog(_('There are actions left:') + message, dojo.hitch(this, function () {
                        this.ajaxAction(action);
                    }));
                    return;
                } else {
                    this.ajaxAction(action);
                }
            },

            unlockAllWorkersClick: function (event) {
                dojo.stopEvent(event);

                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'unlockAllWorkers';
                actionConfirm = 'client_playerTurn_unlockAllWorkers_and_move_confirm';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }

                    this.clientStateArgs = {};

                    this.clientStateArgs.action = action;
                    this.clientStateArgs.price = 3;
                    var translated = dojo.string.substitute(_("Pay 3 ${moneySymbol} to do a normal turn?"), {
                        moneySymbol: this.getMoneySymbol('cocoa')
                    });
                    this.setClientStateAction(actionConfirm, translated);
                }
            },

            doBoardCollectCocoaClick: function (event) {
                dojo.stopEvent(event);

                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'collectCocoa';
                actionConfirm = 'client_playerTurn_collectCocoa_confirm';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }

                    this.clientStateArgs = {};

                    this.clientStateArgs.action = action;
                    this.clientStateArgs.price = -(this.getDiffrentColorsOnBoard(this.selected_board_id_to) + 1);

                    this.clientStateArgs.price_token = 0;
                    var translated = _("Collect cocoa?") + " " + this.moneyPreview("cocoa");
                    this.setClientStateAction(actionConfirm, translated);
                }
            },

            doBoardCollectCocoaClickConfirmed: function (event) {
                var action = 'collectCocoaOnBoard';
                if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                    this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                        this.ajaxAction(action);
                    }));
                    return;
                } else {
                    this.ajaxAction(action);
                }
            },

            doBoardWorshipClick: function (event) {
                dojo.stopEvent(event);

                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'doWorshipOnBoard';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }

                    board_id = dojo.attr($('actionBoard_' + this.selected_board_id_to), "data-id");

                    if (board_id == 1) {

                        actionConfirm = 'client_playerTurn_doWorshipOnBoard_choose';

                        this.clientStateArgs = {};

                        this.clientStateArgs.action = action;
                        var translated = "";
                        this.setClientStateAction(actionConfirm, translated);
                    } else {
                        this.checkWorship(action, 1);
                    }

                }
            },

            checkWorship: function (action, worship_pos) {
                if (this.isWorshipFromOthersTaken(worship_pos)) {
                    this.selected_board_worship_pos = worship_pos;

                    actionConfirm = 'client_playerTurn_doWorshipOnBoard_confirm';

                    this.clientStateArgs = {};

                    this.clientStateArgs = {};
                    this.clientStateArgs.price = 1;

                    this.clientStateArgs.action = action;
                    var translated = _("Unlock Worship space?") + " " + this.moneyPreview("cocoa");
                    this.setClientStateAction(actionConfirm, translated);
                } else {
                    this.selected_board_worship_pos = worship_pos;
                    if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                        this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                            this.ajaxAction(action, {
                                worship_pos: this.selected_board_worship_pos,
                                pay: false
                            });
                        }));
                        return;
                    } else {
                        this.ajaxAction(action, {
                            worship_pos: this.selected_board_worship_pos,
                            pay: false
                        });
                    }
                }
            },

            doBoardWorshipClickConfirmed: function (event) {
                var action = 'doWorshipOnBoard';
                if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                    this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                        this.ajaxAction(action, {
                            worship_pos: this.selected_board_worship_pos,
                            pay: true
                        });
                    }));
                    return;
                } else {
                    this.ajaxAction(action, {
                        worship_pos: this.selected_board_worship_pos,
                        pay: true
                    });
                }
            },

            doBoardWorshipClickConfirmedFree: function (event) {
                var action = 'doWorshipOnBoard';
                if(this.global_moveTwoWorkers && !this.selected_worker2_id){
                    this.confirmationDialog(_('You can move two workers, but you selected only one.'), dojo.hitch(this, function () {
                        this.ajaxAction(action, {
                            worship_pos: this.selected_board_worship_pos,
                            pay: false,
                            freeCocoa: true,
                        });
                    }));
                    return;
                } else {
                    this.ajaxAction(action, {
                        worship_pos: this.selected_board_worship_pos,
                        pay: false,
                        freeCocoa: true,
                    });
                }
            },


            doBoardWorshipClick1: function (event) {
                var action = 'doWorshipOnBoard';
                this.checkWorship(action, 1);
            },

            doBoardWorshipClick2: function (event) {
                var action = 'doWorshipOnBoard';
                this.checkWorship(action, 2);
            },

            doBoardWorshipClick3: function (event) {
                var action = 'doWorshipOnBoard';
                this.checkWorship(action, 3);
            },

            doWorshipClick: function (event) {
                this.gamedatas_local.global.worship_actions_worship = true;
                this.gamedatas_local.global.worship_actions_discovery = false;
                var action = 'worshipAction';
                this.ajaxAction(action, {
                    worship: this.gamedatas_local.global.worship_actions_worship,
                    discovery: this.gamedatas_local.global.worship_actions_discovery
                });
            },

            doWorshipDiscoveryClick: function (event) {
                this.gamedatas_local.global.worship_actions_worship = false;
                this.gamedatas_local.global.worship_actions_discovery = true;
                var action = 'worshipAction';

                if(this.canBuyDiscoveryTile){
                    this.ajaxAction(action, {
                        worship: this.gamedatas_local.global.worship_actions_worship,
                        discovery: this.gamedatas_local.global.worship_actions_discovery
                    });
                } else {
                    this.confirmationDialog(_('You have not enough resources to buy the discovery tile. Do you want to continue?'), dojo.hitch(this, function () {
                        this.ajaxAction(action, {
                            worship: this.gamedatas_local.global.worship_actions_worship,
                            discovery: this.gamedatas_local.global.worship_actions_discovery
                        });
                    }));
                    return;
                }
            },

            doWorshipBothClick: function (event) {
                this.gamedatas_local.global.worship_actions_worship = true;
                this.gamedatas_local.global.worship_actions_discovery = true;
                var action = 'worshipAction';
                if(this.canBuyDiscoveryTileBoth){
                    this.ajaxAction(action, {
                        worship: this.gamedatas_local.global.worship_actions_worship,
                        discovery: this.gamedatas_local.global.worship_actions_discovery
                    });
                } else {
                    this.confirmationDialog(_('You have not enough resources to buy the discovery tile. Do you want to continue?'), dojo.hitch(this, function () {
                        this.ajaxAction(action, {
                            worship: this.gamedatas_local.global.worship_actions_worship,
                            discovery: this.gamedatas_local.global.worship_actions_discovery
                        });
                    }));
                    return;
                }
            },

            doWorshipBothClickFree: function (event) {
                this.gamedatas_local.global.worship_actions_worship = true;
                this.gamedatas_local.global.worship_actions_discovery = true;
                var action = 'worshipAction';

                this.confirmationDialog(_('You are about to use your discovery tile to save 1 cocoa for both actions'), dojo.hitch(this, function () {
                    this.ajaxAction(action, {
                        worship: this.gamedatas_local.global.worship_actions_worship,
                        discovery: this.gamedatas_local.global.worship_actions_discovery,
                        freeCocoa: true
                    });
                }));
            },

            checkWorshipActions: function (queue) {
                if (queue != null && queue != '') {
                    if (queue == 'temple_blue') {
                        dojo.query('#btnTemple_blue').addClass('clickable');
                    } else if (queue == 'temple_red') {
                        dojo.query('#btnTemple_red').addClass('clickable');
                    } else if (queue == 'temple_green') {
                        dojo.query('#btnTemple_green').addClass('clickable');
                    } else if (queue == 'temple_choose') {
                        dojo.query('#btnTemple_blue').addClass('clickable');
                        dojo.query('#btnTemple_red').addClass('clickable');
                        dojo.query('#btnTemple_green').addClass('clickable');
                    } else if (queue.startsWith("deco")) {
                        var temple1 = queue.split('_')[1];
                        var temple2 = queue.split('_')[2];
                        dojo.query('#btnTemple_' + temple1).addClass('clickable');
                        dojo.query('#btnTemple_' + temple2).addClass('clickable');
                    }
                }

                if (this.gamedatas_local.global.worship_actions_discovery == true) {
                    var board_location = this.selected_board_id_to;
                    var board_id = 0;
                    if (this.selected_board_id_to != 0) {
                        board_id = dojo.attr($('actionBoard_' + this.selected_board_id_to), "data-id");
                    }

                    if (board_id == 1) {
                        dojo.query('#POS_aBoard_' + board_location + '_discoveryTile_1 .discoveryTile').addClass('clickable');
                    } else {
                        dojo.query('#POS_aBoard_' + board_location + '_discoveryTile_0 .discoveryTile').addClass('clickable');
                    }
                }
            },

            incrementTempleWood: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.wood++;
                    this.templeResouceConfirm();
                }
            },

            incrementTempleStone: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.stone++;
                    this.templeResouceConfirm();
                }
            },

            incrementTempleGold: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.gold++;
                    this.templeResouceConfirm();
                }
            },

            doChooseTempleResourceConfirmed: function () {
                var action = 'choosed_resource';
                this.ajaxAction(action, {
                    wood: this.clientStateArgs.wood,
                    stone: this.clientStateArgs.stone,
                    gold: this.clientStateArgs.gold
                });
            },

            templeResouceConfirm: function () {
                var actionConfirm = 'client_playerTurn_choose_temple_resources_confirm';

                this.clientStateArgs.action = 'choose_resource';
                count = (this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold);

                var translated = dojo.string.substitute(_("Get ${wood}${wood_symbol} ${stone}${stone_symbol} ${gold}${gold_symbol}?"), {
                    wood: this.clientStateArgs.wood,
                    stone: this.clientStateArgs.stone,
                    gold: this.clientStateArgs.gold,
                    wood_symbol: this.getTokenSymbol('wood'),
                    stone_symbol: this.getTokenSymbol('stone'),
                    gold_symbol: this.getTokenSymbol('gold')
                });

                this.setClientStateAction(actionConfirm, translated);
            },

            incrementTradeCocoa: function () {
                if (this.clientStateArgs.multiplier < this.clientStateArgs.max) {
                    this.clientStateArgs.pay.cocoa++;
                    this.clientStateArgs.multiplier++;
                    this.tradeConfirm();
                }
            },

            incrementTradeWood: function () {
                if ((this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < 1) ||
                    (this.clientStateArgs.isPayCocoa == 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < this.clientStateArgs.max)) {
                    this.clientStateArgs.pay.wood++;
                    this.clientStateArgs.multiplier++;
                    this.tradeConfirm();
                }
            },

            incrementTradeStone: function () {
                if ((this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < 1) ||
                    (this.clientStateArgs.isPayCocoa == 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < this.clientStateArgs.max)) {
                    this.clientStateArgs.pay.stone++;
                    this.clientStateArgs.multiplier++;
                    this.tradeConfirm();
                }
            },

            incrementTradeGold: function () {
                if ((this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < 1) ||
                    (this.clientStateArgs.isPayCocoa == 0 && this.clientStateArgs.isPayResource > 0 && this.clientStateArgs.multiplier < this.clientStateArgs.max)) {
                    this.clientStateArgs.pay.gold++;
                    this.clientStateArgs.multiplier++;
                    this.tradeConfirm();
                }
            },

            incrementTradeGetWood: function () {
                if ((this.clientStateArgs.get.wood + this.clientStateArgs.get.stone + this.clientStateArgs.get.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.get.wood++;
                    this.tradeConfirm();
                }
            },

            incrementTradeGetStone: function () {
                if ((this.clientStateArgs.get.wood + this.clientStateArgs.get.stone + this.clientStateArgs.get.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.get.stone++;
                    this.tradeConfirm();
                }
            },

            incrementTradeGetGold: function () {
                if ((this.clientStateArgs.get.wood + this.clientStateArgs.get.stone + this.clientStateArgs.get.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.get.gold++;
                    this.tradeConfirm();
                }
            },

            tradeConfirm: function () {
                var actionConfirm = ''
                actionConfirm = 'client_playerTurn_worship_actions_trade_confirm';

                this.clientStateArgs.action = 'trade';

                var text = _("Trade: ");

                if (this.clientStateArgs.isPayCocoa > 0) {
                    text += " -${cocoa}${cocoa_symbol}";
                }

                if (this.clientStateArgs.isPayResource > 0) {
                    text += " -${wood}${wood_symbol} -${stone}${stone_symbol} -${gold}${gold_symbol}";
                }

                if (this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0) {
                    this.clientStateArgs.pay.cocoa = this.clientStateArgs.multiplier;
                }

                text += " --> ";

                var value = this.clientStateArgs.getInfo.cocoa;
                if (value > 0) {
                    this.clientStateArgs.get.cocoa = (value * this.clientStateArgs.multiplier);
                    text += this.clientStateArgs.get.cocoa + "${cocoa_symbol}";
                }

                value = this.clientStateArgs.getInfo.wood;
                if (value > 0) {
                    this.clientStateArgs.get.wood = (value * this.clientStateArgs.multiplier);
                }
                if (value > 0 || this.clientStateArgs.getInfo.resource > 0) {
                    text += this.clientStateArgs.get.wood + "${wood_symbol}";
                }

                value = this.clientStateArgs.getInfo.stone;
                if (value > 0) {
                    this.clientStateArgs.get.stone = (value * this.clientStateArgs.multiplier);
                }
                if (value > 0 || this.clientStateArgs.getInfo.resource > 0) {
                    text += this.clientStateArgs.get.stone + "${stone_symbol}";
                }

                value = this.clientStateArgs.getInfo.gold;
                if (value > 0) {
                    this.clientStateArgs.get.gold = (value * this.clientStateArgs.multiplier);
                }
                if (value > 0 || this.clientStateArgs.getInfo.resource > 0) {
                    text += this.clientStateArgs.get.gold + "${gold_symbol}";
                }

                value = this.clientStateArgs.getInfo.temple;
                if (value > 0) {
                    this.clientStateArgs.get.temple = (value * this.clientStateArgs.multiplier);
                    text += this.clientStateArgs.get.temple + "${temple_symbol}";
                }

                var translated = dojo.string.substitute(text, {
                    cocoa: this.clientStateArgs.pay.cocoa,
                    wood: this.clientStateArgs.pay.wood,
                    stone: this.clientStateArgs.pay.stone,
                    gold: this.clientStateArgs.pay.gold,
                    cocoa_symbol: this.getTokenSymbol('cocoa'),
                    wood_symbol: this.getTokenSymbol('wood'),
                    stone_symbol: this.getTokenSymbol('stone'),
                    gold_symbol: this.getTokenSymbol('gold'),
                    temple_symbol: this.getTokenSymbol('temple_choose')
                });

                this.setClientStateAction(actionConfirm, translated);
            },

            doTradeConfirmed: function () {
                var action = 'trade';

                var resources = (this.clientStateArgs.get.wood + this.clientStateArgs.get.stone + this.clientStateArgs.get.gold);
                var isMax = resources == this.clientStateArgs.max;
                if (this.clientStateArgs.isPayCocoa > 0 && this.clientStateArgs.isPayResource > 0 && !isMax) {
                    var message = dojo.string.substitute(_("You selected ${resources} resources out of ${max}"), {
                        resources: resources,
                        max: this.clientStateArgs.max,
                    });
                    this.confirmationDialog(message, dojo.hitch(this, function () {
                        this.ajaxAction(action, {
                            get_cocoa: this.clientStateArgs.get.cocoa,
                            get_wood: this.clientStateArgs.get.wood,
                            get_stone: this.clientStateArgs.get.stone,
                            get_gold: this.clientStateArgs.get.gold,
                            get_temple: this.clientStateArgs.get.temple,
                            pay_cocoa: this.clientStateArgs.pay.cocoa,
                            pay_wood: this.clientStateArgs.pay.wood,
                            pay_stone: this.clientStateArgs.pay.stone,
                            pay_gold: this.clientStateArgs.pay.gold,
                            freeCocoa: false
                        });
                    }));
                } else {
                    this.ajaxAction(action, {
                        get_cocoa: this.clientStateArgs.get.cocoa,
                        get_wood: this.clientStateArgs.get.wood,
                        get_stone: this.clientStateArgs.get.stone,
                        get_gold: this.clientStateArgs.get.gold,
                        get_temple: this.clientStateArgs.get.temple,
                        pay_cocoa: this.clientStateArgs.pay.cocoa,
                        pay_wood: this.clientStateArgs.pay.wood,
                        pay_stone: this.clientStateArgs.pay.stone,
                        pay_gold: this.clientStateArgs.pay.gold,
                        freeCocoa: false
                    });
                }
            },

            doTradeConfirmedFree: function () {
                var action = 'trade';
                this.ajaxAction(action, {
                    get_cocoa: this.clientStateArgs.get.cocoa,
                    get_wood: this.clientStateArgs.get.wood,
                    get_stone: this.clientStateArgs.get.stone,
                    get_gold: this.clientStateArgs.get.gold,
                    get_temple: this.clientStateArgs.get.temple,
                    pay_cocoa: this.clientStateArgs.pay.cocoa,
                    pay_wood: this.clientStateArgs.pay.wood,
                    pay_stone: this.clientStateArgs.pay.stone,
                    pay_gold: this.clientStateArgs.pay.gold,
                    freeCocoa: true
                });
            },

            claimDiscovery: function (id) {
                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'claimDiscovery';
                actionConfirm = 'client_playerTurn_claimDiscovery_confirm';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }

                    $("claimDiscovery-zone").innerHTML = '';

                    dojo.place(this.format_block('jstpl_discoveryTiles', {
                        type_arg: id,
                        location: "",
                    }), "claimDiscovery-zone");
                    this.addTooltipHtml("discoveryTile_" + id, this.getDiscoveryTileTooltip(id));

                    this.clientStateArgs = {};
                    this.clientStateArgs.action = action;
                    this.clientStateArgs.id = id;

                    var cocoa = this.gamedatas_local.discoveryTiles_data[id].price.cocoa;
                    var wood = this.gamedatas_local.discoveryTiles_data[id].price.wood;
                    var gold = this.gamedatas_local.discoveryTiles_data[id].price.gold;

                    var translated_cocoa = "";
                    var translated_wood = "";
                    var translated_gold = "";
                    if (cocoa > 0) {
                        translated_cocoa = dojo.string.substitute(_("${cocoa}${cocoaSymbol}"), {
                            cocoa: cocoa,
                            cocoaSymbol: this.getMoneySymbol('cocoa')
                        });
                    }
                    if (wood > 0) {
                        translated_wood = dojo.string.substitute(_("${wood}${woodSymbol}"), {
                            wood: wood,
                            woodSymbol: this.getMoneySymbol('wood')
                        });
                    }
                    if (gold > 0) {
                        translated_gold = dojo.string.substitute(_("${gold}${goldSymbol}"), {
                            gold: gold,
                            goldSymbol: this.getMoneySymbol('gold')
                        });
                    }

                    var translated = _("Claim discovery tile ") + translated_cocoa + translated_wood + translated_gold + "?";
                    this.setClientStateAction(actionConfirm, translated);

                }
            },

            useDiscoveryTile: function (id) {
                var action = 'useDiscoveryTile';
                this.ajaxAction(action, {
                    id: id,
                });

                var bonus = this.gamedatas_local.discoveryTiles_data[id].bonus;

                if (parseInt(bonus['move_choose']) > 0) {
                    this.global_moveAnywhere = true;
                    if (dojo.query('.dice.clickable.selected').length > 0) {
                        for (var i = 1; i <= 8; i++) {
                            dojo.addClass('actionBoard_' + i, 'clickable');
                        }
                    }
                } else if (parseInt(bonus['move_double']) > 0) {
                    this.global_moveTwoWorkers = true;
                }
            },

            onEclipseChanged: function (event) {
                dojo.place('<div id="eclipse-overlay" class="eclipse"></div>', "overlay-content");
                dojo.style('overlay', 'display', "block");
            },

            onDiscoveryClick: function (event) {
                dojo.stopEvent(event);
                var element = event.target;
                var id = dojo.attr(element, "id");

                if (dojo.hasClass(element, 'discoveryTile')) {

                    if (dojo.attr(element.parentElement.parentElement, "id") == 'overlay') {
                        if (dojo.hasClass(element, 'clickable') && this.checkAction("claimDiscovery")) {
                            var type_arg = id.split('_')[1];
                            this.hideOverlay();
                            this.claimDiscovery(type_arg);
                        } else {
                            this.hideOverlay();
                        }
                    } else if (dojo.hasClass(element.parentElement, 'non-mask')) {
                        if (dojo.hasClass(element, 'clickable') && !dojo.hasClass(element, 'used')) {
                            if (this.checkAction("useDiscoveryTile")) {
                                var type_arg = id.split('_')[1];
                                this.useDiscoveryTile(type_arg);
                            }
                        }
                    } else if (dojo.hasClass(element.parentElement.parentElement.parentElement, 'temple') || dojo.hasClass(element.parentElement.parentElement.parentElement, 'avenue')) {
                        for (var i = 0; i < element.parentElement.childNodes.length; i++) {
                            var type_arg = dojo.attr(element.parentElement.childNodes[i], "id").split('_')[1];
                            dojo.place(this.format_block('jstpl_discoveryTiles', {
                                type_arg: type_arg,
                                location: ""
                            }), "overlay-content");

                            this.addTooltipHtml("discoveryTile_" + type_arg, this.getDiscoveryTileTooltip(type_arg));
                        }

                        this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
                        dojo.style('overlay', 'display', "block");

                        if (dojo.hasClass(element, 'clickable')) {
                            dojo.query('#overlay .discoveryTile').addClass('clickable');
                        }
                    } else {
                        if (dojo.hasClass(element, 'clickable')) {
                            if (this.checkAction("claimDiscovery")) {
                                var type_arg = id.split('_')[1];
                                this.claimDiscovery(type_arg);
                            }
                        } else {
                            var type_arg = id.split('_')[1];
                            dojo.place(this.format_block('jstpl_discoveryTiles', {
                                type_arg: type_arg,
                                location: ""
                            }), "overlay-content");
                            dojo.style('overlay', 'display', "block");
                            this.addTooltipHtml("discoveryTile_" + type_arg, this.getDiscoveryTileTooltip(type_arg));
                        }
                    }

                }
            },

            setPyramidZoom: function (id) {
                for(var i = 0; i < 32; i++){
                    if($('pyramidTile_' + i)){
                        var rotate = parseInt(dojo.attr('pyramidTile_' + i, "data-rotate"));
                        this.addTooltipHtml("pyramidTile_" + i, '<div class="pyramidTile zoom rotate_'+rotate+'" id="pyramidTile_'+i+'"></div>');
                    }
                }
            },

            setDecorationZoom: function (id) {
                for(var i = 0; i < 15; i++){
                    if($('decorationTile_' + i)){
                        this.addTooltipHtml("decorationTile_" + i, '<div class="decorationTile zoom" id="decorationTile_'+i+'"><span>></span></div>');
                    }
                }
            },

            getDiscoveryTileTooltip: function (id) {
                var tooltip = this.gamedatas_local.discoveryTiles_data[id].tooltip;

                tooltip = tooltip.replace(/{token_vp}/g, this.getTokenSymbol('vp', true));

                var cocoa = this.gamedatas_local.discoveryTiles_data[id].price.cocoa;
                if(cocoa > 0){
                    tooltip = tooltip.replace('{cocoa}', cocoa + this.getTokenSymbol('cocoa', true));
                } else {
                    tooltip = tooltip.replace('{cocoa}', '');
                }
                var wood = this.gamedatas_local.discoveryTiles_data[id].price.wood;
                if(wood > 0){
                    tooltip = tooltip.replace('{wood}', wood + this.getTokenSymbol('wood', true));
                } else {
                    tooltip = tooltip.replace('{wood}', '');
                }
                var gold = this.gamedatas_local.discoveryTiles_data[id].price.gold;
                if(gold > 0){
                    tooltip = tooltip.replace('{gold}', gold + this.getTokenSymbol('gold', true));
                } else {
                    tooltip = tooltip.replace('{gold}', '');
                }

                if(cocoa == 0 && wood == 0 && gold == 0){
                    tooltip = tooltip.replace('Price: ', 'Price: ' + _('free'));
                }

                return tooltip;
            },

            getActionBoardTooltip: function (id) {
                var tooltip = this.gamedatas_local.actionBoards_data[id].tooltip;

                tooltip = tooltip.replace('{token_temple_blue}', this.getTokenSymbol('temple_blue', true));
                tooltip = tooltip.replace('{token_temple_red}', this.getTokenSymbol('temple_red', true));
                tooltip = tooltip.replace('{token_temple_green}', this.getTokenSymbol('temple_green', true));
                tooltip = tooltip.replace(/{token_vp}/g, this.getTokenSymbol('vp', true));

                for (var i = 1; i <= 8; i++) {
                    tooltip = tooltip.replace('{board'+i+'}', '<div class="board_color board_color_'+i+'">'+i+'</div>');
                }

                return tooltip;
            },

            getRoyalTileTooltip: function (id) {
                var tooltip = this.gamedatas_local.royalTiles_data[id].tooltip;

                tooltip = tooltip.replace(/{token_vp}/g, this.getTokenSymbol('vp', true));

                return tooltip;
            },

            getTempleBonusTileTooltip: function (id) {
                var tooltip = this.gamedatas_local.templeBonusTiles_data[id].tooltip;

                tooltip = tooltip.replace(/{token_vp}/g, this.getTokenSymbol('vp', true));

                return tooltip;
            },
            getTechnologyTileTooltip: function (id) {
                var tooltip = this.gamedatas_local.technologyTiles_data[id].tooltip;

                tooltip = tooltip.replace('{token_gold}', this.getTokenSymbol('gold', true));
                tooltip = tooltip.replace(/{token_vp}/g, this.getTokenSymbol('vp', true));

                for (var i = 1; i <= 8; i++) {
                    tooltip = tooltip.replace('{board'+i+'}', '<div class="board_color board_color_'+i+'">'+i+'</div>');
                }

                return tooltip;
            },

            getStartingTileTooltip: function (id) {
                var tooltip = this.gamedatas_local.startingTiles_data[id].tooltip;

                var board0 = this.gamedatas_local.startingTiles_data[id].board[0];
                tooltip = tooltip.replace('{board0}', '<div class="board_color board_color_'+board0+'">'+board0+'</div>');
                var board1 = this.gamedatas_local.startingTiles_data[id].board[1];
                tooltip = tooltip.replace('{board1}', '<div class="board_color board_color_'+board1+'">'+board1+'</div>');

                tooltip = tooltip.replace('{token_vp}', this.getTokenSymbol('vp', true));

                return tooltip;
            },

            hideOverlay: function (event) {
                dojo.style('overlay', 'display', "none");
                var type_args = [];

                for (var i = 0; i < $('overlay-content').childNodes.length; i++) {
                    type_args[i] = dojo.attr($('overlay-content').childNodes[i], "id").split('_')[1];
                }
                $('overlay-content').innerHTML = "";

                for (var i = 0; i < type_args.length; i++) {
                    if (this.gamedatas_local.discoveryTiles_data[type_args[i]]) {
                        this.addTooltipHtml("discoveryTile_" + type_args[i], this.getDiscoveryTileTooltip(type_args[i]));
                    }
                }
            },

            doClaimDiscoveryConfirmed: function (event) {
                dojo.stopEvent(event);
                $("claimDiscovery-zone").innerHTML = '';
                var action = 'claimDiscovery';
                this.ajaxAction(action, {
                    id: this.clientStateArgs.id,
                });
            },

            takeNormalBonus: function (event) {
                dojo.stopEvent(event);
                var action = 'temple_bonus';
                if (this.checkAction(action)) {
                    this.ajaxAction(action);
                }
            },

            onAscensionChanged: function (event) {
                dojo.stopEvent(event);
                var action = 'ascension';
                if (this.checkAction(action)) {
                    var id = parseInt(dojo.attr(event.target, "data-id"));

                    if (id == 1 && this.isFreeCocoa()) {
                        var action = 'ascension';
                        var actionConfirm = 'client_playerTurn_ascension_cocoaFree_confirm';

                        this.clientStateArgs = {};
                        this.clientStateArgs.action = action;
                        this.clientStateArgs.id = id;

                        var translated = _("Use discovery tile to pay cocoa?");
                        this.setClientStateAction(actionConfirm, translated);

                        dojo.query('#claimDiscovery-zone').addClass('show');

                        $("claimDiscovery-zone").innerHTML = '';

                        dojo.place(this.format_block('jstpl_discoveryTiles', {
                            type_arg: 45,
                            location: "",
                        }), "claimDiscovery-zone");
                        this.addTooltipHtml("discoveryTile_" + id, this.getDiscoveryTileTooltip(id));
                        this.resizeGame();

                    } else {
                        this.ajaxAction(action, {
                            id: id,
                            freeCocoa: false
                        });
                    }

                }
            },

            onAscensionFreeCocoaDeclined: function (event) {
                dojo.stopEvent(event);
                var action = 'ascension';
                if (this.checkAction(action)) {
                    this.ajaxAction(action, {
                        id: this.clientStateArgs.id,
                        freeCocoa: false
                    });

                }
            },

            noUndo: function (event) {
                dojo.stopEvent(event);
                var action = 'noUndo';
                if (this.checkAction(action)) {
                    this.ajaxAction(action);
                }
            },

            onAscensionFreeCocoaAccepted: function (event) {
                dojo.stopEvent(event);
                var action = 'ascension';
                if (this.checkAction(action)) {
                    this.ajaxAction(action, {
                        id: this.clientStateArgs.id,
                        freeCocoa: true
                    });
                }
            },

            onRoyalTileChanged: function (event) {
                var action = 'royalTileAction';
                var element = event.target;
                if (dojo.hasClass(element, 'clickable') && this.checkAction(action)) {
                    dojo.stopEvent(event);
                    this.ajaxAction(action);
                }
            },

            onTechnologyTileChanged: function (event) {
                var action = 'acquireTechnology';
                var element = event.target;
                if (dojo.hasClass(element, 'clickable') && this.checkAction(action)) {
                    dojo.stopEvent(event);
                    var id = dojo.attr(element, "id").split('_')[1];
                    this.ajaxAction(action, {
                        id: id
                    });
                }
            },

            doNoblesClick: function (event) {
                dojo.stopEvent(event);
                var action = 'nobles';
                if (this.checkAction(action)) {
                    this.ajaxAction(action);
                }
            },

            onNoblesChanged: function (event) {
                dojo.stopEvent(event);
                var action = 'placeBuilding';
                if (this.checkAction(action)) {
                    var id = parseInt(dojo.attr(event.target, "data-id"));
                    this.ajaxAction(action, {
                        row: id
                    });
                }
            },

            onPyramidTileChanged: function (event) {
                var element = event.target;
                if (dojo.hasClass(element, 'clickable') && !dojo.hasClass(element, 'disabled')) {
                    dojo.stopEvent(event);
                    if (dojo.hasClass(element, 'selected')) {
                        var rotate = parseInt(dojo.attr(event.target, "data-rotate"));
                        dojo.query(event.target).removeClass("rotate_" + rotate);
                        rotate++;
                        if (rotate > 3) {
                            rotate = 0;
                        }
                        dojo.addClass(event.target, "rotate_" + rotate);
                        dojo.attr(event.target, "data-rotate", rotate);
                        this.setPyramidZoom();
                    } else {
                        this.setupPyramid(false);
                        dojo.query('.actionBoard .pyramidTile.selected').removeClass('selected');
                        dojo.query('.construction-wrapper.unlocked:not(.disabled)').addClass('clickable');
                        dojo.addClass(event.target, 'selected');
                        this.pyramidTile = event.target;
                    }
                    if (dojo.query('.construction-wrapper.selected').length > 0) {
                        var level = parseInt(dojo.attr(this.constructionWrapper, "data-level"));

                        this.clientStateArgs = {};
                        this.clientStateArgs.wood = parseInt(dojo.attr(this.constructionWrapper, "data-level"));
                        this.clientStateArgs.stone = 2;
                        this.buildPyramid();
                    }
                }
            },

            onDecorationTileChanged: function (event) {
                dojo.stopEvent(event);
                var element = event.target;
                while (!dojo.hasClass(element, 'decorationTile')) {
                    element = element.parentElement;
                }
                if (dojo.hasClass(element, 'clickable')) {
                    this.setupDecoration(false);
                    dojo.query('.actionBoard .decorationTile.selected').removeClass('selected');
                    dojo.query('.pyramid_decoration-wrapper.unlocked:not(.disabled)').addClass('clickable');
                    dojo.addClass(element, 'selected');
                    this.decorationTile = element;
                }
            },

            onPyramidChanged: function (event) {
                dojo.stopEvent(event);
                var element = event.target;
                if (dojo.hasClass(element, 'clickable')) {
                    dojo.query('.construction-wrapper.selected').removeClass('selected');
                    dojo.addClass(event.target, 'selected');

                    if (dojo.query('.actionBoard .pyramidTile.selected').length > 0) {
                        this.constructionWrapper = dojo.query('.construction-wrapper.selected')[0];
                        this.clientStateArgs = {};
                        this.clientStateArgs.wood = parseInt(dojo.attr(this.constructionWrapper, "data-level"));
                        this.clientStateArgs.stone = 2;
                        this.buildPyramid();
                    }
                }
            },

            decrementConstuctionGetWood: function () {
                var level = parseInt(dojo.attr(this.constructionWrapper, "data-level"));
                if (this.clientStateArgs.wood > 0 && this.clientStateArgs.wood == level && this.clientStateArgs.stone == 2) {
                    this.clientStateArgs.wood--;
                    this.buildPyramid();
                }
            },

            decrementConstuctionGetStone: function () {
                var level = parseInt(dojo.attr(this.constructionWrapper, "data-level"));
                if (this.clientStateArgs.wood == level && this.clientStateArgs.stone == 2) {
                    this.clientStateArgs.stone--;
                    this.buildPyramid();
                }
            },

            buildPyramid: function () {
                var action = "";
                var actionConfirm = "";

                if (!this.isCurrentPlayerActive()) {
                    return;
                }

                action = 'buildPyramid';
                actionConfirm = 'client_playerTurn_buildPyramid_confirm';

                if (action) {
                    if (!this.checkAction(action)) {
                        return;
                    }

                    this.clientStateArgs.action = action;

                    var translated_wood = "";

                    if (this.clientStateArgs.wood > 0) {
                        translated_wood = dojo.string.substitute(_("${wood}${woodSymbol}"), {
                            wood: this.clientStateArgs.wood,
                            woodSymbol: this.getMoneySymbol('wood')
                        });
                    }
                    translated_stone = dojo.string.substitute(_("${stone}${stoneSymbol}"), {
                        stone: this.clientStateArgs.stone,
                        stoneSymbol: this.getMoneySymbol('stone')
                    });

                    var translated = _("Build pyramid tile:  ") + translated_wood + translated_stone + "?";
                    this.setClientStateAction(actionConfirm, translated);
                }
            },

            doBuildPyramidConfirmed: function () {
                var action = 'buildPyramid';
                if (this.checkAction(action)) {
                    var id = dojo.attr(this.pyramidTile, "id").split('_')[1];
                    var rotate = parseInt(dojo.attr(this.pyramidTile, "data-rotate"));
                    this.ajaxAction(action, {
                        constructionWrapper: this.constructionWrapper.id,
                        pyramidTile: id,
                        rotate: rotate,
                        wood: this.clientStateArgs.wood,
                        stone: this.clientStateArgs.stone
                    });
                }
            },

            doBuildDecorationConfirmed: function () {
                var action = 'buildDecoration';
                if (this.checkAction(action)) {
                    var id = dojo.attr(this.decorationTile, "id").split('_')[1];
                    this.ajaxAction(action, {
                        decorationWrapper: this.decorationWrapper.id,
                        decorationTile: id
                    });
                }
            },

            onPyramidDecorationChanged: function (event) {
                dojo.stopEvent(event);
                var element = event.target;
                if (dojo.hasClass(element, 'clickable')) {
                    dojo.query('.pyramid_decoration-wrapper.selected').removeClass('selected');
                    dojo.addClass(event.target, 'selected');

                    this.decorationWrapper = dojo.query('.pyramid_decoration-wrapper.selected')[0];
                    this.clientStateArgs = {};
                    var action = "";
                    var actionConfirm = "";

                    if (!this.isCurrentPlayerActive()) {
                        return;
                    }

                    action = 'buildDecoration';
                    actionConfirm = 'client_playerTurn_buildDecoration_confirm';

                    if (action) {
                        if (!this.checkAction(action)) {
                            return;
                        }

                        this.clientStateArgs.action = action;

                        var gold = (4 - this.countWorkersOnDecoration);
                        if (gold < 1) {
                            gold = 1;
                        }

                        var translated_gold = dojo.string.substitute(_("${gold}${goldSymbol}"), {
                            gold: gold,
                            goldSymbol: this.getMoneySymbol('gold')
                        });

                        var translated = _("Build decoration Tile ") + translated_gold + "?";
                        this.setClientStateAction(actionConfirm, translated);
                    }
                }
            },

            decrementSalaryCocoa: function () {
                if (this.clientStateArgs.cocoa > 0) {
                    this.clientStateArgs.cocoa--;
                    this.paySalaryConfirm();
                }
            },

            incrementSalaryCocoa: function () {
                if (this.clientStateArgs.cocoa < this.clientStateArgs.max) {
                    this.clientStateArgs.cocoa++;
                    this.paySalaryConfirm();
                }
            },

            paySalaryConfirm: function () {
                var action = 'paySalary';
                var actionConfirm = 'client_playerTurn_paySalary_confirm';

                this.clientStateArgs.action = action;

                var vp = 3 * (this.clientStateArgs.max - this.clientStateArgs.cocoa);

                var translated_vp = dojo.string.substitute(_("${vp}${vpSymbol}"), {
                    vp: vp,
                    vpSymbol: this.getMoneySymbol('vp')
                });
                var translated_cocoa = dojo.string.substitute(_("${cocoa}${cocoaSymbol}"), {
                    cocoa: this.clientStateArgs.cocoa,
                    cocoaSymbol: this.getMoneySymbol('cocoa')
                });
                var translated_cocoa_max = dojo.string.substitute(_("${cocoa}${cocoaSymbol}"), {
                    cocoa: this.clientStateArgs.max,
                    cocoaSymbol: this.getMoneySymbol('cocoa')
                });

                var translated = "Pay Salary for workers: -" + translated_vp + " | -" + translated_cocoa + " / " + translated_cocoa_max;
                this.setClientStateAction(actionConfirm, translated);
            },

            doSalaryConfirmed: function () {
                var action = 'paySalary';
                if (this.checkAction(action)) {
                    this.ajaxAction(action, {
                        cocoa: this.clientStateArgs.cocoa,
                        freeCocoa: false
                    });
                }
            },

            doSalaryConfirmedFree: function () {
                var action = 'paySalary';
                if (this.checkAction(action)) {
                    this.ajaxAction(action, {
                        cocoa: this.clientStateArgs.cocoa,
                        freeCocoa: true
                    });
                }
            },

            enableUndoChanged: function (event) {
                dojo.stopEvent(event);
                this.ajaxAction('enableUndo', {
                    checked: !this.isCheckbox('enableUndo')
                });
            },

            enableAutoChanged: function (event) {
                dojo.stopEvent(event);
                this.ajaxAction('enableAuto', {
                    checked: !this.isCheckbox('enableAuto')
                });
            },

            isCheckbox: function (name) {
                var checked = $(name + '_' + this.getThisPlayerId()).innerHTML == 'X';
                if(checked){
                    $(name + '_' + this.getThisPlayerId()).innerHTML = '';
                } else {
                    $(name + '_' + this.getThisPlayerId()).innerHTML = 'X';
                }
                return checked;
            },

            onStartingTileChanged: function (event) {
                dojo.stopEvent(event);
                var element = event.target;
                if (dojo.hasClass(element, 'clickable')) {
                    if (this.checkAction("chooseStartingTile")) {
                        if (dojo.hasClass(element, 'selected')) {
                            dojo.removeClass(event.target, 'selected');
                            if (event.target.nextElementSibling) {
                                dojo.removeClass(event.target.nextElementSibling, 'selected');
                            }
                            dojo.addClass(event.target, 'unselected');
                        } else {
                            dojo.addClass(event.target, 'selected');
                            if (event.target.nextElementSibling) {
                                dojo.addClass(event.target.nextElementSibling, 'selected');
                            }
                            dojo.removeClass(event.target, 'unselected');
                        }
                    }
                }
            },

            doStartingTilesConfirmed: function () {
                if (dojo.query('.startingTile.clickable.selected').length == 2) {
                    var action = 'chooseStartingTile';
                    if (this.checkAction(action)) {
                        var selectedStartingTiles = dojo.query('.startingTile.clickable.selected');
                        var startingTile0 = selectedStartingTiles[0].id.split('_')[1];
                        var startingTile1 = selectedStartingTiles[1].id.split('_')[1];
                        if (startingTile0 == "6" || startingTile0 == "17") {
                            this.clientStateArgs.max += 2;
                        }
                        if (startingTile1 == "6" || startingTile1 == "17") {
                            this.clientStateArgs.max += 2;
                        }
                        if (this.clientStateArgs.max > 0) {
                            for (var j in this.gamedatas_local.startingTiles) {
                                var startingTile = this.gamedatas_local.startingTiles[j];
                                if (startingTile.type == "startingTiles" && startingTile.type_arg != startingTile0 && startingTile.type_arg != startingTile1) {
                                    $("startingTile_" + startingTile.type_arg + "-wrapper").remove();
                                }
                            }
                            dojo.query('.startingTile').removeClass('clickable');
                            this.StartResouceConfirm();
                        } else {
                            var current_player = this.gamedatas_local.players[this.getThisPlayerId()];
                            current_player.startingTile0 = selectedStartingTiles[0].id.split('_')[1];
                            current_player.startingTile1 = selectedStartingTiles[1].id.split('_')[1];
                            if ((current_player.startingTile0 == "3" || current_player.startingTile0 == "13")) {
                                var selectedDiscoveryTiles = dojo.query('#startingTile_'+current_player.startingTile0+' + .discoveryTile.selected');
                                if(selectedDiscoveryTiles[0]){
                                    current_player.startingDiscovery0 = selectedDiscoveryTiles[0].id.split('_')[1];
                                }
                            }
                            if ((current_player.startingTile1 == "3" || current_player.startingTile1 == "13")) {
                                var selectedDiscoveryTiles = dojo.query('#startingTile_'+current_player.startingTile1+' + .discoveryTile.selected');
                                if(selectedDiscoveryTiles[0]){
                                    current_player.startingDiscovery1 = selectedDiscoveryTiles[0].id.split('_')[1];
                                }
                            }

                            $("startingTiles-zone").remove();

                            this.setupStartingTilesOnTable(current_player);

                            this.ajaxAction(action, {
                                startingTile0: selectedStartingTiles[0].id.split('_')[1],
                                startingTile1: selectedStartingTiles[1].id.split('_')[1],
                                wood: this.clientStateArgs.wood,
                                stone: this.clientStateArgs.stone,
                                gold: this.clientStateArgs.gold
                            });
                        }
                    }
                } else {
                    this.showMessage(_("You should select two starting Tiles"), "error");
                }
            },

            doStartingTilesDraftConfirmed: function () {
                if (dojo.query('.startingTile.clickable.selected').length == 1) {
                    var action = 'chooseStartingTile';
                    if (this.checkAction(action)) {
                        var selectedStartingTiles = dojo.query('.startingTile.clickable.selected');
                        var startingTile0 = selectedStartingTiles[0].id.split('_')[1];
                        if (startingTile0 == "6" || startingTile0 == "17") {
                            this.clientStateArgs.max += 2;
                        }
                        this.ajaxAction(action, {
                            startingTile0: selectedStartingTiles[0].id.split('_')[1],
                            startingTile1: 0,
                            wood: this.clientStateArgs.wood,
                            stone: this.clientStateArgs.stone,
                            gold: this.clientStateArgs.gold
                        });
                    }
                } else {
                    this.showMessage(_("You should select one starting Tile only"), "error");
                }
            },

            decrementStartWood: function () {
                if (this.clientStateArgs.wood > 0) {
                    this.clientStateArgs.wood--;
                    this.StartResouceConfirm();
                }
            },

            incrementStartWood: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.wood++;
                    this.StartResouceConfirm();
                }
            },

            decrementStartStone: function () {
                if (this.clientStateArgs.stone > 0) {
                    this.clientStateArgs.stone--;
                    this.StartResouceConfirm();
                }
            },

            incrementStartStone: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.stone++;
                    this.StartResouceConfirm();
                }
            },

            decrementStartGold: function () {
                if (this.clientStateArgs.gold > 0) {
                    this.clientStateArgs.gold--;
                    this.StartResouceConfirm();
                }
            },

            incrementStartGold: function () {
                if ((this.clientStateArgs.wood + this.clientStateArgs.stone + this.clientStateArgs.gold) < this.clientStateArgs.max) {
                    this.clientStateArgs.gold++;
                    this.StartResouceConfirm();
                }
            },

            doChooseStartResourceConfirmed: function () {
                var selectedStartingTiles = dojo.query('.startingTile.selected');
                var startingTile0 = selectedStartingTiles[0].id.split('_')[1];
                var startingTile1 = 0;
                if (selectedStartingTiles[1]) {
                    startingTile1 = selectedStartingTiles[1].id.split('_')[1];
                }
                var action = 'chooseStartingTile';
                var current_player = this.gamedatas_local.players[this.getThisPlayerId()];
                current_player.startingTile0 = startingTile0;
                current_player.startingTile1 = startingTile1;
                if ((current_player.startingTile0 == "3" || current_player.startingTile0 == "13")) {
                    var selectedDiscoveryTiles = dojo.query('#startingTile_'+current_player.startingTile0+' + .discoveryTile.selected');
                    if(selectedDiscoveryTiles[0]){
                        current_player.startingDiscovery0 = selectedDiscoveryTiles[0].id.split('_')[1];
                    }
                }
                if ((current_player.startingTile1 == "3" || current_player.startingTile1 == "13")) {
                    var selectedDiscoveryTiles = dojo.query('#startingTile_'+current_player.startingTile1+' + .discoveryTile.selected');
                    if(selectedDiscoveryTiles[0]){
                        current_player.startingDiscovery1 = selectedDiscoveryTiles[0].id.split('_')[1];
                    }
                }

                $("startingTiles-zone").remove();

                this.setupStartingTilesOnTable(current_player);

                this.ajaxAction(action, {
                    startingTile0: startingTile0,
                    startingTile1: startingTile1,
                    wood: this.clientStateArgs.wood,
                    stone: this.clientStateArgs.stone,
                    gold: this.clientStateArgs.gold
                });
            },

            StartResouceConfirm: function () {
                var actionConfirm = 'choose_starting_tiles_choose_resources_confirm';
                this.clientStateArgs.action = 'chooseStartingTile';

                var translated = dojo.string.substitute(_("Get ${wood}${wood_symbol} ${stone}${stone_symbol} ${gold}${gold_symbol}?"), {
                    wood: this.clientStateArgs.wood,
                    stone: this.clientStateArgs.stone,
                    gold: this.clientStateArgs.gold,
                    wood_symbol: this.getTokenSymbol('wood'),
                    stone_symbol: this.getTokenSymbol('stone'),
                    gold_symbol: this.getTokenSymbol('gold')
                });

                this.setClientStateAction(actionConfirm, translated);
            },

            ///////////////////////////////////////////////////
            //// Reaction to cometD notifications

            setupNotifications: function () {
                console.log('notifications subscriptions setup');

                dojo.subscribe('moveWokerToBoard', this, "notif_moveWokerToBoard");
                dojo.subscribe('unlockAllWorkers', this, "notif_unlockAllWorkers");
                dojo.subscribe('unlockSingleWorker', this, "notif_unlockSingleWorker");
                dojo.subscribe('stepTemple', this, "notif_stepTemple");
                dojo.subscribe('choosed_resources', this, "notif_choosed_resources");
                dojo.subscribe('payCocoa', this, "notif_payCocoa");
                dojo.subscribe('claimDiscovery', this, "notif_claimDiscovery");
                dojo.subscribe('collectResource', this, "notif_collectResource");
                dojo.subscribe('payResource', this, "notif_payResource");
                dojo.subscribe('useDiscoveryTile', this, "notif_useDiscoveryTile");
                dojo.subscribe('stepAvenue', this, "notif_stepAvenue");
                dojo.subscribe('upgradeWorker', this, "notif_upgradeWorker");
                dojo.subscribe('placeBuilding', this, "notif_placeBuilding");
                dojo.subscribe('acquireTechnology', this, "notif_acquireTechnology");
                dojo.subscribe('buildPyramid', this, "notif_buildPyramid");
                dojo.subscribe('refillPyramidTileOffer', this, "notif_refillPyramidTileOffer");
                dojo.subscribe('refillDecorationTileOffer', this, "notif_refillDecorationTileOffer");
                dojo.subscribe('refillDiscoveryTilesOffer', this, "notif_refillDiscoveryTilesOffer");
                dojo.subscribe('stepPyramidTrack', this, "notif_stepPyramidTrack");
                dojo.subscribe('buildDecoration', this, "notif_buildDecoration");
                dojo.subscribe('updateCalenderTrack', this, "notif_updateCalenderTrack");
                dojo.subscribe('showEclipseBanner', this, "notif_showEclipseBanner");
                dojo.subscribe('calculateEndGameScoring', this, "notif_calculateEndGameScoring");
                dojo.subscribe('choosedStartingTiles', this, "notif_choosedStartingTiles");
                dojo.subscribe('choosedStartingTilesDraft', this, "notif_choosedStartingTilesDraft");
                dojo.subscribe('placeWorker', this, "notif_placeWorker");
                dojo.subscribe('removeNonPlayerWorkers', this, "notif_removeNonPlayerWorkers");
                dojo.subscribe('removeLeftDiscoveryTiles', this, "notif_removeLeftDiscoveryTiles");
            },

            notif_moveWokerToBoard: function (notif) {
                var player_id = notif.args.player_id;
                var worship_pos = parseInt(notif.args.worship_pos);
                var worker_id = notif.args.selected_worker_id;
                var worker2_id = parseInt(notif.args.selected_worker2_id);
                var moveTwoWorkers = notif.args.moveTwoWorkers;
                var nextBoard = parseInt(notif.args.selected_board_id_to);
                var selected_board_id_from = parseInt(notif.args.selected_board_id_from);
                var _this = this;

                var animateWorkerOnNextPostion = null;
                if (selected_board_id_from == -1) {
                    for (var index in this.gamedatas_local.map[player_id]) {
                        var map = this.gamedatas_local.map[player_id][index];
                        if (map.worker_id == worker_id) {
                            map.locked = false;
                            break;
                        }
                    }
                    dojo.query('#' + player_id + '_worker_' + worker_id).removeClass('locked');
                }
                this.animateWorker(player_id, worship_pos, worker_id, nextBoard, animateWorkerOnNextPostion);

                if (moveTwoWorkers == true && worker2_id > 0) {
                    setTimeout(function () {
                        _this.animateWorker(player_id, 0, worker2_id, nextBoard, null);
                    }, 500);
                }
            },

            notif_collectResource: function (notif) {
                var player_id = notif.args.player_id;
                var token = notif.args.token;
                var amount = parseInt(notif.args.amount);
                var source = notif.args.source;

                if (token == 'vp') {
                    var target = 'icon_point_' + player_id;
                    this.animateVP(player_id, amount, token, source, target, 500);
                } else if (token == 'cocoa' || token == 'wood' || token == 'stone' || token == 'gold') {
                    var target = token + '_' + player_id + '_side';
                    this.animateResource(player_id, amount, token, source, target, 500);
                }
            },

            notif_payResource: function (notif) {
                var player_id = notif.args.player_id;
                var token = notif.args.token;
                var amount = parseInt(notif.args.amount);
                var target = notif.args.target;

                if (token == 'vp') {
                    var source = 'icon_point_' + player_id;
                    this.animateVP(player_id, -amount, token, source, target, 500);
                } else if (token == 'cocoa' || token == 'wood' || token == 'stone' || token == 'gold') {
                    var source = token + '_' + player_id + '_side';
                    this.animateResource(player_id, -amount, token, source, target, 500);
                }
            },

            notif_unlockAllWorkers: function (notif) {
                var player_id = notif.args.player_id;
                var pay = notif.args.pay;

                var multipleWorkers = 0;

                for (var index in this.gamedatas_local.map[player_id]) {
                    var map = this.gamedatas_local.map[player_id][index];
                    if (map.locked == true && parseInt(map.worship_pos) != 0) {
                        var worker_id = map.worker_id;
                        var worker = player_id + '_worker_' + worker_id;
                        var nextBoard = parseInt(dojo.attr(worker, "data-board-id"));
                        var worship_pos = 0;
                        var animateWorkerOnNextPostion = null;

                        if (nextBoard == 1) {
                            if (multipleWorkers) {
                                animateWorkerOnNextPostion = multipleWorkers;
                            }
                            multipleWorkers++;
                        }
                        this.animateWorker(player_id, worship_pos, worker_id, nextBoard, animateWorkerOnNextPostion);
                        this.gamedatas_local.map[player_id][index].locked = false;
                        this.gamedatas_local.map[player_id][index].worship_pos = 0;
                    }
                }

                this.bindData(this.gamedatas_local);

            },

            notif_unlockSingleWorker: function (notif) {
                var player_id = notif.args.player_id;
                var worker_id = notif.args.worker_id;

                var worker = player_id + '_worker_' + worker_id;
                var nextBoard = parseInt(dojo.attr(worker, "data-board-id"));
                var worship_pos = 0;
                this.animateWorker(player_id, worship_pos, worker_id, nextBoard, null);

                for (var index in this.gamedatas_local.map[player_id]) {
                    var map = this.gamedatas_local.map[player_id][index];
                    if (map.worker_id == worker_id) {
                        this.gamedatas_local.map[player_id][index].locked = false;
                        this.gamedatas_local.map[player_id][index].worship_pos = 0;
                    }
                }

                this.gamedatas_local.players[this.getActivePlayerId()].cocoa = parseInt(this.gamedatas_local.players[this.getActivePlayerId()].cocoa) - 1;


                this.bindData(this.gamedatas_local);
            },

            notif_stepTemple: function (notif) {
                var player_id = notif.args.player_id;
                var temple = notif.args.temple;
                var step = notif.args.step;
                var bonus = notif.args.bonus;
                this.global_last_temple_id = parseInt(notif.args.last_temple_id);

                var marker = 'temple_' + temple + '_marker_' + player_id;
                var next_step = 'temple_' + temple + '_step_' + step + '_marker_' + player_id;
                this.animateStep(marker, player_id, next_step);

                if (temple == "blue") {
                    this.gamedatas_local.players[player_id].temple_blue = step;
                } else if (temple == "red") {
                    this.gamedatas_local.players[player_id].temple_red = step;
                } else if (temple == "green") {
                    this.gamedatas_local.players[player_id].temple_green = step;
                }

                this.bindData(this.gamedatas_local);

                if (temple == 'blue' && step == 4 ||
                    temple == 'red' && step == 5 ||
                    temple == 'green' && step == 3 ||
                    temple == 'green' && step == 6) {
                    this.setTempleDiscoveryTilesClickable();
                }
            },

            notif_choosed_resources: function (notif) {
                var player_id = notif.args.player_id;
                var wood = parseInt(notif.args.wood);
                var stone = parseInt(notif.args.stone);
                var gold = parseInt(notif.args.gold);

                this.bindData(this.gamedatas_local);
            },

            notif_payCocoa: function (notif) {
                var player_id = notif.args.player_id;
                var amount = parseInt(notif.args.amount);

                this.gamedatas_local.players[player_id].cocoa = parseInt(this.gamedatas_local.players[player_id].cocoa) - amount;

                this.bindData(this.gamedatas_local);
            },

            notif_claimDiscovery: function (notif) {
                var player_id = notif.args.player_id;
                var discoveryTiles = notif.args.discoveryTiles;
                var row = notif.args.row;
                var discTile = notif.args.discTile;
                var board_location = notif.args.selected_board_id_to;

                this.gamedatas_local.playersHand = notif.args.player_hand;

                this.gamedatas_local.discoveryTiles = discoveryTiles;

                this.restoreServerGameState();

                this.bindData(this.gamedatas_local);

                dojo.query('.discoveryTile.clickable').removeClass('clickable');

                var mask_id = parseInt(this.gamedatas_local.discoveryTiles_data[discTile['type_arg']].bonus.mask);
                var target = "other_" + player_id;

                if (mask_id > 0) {
                    target = "mask_" + player_id + '_row_' + row;
                    if ($(target) == null) {
                        var row_id = 'mask_' + player_id + '_row_' + row;
                        dojo.place('<div class="row" id="' + row_id + '"></div>', "mask_" + player_id);
                    }
                }
                this.animateClaimDiscovery(discTile, target);
                this.resizeGame();
            },

            notif_useDiscoveryTile: function (notif) {
                var id = notif.args.id;
                dojo.query('#discoveryTile_' + id).addClass('used');
                this.gamedatas_local.playersHand = notif.args.player_hand;
            },

            notif_stepAvenue: function (notif) {
                var player_id = notif.args.player_id;
                var step = notif.args.step;

                var marker = 'avenue_marker_' + player_id;
                var next_step = 'avenue_step_' + step + '_marker_' + player_id;
                this.animateStep(marker, player_id, next_step);

                this.gamedatas_local.players[player_id].avenue_of_dead = step;

                this.bindData(this.gamedatas_local);

                var query = '';
                if (step == 3) {
                    query = '#avenue_discoveryTile_0 .discoveryTile';
                } else if (step == 6) {
                    query = '#avenue_discoveryTile_1 .discoveryTile';
                } else if (step == 8) {
                    query = '#avenue_discoveryTile_2 .discoveryTile';
                }
                if (query != '') {
                    dojo.query(query).addClass('clickable');
                }
            },

            notif_stepPyramidTrack: function (notif) {
                var player_id = notif.args.player_id;
                var step = notif.args.step;

                var marker = 'pyramid_track_marker_' + player_id;
                var next_step = 'pyramid_track_step_' + step + '_marker_' + player_id;
                this.animateStep(marker, player_id, next_step);

                this.gamedatas_local.players[player_id].pyramid_track = step;

                this.bindData(this.gamedatas_local);
            },

            notif_upgradeWorker: function (notif) {
                var player_id = notif.args.player_id;
                var worker_id = notif.args.worker_id;
                var worker_power = notif.args.worker_power;
                var x = -((worker_power - 1) * 100);

                for (var index in this.gamedatas_local.map[player_id]) {
                    var map = this.gamedatas_local.map[player_id][index];
                    if (map.worker_id == worker_id) {
                        map.worker_power = worker_power;
                        break;
                    }
                }
                this.bindData(this.gamedatas_local);

                var worker = player_id + '_worker_' + worker_id;
                if ($(worker)) {
                    dojo.style(worker, 'background-position-x', x + "%");
                    dojo.attr(worker, "data-worker-power", worker_power);
                }
            },

            notif_placeBuilding: function (notif) {
                var player_id = notif.args.player_id;
                var sum = notif.args.sum;
                var row = notif.args.row;
                var column = notif.args.column;
                var source = 'building_' + (sum - 1);
                var target = 'building_' + column + '_row_' + row;

                this.animateBuilding(source, target);
            },

            notif_acquireTechnology: function (notif) {
                var player_id = notif.args.player_id;
                var location = notif.args.location;

                var player = this.gamedatas_local.players[player_id];
                var target = location + '_markers';
                var id = location + "_marker_" + player_id;
                dojo.place(this.format_block('jstpl_markerOntable', {
                    id: id,
                    player_color: player.player_color,
                }), target);

                this.resizeGame();
            },

            notif_buildPyramid: function (notif) {
                var player_id = notif.args.player_id;
                var pyramidTile = notif.args.pyramidTile;
                var constructionWrapper = notif.args.constructionWrapper;
                var rotate = notif.args.rotate;
                var pyramidTiles = notif.args.pyramidTiles;

                var source = $("pyramidTile_" + pyramidTile).parentElement;

                this.gamedatas_local.pyramidTiles = pyramidTiles;

                this.setupPyramid(false);

                this.slideTemporaryObject("pyramidTile_" + pyramidTile, 'workers', source, constructionWrapper, 2000);
                dojo.style("pyramidTile_" + pyramidTile, 'z-index', 5);

                dojo.query('#' + constructionWrapper).addClass('disabled');

                var _this = this;

                setTimeout(function () {
                    dojo.place(_this.format_block('jstpl_pyramidTiles', {
                        type_arg: pyramidTile,
                        location: "",
                        rotate: rotate
                    }), constructionWrapper);

                    _this.queryAndAddEvent('.pyramidTile', 'onclick', 'onPyramidTileChanged');
                    _this.resizeGame();
                }, 2000);
            },

            notif_refillPyramidTileOffer: function (notif) {
                var newTiles = notif.args.newTiles;
                var pyramidTiles = notif.args.pyramidTiles;

                this.gamedatas_local.pyramidTiles = pyramidTiles;

                for (var id in newTiles) {
                    var newTile = newTiles[id];
                    if (newTile && newTile.location) {
                        var target = 'pyramid_wrapper_' + newTile.location.split('_')[1];
                        dojo.place(this.format_block('jstpl_pyramidTiles', {
                            type_arg: newTile.type_arg,
                            location: newTile.location,
                            rotate: 0
                        }), target);
                    }
                }

                this.queryAndAddEvent('.pyramidTile', 'onclick', 'onPyramidTileChanged');
                this.resizeGame();
                this.setPyramidZoom();
            },

            notif_refillDecorationTileOffer: function (notif) {
                var newTiles = notif.args.newTiles;
                var decorationTiles = notif.args.decorationTiles;

                this.gamedatas_local.decorationTiles = decorationTiles;

                for (var id in newTiles) {
                    var newTile = newTiles[id];
                    if (newTile && newTile.location) {
                        var target = 'decoration_wrapper_' + newTile.location.split('_')[1];
                        dojo.place(this.format_block('jstpl_decorationTiles', {
                            type_arg: newTile.type_arg,
                            location: newTile.location
                        }), target);
                    }
                }

                this.queryAndAddEvent('.decorationTile', 'onclick', 'onDecorationTileChanged');
                this.resizeGame();
                this.setDecorationZoom();
            },

            notif_refillDiscoveryTilesOffer: function (notif) {
                var newTiles = notif.args.newTiles;
                var discoveryTiles = notif.args.discoveryTiles;

                this.gamedatas_local.discoveryTiles = discoveryTiles;

                for (var id in newTiles) {
                    var newTile = newTiles[id];
                    if (newTile && newTile.location) {
                        var board = newTile.location.split('b')[1];
                        board = this.gamedatas_local.actionBoards[board].card_location_arg;
                        var position = 0;
                        if(board == 1){
                            position = 1;
                        }
                        var target = 'POS_aBoard_' + board + '_discoveryTile_' + position;
                        dojo.place(this.format_block('jstpl_discoveryTiles', {
                            type_arg: newTile.type_arg,
                            location: newTile.location
                        }), target);
                        this.addTooltipHtml("discoveryTile_" + newTile.type_arg, this.getDiscoveryTileTooltip(newTile.type_arg));
                    }
                }

                this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
                this.resizeGame();
            },

            notif_buildDecoration: function (notif) {
                var decorationTile = notif.args.decorationTile;
                var decorationWrapper = notif.args.decorationWrapper;

                var source = $("decorationTile_" + decorationTile).parentElement;

                this.setupDecoration(false);

                this.slideTemporaryObject("decorationTile_" + decorationTile, 'workers', source, decorationWrapper, 2000);
                dojo.style("decorationTile_" + decorationTile, 'z-index', 5);

                dojo.query('#' + decorationWrapper).addClass('disabled');

                var _this = this;

                setTimeout(function () {
                    dojo.place(_this.format_block('jstpl_decorationTiles', {
                        type_arg: decorationTile,
                        location: ""
                    }), decorationWrapper);

                    _this.queryAndAddEvent('.decorationTile', 'onclick', 'onDecorationTileChanged');
                    _this.queryAndAddEvent('.decorationTile span', 'onclick', 'onDecorationTileChanged');
                    _this.resizeGame();
                }, 2000);
            },

            notif_updateCalenderTrack: function (notif) {
                var step = notif.args.step;
                var color = notif.args.color;

                var id = "calendarTrack_" + color;

                var source = $(id).parentElement;
                var target = $("calendar_track_step_" + step);

                this.slideTemporaryObject($(id), 'workers', source, target);

                if(color == 'white'){
                    this.global_eclipseDiscWhite = parseInt(step);
                } else {
                    this.global_eclipseDiscBlack = parseInt(step);
                }
                this.showEclipseBanner();

                var _this = this;

                setTimeout(function () {
                    dojo.place(_this.format_block('jstpl_calendarTrack', {
                        color: color
                    }), target);

                    _this.resizeGame();
                }, 500);
            },

            notif_showEclipseBanner: function (notif) {
                this.global_lastRound = notif.args.lastRound;
                this.global_eclipseNumber = notif.args.eclipseNumber;
                this.showEclipseBanner();
            },

            showEclipseBanner: function () {
                if(this.global_eclipseDiscWhite >= this.global_eclipseDiscBlack){
                    $('eclipse-title').innerHTML = dojo.string.substitute(_('Eclipse ${number} is triggered'), {
                        number: this.global_eclipseNumber
                    });
                    dojo.query('#eclipse-zone').addClass('show');
                    if(this.global_lastRound == 3){
                        $('eclipse-subtitle').innerHTML = _('Scoring happens immediately after the turn of the current player');
                    } else if(this.global_lastRound == 2){
                        $('eclipse-subtitle').innerHTML = _('Finish current round and then play another full round, before eclipse scoring happens');
                    }  else if(this.global_lastRound == 1){
                        $('eclipse-subtitle').innerHTML = _('Finish current round, before eclipse scoring happens');
                    }  else {
                        $('eclipse-title').innerHTML = dojo.string.substitute(_('Eclipse ${number} salary and scoring'), {
                            number: this.global_eclipseNumber
                        });
                        $('eclipse-subtitle').innerHTML = '';
                    }
                } else {
                    dojo.query('#eclipse-zone.show').removeClass('show');
                }
            },

            notif_calculateEndGameScoring: function (notif) {
                var player_id = notif.args.player_id;
                var score = notif.args.score;
                this.gamedatas_local.players[player_id].score = parseInt(this.gamedatas_local.players[player_id].score) + score;
                $('player_score_' + player_id).innerHTML = this.gamedatas_local.players[player_id].score;
            },

            notif_choosedStartingTiles: function (notif) {
                var players = notif.args.players;
                this.gamedatas_local.players = players;

                var current_player = this.gamedatas_local.players[this.getThisPlayerId()];
                for (var player_id in this.gamedatas_local.players) {
                    var player = this.gamedatas_local.players[player_id];
                    if (player.id != current_player.id) {
                        this.setupStartingTilesOnTable(player);
                    }
                }
            },

            notif_choosedStartingTilesDraft: function (notif) {
                var player_id = notif.args.player_id;
                var startingTile0 = notif.args.startingTile0;
                var discoveryTile = notif.args.discoveryTile;

                $("startingTile_" + startingTile0 + "-wrapper").remove();

                var target = "player_" + player_id + "_startingTiles";
                dojo.place(this.format_block('jstpl_startingTiles', {
                    type_arg: startingTile0
                }), target, "last");
                this.addTooltipHtml("startingTile_" + startingTile0, this.getStartingTileTooltip(startingTile0));

                if (discoveryTile != null && (startingTile0 == "3" || startingTile0 == "13")) {
                    var target_disc = "startingTile_" + startingTile0 + "-wrapper";
                    dojo.place(this.format_block('jstpl_discoveryTiles', {
                        type_arg: discoveryTile,
                        location: ''
                    }), target_disc);
                    this.addTooltipHtml("discoveryTile_" + discoveryTile, this.getDiscoveryTileTooltip(discoveryTile));
                }
                this.queryAndAddEvent('.discoveryTile', 'onclick', 'onDiscoveryClick');
                this.resizeGame();
            },

            notif_placeWorker: function (notif) {
                var player_id = notif.args.player_id;
                var board_pos = notif.args.board_pos;
                var worker_id = notif.args.worker_id;

                this.gamedatas_local.map = notif.args.map;
                this.createWorker(player_id, worker_id, 1, false, 0, board_pos, 0);
            },

            notif_removeNonPlayerWorkers: function (notif) {
                var nonPlayers = parseInt(notif.args.nonPlayers);
                for (var i = 0; i < nonPlayers; i++) {
                    for (var j = 1; j <= 3; j++) {
                        if ($(i + "_worker_" + j)) {
                            $(i + "_worker_" + j).remove();
                        }
                    }
                }
            },

            notif_removeLeftDiscoveryTiles: function (notif) {
                var startingDiscovery0 = notif.args.startingDiscovery0;
                var startingDiscovery1 = notif.args.startingDiscovery1;
                if (startingDiscovery0 != null && $("discoveryTile_" + startingDiscovery0)) {
                    $("discoveryTile_" + startingDiscovery0).remove();
                }
                if (startingDiscovery1 != null && $("discoveryTile_" + startingDiscovery1)) {
                    $("discoveryTile_" + startingDiscovery1).remove();
                }
            },
        });
    });
