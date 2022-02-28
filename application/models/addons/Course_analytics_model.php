<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Course_analytics_model extends CI_Model
{

	function __construct() {
		parent::__construct();
	}

	function get_course_progress_data($course_id = ""){
		$analytics_values = array(0,0,0,0,0,0,0,0,0,0);

		$this->db->select('course_id');
	    $this->db->select('user_id');
	    $this->db->select('email');
	    $this->db->select('watch_history');
	    $this->db->where('course_id', $course_id);
	    $this->db->from('enrol');
	    $this->db->join('users', 'users.id = enrol.user_id');
	    $query = $this->db->get();	    
	    $total_enrol_histories = $query->result_array();
	    $lessons_for_that_course = $this->crud_model->get_lessons('course', $course_id);
	    $total_number_of_lessons = $lessons_for_that_course->num_rows();

	    foreach ($total_enrol_histories as $key => $total_enrol_history) {
	    	$completed_lessons_ids = array();
			$lesson_completed = 0;

		    $watch_history_array = json_decode($total_enrol_history['watch_history'], true);

		    for ($i = 0; $i < count($watch_history_array); $i++) {
		    	$watch_history_for_each_lesson = $watch_history_array[$i];
	            if ($watch_history_for_each_lesson['progress'] == 1) {
	                array_push($completed_lessons_ids, $watch_history_for_each_lesson['lesson_id']);
	            }
	        }

	        $lesson_completed = count($completed_lessons_ids);
	        

	        if ($lesson_completed > 0 && $total_number_of_lessons > 0) {
	            // calculate the percantage of progress
	            $course_progress = ($lesson_completed / $total_number_of_lessons) * 100;

	            //student number for persentage in array
	            if($course_progress <= 90 && $course_progress > 10){
	            	$first_number_of_progress = str_split($course_progress, 1);
	            	$index_of_analytics_values = $first_number_of_progress[0];
	            	$analytics_values[$index_of_analytics_values] = $analytics_values[$index_of_analytics_values]+1;
	            }elseif($course_progress <= 10){
	            	$analytics_values[0] = $analytics_values[0]+1;
	            }else{
	            	$analytics_values[9] = $analytics_values[9]+1;
	            }
	            //return $course_progress;
	        }else{
	        	$analytics_values[0] = $analytics_values[0]+1;
	        }
	    }

	    return json_encode($analytics_values);
	}

	function get_course_enrolment_data($course_id = "", $date = ""){
		$response = array();
		$enrolment_analytics_values = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

		$filter_date = $this->input->post('filter_date');

		if(isset($filter_date) && $filter_date != ""){
			$timestamp = strtotime('1 '.$filter_date);
		}else{
			$timestamp = strtotime(date('d M Y h:i:s'));
		}
		$year = date('Y', $timestamp);
		$month = date('m', $timestamp);
		$month_name = date('M', $timestamp);
		$total_days_in_this_month = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$start_date = strtotime('01 '.$month_name.' '.$year);
		$end_date = strtotime($total_days_in_this_month.' '.$month_name.' '.$year.' 23:59:59');
		
	    $this->db->where('date_added >=', $start_date);
	    $this->db->where('date_added <=', $end_date);
	    $this->db->where('course_id', $course_id);
	    $query = $this->db->get('enrol');

	    foreach($query->result_array() as $key => $row){
	    	$enrolled_day = date('d', $row['date_added']);
	    	$enrolment_analytics_values[$enrolled_day-1] = $enrolment_analytics_values[$enrolled_day-1] + 1;
	    }
	    $response['enrolment_analytics_values'] = json_encode($enrolment_analytics_values);
	    $response['total_days_in_this_month'] = $total_days_in_this_month;
	    $response['selected_month'] = $month;
	    $response['selected_year'] = $year;
	    $response['total_enrol_student_number'] = $query->num_rows();

	    return $response;

	}



}