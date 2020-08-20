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
		//print_r($this->input->post());die;
		$p = file_get_contents('php://input');
		if(empty($p))
		{
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
		}
		else
		{
			//$data = array(json_encode($p));
			$data[] = json_decode($p);
			//$data = json_encode($p, JSON_UNESCAPED_SLASHES);
			//$str = preg_replace('/(\v|\s)+/', ' ', $data);
			//print_r($data[]);die;
			$validation = get_object_vars($data[0]);
			//print_r($validation['first_name']);die;
			$check = $this->api_model->emailchecker($validation['email']);
			$validemail = filter_var($validation['email'], FILTER_VALIDATE_EMAIL);
			$password = strlen($validation['password']);
			if($validation['first_name'] == "")
			{
				$data = '{"code":200,"success":"false","message":"First name is empty"}';
				echo $data;die;
			}
			else if($validation['last_name'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Last name is empty"}';
				echo $data;die;
			}
			if($validation['email'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Email is empty"}';
				echo $data;die;
			}
			if(!empty($check))
			{
				$data = '{"code":200,"success":"false","message":"Email already exist"}';
				echo $data;die;
			}
			if(empty($validemail))
			{
				$data = '{"code":200,"success":"false","message":"Email is not valid"}';
				echo $data;die;
			}
			if($validation['password'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Password is empty"}';
				echo $data;die;
			}
			if($password < 8)
			{
				$data = '{"code":200,"success":"false","message":"Password contained minimum 8 digits"}';
				echo $data;die;
			}
			{
				$data = '{"code":200,"success":"false","message":"Password is empty"}';
				echo $data;die;
			}
			if($validation['phone'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Phone is empty"}';
				echo $data;die;
			}
			if($validation['image'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Image is empty"}';
				echo $data;die;
			}
			$this->api_model->insert_api($data);
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
		}
		//echo '{"data":"'.json_encode($result).'"}';
		//print_r(json_encode($result));die;
		
		//echo json_encode($array);
	}
	
	function single($id)
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
		else
		{
			$data = $this->api_model->fetch_single_user($id);

			foreach($data as $row)
			{
				$output['first_name'] = $row['first_name'];
				$output['last_name'] = $row['last_name'];
				$output['email'] = $row['email'];
				$output['phone'] = $row['phone'];
				$output['password'] = $row['password'];
				$output['image'] = $row['image'];
			}
			$data = '{"code":200,"success":"true","data":'.json_encode($output).'}';
				echo $data;
		}
	}

	function update($id)
	{
		 //print_r($id);die;
		//print_r($_POST['image']);die;
		//$image = explode('.',$_POST['image']);
		//print_r($image);die;
		$p = file_get_contents('php://input');
		//print_r($p);die;
		if(empty($p))
		{
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
			else
			{
				//$data = array(json_encode($p));
			$data = json_decode($p);
			//$data = json_encode($p, JSON_UNESCAPED_SLASHES);
			//$str = preg_replace('/(\v|\s)+/', ' ', $data);
			//print_r($data[]);die;
			$array = get_object_vars($data);
			//print_r($array);die;
			$data = array(
						'first_name'		=>	$array['first_name'],
						'last_name'			=>	$array['last_name'],
						'email'			=>	$array['email'],
						'phone'			=>	$array['phone'],
						'password'			=>	$array['password']
						//'image'			=>	$array['first_name']
					);
			$data[] = $data;
			//print_r($data[0]);die;
			$this->api_model->update_api($id,$data);
			if(isset($data))
			{
				$result = $data;
				//print_r($result);die;
				$validation = $result;
			//print_r($validation['first_name']);die;
			$check = $this->api_model->emailchecker($validation['email']);
			$validemail = filter_var($validation['email'], FILTER_VALIDATE_EMAIL);
			$password = strlen($validation['password']);
			if($validation['first_name'] == "")
			{
				$data = '{"code":200,"success":"false","message":"First name is empty"}';
				echo $data;die;
			}
			else if($validation['last_name'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Last name is empty"}';
				echo $data;die;
			}
			if($validation['email'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Email is empty"}';
				echo $data;die;
			}
			if(!empty($check))
			{
				$data = '{"code":200,"success":"false","message":"Email already exist"}';
				echo $data;die;
			}
			if(empty($validemail))
			{
				$data = '{"code":200,"success":"false","message":"Email is not valid"}';
				echo $data;die;
			}
			if($validation['password'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Password is empty"}';
				echo $data;die;
			}
			if($password < 8)
			{
				$data = '{"code":200,"success":"false","message":"Password contained minimum 8 digits"}';
				echo $data;die;
			}
			{
				$data = '{"code":200,"success":"false","message":"Password is empty"}';
				echo $data;die;
			}
			if($validation['phone'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Phone is empty"}';
				echo $data;die;
			}
			if($validation['image'] == "")
			{
				$data = '{"code":200,"success":"false","message":"Image is empty"}';
				echo $data;die;
			}
				//print_r($result);die;
				//echo '{"data":"'.json_encode($result).'"}';
				//print_r(json_encode($result));die;
				$data = '{"code":200,"success":"true","message":"Records updated Successfully","data":'.json_encode($result[0]).'}';
			echo $data;
			}
			else
			{
				$data = '{"code":200,"success":"false","data":'.json_encode($array).'}';
				echo $data;
			}
			}
	}

	function delete($id)
	{
		//$p = file_get_contents('php://input');
		if(empty($id))
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
		else
		{
			//print_r($id);die;
			if($this->api_model->delete_single_user($id))
			{
				echo '{"code":200,"success":"true","message":"Record Deleted Successfully"}';
			}
		}
	}
	function numeric_wcomma ($str)
{
    return preg_match('/^[0-9,]+$/', $str);
}
function upload()
{
	// print_r($this->response($_POST));die;
	$this->load->helper(array('form', 'url'));
	//print_r($_POST['image']);die;
	$config = array(
	'upload_path' => "./images/",
	'allowed_types' => "jpg|png|jpeg",
	'overwrite' => TRUE,
	'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
	'max_height' => "6000",
	'max_width' => "6000"
	);
	$this->load->library('upload', $config);
	if($this->upload->do_upload('image'))
	{
	$data = array('upload_data' => $this->upload->data());
	$data = '{"code":200,"success":"true","message":"Files uploaded successfully","data":'.json_encode($data).'}';
	echo $data;
	//$this->load->view('upload_success',$data);
	}
	else
	{
	$error = array('error' => $this->upload->display_errors());
	$data = '{"code":200,"success":"true","message":"Please select this type of images JPG,JPEG,PNG"}';
	echo $data;
	//$this->load->view('custom_view', $error);
	}
}

}


?>