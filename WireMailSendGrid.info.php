<?php

namespace ProcessWire;


/**
 * Wire Mail SendGrid
 *
 */

$info = [

    'title'      => "WireMailSendGrid",
    'version'    => 104,
    'summary'    => "Extend WireMail to bypass PHP Mail and send mail via SendGrids Web API",

    'author'     => "The Big Surf",
    'href'       => "http://www.thebigsurf.co.uk",

    'autoload'   => true,
    'singular'   => true,

    'requires'   => [
        "ProcessWire>=3.0",
    ],

];
