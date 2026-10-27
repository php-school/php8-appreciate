<?php

$fp = fopen('users.csv', 'w+');
fputcsv($fp, ['First Name', 'Last Name', 'Age', 'House num', 'Addr 1', 'Addr 2'], escape: '');
fputcsv(
    $fp,
    [
        $user->firstName,
        $user->lastName,
        $user?->age,
        $user?->address?->number,
        $user?->address?->addressLine1,
        $user?->address?->addressLine2
    ],
    escape: ''
);
fclose($fp);
