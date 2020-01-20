<?php


/**
 * WireMailSendGrid
 * Extend WireMail to bypass PHP Mail and send mail via SendGrids Web API
 *
 */

class WireMailSendGrid extends WireMail implements Module, ConfigurableModule {


    /**
     * log file names
     *
     */
    const SUCCESS_LOG = 'wiremail-sendgrid';

    const ERROR_LOG = 'wiremail-sendgrid-errors';


    // SendGrid email instance
    // Access this to set features directly on the instance
    // https://github.com/sendgrid/sendgrid-php/
    // this is an instance of \SendGrid\Mail\Mail()
    public $email = null;


    // SendGrid template ID
    protected $templateId = '';

    // SendGrid dynamic template data
    protected $dynamicTemplateData = [];

    // SendGrid custom args
    protected $customArgs = [];

    // SendGrid sections
    protected $sections = [];

    // SendGrid categories
    protected $categories = [];


    /**
     * Initialize the module
     *
     */
    public function init() {

        // get the SendGrid Classes
        //----------------------------------

        require_once( __DIR__ . '/sendgrid-php/sendgrid-php.php' );


        // set up an email with SendGrids PHP API
        //----------------------------------

        $this->email = new \SendGrid\Mail\Mail();


        // Add additional keys to mail
        //----------------------------------

        // fallback for older versions of WireMail
        if (!isset($this->mail['attachments'])) $this->mail['attachments'] = [];

        if (!isset($this->mail['cc'])) $this->mail['cc'] = [];

        if (!isset($this->mail['bcc'])) $this->mail['bcc'] = [];


        // Set Default Email Config
        //----------------------------------

        $this->setDefaultConfig();

    }


    /**
     * Set up any config values stored on the module
     *
     */
    protected function setDefaultConfig()
    {

        // Click Tracking Settings
        //----------------------------------

        if ($this->sendGridClickTrackingEnable) {

            $this->email->setClickTracking(
                true,
                !!($this->sendGridClickTrackingEnableText)
            );

        }


        // Open Tracking Settings
        //----------------------------------

        if ($this->sendGridOpenTrackingEnable) {

            $this->email->setOpenTracking(
                true,
                $this->sendGridOpenTrackingSubstitutionTag
                    ? $this->sendGridOpenTrackingSubstitutionTag
                    : null
            );

        }


        // Subscription Tracking Settings
        //----------------------------------

        if ($this->sendGridSubscriptionTrackingEnable) {

            $this->email->setSubscriptionTracking(
                true,
                $this->sendGridSubscriptionTrackingText
                    ? $this->sendGridSubscriptionTrackingText
                    : null,
                $this->sendGridSubscriptionTrackingHTML
                    ? $this->sendGridSubscriptionTrackingHTML
                    : null,
                $this->sendGridSubscriptionTrackingSubstitutionTag
                    ? $this->sendGridSubscriptionTrackingSubstitutionTag
                    : null
            );

        }


        // Subscription Tracking Settings
        //----------------------------------

        if ($this->sendGridAnalyticsEnable) {

            $this->email->setGanalytics(
                true,
                $this->sendGridAnalyticsUtmSource
                    ? $this->sendGridAnalyticsUtmSource
                    : null,
                $this->sendGridAnalyticsUtmMedium
                    ? $this->sendGridAnalyticsUtmMedium
                    : null,
                $this->sendGridAnalyticsUtmTerm
                    ? $this->sendGridAnalyticsUtmTerm
                    : null,
                $this->sendGridAnalyticsUtmContent
                    ? $this->sendGridAnalyticsUtmContent
                    : null,
                $this->sendGridAnalyticsUtmCampaign
                    ? $this->sendGridAnalyticsUtmCampaign
                    : null
            );

        }


        // Sandbox
        //----------------------------------

        if ($this->sendGridSandbox) $this->email->enableSandBoxMode();

    }


    /**
     * Prevent the use of WireMail and Process the send via SendGrid Web API
     * @return {int} a positive number (indicating number of addresses emailed) or 0 on failure
     *
     */
    public function ___send()
    {

        // set the subject
        //----------------------------------

        $this->email->setSubject($this->subject);


        // set a from address
        //----------------------------------

        $fromEmail = $this->from ? $this->from : $this->sendGridFromEmail;

        $fromName = $this->fromName ? $this->fromName : '';

        if (!$fromName
            && $fromEmail === $this->sendGridFromEmail
            && $this->sendGridFromName) {

            $fromName = $this->sendGridFromName;

        }

        $this->email->setFrom($fromEmail, $fromName);


        // set a reply to address if different to from
        //----------------------------------

        $replyToEmail = $this->mail['replyTo'] ? $this->mail['replyTo'] : $this->sendGridReplyToEmail;

        if (!$replyToEmail) $replyToEmail = $fromEmail;

        $replyToName = $this->mail['replyToName'] ? $this->mail['replyToName'] : '';

        if (!$replyToName
            && $replyToEmail === $this->sendGridReplyToEmail
            && $this->sendGridReplyToName) {

            $replyToName = $this->sendGridReplyToName;

        }

        if ($replyToEmail !== $fromEmail
            || $replyToName !== $fromName) {

            $this->email->setReplyTo($replyToEmail, $replyToName);

        }


        // set the mail body - html and text
        //----------------------------------

        if ($this->body) {

            $this->email->addContent('text/plain', $this->body);

        }

        if ($this->bodyHTML) {

            $this->email->addContent('text/html', $this->bodyHTML);

        }


        // set any cc
        //----------------------------------

        if (count($this->mail['cc'])) {

            foreach ($this->mail['cc'] as $cc) {

                $this->email->addCc($cc['email'], $cc['name'], $cc['substitutions']);

            }

        }


        // set any bcc
        //----------------------------------

        if (count($this->mail['bcc'])) {

            foreach ($this->mail['bcc'] as $bcc) {

                $this->email->addBcc($bcc['email'], $bcc['name'], $bcc['substitutions']);

            }

        }


        // set any attachments
        //----------------------------------

        if (count($this->mail['attachments'])) {

            foreach ($this->mail['attachments'] as $attachmentPayload) {

                $filename = $this->wire('sanitizer')->text($attachmentPayload['filename'], [
                    'maxLength' => 512,
                    'truncateTail' => false,
                    'stripSpace' => '-',
                    'stripQuotes' => true
                ]);

                if(!$filename) continue;

                $content = base64_encode(file_get_contents($attachmentPayload['path']));

                if(!$content) continue;

                $mimeType = mime_content_type($filename);

                if(!$mimeType) continue;

                $this->email->addAttachment(
                    $content,
                    $mimeType,
                    $filename,
                    $attachmentPayload['disposition'],
                    $attachmentPayload['contentId']
                );
            }

        }


        // add headers
        //----------------------------------

        foreach ($this->mail['header'] as $key => $value) {

            $this->email->addHeader($key, $value);

        }


        // set 'to' addresses
        //----------------------------------

        // retain WireMail $numSent as return value
        $numSent = 0;

        foreach ($this->mail['to'] as $to) {

            $this->email->addTo($to['email'], $to['name'], $to['substitutions']);

            $numSent++;

        }


        // Set any dynamic template data
        //----------------------------------

        if (count($this->dynamicTemplateData)) {

            $this->email->addDynamicTemplateDatas($this->dynamicTemplateData);

        }


        // Set any custom args
        //----------------------------------

        if (count($this->customArgs)) {

            $this->email->addCustomArgs($this->customArgs);

        }


        // Set any sections
        //----------------------------------

        if (count($this->sections)) {

            $this->email->addSections($this->sections);

        }


        // Create instance of authenticated SendGrid API
        //----------------------------------

        $sendgrid = new \SendGrid($this->sendGridApiKey);

        try {

            $response = $sendgrid->send($this->email);

            $statusCode = $response->statusCode();

            $message = $statusCode;

            if ($response->body()) $message .= ': ' . $response->body();

            if ($statusCode >= 300) {

                $this->log->save(self::ERROR_LOG, $message);

            } else if ($this->sendGridLogSuccess) {

                $this->log->save(self::SUCCESS_LOG, $message);

            }

        } catch (Exception $e) {

            $numSent = 0;

            $this->log->save(self::ERROR_LOG, $e->getMessage());

        }


        // replicate WireMail return value
        //----------------------------------

        return $numSent;

    }

    /**
     * process the passed payload and assign to the mail key
     * use the email address as a key for the associated array
     * @param {string} $mailKey - the key to assign
     * @param {string} $email - email address
     * @param {string} $name - name of the person associated with the email
     * @param {array|null} $substitutions - key/value substitutions to be be applied to an email template
     * @param {string} $subject - personalized subject of the email
     * @return $this
     * @throws WireException if you attempt to set an invlaid mail key
     *
     */
    protected function addEmailPayload(
        $mailKey = '',
        $email = '',
        $name = '',
        $substitutions = null,
        $subject = null
    ) {

        if (!$mailKey
            || !isset($this->mail[$mailKey])
            || !is_array($this->mail[$mailKey])) {

            throw new WireException("mail key not a valid array: $mailKey");

        }

        $email = $this->sanitizeEmail($email);

        $name = $this->wire('sanitizer')->text($name);

        if ($subject && is_string($subject)) {

            $substitutions = [
                'subject' => $subject,
            ];

        }

        if ($email) {

            $this->mail[$mailKey][$email] = [
                'email' => $email,
                'name' => $name,
                'substitutions' => $substitutions,
            ];

        }

        return $this;

    }

    /**
     * Support WireMail feature albeit with the requirement for the email to be passed
     * @param {string} $mailKey - the key to assign
     * @param {string} $name - name of the person associated with the email
     * @param {string} $email - email address
     * @return $this
     * @throws WireException if you attempt to set an invlaid mail key
     *
     */
    protected function addEmailName(
        $mailKey = '',
        $name = '',
        $email = ''
    ) {

        if (!$mailKey
            || !isset($this->mail[$mailKey])
            || !is_array($this->mail[$mailKey])) {

            throw new WireException("mail key not a valid array: $mailKey");

        }

        if ($name
            && $email
            && $this->mail[$mailKey][$email]) {

            $this->mail[$mailKey][$email]['name'] = $name;

        }

        return $this;

    }


    /**
     * set to
     * @param {string} $email - email address
     * @param {string} $name - name of the person associated with the email
     * @param {array|null} $substitutions - key/value substitutions to be be applied to an email template
     * @param {string|null} $subject - personalized subject of the email
     * @return $this
     *
     */
    public function to($email = '', $name = '', $substitutions = null, $subject = null)
    {

        // WireMail clears existing values if email is null
        //----------------------------------

        if (!$email) {

            $this->mail['to'] = [];

            return $this;

        }


        // convert email & name to array to support WireMail
        //----------------------------------

        $emails = is_array($email) ? $email : explode(',', $email);

        $names = is_array($name) ? $name : explode(',', $name);

        foreach ($emails as $index => $value) {

            $this->addEmailPayload('to', $emails[$index], $names[$index], $substitutions, $subject);

        }

        return $this;

    }


    /**
     * Set the 'to' name - 'to' is an associated array keyed by email
     *
     * @param {string} $name - name of the person associated with the email
     * @param {string} $email - email address
     * @return $this
     *
     */
    public function toName($name = '', $email = '') {

        return $this->addEmailName('to', $name, $email);

    }


    /**
     * set CC
     * @param {string} $email - email address
     * @param {string} $name - name of the person associated with the email
     * @param {array|null} $substitutions - key/value substitutions to be be applied to an email template
     * @param {string|null} $subject - personalized subject of the email
     * @return $this
     *
     */
    public function cc($email = '', $name = '', $substitutions = null, $subject = null)
    {

        return $this->addEmailPayload('cc', $email, $name, $substitutions, $subject);

    }


    /**
     * Set the 'cc' name - 'cc' is an associated array keyed by email
     *
     * @param {string} $name - name of the person associated with the email
     * @param {string} $email - email address
     * @return $this
     *
     */
    public function ccName($name = '', $email = '') {

        return $this->addEmailName('cc', $name, $email);

    }


    /**
     * set BCC
     * @param {string} $email - email address
     * @param {string} $name - name of the person associated with the email
     * @param {array|null} $substitutions - key/value substitutions to be be applied to an email template
     * @param {string|null} $subject - personalized subject of the email
     * @return $this
     *
     */
    public function bcc($email = '', $name = '', $substitutions = null, $subject = null)
    {

        return $this->addEmailPayload('bcc', $email, $name, $substitutions, $subject);

    }


    /**
     * Set the 'bcc' name - 'bcc' is an associated array keyed by email
     *
     * @param {string} $name - name of the person associated with the email
     * @param {string} $email - email address
     * @return $this
     *
     */
    public function bccName($name = '', $email = '') {

        return $this->addEmailName('bcc', $name, $email);

    }


    /**
     * Add ReplyTo for older versions of ProcessWire (v2)
     * @param {String} $email - email address
     * @param {String} $name - name of the person associated with the email
     * @return $this
     *
     */
    public function replyTo($email = '', $name = '')
    {

        $email = $this->sanitizeEmail($email);

        if ($email) $this->mail['replyTo'] = $email;

        $name = $this->wire('sanitizer')->text($name);

        if ($name) $this->mail['replyToName'] = $name;

        return $this;

    }


    /**
     * Add Attachments - provides fallback for older versions and adds SendGrid type and id
     * @param {string} $path - Full path and filename of file attachment
     * @param {string} $filename - Optional different basename for file as it appears in the mail
     * @param {string|null} $disposition - How the attachment should be displayed: inline or attachment
     * @param {string|null} $contentId - Used when disposition is inline to diplay the file within the body of the email
     * @return $this
     *
     */
    public function attachment(
        $path = '',
        $filename = '',
        $disposition = null,
        $contentId = null
    ) {

        if (is_null($path)) {

            $this->mail['attachments'] = [];

        } else if (is_file($path)) {

            if (!$filename) $filename = basename($path);

            $this->mail['attachments'][$filename] = [
                'path' => $path,
                'filename' => $filename,
                'disposition' => $disposition,
                'contentId' => $contentId,
            ];

        }

        return $this;

    }


    /**
     * Set a SendGrid Template ID
     * @param {string} $id - SendGrid template ID
     * @return $this
     * @throws WireException if ID not a string
     *
     */
    public function setTemplateId($id = '')
    {

        if (!is_string($id)) throw new WireException('template ID must be of type string.');

        $this->email->setTemplateId($id);

        return $this;

    }


    /**
     * Set a SendGrid Dynamic Template Substitution
     * @param {String} $name
     * @param {String|Array|Object|Boolean|Integer|null} $value - if null unset the key
     * @return $this
     * @throws WireException if name not a string
     *
     */
    public function setDynamicTemplateData($name = '', $value = '')
    {

        if (!is_string($name)) throw new WireException('dynamic template name/key must be of type string.');

        if (!$value) {

            unset($this->dynamicTemplateData[$name]);

        } else {

            $this->dynamicTemplateData[$name] = $value;

        }

        return $this;

    }


    /**
     * Set a SendGrid Custom Arg
     * @param {String} $name
     * @param {String|null} $value - if null unset the key
     * @return $this
     * @throws WireException if name not a string
     *
     */
    public function setCustomArg($name = '', $value = '')
    {

        if (!is_string($name)) throw new WireException('Custom Arg name/key must be of type string.');

        if (!is_string($value)
            || !$value) {

            unset($this->customArgs[$name]);

        } else {

            $this->customArgs[$name] = $value;

        }

        return $this;

    }


    /**
     * Set a SendGrid section
     * @param {String} $name - Section name/key
     * @param {String|null} $value - section value - if null unset the key
     * @return $this
     * @throws WireException if name not a string
     *
     */
    public function setSection($name = '', $value = '')
    {

        if (!is_string($name)) throw new WireException('Section name/key must be of type string.');

        if (!is_string($value)
            || !$value) {

            unset($this->sections[$name]);

        } else {

            $this->sections[$name] = $value;

        }

        return $this;

    }


    /**
     * Add a SendGrid category
     * @param {String} $category - category name
     * @return $this
     * @throws WireException if category not a string
     *
     */
    public function addCategory($category = '')
    {

        if (!is_string($category)) throw new WireException('category must be of type string.');

        $this->email->addCategory($category);

        return $this;

    }


    /**
     * Set a SendGrid sendAt value
     *
     * @param {int} $sendAt - unix timestamp, when you want your email to be delivered. (no more than 72hrs away)
     * @return $this
     * @throws WireException if $sendAt not an int
     */
    public function setSendAt($sendAt)
    {

        if (!is_int($sendAt)) throw new WireException('$sendAt must be of type int.');

        $this->email->setSendAt($sendAt);

        return $this;

    }


    /**
     * Add the a batch ID value - ID represents a batch of emails to be sent at the same time
     *
     * @param {String} $batchId - SendGrid Batch ID
     * @return $this
     * @throws WireException if $batchId not a string
     */
    public function setBatchId($batchId)
    {

        if (!is_string($batchId)) throw new WireException('$batchId must be of type string.');

        $this->email->setBatchId($batchId);

        return $this;

    }

}