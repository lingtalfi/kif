<?php


namespace Layout;

use Lang\LangModule;
use LayoutDynamicHead\LayoutDynamicHeadModule;

class LayoutBridge
{
    /**
     * Owned by:
     * - class/Layout
     */
    public static function registerAssets(AssetsList $assetsList)
    {
//        QuickDocModule::registerAssets($assetsList);
        LayoutDynamicHeadModule::registerAssets($assetsList);
    }

    /**
     * Owned by:
     * - class/Layout
     */
    public static function displayLeftMenuBlocks()
    {
    }


    /**
     * Owned by:
     * - class-modules/LeftMenuSection/Tools/ToolsLeftMenuSectionModule
     */
    public static function displayToolsLeftMenuLinks()
    {
    }


    /**
     * Owned by:
     * - class/Layout
     */
    public static function displayTopBar()
    {
//        LangModule::displayTopBar();
    }

}