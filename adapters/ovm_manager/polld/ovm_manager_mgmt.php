<?php
/*
 * Date : Sep 24, 2007
 * Available global variables
 *  $sms_sd_info       sd_info structure
 *  $sdid
 *  $sms_module        module name (for patterns)
 *  $sd_poll_elt       pointer on sd_poll_t structure
 *  $sd_poll_peer      pointer on sd_poll_t structure of the peer (slave of master)
 */

// Script description
require_once 'smsd/sms_common.php';
require_once load_once('ovm_manager', 'adaptor.php');
require_once "$db_objects";

function exit_error($line, $error)
{
  sms_log_error("$line: $error\n");
  sd_disconnect();
  exit($error);
}


try
{
  // Connection
  sd_connect();

  $asset['firmware'] = sendexpectone(__FILE__ . ':' . __LINE__, $sms_sd_ctx, "showversion");

  $line = get_one_line($buffer);
  while ($line !== false)
  {
    // regular asset fields
    foreach ($show_ver_asset_patterns as $name => $pattern)
    {
    		if (preg_match($pattern, $line, $matches) > 0)
    		{
    		  $asset[$name] = trim($matches[$name]);
    		}
    }

    // remove already used patterns
    if (isset($asset))
    {
    		foreach ($asset as $name => $value)
    		{
    		  unset($show_ver_asset_patterns[$name]);
    		}
    }

    $line = get_one_line($buffer);
  }

  $ret = sms_polld_set_asset_in_sd($sd_poll_elt, $asset);
  if ($ret !== 0)
  {
    exit_error(__FILE__ . ':' . __LINE__, ": sms_polld_set_asset_in_sd($sms_sd_ctx, $asset) Failed\n");
  }


  sd_disconnect();
}
catch (Exception | Error $e)
{
  sd_disconnect();
  exit($e->getCode());
}

return 0;

?>
