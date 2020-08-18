<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('api_model');
		$this->load->library('form_validation');

	}

	function index()
	{
		$data = $this->api_model->fetch_all();
		//print_r($data->result_array());die;
		//echo json_encode($data->result_array());
		echo '{"code":200,"success":"true","message":"Records Fetch Successfully","data":'.json_encode($data->result_array()).'}';
		//echo $data;
	}

	function insert()
	{
		//print_r($_POST);
				//print_r($_FILES);die;


		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		//$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|is_unique[tbl_sample.email]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_sample.email]');
		//$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_message('is_unique', 'The Email is already taken');
		$this->form_validation->set_rules('phone', 'Phone', 'required');

		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[20]');
		//$this->form_validation->set_rules('password', 'Password', 'required');
		//$this->form_validation->set_rules('image', 'Image', 'required');
		if($this->form_validation->run())
		{
			$data[] = array(
				'first_name'	=>	$this->input->post('first_name'),
				'last_name'		=>	$this->input->post('last_name'),
				'email'		=>	$this->input->post('email'),
				'phone'		=>	$this->input->post('phone'),
				'password'		=>	$this->input->post('password'),
				'image'		=>	$this->input->post('image')
			);

			$this->api_model->insert_api($data);

			/*$array = array(
				'success'		=>	true
			);*/
		}
		else
		{
			$array = array(
				'error'					=>	true,
				'first_name_error'		=>	form_error('first_name'),
				'last_name_error'		=>	form_error('last_name'),
				'email_error'		=>	form_error('email'),
				'phone_error'		=>	form_error('phone'),
				'password_error'		=>	form_error('password'),
				'image_error'		=>	form_error('image')
			);
		}
		if(isset($data))
		{
			$result = $data;
			//echo '{"data":"'.json_encode($result).'"}';
			//print_r(json_encode($result));die;
			$data = '{"code":200,"success":"true","message":"Records Created Successfully","data":'.json_encode($result).'}';
		echo $data;
		}
		else
		{
			$data = '{"code":200,"success":"false","data":'.json_encode($array).'}';
			echo $data;
		}
		//echo '{"data":"'.json_encode($result).'"}';
		//print_r(json_encode($result));die;
		
		//echo json_encode($array);
	}
	
	function fetch_single()
	{
		if($this->input->post('id'))
		{
			$data = $this->api_model->fetch_single_user($this->input->post('id'));

			foreach($data as $row)
			{
				$output['first_name'] = $row['first_name'];
				$output['last_name'] = $row['last_name'];
				$output['email'] = $row['email'];
				$output['phone'] = $row['phone'];
				$output['password'] = $row['password'];
				$output['image'] = $row['image'];
			}
			echo json_encode($output);
		}
	}

	function update()
	{

		//print_r($_POST['image']);die;
		//$image = explode('.',$_POST['image']);
		//print_r($image);die;
		$this->form_validation->set_rules('first_name', 'First Name', 'required');

		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		//$this->form_validation->set_rules('image', 'Profile', 'required');
		//$this->form_validation->set_rules('email', 'Email', 'required');
		$datavalue = $this->api_model->fetch_single_user($this->input->post('id'));
		if($datavalue[0]['email'] == $this->input->post('email'))
		{
			$this->form_validation->set_rules('email', 'Email', 'required');
		}
		else
		{
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[tbl_sample.email]');
		}
		
		//$this->form_validation->set_message('is_unique', 'The Email is already taken');
		$this->form_validation->set_rules('phone', 'Phone', 'required|min_length[12]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[20]');
		$this->form_validation->set_message('is_unique', 'The Email is already taken');
		//$this->form_validation->set_rules('phone', 'Phone', 'callback_numeric_wcomma');


		//$this->form_validation->set_rules('password', 'Password', 'required');
		//$this->form_validation->set_rules('image', 'Image', 'required');
		if($this->form_validation->run())
		{	
			$data[] = array(
				'first_name'		=>	$this->input->post('first_name'),
				'last_name'			=>	$this->input->post('last_name'),
				'email'			=>	$this->input->post('email'),
				'phone'			=>	$this->input->post('phone'),
				'password'			=>	$this->input->post('password'),
				'image'			=>	$this->input->post('image')
			);

			$this->api_model->update_api($this->input->post('id'), $data);

			/*$array = array(
				'success'		=>	true
			);*/
		}
		else
		{
			$array = array(
				'error'				=>	true,
				'first_name_error'	=>	form_error('first_name'),
				'last_name_error'	=>	form_error('last_name'),
				'email_error'		=>	form_error('email'),
				'phone_error'		=>	form_error('phone'),
				'password_error'		=>	form_error('password'),
				'image_error'		=>	form_error('image')
			);
		}
		//$result['data'][] = json_encode($data);
		if(isset($data))
		{
			$result = $data;
			//echo '{"data":"'.json_encode($result).'"}';
			//print_r(json_encode($result));die;
			$data = '{"code":200,"success":"true","message":"Records Updated Successfully","data":'.json_encode($result).'}';
			echo $data;
		}
		else
		{
			$data = '{"code":200,"success":"false","data":'.json_encode($array).'}';
			echo $data;
		}
	}

	function delete()
	{
		if($this->input->post('id'))
		{
			if($this->api_model->delete_single_user($this->input->post('id')))
			{
				$array = array(

					'success'	=>	true
				);
			}
			else
			{
				$array = array(
					'error'		=>	true
				);
			}
			echo '{"code":200,"success":"true","message":"Record Deleted Successfully"}';
		}
	}
	function numeric_wcomma ($str)
{
    return preg_match('/^[0-9,]+$/', $str);
}

}


?>