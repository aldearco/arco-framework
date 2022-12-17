<?php

namespace Arco\Database\Migrations;

interface Migration {
    public function up();
    public function down();
}
