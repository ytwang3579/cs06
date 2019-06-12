<?php
  session_start();
  session_unset();//clear all session
  echo "<script> location.href='..'; </script>";//redirect to login page
?>
