<?php

use ElxrGraphql\ElxrGraphql;

function get_or_create_suggestic_id($name, $email, $user_id)
{
    $gql = new ElxrGraphql();

    $suggestic_id = $gql->get_or_create($name, $email, $user_id);

    return $suggestic_id;
}
