<?php
/**
 * Modify the response of valid credential.
 *
 * @param array $response The default valid credential response.
 * @param WP_User $user The authenticated user.
 * .
 * @return array The valid credential response.
 */
add_filter(
    'jwt_auth_valid_credential_response',
    function ($response, $user) {

        $roles = [];

        foreach ($user->roles as $key => $value) {
            $roles[] = $value;
        }

        $response['data']['roles'] = $roles;
        $response['data']['suggestic_id'] = get_or_create_suggestic_id($user->user_login, $user->user_email, $user->ID);

        return $response;
    },
    10,
    2
);