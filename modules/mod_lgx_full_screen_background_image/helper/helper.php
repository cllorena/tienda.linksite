<?php
/**
 * ------------------------------------------------------------------------
 * Helper - Full Screen  Background Image By LogicHunt.com
 * ------------------------------------------------------------------------
 * Copyright (C) 2014-2021 LogicHunt, All Rights Reserved.
 * license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: LogicHunt
 * Websites: http://logichunt.com - http://logichunt.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

class LgxFullScreenBackgroundImageHelper {

    public static function lgx_color_hex2rgba($color, $opacity) {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if(empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if(isset($opacity)){
            if(abs($opacity) > 1){
                $opacity = 1.0;
            }

            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }

        //Return rgb(a) color string
        return $output;
    }

    public static function lgx_counter_up_btn_group($option){

        if($option == 1) {
            $value = 'true';
        } elseif($option == 0)  {
            $value = 'false';
        }

        return $value;

    }


    /**
     * Source Data
     *
     * @param $source
     * @param $native_field
     * @param $custom_field
     *
     * @return array
     */



    public static function LgxCounterUpRandomize($items)
    {
        $shuffleKeys = array_keys($items);
        shuffle($shuffleKeys);
        $newArray = array();
        foreach ($shuffleKeys as $key) {
            $newArray[$key] = $items[$key];
        }
        return $newArray;
    }



    public static function LgxRowItemCount($item) {

        $gridWidth = '100%';
        if(!empty($item)) {

            $gridWidth = (100/$item).'%';

        }
        return $gridWidth;

    }



}