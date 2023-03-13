<?php 

namespace App\Policies;

use Arco\Http\Response;
use Arco\Auth\Access\NotAllowedResponse;

class PolicyName {
    use NotAllowedResponse;

    public function __construct() {
        // $this->setDenyResponse('method', Response::text("Some text"));
    }

    // Put your conditional methods here
}