<?php

/**
 * Mail OpenLDAP group extension.
 *
 * @category   Apps
 * @package    Mail_Extension
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mail_extension/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Lesser General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\mail_extension;

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('mail_extension');

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\base\Engine as Engine;
use \clearos\apps\mail\Base_Mail as Base_Mail;
use \clearos\apps\openldap_directory\Utilities as Utilities;

clearos_load_library('base/Engine');
clearos_load_library('mail/Base_Mail');
clearos_load_library('openldap_directory/Utilities');

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * Mail OpenLDAP group extension.
 *
 * @category   Apps
 * @package    Mail_Extension
 * @subpackage Libraries
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/lgpl.html GNU Lesser General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/mail_extension/
 */

class OpenLDAP_Group_Extension extends Engine
{
    ///////////////////////////////////////////////////////////////////////////////
    // V A R I A B L E S
    ///////////////////////////////////////////////////////////////////////////////

    protected $info_map = array();

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Mail OpenLDAP_group extension constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        include clearos_app_base('mail_extension') . '/deploy/group_map.php';

        $this->info_map = $info_map;
    }

    /** 
     * Add LDAP attributes hook.
     *
     * @param array $group_info  group information in hash array
     * @param array $ldap_object LDAP object
     *
     * @return array LDAP attributes
     * @throws Engine_Exception
     */

    public function add_attributes_hook($group_info, $ldap_object)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! isset($group_info['extensions']['mail']['distribution_list']))
            $group_info['extensions']['mail']['distribution_list'] = 0;
        else
            $group_info['extensions']['mail']['distribution_list'] = ($group_info['extensions']['mail']['distribution_list']) ? 1 : 0;

        $mail = new Base_Mail();
        $domain = $mail->get_domain();

        $group_info['extensions']['mail']['mail'] = $group_info['core']['group_name'] . '@' . $domain;

        $attributes = Utilities::convert_array_to_attributes($group_info['extensions']['mail'], $this->info_map, FALSE);

        return $attributes;
    }

    /**
     * Returns group info defaults hash array.
     *
     * @param string $group group name
     *
     * @return array group info defaults array
     * @throws Engine_Exception
     */

    public function get_info_defaults_hook($group)
    {
        clearos_profile(__METHOD__, __LINE__);

        $mail = new Base_Mail();
        $domain = $mail->get_domain();

        $info['distribution_list'] = 1;
        $info['mail'] = $group . '@' . $domain;

        return $info;
    }

    /**
     * Returns group info hash array.
     *
     * @param array $attributes LDAP attributes
     *
     * @return array group info array
     * @throws Engine_Exception
     */

    public function get_info_hook($attributes)
    {
        clearos_profile(__METHOD__, __LINE__);

        $info = Utilities::convert_attributes_to_array($attributes, $this->info_map);

        return $info;
    }

    /** 
     * Returns user info hash array.
     *
     * @return array user info array
     * @throws Engine_Exception
     */

    public function get_info_map_hook()
    {
        clearos_profile(__METHOD__, __LINE__);

        return $this->info_map;
    }

    /** 
     * Update LDAP attributes hook.
     *
     * @param array $group_info  group information in hash array
     * @param array $ldap_object LDAP object
     *
     * @return array LDAP attributes
     * @throws Engine_Exception
     */

    public function update_attributes_hook($group_info, $ldap_object)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! isset($group_info['extensions']['mail']))
            return array();

        $attributes = Utilities::convert_array_to_attributes($group_info['extensions']['mail'], $this->info_map, TRUE);
        $attributes['objectClass'][] = 'clearMailGroupAccount';

        return $attributes;
    }

    ///////////////////////////////////////////////////////////////////////////////
    // V A L I D A T I O N   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Validation routine for distribution list state.
     *
     * @param boolean $state state
     *
     * @return string error message if state is invalid
     */

    public function validate_distribution_list_state($state)
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Validation routine for e-mail address.
     *
     * @param string $email e-mail address
     *
     * @return string error message if e-mail address invalid
     */

    public function validate_email($email)
    {
        clearos_profile(__METHOD__, __LINE__);

        if (! preg_match("/^([a-z0-9_\-\.\$]+)@/", $email))
            return lang('mail_extension_email_invalid');
    }
}
