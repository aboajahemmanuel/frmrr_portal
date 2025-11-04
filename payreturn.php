<?php

echo  $status =  $_POST["newStatus"];



header("Location: http://localhost/fmrr_dev/payment/success?status=$status");


