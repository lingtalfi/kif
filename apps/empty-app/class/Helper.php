<?php


class Helper
{

    public static function isLocal()
    {
        if ('/Volumes/' === substr(__DIR__, 0, 9)) {
            return true;
        }
        return false;
    }
}