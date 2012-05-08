<?php

/**
 * Mail OpenLDAP group extension.
 *
 * @category   Apps
 * @package    Mail_Extension
 * @subpackage Configuration
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
clearos_load_language('base');

///////////////////////////////////////////////////////////////////////////////
// C O N F I G
///////////////////////////////////////////////////////////////////////////////

$info_map = array(
    'distribution_list' => array(
        'type' => 'integer',
        'field_type' => 'list',
        'field_options' => array(
            '0' => lang('base_disabled'),
            '1' => lang('base_enabled'),
        ),
        'required' => TRUE,
        'validator' => 'validate_distribution_list_state',
        'validator_class' => 'mail_extension/OpenLDAP_Group_Extension',
        'description' => lang('mail_extension_distribution_list'),
        'object_class' => 'clearMailGroupAccount',
        'attribute' => 'clearMailDistributionList'
    ),
    'mail' => array(
        'type' => 'string',
        'field_type' => 'text',
        'field_priority' => 'read_only',
        'required' => FALSE,
        'validator' => 'validate_email',
        'validator_class' => 'mail_extension/OpenLDAP_Group_Extension',
        'description' => lang('mail_extension_email'),
        'object_class' => 'clearMailGroupAccount',
        'attribute' => 'mail'
    ),
);
