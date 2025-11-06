<?php

echo  $status =  $_POST["newStatus"];



header("Location: https://adgtest.fmdqgroup.com/deploy_fmrr/payment/success?status=$status");
//header("Location: http://localhost/fmrr_dev/payment/success?status=$status");



