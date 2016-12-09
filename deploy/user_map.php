<?php

/**
 * Mail OpenLDAP user extension.
 *
 * @category   apps
 * @package    mail-extension
 * @subpackage configuration
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
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('mail_extension');

///////////////////////////////////////////////////////////////////////////////
// C O N F I G
///////////////////////////////////////////////////////////////////////////////

if (file_exists('/etc/clearos/mail_extension.conf'))
    require_once '/etc/clearos/mail_extension.conf';

$mail_priority = isset($mail_visibility) ? $mail_visibility : 'read_only';

$info_map = array(
    'mail' => array(
        'type' => 'string',
        'field_type' => 'text',
        'field_priority' => $mail_priority,
        'required' => FALSE,
        'validator' => 'validate_email',
        'validator_class' => 'mail_extension/OpenLDAP_User_Extension',
        'description' => lang('mail_extension_email'),
        'object_class' => 'clearMailAccount',
        'attribute' => 'mail'
    ),

    'aliases' => array(
        'type' => 'string_array',
        'field_type' => 'text_array',
        'required' => FALSE,
        'validator' => 'validate_alias',
        'validator_class' => 'mail_extension/OpenLDAP_User_Extension',
        'description' => lang('mail_extension_aliases'),
        'object_class' => 'clearMailAccount',
        'attribute' => 'clearMailAliases'
    ),
);

// Experimental

if (file_exists('/var/clearos/mail_routing/forwarders')) {
    $info_map['forwarders'] = array(
        'type' => 'string_array',
        'field_type' => 'text_array',
        'required' => FALSE,
        'validator' => 'validate_forwarder',
        'validator_class' => 'mail_extension/OpenLDAP_User_Extension',
        'description' => lang('mail_extension_forwarders'),
        'object_class' => 'clearMailAccount',
        'attribute' => 'clearMailForwarders'
    );
}
