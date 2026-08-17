<?php
/**
 * Plugin Name: DentAll Core
 * Description: DentAll 商城跨主题的最小业务能力。
 * Version: 0.2.3
 * Requires at least: 7.0
 * Requires PHP: 8.2
 * Text Domain: dentall-core
 */

defined( 'ABSPATH' ) || exit;

const DENTALL_CORE_PLUGIN_FILE         = __FILE__;
const DENTALL_CORE_ROLE_VERSION        = '5';
const DENTALL_CORE_ROLE_OPTION         = 'dentall_core_role_version';
const DENTALL_CONTENT_ROLE             = 'dentall_content_editor';
const DENTALL_WEBSITE_MANAGER_ROLE     = 'dentall_website_manager';
const DENTALL_WEBSITE_MANAGER_MARKER   = 'dentall_website_manager';

require_once __DIR__ . '/includes/roles.php';
require_once __DIR__ . '/includes/media-policy.php';
require_once __DIR__ . '/includes/product-governance.php';
require_once __DIR__ . '/includes/admin-access.php';
require_once __DIR__ . '/includes/seo-compatibility.php';
