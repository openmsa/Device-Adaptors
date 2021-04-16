<?php
/*
 * Version: $Id$
 * Created: May 13, 2011
 * Available global variables
 *  $sms_csp            pointer to csp context to send response to user
 *  $sms_sd_ctx         pointer to sd_ctx context to retreive usefull field(s)
 *  $sms_sd_info        pointer to sd_info structure
 *  $SMS_RETURN_BUF     string buffer containing the result
 */

// Transfer the configuration file on the router
// First try to use SCP then TFTP
require_once 'smsd/sms_common.php';
require_once load_once('dogtag_pki', 'common.php');
require_once load_once('dogtag_pki', 'apply_errors.php');
require_once load_once('dogtag_pki', 'dogtag_pki_configuration.php');
require_once load_once('dogtag_pki', 'dogtag_pki_connect.php');

require_once "$db_objects";

define('DELAY', 200000);
function dogtag_pki_apply_conf($configuration, $is_ztd)
{
  global $sdid;
  global $sms_sd_ctx;
  global $sms_sd_info;
  global $sendexpect_result;
  global $apply_errors;

  $network = get_network_profile();
  $SD = &$network->SD;

  $ret = save_result_file($configuration, "conf.applied");
  if ($ret !== SMS_OK)
  {
    return $ret;
  }

  $SMS_OUTPUT_BUF = '';
  $ERROR_BUFFER = '';

  $ret = SMS_OK;

  unset($tab);
  $tab[0] = $sms_sd_ctx->getPrompt();
  $tab[1] = "Password:";
  $tab[2] = "#";
  $tab[3] = "$";

  $buffer = $configuration;
  $line = get_one_line($buffer);

  while ($line !== false)
  {
    $line = trim($line);
    $op = "pki -h {$SD->SD_IP_CONFIG} -u '{$SD->SD_LOGIN_ENTRY}' -w '{$SD->SD_PASSWD_ENTRY}' ".$line;
    $sendexpect_result .= sendexpectone(__FILE__ . ':' . __LINE__, $sms_sd_ctx, $op);
    $SMS_OUTPUT_BUF .= $sendexpect_result;
    save_result_file("No error found during the application of the configuration", "conf.error");
    $line = get_one_line($buffer);
  }
  return $ret;
}

?>

