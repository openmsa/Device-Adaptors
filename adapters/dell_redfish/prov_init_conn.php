<?php

// -------------------------------------------------------------------------------------
// INITIAL CONNECTION
// -------------------------------------------------------------------------------------
function prov_init_conn($sms_csp, $sdid, $sms_sd_info, &$err)
{
  global $ipaddr;
  global $login;
  global $passwd;

  dell_redfish_connect($ipaddr, $login, $passwd);
  dell_redfish_disconnect();

  return SMS_OK;
}

?>