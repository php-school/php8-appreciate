<?php

$fp = fopen('users.csv', 'w+');
fputcsv($fp, ['Wrong column', 'Last Name', 'Age', 'House num', 'Addr 1', 'Addr 2']);
fputcsv($fp, [$user->firstName, $user->lastName, $user?->age, $user?->address?->number, $user?->address?->addressLine1, $user?->address?->addressLine2]);
fclose($fp);
