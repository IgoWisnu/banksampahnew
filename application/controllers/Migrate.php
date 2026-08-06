<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migrate controller — trigger CI migrations from the browser.
 *
 * Usage:
 *   Run ALL pending migrations up:   /migrate
 *   Roll back the latest migration:  /migrate/down
 *
 * ⚠️ DISABLE or DELETE this controller after you are done migrating
 * to prevent unintentional schema changes in production.
 */
class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Only allow from localhost or CLI for safety
        if (!in_array($this->input->ip_address(), ['127.0.0.1', '::1']) && !$this->input->is_cli_request()) {
            show_error('Access denied.', 403);
        }
    }

    /**
     * Run all pending migrations up to the latest version
     */
    public function index()
    {
        $this->load->library('migration');

        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            $ver_row = $this->db->get('migrations')->row();
            $current_ver = $ver_row ? $ver_row->version : '0';
            echo '<pre>';
            echo "✅ Migration complete. Current version: " . $current_ver;
            echo '</pre>';
        }
    }

    /**
     * Roll back the last migration (down)
     */
    public function down()
    {
        $this->load->library('migration');
        $ver_row = $this->db->get('migrations')->row();
        $current = $ver_row ? (int)$ver_row->version : 0;

        if ($current == 0) {
            echo '<pre>⚠️ Already at version 0. Nothing to roll back.</pre>';
            return;
        }

        // Step down by 1
        $target = $current - 1;
        if ($this->migration->version($target) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo '<pre>';
            echo "✅ Rolled back to version: " . $target;
            echo '</pre>';
        }
    }
}
