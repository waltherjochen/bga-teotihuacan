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
 * teotihuacan.action.php
 *
 * teotihuacan main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/teotihuacan/teotihuacan/myAction.html", ...)
 *
 */
  
  
  class action_teotihuacan extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "teotihuacan_teotihuacan";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

      public function doMainActionOnBoard()
      {
          self::setAjaxMode();

          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->doMainActionOnBoard($freeCocoa);

          self::ajaxResponse( );
      }

      public function showBoardActions()
      {
          self::setAjaxMode();

          $board_id_to = self::getArg( "board_id_to", AT_posint, true );
          $board_id_from = self::getArg( "board_id_from", AT_posint, true );
          $worker_id = self::getArg( "worker_id", AT_posint, true );
          $worker2_id = self::getArg( "worker2_id", AT_posint, true );

          $this->game->showBoardActions($board_id_to,$board_id_from,$worker_id,$worker2_id);

          self::ajaxResponse( );
      }

      public function collectCocoaOnBoard()
      {
          self::setAjaxMode();

          $this->game->collectCocoaOnBoard();

          self::ajaxResponse( );
      }

      public function doWorshipOnBoard()
      {
          self::setAjaxMode();
          $worship_pos = self::getArg( "worship_pos", AT_posint, true );
          $pay = self::getArg( "pay", AT_bool, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->doWorshipOnBoard($worship_pos,$pay,$freeCocoa);

          self::ajaxResponse( );
      }

      public function unlockAllWorkers()
      {
          self::setAjaxMode();

          $pay = self::getArg( "pay", AT_bool, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->unlockAllWorkers($pay, $freeCocoa);

          self::ajaxResponse( );
      }

      public function worshipAction()
      {
          self::setAjaxMode();

          $worship = self::getArg( "worship", AT_bool, true );
          $discovery = self::getArg( "discovery", AT_bool, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->worshipAction($worship, $discovery, $freeCocoa);

          self::ajaxResponse( );
      }

      public function stepTemple()
      {
          self::setAjaxMode();

          $temple= self::getArg( "temple", AT_alphanum, true );

          $this->game->stepTemple($temple);

          self::ajaxResponse( );
      }

      public function cancelMoveToBoard()
      {
          self::setAjaxMode();

          $this->game->cancelMoveToBoard();

          self::ajaxResponse( );
      }

      public function pass()
      {
          self::setAjaxMode();

          $this->game->pass();

          self::ajaxResponse( );
      }

      public function choosed_resource()
      {
          self::setAjaxMode();

          $wood = self::getArg( "wood", AT_posint, true );
          $stone = self::getArg( "stone", AT_posint, true );
          $gold = self::getArg( "gold", AT_posint, true );

          $this->game->choosed_resource($wood, $stone, $gold);

          self::ajaxResponse( );
      }

      public function claimDiscovery()
      {
          self::setAjaxMode();

          $id = self::getArg( "id", AT_posint, true );

          $this->game->claimDiscovery($id);

          self::ajaxResponse( );
      }

      public function useDiscoveryTile()
      {
          self::setAjaxMode();

          $id = self::getArg( "id", AT_posint, true );

          $this->game->useDiscoveryTile($id);

          self::ajaxResponse( );
      }

      public function temple_bonus()
      {
          self::setAjaxMode();

          $this->game->temple_bonus();

          self::ajaxResponse( );
      }

      public function stepAvenue()
      {
          self::setAjaxMode();

          $this->game->stepAvenue();

          self::ajaxResponse( );
      }

      public function upgradeWorker()
      {
          self::setAjaxMode();

          $worker_id = self::getArg( "id", AT_posint, true );
          $board_id_from = self::getArg( "board_id_from", AT_posint, true );

          $this->game->upgradeWorker($worker_id, $board_id_from);

          self::ajaxResponse( );
      }
      public function ascension()
      {
          self::setAjaxMode();

          $id = self::getArg( "id", AT_posint, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->ascension($id, $freeCocoa);

          self::ajaxResponse( );
      }
      public function placeBuilding()
      {
          self::setAjaxMode();

          $row = self::getArg( "row", AT_posint, true );

          $this->game->placeBuilding($row);

          self::ajaxResponse( );
      }
      public function trade()
      {
          self::setAjaxMode();

          $get_cocoa = self::getArg( "get_cocoa", AT_posint, true );
          $get_wood = self::getArg( "get_wood", AT_posint, true );
          $get_stone = self::getArg( "get_stone", AT_posint, true );
          $get_gold = self::getArg( "get_gold", AT_posint, true );
          $pay_cocoa = self::getArg( "pay_cocoa", AT_posint, true );
          $pay_wood = self::getArg( "pay_wood", AT_posint, true );
          $pay_stone = self::getArg( "pay_stone", AT_posint, true );
          $pay_gold = self::getArg( "pay_gold", AT_posint, true );
          $get_temple = self::getArg( "get_temple", AT_posint, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->trade($get_cocoa,$get_wood,$get_stone,$get_gold,$pay_cocoa,$pay_wood,$pay_stone,$pay_gold,$get_temple,$freeCocoa);

          self::ajaxResponse( );
      }
      public function nobles()
      {
          self::setAjaxMode();

          $this->game->nobles();

          self::ajaxResponse( );
      }
      public function royalTileAction()
      {
          self::setAjaxMode();

          $this->game->royalTileAction();

          self::ajaxResponse( );
      }
      public function acquireTechnology()
      {
          self::setAjaxMode();

          $id = self::getArg( "id", AT_posint, true );

          $this->game->acquireTechnology($id);

          self::ajaxResponse( );
      }
      public function paySalary()
      {
          self::setAjaxMode();

          $cocoa = self::getArg( "cocoa", AT_posint, true );
          $freeCocoa = self::getArg( "freeCocoa", AT_bool, false );

          $this->game->paySalary($cocoa,$freeCocoa);

          self::ajaxResponse( );
      }
      public function buildPyramid()
      {
          self::setAjaxMode();

          $pyramidTile = self::getArg( "pyramidTile", AT_posint, true );
          $constructionWrapper = self::getArg( "constructionWrapper", AT_alphanum, true );
          $rotate = self::getArg( "rotate", AT_posint, true );
          $wood = self::getArg( "wood", AT_posint, true );
          $stone = self::getArg( "stone", AT_posint, true );

          $this->game->buildPyramid($constructionWrapper, $pyramidTile, $rotate, $wood, $stone);

          self::ajaxResponse( );
      }
      public function buildDecoration()
      {
          self::setAjaxMode();

          $decorationTile = self::getArg( "decorationTile", AT_posint, true );
          $decorationWrapper = self::getArg( "decorationWrapper", AT_alphanum, true );

          $this->game->buildDecoration($decorationWrapper, $decorationTile);

          self::ajaxResponse( );
      }
      public function chooseStartingTile()
      {
          self::setAjaxMode();

          $startingTile0 = self::getArg( "startingTile0", AT_posint, true );
          $startingTile1 = self::getArg( "startingTile1", AT_posint, true );
          $wood = self::getArg( "wood", AT_posint, true );
          $stone = self::getArg( "stone", AT_posint, true );
          $gold = self::getArg( "gold", AT_posint, true );

          $this->game->chooseStartingTile($startingTile0, $startingTile1, $wood, $stone, $gold);

          self::ajaxResponse( );
      }
      public function buyPowerUp()
      {
          self::setAjaxMode();

          $this->game->buyPowerUp();

          self::ajaxResponse( );
      }
      public function undo()
      {
          self::setAjaxMode();

          $this->game->undo();

          self::ajaxResponse( );
      }
      public function placeWorker()
      {
          self::setAjaxMode();

          $board_id = self::getArg( "board_id", AT_posint, true );
          $board_pos = self::getArg( "board_pos", AT_posint, true );

          $this->game->placeWorker($board_id, $board_pos);

          self::ajaxResponse( );
      }
  }
  

