<?php

namespace ElxrGraphql;

use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\Mutation;
use GraphQL\Query;
use GraphQL\Variable;
use WP_User;

class ElxrGraphql
{
    protected $client = null;


    public function __construct()
    {
        $this->client = new Client("http://production.suggestic.com/graphql", [
            'Authorization' => 'Token ' . SG_TOKEN
        ]);
    }

    public function get_or_create($name, $email, $user_id)
    {
        $suggestic_id = get_user_meta($user_id, '_suggestic_id', true);

        if (!empty($suggestic_id)) {
            return $suggestic_id;
        }

        return $this->get_suggestic_id($name, $email, $user_id);
    }

    public function get_or_create_user_suggestic($name, $email, $user_id)
    {

        try {
            $mutation = (new Mutation('createUser'))
                ->setArguments(['name' => '$name', 'email' => '$email', 'emailPasswordNotification' => '$emailPasswordNotification'])
                ->setVariables([
                    new Variable('name', 'String', true),
                    new Variable('email', 'String', true),
                    new Variable('emailPasswordNotification', 'Boolean', true),
                ])
                ->setSelectionSet(
                    [
                        'success',
                        'message',
                        (new Query('user'))->setSelectionSet([
                            'databaseId'
                        ])
                    ]
                );
            $user = ['name' => $name, 'email' => $email, 'emailPasswordNotification' => false];
            $results = $this->client->runQuery($mutation, true, $user);
            $data = $results->getData();
            update_user_meta($user_id, '_suggestic_id', $data['createUser']['user']['databaseId']);
            return $data['createUser']['user']['databaseId'];
        } catch (QueryError $exception) {
            wp_send_json($exception->getErrorDetails(), 400);
        }
    }

    public function get_suggestic_id($user_nicename, $user_email, $user_id)
    {
        return $this->get_or_create_user_suggestic($user_nicename, $user_email, $user_id);
    }

    public function get_suggestic_token($user_id)
    {

        $user = get_user_by('ID', $user_id);
        $suggestic_id = get_user_meta($user_id, '_suggestic_id', true);

        if (!empty($suggestic_id)) {
            return $this->get_token($suggestic_id, $user);
        }

        $suggestic_id = $this->get_suggestic_id($user->user_nicename, $user->user_email, $user_id);

        return $this->get_token($suggestic_id, $user);
    }

    public function get_token($suggestic_id, WP_User $user)
    {

        $id_suggestic = $suggestic_id;

        try {
            $query = (new Query('searchProfile'))
                ->setArguments(['email' => '$email'])
                ->setVariables([
                    new Variable('email', 'String', true)
                ])
                ->setSelectionSet([
                    'userId'
                ]);
            $email = ['email' => $user->user_email];
            $results = $this->client->runQuery($query, true, $email);
            $data = $results->getData();
            $id_suggestic = $data["searchProfile"]["userId"];
            return $this->login($id_suggestic);
        } catch (QueryError $exception) {
            $id_suggestic = $this->get_suggestic_id($user->user_nicename, $user->user_email, $user->ID);
            return $this->login(strval($id_suggestic));
        }
    }

    public function login($id_suggestic)
    {
        try {
            $mutation = (new Mutation('login'))
                ->setArguments(['userId' => '$userId'])
                ->setVariables([
                    new Variable('userId', 'String', true)
                ])
                ->setSelectionSet([
                    'accessToken',
                    'refreshToken'
                ]);
            $login = ['userId' => $id_suggestic];
            $results = $this->client->runQuery($mutation, true, $login);
            $data = $results->getData();
            return $data['login'];
        } catch (QueryError $exception) {
            wp_send_json($exception->getErrorDetails(), 400);
        }
    }

    public function createMealPlan($suggestic_id){

    }

    public function changeMealPlan($suggestic_id, $mealplan_id, $user_id, $mealplan){

    }

    public function updateMealPlan($suggestic_id, $mealplan_id, $user_id, $status){

    }

    public function retrieveNutrition($suggestic_id, $mealplan_id, $user_id, $status){

    }

}
