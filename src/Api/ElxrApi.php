<?php

namespace App\ElxrApi;

use ElxrGraphql\ElxrGraphql;
use Error;
use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use ElxrSchema\ElxrSchema;

class ElxrApi extends WP_REST_Controller{
    
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'elxr/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'suggestics';

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ), 10 );
    }


    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/register/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'create_item' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'email' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Email User', 'elxr' )
                    ),
                    'name' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Name', 'elxr' )
                    )
                )
            )
        ) );
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/login/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'login' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'user_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Id User', 'elxr' )
                    )
                ) ,
            )
        ) );
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/suggestic-id/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'get_id' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'user_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Id User', 'elxr' )
                    )
                )
            )
        ) );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/change-suggestic-id/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'change_id' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'suggestic_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Suggestic id', 'elxr' )
                    )
                )
            )
        ) );

        // JAKE'S ADDITIONS

        // Create Meal Plan
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/create-mealplan/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'create_mealplan' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'suggestic_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Suggestic id', 'elxr' )
                    )
                ) ,
            )
        ) );

        // change Meal Plan
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/change-mealplan/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'change_mealplan' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'suggestic_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Suggestic id', 'elxr' )
                    ),
                    'mealplan_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Meal Plan id', 'elxr' )
                    ),
                    'user_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Owner', 'elxr' )
                    ),
                    'meanplan' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'New Meal Plan', 'elxr' )
                    )
                )
            )
        ) );

        // Skip or consume Meal
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/update-mealplan/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'update_mealplan' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'suggestic_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Suggestic id', 'elxr' )
                    ),
                    'mealplan_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Meal Plan id', 'elxr' )
                    ),
                    'user_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Owner', 'elxr' )
                    ),
                    'status' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Consumed or Skipped', 'elxr' )
                    ),
                )
            )
        ) );

        // Show Nutrition By Date Range
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/retrieve-nutrition/', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'retrieve_nutrition' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'                =>  array(
                    'suggestic_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Suggestic id', 'elxr' )
                    ),
                    'user_id' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Owner', 'elxr' )
                    ),
                    'timezone' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'Timezone', 'elxr' )
                    ),
                    'start_date' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'start date', 'elxr' )
                    ),
                    'end_date' => array(
                        'required'    => true,
                        'type'        => 'string',
                        'description' => __( 'end date', 'elxr' )
                    )
                )
            )
        ) );


    }

    public function create_item($request)
    {
        $params = $request->get_params();

        $user_id = get_current_user_id();

        $graphql = new ElxrGraphql();

        try {
            $suggestic_id = $graphql->get_or_create_user_suggestic($params['name'], $params['email'], $user_id);

            return new WP_REST_Response(['suggestic_id' => $suggestic_id], 200 );
        } catch (Error $e) {
            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
    }

    public function login($_)
    {
        try {

            $user_id = get_current_user_id();

            $graphql = new ElxrGraphql();

            $data = $graphql->get_suggestic_token($user_id);

            return new WP_REST_Response($data);
        } catch (Error $e) {
            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
        
    }

    public function get_id($request)
    {

        $user_id = get_current_user_id();

        $suggestic_id = get_user_meta($user_id, '_suggestic_id', true);

        if (empty($suggestic_id)) {
            return new WP_REST_Response([
                "message" => "user no exists"
            ],403);
        }

        return new WP_REST_Response(['suggestic_id' => $suggestic_id]);
    }

    public function create_item_permissions_check( $request )
    {
        return true;
    }

    public function change_id( $request )
    {
        $suggestic_id = $request->get_param('suggestic_id');
        $user_id = get_current_user_id();
        update_user_meta($user_id, '_suggestic_id', $suggestic_id);
        return new WP_REST_Response(['message'=>'successful change']);
    }

    public function create_mealplan( $request ){

        $suggestic_id = $request->get_param('suggestic_id');
        try {

            $user_id = get_current_user_id();

            $graphql = new ElxrGraphql();

            $data = $graphql->createMealPlan($suggestic_id);

            return new WP_REST_Response($data);

        } catch (Error $e) {

            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
    }

    public function change_mealplan( $request ){

        $suggestic_id = $request->get_param('suggestic_id');
        $mealplan_id = $request->get_param('mealplan_id');
        $user_id = $request->get_param('user_id');
        $mealplan = $request->get_param('mealplan');

        try {

            $user_id = get_current_user_id();

            $graphql = new ElxrGraphql();

            $data = $graphql->changeMealPlan($suggestic_id, $mealplan_id, $user_id, $mealplan);

            return new WP_REST_Response($data);

        } catch (Error $e) {

            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
    }

    public function update_mealplan( $request ){

        $suggestic_id = $request->get_param('suggestic_id');

        try {

            $user_id = get_current_user_id();

            $graphql = new ElxrGraphql();

            $data = $graphql->updateMealPlan($suggestic_id, $mealplan_id, $user_id, $mealplan);

            return new WP_REST_Response($data);

        } catch (Error $e) {

            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
    }

    public function retrieve_nutrition( $request ){

        $suggestic_id = $request->get_param('suggestic_id');

        try {

            $user_id = get_current_user_id();

            $graphql = new ElxrGraphql();

            $data = $graphql->retrieveNutrition($suggestic_id, $user_id, $timezone, $start_date, $end_date);

            return new WP_REST_Response($data);

        } catch (Error $e) {

            return new WP_REST_Response( ['error' => $e->getMessage()], 400 );
        }
    }


}