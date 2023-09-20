<?php

use Model\Services\DataAccess;

if(isset($_SERVER['HTTP_TYPE']))
{

    switch($_SERVER['HTTP_TYPE'])
    {
        case 'test':
            echo 'The server is alive';
            break;
        /*case 'populate-itemTypes':
            echo json_encode(DataAccess::GetColumn('item_types', 'type', DB));
            break;*/
    }
}
else
{
    echo 'error :)';
}