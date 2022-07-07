<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
    $data['title']= "HashRoot | Careers";
    $data['style']= 1;
		$this->load->view('template/header',$data);
		$this->load->view('careers');
		$data['script_val']=1;
		$this->load->view('template/footer',$data);
	}

// Superheroes form
	public function careerform()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		//$this->form_validation->set_error_delimiters(' < div id = "error" > ', '</div > ');
		$this->form_validation->set_rules('name','Name', 'required');
		$this->form_validation->set_rules('email','Email Address','required|valid_email');
		$this->form_validation->set_rules('position', 'Position', 'required');
		$this->form_validation->set_rules('comment', 'Bio in brief', 'required');
		$this->form_validation->set_rules('expected', 'Expected Salary', 'numeric');
		$this->form_validation->set_rules('current', 'Current Salary', 'numeric');

  	//captcha validation
		$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
		$this->form_validation->set_message('validate_captcha', 'Please check the the captcha form');
		if($this->form_validation->run() == FALSE)
		{
			//echo validation_errors();
			$er = array();
			$er['stat-msg'] = validation_errors();
			$er['stat'] = 0;
		}
		else
		{
			// file upload

			$config['upload_path']          = './assets/cvs';
			$config['allowed_types']        = 'pdf|doc|docx|txt|dot';
			$config['max_size']             = 5024;
			$config['file_name']            = $this->input->post('name').'_'.Date('Y-M-D');
			// $config['max_width']         = 1024;
			// $config['max_height']        = 768;
			$this->load->library('upload', $config);

			// echo('hii');
			// // exit();

			if ( ! $this->upload->do_upload('userfile2'))
			{
							$er = array();
							$er['stat'] = 0;
							$er['stat-msg']=$this->upload->display_errors();
							//$this->load->view('Category/addcat', $error);
							echo(json_encode($er));
							exit();
			}
			else
			{
							$data=$this->upload->data();
							// echo('hii');
							// print_r($data);
              // echo(json_encode($data));
			}
			// Close file upload

			// mail
			$er      = array();
			$name    = $this->input->post('name');
			$email   = $this->input->post('email');
			$message = $this->input->post('comment');
			$position= $this->input->post('position_changed');
			$expectedsal = $this->input->post('expected');
			$currentsal = $this->input->post('current');
			$subject = "JOB APPLICATION OF HIGHLY TALENTED PEOPLE";
			// $this->load->library('email');
			$config = array(
			 'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
			 'smtp_host' => 'zimbra.hashroot.com',
			 'smtp_port' => 465,
			 'smtp_user' => 'site@hashroot.com',
			 'smtp_pass' => 'GYTTf78!e467!@#',
			 'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
			 'mailtype' => 'html', //plaintext 'text' mails or 'html'
			 'smtp_timeout' => '4', //in seconds
			 'charset' => 'iso-8859-1',
			 'wordwrap' => TRUE
	 );
			$this->load->library('email',$config);

			$this->email->from($email, 'Website Enquiry');

			$this->email->to('careers@hashroot.com');
			//$this->email->cc('another@another - example.com');
			//$this->email->bcc('them@their - example.com');
  			$this->email->subject($subject);

			$this->email->message("<b>Name :</b> ".$name."<br/><br/><b>  Email :</b> ".$email."<br/><br/><b> Message :</b> ".$message."<br/><br/><b> Position : </b>".$position."<br/><br/><b> Expected Salary : </b>".$expectedsal."<br/><br/><b> Current Salary : </b>".$currentsal);

			// $this->email->message("<b>Name :</b> ".$name."<br/><br/><b>  Email :</b> ".$email."<br/>br/><b> Message :<b> ".$message);

			$this->email->attach('./assets/cvs/'.$data['file_name']);
  		if($this->email->send())
			{
				$er['stat'] = 1;
				$er['stat-msg'] = "Successfully sent mail";
			}
			else
			{
				$er['stat'] = 0;
				$er['stat-msg'] = "Something went wrong! Please contact sales@hashroot.com";
			}

  	}
	echo(json_encode($er));

	}
// End Superheroes form
// pop up form
	public function applicaions_cv()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		//$this->form_validation->set_error_delimiters(' < div id = "error" > ', '</div > ');
		$this->form_validation->set_rules('name','Name', 'required');
		$this->form_validation->set_rules('email','Email Address','required|valid_email');
		$this->form_validation->set_rules('phone','Phone Number','required');
		$this->form_validation->set_rules('expected2','Expected salary','numeric');
		$this->form_validation->set_rules('current2','Current Salary','numeric');
  	//captcha validation
		$this->form_validation->set_rules('g-recaptcha-response', 'recaptcha validation', 'required|callback_validate_captcha');
		$this->form_validation->set_message('validate_captcha', 'Please check the the captcha form');
		if($this->form_validation->run() == FALSE)
		{
			//echo validation_errors();
			$er = array();
			$er['stat-msg'] = validation_errors();
			$er['stat'] = 0;
		}
		else
		{
			// file upload

			$config['upload_path']          = './assets/resumes';
			$config['allowed_types']        = 'pdf|doc|docx|txt|dot';
			$config['max_size']             = 5024;
			$config['file_name']            = $this->input->post('name').'_'.Date('Y-M-D');
			// $config['max_width']         = 1024;
			// $config['max_height']        = 768;

			$this->load->library('upload', $config);

			// echo('hii');
			// // exit();

			if ( ! $this->upload->do_upload('userfile'))
			{
							$er = array();
							$er['stat'] = 0;
							$er['stat-msg']    =   $this->upload->display_errors();
							//$this->load->view('Category/addcat', $error);
							echo(json_encode($er));

			}
			else
			{
							$data=$this->upload->data();
							// echo('hii');
							// print_r($data);
              // echo(json_encode($data));
			}
			// Close file upload

			// mail
			$er      = array();
			$name    = $this->input->post('name');
			$email   = $this->input->post('email');
			$message = $this->input->post('comment');
			$phone   = $this->input->post('phone');
			$jobcode = $this->input->post('jobcode');
			$position = $this->input->post('pos');
			$expectedsal2 = $this->input->post('expected2');
			$currentsal2 = $this->input->post('current2');
			$subject = "JOB APPLICATION ".$jobcode;
			// $this->load->library('email');
			$config = array(
	     'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
	     'smtp_host' => 'zimbra.hashroot.com',
	     'smtp_port' => 465,
	     'smtp_user' => 'site@hashroot.com',
	     'smtp_pass' => 'GYTTf78!e467!@#',
	     'smtp_crypto' => 'ssl', //can be 'ssl' or 'tls' for example
	     'mailtype' => 'html', //plaintext 'text' mails or 'html'
	     'smtp_timeout' => '4', //in seconds
	     'charset' => 'iso-8859-1',
	     'wordwrap' => TRUE
	 );
	 		$this->load->library('email',$config);
			$this->email->from($email, 'Career form');
			$this->email->to('careers@hashroot.com');
			$this->email->subject($subject);
			$this->email->message(" <b>Name :</b> ".$name."<br/><br/><b>  Email :</b> ".$email."<br/><br/><b>Phone : </b>".$phone."<br/><br/><b>Jobcode : </b>".$jobcode."<br/><br/><b>Position : </b>".$position."<br/><br/><b> Expected Salary : </b>".$expectedsal2."<br/><br/><b> Current Salary : </b>".$currentsal2);
			$this->email->attach('./assets/resumes/'.$data['file_name']);
		  //$this->email->attach(	$config['upload_path'] '/'$config['file_name'] );
  		if($this->email->send())
			{
				$er['stat'] = 1;
				$er['stat-msg'] = "Thank you for contacting us. We'll contact you shortly.";
			}
			else
			{
				$er['stat'] = 0;
				$er['stat-msg'] = "Something went wrong! Please contact sales@hashroot.com";
			}
			//Close  mail
  	}
	echo(json_encode($er));

	}
// close application form validation

//Recaptcha validation
	function validate_captcha() {
        $recaptcha = trim($this->input->post('g-recaptcha-response'));
        $userIp= $this->input->ip_address();
        $secret='6LcGKC0UAAAAAHPL6zFQEzorM9iYIcB2lwD3fio6';
        $data = array(
            'secret'   => "$secret",
            'response' => "$recaptcha",
            'remoteip' => "$userIp"
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);
        $status= json_decode($response, true);
        if(empty($status['success'])){
            return FALSE;
        }else{
            return TRUE;
        }
    }

//End Recaptcha validation

}
?>
