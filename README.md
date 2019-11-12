# WireMail: SendGrid

<img alt="SendGrid Logo" src="https://sendgrid.com/brand/sg-twilio/sg-twilio-lockup.svg" width="200px" height="auto">

A ProcessWire module: Extends WireMail to bypass PHP `mail` and send email via SendGrids Web API.

To use this module you'll need a SendGrid account.

> This module is considered beta. It should be safe for production but testing has been limited to ProcessWire master branch version >= 2.8.35 | PHP 7.3. Please report issues if you find them within Github.

## Requirements

* ProcessWire >= 2.8

## Module Use

WireMailSendGrid extends `\ProcessWire\WireMail`. To access its methods, as is the case with all WireMail Classes, you have 3 different methods to generate a new instance:

```
$m = $mail->new(); // option A
$m = wireMail(); // option B
$m = $modules->get('WireMailSendGrid'); // option C
```

Once you have a  `new mail()` instance you can access the public set methods or directly communicate with `\SendGrid\Mail\Mail()` via the modules $email property, i.e.

```
$m = $mail->new();

// call a public method on the module
$m->setTemplateId(<template-id>);

// call a method directly on the \SendGrid\Mail\Mail() instance
$m->email->setClickTracking(<enable>, <enable-text>);
```

For further info on the methods available via  `\SendGrid\Mail\Mail()` see:

[https://github.com/sendgrid/sendgrid-php/](https://github.com/sendgrid/sendgrid-php/)

## Module Config

Module Config settings are applied globally, so every email sent via WireMailSendGrid will recieve the config you set. You can override the config per instance by calling the modules methods as above.

<hr>

### Config Settings

#### SendGrid API Key (required)
Use an existing, or log into SendGrid and under `Settings` generate a new API Key: [SendGrid Settings / API Keys](https://app.sendgrid.com/settings/api_keys)

Copy and Paste your API Key into this field.

As a minimum, the API Key requires **Full** Mail Send Permissions.

For further info on API Keys: [SendGrid API Keys Docs](https://sendgrid.com/docs/ui/account-and-settings/api-keys/)

<hr>

### User Settings

#### From Email (required)
This is your default `from` email address. It is overwritten when a `from` address is passed to WireMail.

#### From Name
This is your default `from` email name. It is overwritten when a `from` name is passed to WireMail.

#### Reply-To Email
This is your default `reply-to` email address. It is overwritten when a `reply-to` address is passed to WireMail.

If not populated the emails `from` address is used for replies.

#### Reply-To Name
This is your default `reply-to` email address. It is overwritten when a `reply-to` address is passed to WireMail.

<hr>

### Click Tracking Settings

> Allows you to track whether a recipient clicked a link in your email.

#### Enable Click Tracking
Enables click tracking globally.

#### Enable Click Tracking Text
Indicates if this setting should be included in the text/plain portion of your email.

<hr>

### Open Tracking Settings

> Allows you to track whether the email was opened or not, by including a single pixel image in the body of the content. When the pixel is loaded, we can log that the email was opened.

#### Enable Open Tracking
Enables open tracking globally.

#### Substitution Tag
Allows you to specify a substitution tag that you can insert in the body of your email at a location that you desire. This tag will be replaced by the open tracking pixel.

<hr>

### Subscription Tracking Settings

> Allows you to insert a subscription management link at the bottom of the text and html bodies of your email. If you would like to specify the location of the link within your email, you may use the substitution_tag.

#### Enable Subscription Tracking
Enables subscription tracking globally.

#### Subscription Text
Text to be appended to the email, with the subscription tracking link. You may control where the link is by using the tag <% %>

#### Subscription HTML
HTML to be appended to the email, with the subscription tracking link. You may control where the link is by using the tag <% %>

#### Substitution Tag
A tag that will be replaced with the unsubscribe URL. for example: [unsubscribe_url]. If this parameter is used, it will override both the text and html parameters. The URL of the link will be placed at the substitution tag’s location, with no additional formatting.

<hr>

### Google Analytics Settings

> Allows you to enable tracking provided by Google Analytics.

#### Enable Google Analytics Tracking
Enables Google Analytics tracking globally.

#### UTM Source
Name of the referrer source. (e.g. Google, SomeDomain.com, or Marketing Email).

#### UTM Medium
Name of the marketing medium. (e.g. Email).

#### UTM Term
Used to identify any paid keywords.

#### UTM Content
Used to differentiate your campaign from advertisements.

#### UTM Campaign
The name of the campaign.

<hr>

### Test Settings

#### Enable Sandbox
This allows you to send a test email to ensure that your request body is valid and formatted correctly.

#### Log Successful Email
Useful for testing, will usually just pass a `202` code to the logs to indicate a mail was sent. Disable once set up as has little value in production.

Errors are always logged.

<hr>

## Public Module Methods

### send

method: `___send()`

Prevents the use of WireMail and Processes the email sending via SendGrids Web API.

<hr>

### to

method: `to($email = '', $name = '', $substitutions = null, $subject = null)`

args:
```
@param {string} $email - email address
@param {string} $name - name of the person associated with the email
@param {array|null} $substitutions - key/value substitutions to be be applied to an email template
@param {string|null} $subject - personalized subject of the email
@return $this
```

Sets an email 'to' payload. Emails can have multiple 'to' payloads via seperate calls to this method with distinct `$email` args.

<hr>

### toName

method: `toName($name = '', $email = '')`

args:
```
@param {string} $name - name of the person associated with the email
@param {string} $email - email address
@return $this
```

<hr>

### cc

method: `cc($email = '', $name = '', $substitutions = null, $subject = null)`

args:
```
@param {string} $email - email address
@param {string} $name - name of the person associated with the email
@param {array|null} $substitutions - key/value substitutions to be be applied to an email template
@param {string|null} $subject - personalized subject of the email
@return $this
```

Sets an email 'cc' payload. Emails can have multiple 'cc' payloads via seperate calls to this method with distinct `$email` args.

<hr>

### ccName

method: `ccName($name = '', $email = '')`

args:
```
@param {string} $name - name of the person associated with the email
@param {string} $email - email address
@return $this
```

<hr>

### bcc

method: `bcc($email = '', $name = '', $substitutions = null, $subject = null)`

args:
```
@param {string} $email - email address
@param {string} $name - name of the person associated with the email
@param {array|null} $substitutions - key/value substitutions to be be applied to an email template
@param {string|null} $subject - personalized subject of the email
@return $this
```

Sets an email 'bcc' payload. Emails can have multiple 'bcc' payloads via seperate calls to this method with distinct `$email` args.

<hr>

### bccName

method: `bccName($name = '', $email = '')`

args:
```
@param {string} $name - name of the person associated with the email
@param {string} $email - email address
@return $this
```

<hr>

### replyTo

method: `replyTo($email = '', $name = '')`

args:
```
@param {string} $email - email address
@param {string} $name - name of the person associated with the email
@return $this
```

Sanitises and sets the emails reply-to address.

<hr>

### attachment

method: `attachment($path = '', $filename = '', $disposition = null, $contentId = null)`

args:
```
@param {string} $path - Full path and filename of file attachment
@param {string} $filename - Optional different basename for file as it appears in the mail
@param {string|null} $disposition - How the attachment should be displayed: inline or attachment
@param {string|null} $contentId - Used when disposition is inline to diplay the file within the body of the email
@return $this
```

Add Attachments - provides fallback for older versions and adds SendGrid type and id to payload.

<hr>

### setTemplateId

method: `setTemplateId($id = '')`

args:
```
@param {string} $id - SendGrid template ID
@return $this
@throws WireException if ID not a string
```

Set a SendGrid Template ID to render the email within.

<hr>

### setDynamicTemplateData

method: `setDynamicTemplateData($name = '', $value = '')`

args:
```
@param {String} $name - dynamicTemplateSubstitution name/key
@param {String|Array|Object|Boolean|Integer|null} $value - if null unset the key
@return $this
@throws WireException if name not a string
```

Set a SendGrid Dynamic Template Substitution. Allows dynamicTemplateSubstitutions to be unset by passing a `null` value.

<hr>

### setCustomArg

method: `setCustomArg($name = '', $value = '')`

args:
```
@param {String} $name - Custom Arg name/key
@param {String|null} $value - if null unset the key
@return $this
@throws WireException if name not a string
```

Set a SendGrid Custom Arg. Allows customArgs to be unset by passing a `null` value.

<hr>

### setSection

method: `setSection($name = '', $value = '')`

args:
```
@param {String} $name - Section name/key
@param {String|null} $value - if null unset the key
@return $this
@throws WireException if name not a string
```

Set a SendGrid section. Allows sections to be unset by passing a `null` value.

<hr>

### addCategory

method: `addCategory($category = '')`

args:
```
@param {String} $category - category name
@return $this
@throws WireException if category not a string
```

Add a SendGrid category.

<hr>

### setSendAt

method: `setSendAt($sendAt)`

args:
```
@param {int} $sendAt - unix timestamp, when you want your email to be delivered. (no more than 72hrs away)
@return $this
@throws WireException if $sendAt not an int
```

Set a SendGrid sendAt value.

<hr>

### setBatchId

method: `setBatchId($batchId)`

args:
```
@param {String} $batchId - SendGrid Batch ID
@return $this
@throws WireException if $batchId not a string
```

Add the a batch ID value - ID represents a batch of emails to be sent at the same time.

## SendGrid API Methods

When instantiated this module provides an `$email` param allowing you to directly call the methods available to the SendGrid API.

```
$m = $mail->new();

// call a method directly on the \SendGrid\Mail\Mail() instance
$m->email->setClickTracking(<enable>, <enable-text>);
```

For further info on the methods available via  `\SendGrid\Mail\Mail()` see:

[https://github.com/sendgrid/sendgrid-php/](https://github.com/sendgrid/sendgrid-php/)