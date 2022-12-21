<?php

namespace Arco\Database\Migrations;

interface Migration {
    /**
     * Execute migration
     */
    public function up();

    /**
     * Rollback migration
     */
    public function down();
}
