<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Emmmmmmmaaa
 *
 * @package		Emma
 * @subpackage	ThirdParty
 * @category	Modules
 * @author		Maju Ansari
 * @link		http://www.empressem.in
 */
class Emma {

	var $return_data;

    /**
     * @var Devkit_code_completion
     */
    private $EE;

	function Emma()
	{		
	    $this->EE =& get_instance(); // Make a local reference to the ExpressionEngine super object
            $this->EE->load->model('emma_model');    
        
	}
	/*{exp:emma:subscribe}
        {/exp:emma:subscribe}  */

        public function subscribe() 
        {
            $vars=array();
            $this->EE->load->library('table');
            $this->EE->load->library('session');                                   
            $this->EE->load->helper('form');
            $message="";
            $message_color="red";
            $member_id='';
            if(isset($_POST['emma_subscribe']))
            {                                    
               $group_data[]=intval($this->EE->config->item('emma_default_group'));
               $email = $_POST['email'];             
               if($email)
               {
                   $fields['first_name']  = $_POST['name'];
                   $response = $this->EE->emma_model->createEmmaUser($email,$fields,$group_data);                                                 
                   if(!isset($response_add->error))
                   {
                       if( isset($response->member_id) )
                       {
                           $group_data[] = $this->EE->config->item('emma_default_group');
                           $response = $this->EE->emma_model->addMemberToGroups($response->member_id,$group_data);  
                           $message=lang("Data Updated successfully !!!");                                                                                                                        $message_color="green";
                       }
                   }
                   else
                   {
                       $message=lang("Oooops Something went wrong");
                                                                                                                                                                                          }               
               }
            }                                                                   
            $vars=array(
               'member_id'=>$member_id,
               'message_color'=>$message_color,
               'message'=>$message,
               'action_url'=>"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                 );
            return $this->EE->load->view('emma_subscribe_form',$vars,TRUE); 
              
        }  

    public function subscribe_old() {
        $vars=array();
        $this->EE->load->library('table');
        $this->EE->load->library('session');
#        $user_email=$this->EE->session->userdata('email');
        //var_dump($this->EE->session->userdata);
        $this->EE->load->helper('form');
        $message="";
        $message_color="red";
        $member_id='';
        $group_data=$member_group_ids=array();
 #       if($user_email)
  #      {
            if(isset($_POST['emma_subscribe']))
            {
               foreach($_POST['group_list'] as $val)
               {
                    if($val)
                    {
                        $group_data[]=intval($val);
                    }    
               }           
               if($_POST['emma_member_id'])
               {
                    $member_id=$_POST['emma_member_id'];
                   $response_remove= $this->EE->emma_model->removeMemberFromAllGroups($_POST['emma_member_id']);
                   $response_add=$this->EE->emma_model->addMemberToGroups($_POST['emma_member_id'],$group_data);
                   if(!isset($response_add->error))
                   {
                        $message=lang("Data Updated successfully !!!");
                        $member_group_ids=$group_data;
                        $message_color="green";
                   }
                    else
                    {
                        $message=lang("Oooops Something went wrong");
                    }               
               }
               else
               {
                    $members = array(   
                                        array('email'=>$user_email),
                                    );
             
                    $response=$this->EE->emma_model->importMemberList($members, 'EE-import'.time(), 1, $group_data);
                    if(isset($response->import_id))
                    {
                        $message=lang("Data Updated successfully !!!");
                        $member_group_ids=$group_data;
                        $message_color="green";
                    }
                    else
                    {
                        $message=lang("Oooops Something went wrong");
                    }
               }
            }   
            if(!$message)
            {        
                $member_details=$this->EE->emma_model->get_member_detail_by_email($user_email);
                if(isset($member_details->member_id))
                {
                    $member_id=$member_details->member_id;
                    $groups=  $this->EE->emma_model->getMemberGroups($member_details->member_id);
                    foreach($groups as $group)
                    {
                        $member_group_ids[$group->member_group_id]=$group->member_group_id;
                    }  
                }       
            }
            $groups=$this->EE->emma_model->getGroups();
            foreach($groups as $group)
            {
                $groups_list[$group->member_group_id]=$group->group_name;
            }        
            $vars=array(
                'groups'=>$groups_list,
                'member_group_ids'=>$member_group_ids,
                'member_id'=>$member_id,
                'message_color'=>$message_color,
                'message'=>$message,
                'action_url'=>"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
            );
            return $this->EE->load->view('emma_subscribe_form', $vars, TRUE); 
      #  }
    }  
	/**
     * Helper function for getting a parameter
	 */		 
	function _get_param($key, $default_value = '')
	{
		$val = $this->EE->TMPL->fetch_param($key);
		
		if($val == '') {
			return $default_value;
		}
		return $val;
	}

	/**
	 * Helper funciton for template logging
	 */	
	function _error_log($msg)
	{		
		$this->EE->TMPL->log_item("emma ERROR: ".$msg);		
	}

    /**
     * For code completion
     *
     * @return Devkit_code_completion_helper
     */
    function EE() {if(!isset($this->EE)){$this->EE =& get_instance();}return $this->EE;}
}

/* End of file mod.emma.php */ 
/* Location: ./system/expressionengine/third_party/emma/mod.emma.php */
/* Generated by DevKit for EE - develop addons faster! */
