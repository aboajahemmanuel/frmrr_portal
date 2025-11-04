<?php

echo  $status =  $_POST["newStatus"];

die();

header("Location: http://localhost/fmrr_v2/payment/success?status=$status");


