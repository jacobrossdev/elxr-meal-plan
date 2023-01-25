<?php

namespace ElxrSchema;

class ElxrSchema {

    public function saveMealPlan(){

        // Save week meal plan to calendar

        /*INSERT INTO `meal_plans` (
			`name` = <data>
			`user_id` = <data>
			`suggestic_id` = <data>
			`meal_day` = <data>
			`consumed` = 0
			`skipped` = 0
    	)*/

        /*INSERT INTO `nutritional_data` (
	        `mealplan_id` = <previously inserted id>, 
	        `nutritional_data` = <suggestic_nutritional_data>
    	)*/
    }

    public function consumeDailyMeal(){

        /*Consume Meal
        -----------------------
        UPDATE `meal_plans` SET `consumed` = 1, `skipped` = 0 WHERE `suggestic_id` = <suggestic_mealplan_id>*/
    }

    public function skipDailyMeal(){

        // mark the daily meal as skipped
        /*
        Skip Meal
        -----------------------
        UPDATE `meal_plans` SET `skipped` = 1, `consumed` = 0 WHERE `suggestic_id` = <suggestic_mealplan_id>*/
    }

    public function showWeekNutrition(){

        // retrieve the nutrition for the week.
        return = getNutritionByDateRange($start_date, $end_date);
    }

    public function showDayNutrition(){

        // retrieve the nutrition for the Day
        return = getNutritionByDateRange($start_date, $end_date);
    }

    public function showMonthNutrition(){

        // retrieve the nutrition for the month
        return = getNutritionByDateRange($start_date, $end_date);
    }

    public function showYearNutrition(){

        // retrieve the nutrition for the month
        return = getNutritionByDateRange($start_date, $end_date);
    }

    protected function getNutritionByDateRange($start_date, $end_date){

        // Nutritional Data By Date Range
        
        /*SELECT `nutritional_data` FROM `meal_plan_nutrition` as mpn 
        JOIN `meal_plans` as mp 
        WHERE `mp`.`id` = `mpn`.`mealplan_id`
        AND `consumed` = 1
        AND `mp`.`user_id` = <user_id>
        AND `meal_day` BETWEEN <start_date> AND <end_date>*/
    }

    protected function compileNutritionalData($nutrition){

        // assuming nutritional data comes this way
        /*$nutrition = [
            0 => {serialized_array},
            1 => {serialized_array},
            2 => {serialized_array}
        ];*/

        // Lets get all the nutrients as an array to add up the totals
        $nutrients = array_keys(unserialize($nutrition[0]));

        // This is the consumed nutrients
        $consumed_nutrients = array();

        // Loop thru the returned nutrients
        foreach( $nutrition as $data ){
            
            // The array value is serialized
            $data = unserialize($data);

            // lets check all the possible nutrients
            foreach( $nutrients as $nutrient ){

                if( empty($consumed_nutrients[$nutrient]) ){

                    $consumed_nutrients[$nutrient] = intval($data[$nutrient]);

                } else {

                    $consumed_nutrients[$nutrient] += intval($data[$nutrient]);
                }
            }
        }

        return $nutrition;
    }
}