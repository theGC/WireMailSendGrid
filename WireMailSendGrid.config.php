<?php


/**
 * Configure Wire Mail SendGrid
 *
 */

class WireMailSendGridConfig extends ModuleConfig {

    public function __construct() {

        $this->add([

            [
                'name'        => 'userSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Config Settings'),
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

                ],

            ],

            [
                'name'        => 'userSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('User Settings'),
                'columnWidth' => 100,
                'children'    => [

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

                ],

            ],

            [
                'name'        => 'clickTrackingSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Click Tracking Settings'),
                'description' => $this->_('Allows you to track whether a recipient clicked a link in your email.'),
                'columnWidth' => 100,
                'children'    => [

                    [
                        'name'        => 'sendGridClickTrackingEnable',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Click Tracking'),
                        'description' => $this->_('Enable this setting globally.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridClickTrackingEnableText',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Click Tracking Text'),
                        'description' => $this->_('Indicates if this setting should be included in the text/plain portion of your email.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                ],

            ],

            [
                'name'        => 'openTrackingSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Open Tracking Settings'),
                'description' => $this->_('Allows you to track whether the email was opened or not, by including a single pixel image in the body of the content. When the pixel is loaded, we can log that the email was opened.'),
                'columnWidth' => 100,
                'children'    => [

                    [
                        'name'        => 'sendGridOpenTrackingEnable',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Open Tracking'),
                        'description' => $this->_('Enable this setting globally.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridOpenTrackingSubstitutionTag',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('Substitution Tag'),
                        'description' => $this->_('Allows you to specify a substitution tag that you can insert in the body of your email at a location that you desire. This tag will be replaced by the open tracking pixel.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                ],

            ],

            [
                'name'        => 'subscriptionTrackingSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Subscription Tracking Settings'),
                'description' => $this->_('Allows you to insert a subscription management link at the bottom of the text and html bodies of your email. If you would like to specify the location of the link within your email, you may use the substitution_tag.'),
                'columnWidth' => 100,
                'children'    => [

                    [
                        'name'        => 'sendGridSubscriptionTrackingEnable',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Subscription Tracking'),
                        'description' => $this->_('Enable this setting globally.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridSubscriptionTrackingText',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('Subscription Text'),
                        'description' => $this->_('Text to be appended to the email, with the subscription tracking link. You may control where the link is by using the tag <% %>'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridSubscriptionTrackingHTML',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('Subscription HTML'),
                        'description' => $this->_('HTML to be appended to the email, with the subscription tracking link. You may control where the link is by using the tag <% %>'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridSubscriptionTrackingSubstitutionTag',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('Substitution Tag'),
                        'description' => $this->_('A tag that will be replaced with the unsubscribe URL. for example: [unsubscribe_url]. If this parameter is used, it will override both the text and html parameters. The URL of the link will be placed at the substitution tag’s location, with no additional formatting.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                ],

            ],

            [
                'name'        => 'analyticsTrackingSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Google Analytics Settings'),
                'description' => $this->_('Allows you to enable tracking provided by Google Analytics.'),
                'columnWidth' => 100,
                'children'    => [

                    [
                        'name'        => 'sendGridAnalyticsEnable',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Google Analytics Tracking'),
                        'description' => $this->_('Enable this setting globally.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridAnalyticsUtmSource',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('UTM Source'),
                        'description' => $this->_('Name of the referrer source. (e.g. Google, SomeDomain.com, or Marketing Email).'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridAnalyticsUtmMedium',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('UTM Medium'),
                        'description' => $this->_('Name of the marketing medium. (e.g. Email).'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridAnalyticsUtmTerm',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('UTM Term'),
                        'description' => $this->_('Used to identify any paid keywords.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridAnalyticsUtmContent',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('UTM Content'),
                        'description' => $this->_('Used to differentiate your campaign from advertisements.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridAnalyticsUtmCampaign',
                        'collapsed'   => 2,
                        'type'        => 'text',
                        'label'       => $this->_('UTM Campaign'),
                        'description' => $this->_('The name of the campaign.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                ],

            ],

            [
                'name'        => 'testingSettingsFieldset',
                'type'        => 'fieldset',
                'label'       => $this->_('Test Settings'),
                'columnWidth' => 100,
                'children'    => [

                    [
                        'name'        => 'sendGridSandbox',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Enable Sandbox'),
                        'description'       => $this->_('This allows you to send a test email to ensure that your request body is valid and formatted correctly.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                    [
                        'name'        => 'sendGridLogSuccess',
                        'collapsed'   => 2,
                        'type'        => 'checkbox',
                        'label'       => $this->_('Log Successful Emails'),
                        'description' => $this->_('Useful for testing, disable once set up - errors are always logged.'),
                        'required'    => false,
                        'columnWidth' => 50,
                    ],

                ],

            ],

        ]);

    }

}
