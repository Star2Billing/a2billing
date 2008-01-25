<?php
	// Please, include ../Form.inc.php instead!

	/** Generic form
	    The form is the main handler of data->html interaction.
	*/

class FormHandler
{	
	public $FG_DEBUG = 0;
	protected $action = null;
	private $rights_checked = false;
		/** prefix all url vars with this, so that multiple forms can co-exist
		in the same html page! */
	public $prefix = ''; 
	
	public $a2billing; ///< Reference to an a2billing instance
	
	// model-related vars
		/** The most important var: hold one object per field to be viewed/edited */
	public $model = array();
	public $model_name = 'Records'; ///< plural form for table
	public $model_name_s = 'Record'; ///< Singular form
	
	public $model_table = null; ///< the \b main table related to the model
	private $s_modelPK = null; ///< Cached reference to the primary key column
	
	// appearance vars
	public $list_class = 'cclist'; ///< class of the table used in list view
	public $sens = null; ///< sort direction, null should default to ascending
	public $order = null; ///< sort field, should match some model[]->fieldname
	public $follow_params = array(); ///< Parameters to be followed accross pages
	
	//running vars
	protected $_dirty_vars=null; ///< all variables starting with 'prefix'. Set on init.
	
	function FormHandler($tablename=null, $inames=null, $iname=null){
		$this->model_table = $tablename;
		if ($inames) $this->model_name=$inames;
		if ($iname) $this->model_name_s = $iname;
			
	}
	
	/** Before this class can be initted, its rights should be
	   proven. Any attempt to use the class w/o them will fail. */
	public function checkRights($rights){
		if (!has_rights($rights)){
			Header ("HTTP/1.0 401 Unauthorized");
			Header ("Location: PP_error.php?c=accessdenied");
			die();
		}
		$this->rights_checked = true;
	}

	function init($sA2Billing= null){
		if (!$this->rights_checked){
			error_log("Attempt to use FormHandler w/o rights!");
			die();
		}
		if ($sA2Billing)
			$this->a2billing= &$sA2Billing;
		else
			$this->a2billing= &A2Billing::instance();
			
		if (isset($GLOBALS['FG_DEBUG']))
			$this->FG_DEBUG = $GLOBALS['FG_DEBUG'];

			// Fill a local array with dirty versions of data..
		if (!$this_prefix)
			$this->_dirty_vars=array_merge($_GET, $_POST);
		else {
			$tmp_arr = array_merge($_GET, $_POST);
			$tlen=strlen($this->prefix);
			$this->_dirty_vars=array();
			// Find vars matching prefix and strip that!
			foreach($tmp_arr as $key => $data)
				if (strncmp($this->prefix,$key,$tlen))
				$this->_dirty_vars[substr($key,$tlen)]=$data;
		}
			
		// set action, for a start:
		$this->action = $this->getpost_single('action');
		if ($this->action == null)
			$this->action = 'list';
		
		$this->order = $this->getpost_single('order');
		$this->sens = $this->getpost_single('sens');
		
	}


	/** Render the view/edit form for the HTML body */
	public function Render(){
		if (!$this->rights_checked){
			error_log("Attempt to use FormHandler w/o rights!");
			die();
		}
		switch($this->action){
		case 'list':
			$this->RenderList();
			break;
		case 'ask-edit':
		case 'editForm':
			$this->RenderEdit();
			break;
		case 'delForm':
			$this->RenderDel();
			break;
		case 'dump-form':
			if (!$this->FG_DEBUG)
				break;
			$this->dbg_DumpForm();
			break;
		default:
			if ($this->FG_DEBUG) echo "Cannot handle action: $this->action";
		}
	}
	
	protected function RenderList(){
		// This function is one file!
		require("RenderList.inc.php");
	}
	
	protected function RenderEdit(){
		require("RenderEdit.inc.php");
	}

	protected function RenderDel(){
		require("RenderAskDel.inc.php");
	}

	// helper functions
	/** Return a reference to the first primary key column of the model.
	    Throw an exception if no primary key! */
	public function getModelPK(){
		if ($this->s_modelPK)
			return $this->s_modelPK;
		foreach($this->model as &$fld)
			if($fld && ($fld instanceof PKeyField))
			return $this->s_modelPK = &$fld;
		
		throw new Exception('Model doesn\'t have a primary key!');
	}
	/** Construct an url out of the follow parameters + some custom ones
	   @param $arr_more  An array to be added in the form ( key => data ...)
	   @return A string like "?key1=data&key2=data..."
	*/
	function gen_GetParams($arr_more = NULL,$do_amper=false){
		$arr = $this->follow_params;
		if (is_array($arr_more))
		$arr = array_merge($arr, $arr_more);
		$str = arr2url($arr);
		
		if (strlen($str)){
			if ($do_amper)
			$str = '&' . $str;
			else
			$str = '?' . $str;
		}
		return $str;
	}
	
	function gen_PostParams($arr_more = NULL, $do_nulls=false){
		$arr = $this->follow_params;
		if (is_array($arr_more))
		$arr = array_merge($arr, $arr_more);
		// unfortunately, it is hard to use CV_FOLLOWPARAMETERS here!
		
		foreach($arr as $key => $value)
			if ($do_nulls || $value !=NULL){
		?><input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($value) ?>" >
		<?php
		}
	}
	
	/** Return an URL to this page, with some extra params */
	function selfUrl(array $arr){
		return $_SERVER['PHP_SELF']. $this->gen_GetParams($arr);
	}
	
	/** Return a URL to the ask-edit page
	    \param $arr The row of the query
	*/
	function askeditURL(array $arr){
		$mod_pk= $this->getModelPK();
		return $_SERVER['PHP_SELF'].'?'.
			$this->prefix.'action=ask-edit&'.
			$this->prefix.$mod_pk->fieldname.'='.rawurlencode($arr[$mod_pk->fieldname]);
	}
	
	/// Throw away anything that could make data weird.. Sometimes too much.
	function sanitize_data($data){
		if(is_array($data)){
			return $data; //Need to sanatize this later
		}
		$lowerdata = strtolower ($data);
		$data = str_replace('--', '', $data);
		$data = str_replace("'", '', $data);
		$data = str_replace('=', '', $data);
		$data = str_replace(';', '', $data);
		//$lowerdata = str_replace('table', '', $lowerdata);
		//$lowerdata = str_replace(' or ', '', $data);
		if (!(strpos($lowerdata, ' or 1')===FALSE)){ return false;}
		if (!(strpos($lowerdata, ' or true')===FALSE)){ return false;}
		if (!(strpos($lowerdata, 'table')===FALSE)){ return false;}
		return $data;
	}
	
	function getpost_single($vname){
		return sanitize_data($this->_dirty_vars[$vname]);
	}
	
	function getpost_dirty($vname){
		return $this->_dirty_vars[$vname];
	}

	// ---- Debuging functions..
	
	function dbg_DumpForm(){
		echo "<div><pre>\n";
		print_r($this);
		echo "\n</pre></div>\n";
	}
};

?>