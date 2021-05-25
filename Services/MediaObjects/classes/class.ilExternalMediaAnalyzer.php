<?php

/* Copyright (c) 1998-2021 ILIAS open source, GPLv3, see LICENSE */

/**
 * Analyzes external media locations and extracts important information
 * into parameter field.
 *
 * @author Alex Killing <alex.killing@gmx.de>
 */
class ilExternalMediaAnalyzer
{
    /**
    * Identify YouTube links
    */
    public static function isYouTube($a_location)
    {
        if (strpos($a_location, "youtube.com") > 0 ||
                strpos($a_location, "youtu.be") > 0) {
            return true;
        }
        return false;
    }
    
    /**
    * Extract YouTube Parameter
    */
    public static function extractYouTubeParameters($a_location)
    {
        $par = array();
        $pos1 = strpos($a_location, "v=");
        $pos2 = strpos($a_location, "&", $pos1);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["v"] = substr($a_location, $pos1 + 2, $len - ($pos1 + 2));
        } elseif (strpos($a_location, "youtu.be") > 0) {
            $par["v"] = substr($a_location, strrpos($a_location, "/") + 1);
        }

        return $par;
    }

    /**
    * Identify Flickr links
    */
    public static function isFlickr($a_location)
    {
        if (strpos($a_location, "flickr.com") > 0) {
            return true;
        }
        return false;
    }

    /**
    * Extract Flickr Parameter
    */
    public static function extractFlickrParameters($a_location)
    {
        $par = array();
        $pos1 = strpos($a_location, "flickr.com/photos/");
        $pos2 = strpos($a_location, "/", $pos1 + 18);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : $a_location;
            $par["user_id"] = substr($a_location, $pos1 + 18, $len - ($pos1 + 18));
        }
        
        // tags
        $pos1 = strpos($a_location, "/tags/");
        $pos2 = strpos($a_location, "/", $pos1 + 6);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["tags"] = substr($a_location, $pos1 + 6, $len - ($pos1 + 6));
        }

        // sets
        $pos1 = strpos($a_location, "/sets/");
        $pos2 = strpos($a_location, "/", $pos1 + 6);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : $a_location;
            $par["sets"] = substr($a_location, $pos1 + 6, $len - ($pos1 + 6));
        }

        return $par;
    }

    /**
    * Identify GoogleVideo links
    */
    public static function isGoogleVideo($a_location)
    {
        if (strpos($a_location, "video.google") > 0) {
            return true;
        }
        return false;
    }
    
    /**
    * Extract GoogleVideo Parameter
    */
    public static function extractGoogleVideoParameters($a_location)
    {
        $par = array();
        $pos1 = strpos($a_location, "docid=");
        $pos2 = strpos($a_location, "&", $pos1 + 6);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["docid"] = substr($a_location, $pos1 + 6, $len - ($pos1 + 6));
        }

        return $par;
    }

    /**
     * Identify Vimeo links
     */
    public static function isVimeo($a_location)
    {
        if (strpos($a_location, "vimeo.com") > 0) {
            return true;
        }
        return false;
    }

    /**
     * Extract Vimeo Parameter
     */
    public static function extractVimeoParameters($a_location)
    {
        $par = array();
        $pos1 = strpos($a_location, "vimeo.com/");
        $pos2 = strpos($a_location, "&", $pos1 + 10);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["id"] = substr($a_location, $pos1 + 10, $len - ($pos1 + 10));
        }

        return $par;
    }

    /**
    * Identify Google Document links
    */
    public static function isGoogleDocument($a_location)
    {
        if (strpos($a_location, "docs.google") > 0) {
            return true;
        }
        return false;
    }
    
    /**
    * Extract GoogleDocument Parameter
    */
    public static function extractGoogleDocumentParameters($a_location)
    {
        $par = array();
        $pos1 = strpos($a_location, "id=");
        $pos2 = strpos($a_location, "&", $pos1 + 3);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["docid"] = substr($a_location, $pos1 + 3, $len - ($pos1 + 3));
        }
        $pos1 = strpos($a_location, "docID=");
        $pos2 = strpos($a_location, "&", $pos1 + 6);
        if ($pos1 > 0) {
            $len = ($pos2 > 0)
                ? $pos2
                : strlen($a_location);
            $par["docid"] = substr($a_location, $pos1 + 6, $len - ($pos1 + 6));
        }
        if (strpos($a_location, "Presentation?") > 0) {
            $par["type"] = "Presentation";
        }
        if (strpos($a_location, "View?") > 0) {
            $par["type"] = "Document";
        }

        return $par;
    }
    
    /**
    * Extract URL information to parameter array
    */
    public static function extractUrlParameters($a_location, $a_parameter)
    {
        if (!is_array($a_parameter)) {
            $a_parameter = array();
        }
        
        $ext_par = array();
        
        // YouTube
        if (ilExternalMediaAnalyzer::isYouTube($a_location)) {
            $ext_par = ilExternalMediaAnalyzer::extractYouTubeParameters($a_location);
            $a_parameter = array();
        }

        // Flickr
        if (ilExternalMediaAnalyzer::isFlickr($a_location)) {
            $ext_par = ilExternalMediaAnalyzer::extractFlickrParameters($a_location);
            $a_parameter = array();
        }

        // GoogleVideo
        if (ilExternalMediaAnalyzer::isGoogleVideo($a_location)) {
            $ext_par = ilExternalMediaAnalyzer::extractGoogleVideoParameters($a_location);
            $a_parameter = array();
        }

        // GoogleDocs
        if (ilExternalMediaAnalyzer::isGoogleDocument($a_location)) {
            $ext_par = ilExternalMediaAnalyzer::extractGoogleDocumentParameters($a_location);
            $a_parameter = array();
        }

        foreach ($ext_par as $name => $value) {
            $a_parameter[$name] = $value;
        }

        return $a_parameter;
    }
}
