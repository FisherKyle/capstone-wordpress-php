<?php
 
class Paginator {
 
    private $_conn;
    private $_limit;
    private $_page;
    private $contacts;
    private $_total;

 	public function __construct( $contacts ) {
     
     	$this->contacts = $contacts;
	    $this->_total = count($contacts);  
	}

	/* 
		* Function returns data to display
		* @Since 1.0
	*/
	public function getData( $limit = 10, $page = 1 ,$orderby , $order , $searchKey , $serachInParams , $maintainKeys ) {
     	$this->serachInParams ='';
	    $this->_limit   = $limit;
	    $this->_page    = $page;
	    $this->_offset   = ( ( $this->_page - 1 ) * $this->_limit );
	    $this->_upto     = $this->_offset + $this->_limit;
	 	$this->orderby   = $orderby;
	 	$this->order     = $order;
	 	$this->serachInParams = $serachInParams;
	 	$this->maintainKeys = $maintainKeys;

	 	$data = $this->contacts;
	 	//var_dump($searchKey);
	 	if( $searchKey !== '' ) {
		 			
	 		$data = array_filter($data, function ($item) use ($searchKey) {
	 			
	 			$found = false;

	 			foreach($this->serachInParams as $param) {	 			
	 				
	 					if (array_key_exists($param, $item)) {						  		 			
						    if ( stripos( strtolower( urldecode( $item[$param] ) ), $searchKey) !== false ) {
						        $found = true;
						    }	
				    	}		   
			    }
			    if($found) {
			    	return true; 
			    }
			    return false;
			});
		 	
	 		$this->_total = count($data);
		}
		
		if($this->order) {

			if( !$this->maintainKeys ) {
				$data = array_values($data);	
			}

			if($this->order == 'asc')  {
		 		uasort($data, function($a, $b){ 

		 			if( isset( $a[$this->orderby] ) && isset( $b[$this->orderby] ) ) {
			 			
			 			// If type of data is integer
			 			if( gettype($a[$this->orderby]) == 'integer' ) {		 				
							return $this->cp_int_cmp( $a[$this->orderby], $b[$this->orderby] );		 				
			 			}

			 			if($this->orderby == 'date'){ 
			 				return strcmp(strtotime($a[$this->orderby]),strtotime($b[$this->orderby])); 
			 			} else { 
			 				return strcmp(strtolower($a[$this->orderby]),strtolower($b[$this->orderby])); 
			 			}
			 		}
		 		});
		 	} else { 	 		
		 		uasort($data, function($b, $a){ 

		 			if( isset( $a[$this->orderby] ) && isset( $b[$this->orderby] ) ) {

			 			// If type of data is integer
			 			if( gettype($a[$this->orderby]) == 'integer' ) {
							return $this->cp_int_cmp( $a[$this->orderby], $b[$this->orderby] );		 				
			 			}

			 			if($this->orderby == 'date') 
			 				return strcmp(strtotime($a[$this->orderby]),strtotime($b[$this->orderby])); 
			 			else 
			 				return strcmp(strtolower($a[$this->orderby]),strtolower($b[$this->orderby])); 
			 		}
		 		});
		 	}

		 	$data = array_slice($data , $this->_offset ,  $this->_limit, true); 

	 	} else {
	 		$data = array_slice($data , $this->_offset , $this->_limit, true); 
	 	}

	    $result         = new stdClass();
	    $result->data   = $data;
	 
	    return $result;
	}

	/* 
		* Function compare two integers 
		* @Since 1.1.0
	*/
	function cp_int_cmp($a,$b)
    {
    	return ($a-$b) ? ($a-$b)/abs($a-$b) : 0;
    }

	/* 
		* Function create links for pagination
		* @Since 1.0
	*/
	public function createLinks( $links, $list_class, $listID , $sq, $basePageLink ) {
	    if ( $this->_limit == 'all' ) {
	        return '';
	    }

	    if( $listID !== '' ) {
	    	$basePageLink .= '&list='.$listID;
	    }
	 	
	 	$url_link = '';
	 	if(isset( $_GET['orderby'])){
	 		$url_link .= '&orderby='.$_GET['orderby'];
	 	}

	 	if(isset( $_GET['order'])){
	 		$url_link .= '&order='.$_GET['order'];
	 	}

	    $last       = ceil( $this->_total / $this->_limit );
	 
	    $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
	    $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
	 
	    $html       = '<ul class="' . $list_class . '">';
	 
	    $class      = ( $this->_page == 1 ) ? "disabled" : "";
	    $prevPageLink  = ( $this->_page == 1 ) ? "javascript:void(0)" : $basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=' . ( $this->_page - 1 ) . $url_link;
	    $firstPageLink = $basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=1'.$url_link; 
	    $html       .= '<li class="' . $class . '"><a href="'.$firstPageLink.'"><span class="connects-icon-rewind"></span></a></li>';
	    $html       .= '<li class="' . $class . '"><a href="'.$prevPageLink.'"><span class="dashicons dashicons-arrow-left-alt2"></span></a></li>';

	   	if( $this->_page > 1 )
	    	$start = $this->_page - 1;
	    else 
	    	$start = 1;

	    for ( $i = $start; $i <= $end; $i++ ) {
	        $class  = ( $this->_page == $i ) ? "active" : "";
	        $html   .= '<li class="' . $class . '"><a href="'.$basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=' . $i . $url_link .'">' . $i . '</a></li>';
	    }
	 
	    if ( $end < $last ) {
	        $html   .= '<li class="disabled"><span>...</span></li>';
	        $html   .= '<li><a href="'.$basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=' . $last . $url_link.'">' . $last . '</a></li>';
	    }
	 
	    $class      = ( $this->_page == $last ) ? "disabled" : "";
	    $nextPageLink  = ( $this->_page == $last ) ? "javascript:void(0)" : $basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=' . ( $this->_page + 1 ) .$url_link;
	    $lastPageLink  = ( $this->_page == $last ) ? "javascript:void(0)" : $basePageLink.'&limit=' . $this->_limit . '&sq='.$sq.'&cont-page=' . ( $last ).$url_link;
	    $html       .= '<li class="' . $class . '"><a href="'.$nextPageLink.'"><span class="dashicons dashicons-arrow-right-alt2"></span></a></li>';
	    $html       .= '<li class="' . $class . '"><a href="'.$lastPageLink.'"><span class="connects-icon-fast-forward"></span></a></li>';
	 	
	    $html       .= '</ul>';
	 
	    return $html;
	}
}