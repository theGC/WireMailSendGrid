<?php

namespace ProcessWire;


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


    /**
     * Hook WireMail ___send
     *
     */
    public function init() {

        $this->addHookBefore('WireMail::send', $this, 'hookWireMailBeforeSend');

    }


    /**
     * before WireMail send is executed
     * Prevent the use of WireMail and Process the send via SendGrid Web API
     * @param {HookEvent} ProcessWire event hook object
     * @return {int} a positive number (indicating number of addresses emailed) or 0 on failure
     *
     */
    public function hookWireMailBeforeSend(HookEvent $event)
    {

        require_once( __DIR__ . '/sendgrid-php/sendgrid-php.php' );

        // get the text boundaries from WireMail
        // not used by SendGrid but we retain certain string tests from Wiremail that require them
        //----------------------------------

        $boundary = $this->multipartBoundary();
        $subboundary = $this->multipartBoundary('alt');


        // prevent calling of WireMail ___send()
        //----------------------------------

        $event->replace = true;


        // set up an email with SendGrids PHP API
        //----------------------------------

        $email = new \SendGrid\Mail\Mail();


        // set the subject
        //----------------------------------

        $email->setSubject($this->subject);


        // set a from address
        //----------------------------------

        $fromEmail = $this->from ? $this->from : $this->sendGridFromEmail;

        $fromName = $this->fromName ? $this->fromName : '';

        if (!$fromName
            && $fromEmail === $this->sendGridFromEmail
            && $this->sendGridFromName) {

            $fromName = $this->sendGridFromName;

        }

        $email->setFrom($fromEmail, $fromName);


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

            $email->setReplyTo($replyToEmail, $replyToName);

        }


        // set the mail body - html and text
        //----------------------------------

        // as with WireMail - don't allow boundary to appear in visible portions of email
        $text = $this->strReplace($this->body, array($boundary, $subboundary));
        $html = $this->strReplace($this->bodyHTML, array($boundary, $subboundary));

        if ($text) {

            $email->addContent('text/plain', $text);

        }

        if ($html) {

            $email->addContent('text/html', $html);

        }


        // set any attachments
        //----------------------------------

        foreach($this->attachments as $filename => $file) {

            $filename = $this->wire('sanitizer')->text($filename, [
                'maxLength' => 512,
                'truncateTail' => false,
                'stripSpace' => '-',
                'stripQuotes' => true
            ]);

            if(stripos($filename, $boundary) !== false) continue;

            $content = base64_encode(file_get_contents($file));

            if(stripos($content, $boundary) !== false) continue;

            $mimeType = mime_content_type($filename);

            if(!$mimeType) continue;

            $email->addAttachment(
                $content,
                $mimeType,
                $filename,
                'attachment'
            );
        }


        // set 'to' addresses
        //----------------------------------

        // retain WireMail $numSent as return value
        $numSent = 0;

        foreach ($this->to as $to) {

            $toName = isset($this->mail['toName'][$to]) ? $this->mail['toName'][$to] : '';

            $email->addTo($to, $toName);

            $numSent++;

        }


        // Create instance of authenticated SendGrid API
        //----------------------------------

        $sendgrid = new \SendGrid($this->sendGridApiKey);

        try {

            $response = $sendgrid->send($email);

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

        $event->return = $numSent;

    }

}