<?php

echo  $status =  $_POST["newStatus"];



header("Location: http://101-php-01.fmdqgroup.com/deploy_fmrr/payment/success?status=$status");
//header("Location: http://localhost/fmrr_dev/payment/success?status=$status");



