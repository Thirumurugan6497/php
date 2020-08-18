<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_api extends CI_Controller {

	function index()
	{
		$this->load->view('api_view');
	}

	function action()
	{
		//echo "dsf";die;
		if($this->input->post('data_action'))
		{
			$data_action = $this->input->post('data_action');

			if($data_action == "Delete")
			{
				$api_url = "http://localhost/codeigniter/api/delete";

				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;




			}

			if($data_action == "Edit")
			{
				//print_r($_POST);die;
				$api_url = "http://localhost/codeigniter/api/update";
				$config = array(
					'upload_path' => "./images/",
					'allowed_types' => "jpg|png|jpeg|PNG|JPEG|JPG",
					'overwrite' => TRUE,
					'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'max_height' => "6000",
					'max_width' => "6000"
					);
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image'))
					{
					$data = array('upload_data' => $this->upload->data());
					//$this->load->view('upload_success',$data);
					}
					else
					{
					$error = array('error' => $this->upload->display_errors());
					//print_r($error);die;
					//$this->load->view('custom_view', $error);
					}
					//print_r($_POST);die;
					//print_r($data);die;
					if(isset($data))
					{
						$filename = $data['upload_data']['file_name'];
					}
					else
					{
						$filename =  $this->input->post('hidden');
					}

				$form_data = array(
					'first_name'		=>	$this->input->post('first_name'),
					'last_name'			=>	$this->input->post('last_name'),
					'email'			=>	$this->input->post('email'),
					'phone'			=>	$this->input->post('phone'),
					'password'			=>	$this->input->post('password'),
					'image'			=>	$filename,
					'id'				=>	$this->input->post('user_id')
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;

				





			}

			if($data_action == "fetch_single")
			{
				$api_url = "http://localhost/codeigniter/api/fetch_single";

				$form_data = array(
					'id'		=>	$this->input->post('user_id')
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;






			}

			if($data_action == "Insert")
			{
				$api_url = "http://localhost/codeigniter/api/insert";
				
				//print_r($_POST);
				//print_r($_FILES);die;
				$config = array(
					'upload_path' => "./images/",
					'allowed_types' => "gif|jpg|png|jpeg|pdf",
					'overwrite' => TRUE,
					'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
					'max_height' => "6000",
					'max_width' => "6000"
					);
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image'))
					{
					$data = array('upload_data' => $this->upload->data());
					//$this->load->view('upload_success',$data);
					}
					else
					{
					$error = array('error' => $this->upload->display_errors());
					//print_r($error);die;
					//$this->load->view('custom_view', $error);
					}
					//print_r($data);die;
					$filename = $data['upload_data']['file_name'];
					//print_r($filename);die;
				$form_data = array(
					'first_name'		=>	$this->input->post('first_name'),
					'last_name'			=>	$this->input->post('last_name'),
					'email'			=>	$this->input->post('email'),
					'phone'			=>	$this->input->post('phone'),
					'password'			=>	$this->input->post('password'),
					'image'			=>	$filename
				);

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_POST, true);

				curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				echo $response;


			}





			if($data_action == "fetch_all")
			{
				$api_url = "http://localhost/codeigniter/api";

				$client = curl_init($api_url);

				curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

				$response = curl_exec($client);

				curl_close($client);

				$result = json_decode($response);
				$result = $result->data;
				$output = '';
				//print_r($result);die;
				if(count($result) > 0)
				{
					foreach($result as $row)
					{
						$output .= '
						<tr>
							<td>'.$row->first_name.'</td>
							<td>'.$row->last_name.'</td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone.'</td>
							<td>'.$row->password.'</td>
							<td><img style="width:100%;" src="http://localhost/codeigniter/images/'.$row->image.'"></td>
							<td><butto type="button" name="edit" class="btn btn-warning btn-xs edit" id="'.$row->id.'">Edit</button></td>
							<td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row->id.'">Delete</button></td>
						</tr>

						';
					}
				}
				else
				{
					$output .= '
					<tr>
						<td colspan="4" align="center">No Data Found</td>
					</tr>
					';
				}

				echo $output;
			}
		}
	}
	
}

?>