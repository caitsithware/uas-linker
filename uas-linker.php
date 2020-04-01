<?php
/**
 * @package Unity Asset Store Linker
 * @version 1.0
 */
/*
Plugin Name: Unity Asset Store Linker
Plugin URI: https://github.com/caitsithware/uas-linker
Description: Shortcode that embeds a link to UnityAssetStore
Version: 1.0
Author: caitsithware
Author URI: https://github.com/caitsithware
License: GPLv2 or later
*/

/*  
    Copyright 2020 caitsithware
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace caitsithware\UASLinker {
    require_once( __DIR__ . '/classes/Plugin.php' );
    Plugin::init( __FILE__ );
    
    if ( is_admin() ) {
        require_once( __DIR__ . '/classes/Admin.php' );
        Admin::init( __FILE__ );
    }
}