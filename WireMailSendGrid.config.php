<?php

namespace ProcessWire;


/**
 * Configure Wire Mail SendGrid
 *
 */

class WireMailSendGridConfig extends ModuleConfig {

    public function __construct() {

        $this->add([

            'name'        => 'sendGridSettingsFieldset',
            'type'        => 'fieldset',
            'label'       => $this->_('SendGrid Settings'),
            'columnWidth' => 100,
            'children'    => [

                [
                    'name'        => 'sendGridApiKey',
                    'collapsed'   => 1,
                    'type'        => 'text',
                    'label'       => $this->_('SendGrid API Key'),
                    'required'    => true,
                    'columnWidth' => 100,
                ],

                [
                    'name'        => 'sendGridFromEmail',
                    'collapsed'   => 2,
                    'type'        => 'text',
                    'label'       => $this->_('From Email'),
                    'description' => $this->_('Default email from address.'),
                    'required'    => true,
                    'columnWidth' => 50,
                ],

                [
                    'name'        => 'sendGridFromName',
                    'collapsed'   => 2,
                    'type'        => 'text',
                    'label'       => $this->_('From Name'),
                    'description' => $this->_('Default email from name.'),
                    'required'    => true,
                    'columnWidth' => 50,
                ],

                [
                    'name'        => 'sendGridReplyToEmail',
                    'collapsed'   => 2,
                    'type'        => 'text',
                    'label'       => $this->_('Reply-To Email'),
                    'description' => $this->_('Default email reply-to address.'),
                    'required'    => false,
                    'columnWidth' => 50,
                ],

                [
                    'name'        => 'sendGridReplyToName',
                    'collapsed'   => 2,
                    'type'        => 'text',
                    'label'       => $this->_('Reply-To Name'),
                    'description' => $this->_('Default email reply-to name.'),
                    'required'    => false,
                    'columnWidth' => 50,
                ],

                [
                    'name'        => 'sendGridLogSuccess',
                    'collapsed'   => 2,
                    'type'        => 'checkbox',
                    'label'       => $this->_('Log Successful Email'),
                    'description' => $this->_('Useful for testing, disable once set up - errors are always logged.'),
                    'required'    => false,
                    'columnWidth' => 100,
                ],

            ],

        ]);

    }

}
