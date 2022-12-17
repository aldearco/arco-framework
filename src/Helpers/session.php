<?php

use Arco\Session\Session;

function session(): Session {
    return app()->session;
}
