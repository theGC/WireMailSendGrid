
# WireMailSendGrid

![SendGrid Logo](https://sendgrid.com/brand/sg-twilio/sg-twilio-lockup.svg "SendGrid Logo" =180x)

A ProcessWire module: Extends WireMail to bypass PHP `mail` and send email via SendGrids Web API.

To use this module you'll need a SendGrid account.

> This module is considered beta. It should be safe for production but testing has been limited to ProcessWire master branch version >= 3.0.137 | PHP 7.3. Please report issues if you find them within Github.

## Requirements

* ProcessWire >= 3.0

## Config

The modules configuration page allows for configuration of the following:

#### SendGrid API Key (required)
Use an existing, or log into SendGrid and under `Settings` generate a new API Key: [SendGrid Settings / API Keys](https://app.sendgrid.com/settings/api_keys)

Copy and Paste your API Key into this field.

As a minimum, the API Key requires **Full** Mail Send Permissions.

For further info on API Keys: [SendGrid API Keys Docs](https://sendgrid.com/docs/ui/account-and-settings/api-keys/)

#### From Email (required)
This is your default `from` email address. It is overwritten when a `from` address is passed to WireMail.

#### From Name
This is your default `from` email name. It is overwritten when a `from` name is passed to WireMail.

#### Reply-To Email
This is your default `reply-to` email address. It is overwritten when a `reply-to` address is passed to WireMail.

If not populated the emails `from` address is used for replies.

#### Reply-To Name
This is your default `reply-to` email address. It is overwritten when a `reply-to` address is passed to WireMail.

#### Log Successful Email
Useful for testing, will usually just pass a `202` code to the logs to indicate a mail was sent. Disable once set up as has little value in production.

Errors are always logged.