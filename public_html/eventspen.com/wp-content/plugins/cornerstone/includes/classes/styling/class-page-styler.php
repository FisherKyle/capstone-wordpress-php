<?php

class Cornerstone_Page_Styler {

  public $count = 0;

  public function __construct( $post_id ) {

    $elements = cs_get_serialized_post_meta( $post_id, '_cornerstone_data', true );
		$reducer = new Cornerstone_Element_Reducer( array( 'elements' => $this->id_populator( $elements ) ) );

    $reducer->iterate( array( $this, 'styling' ) );

	}

  function id_populator( $elements ) {

    foreach ( $elements as $index => $element ) {

      $elements[$index]['_id'] = ++$this->count;

      if ( isset( $element['elements'] ) ) {
        $elements[$index]['elements'] = $this->id_populator( $element['elements'] );
      }

    }

    return $elements;

  }

  public function styling( $type, $ids, $data ) {
    var_dump("$type:" . implode('|', $ids) . ':' . serialize($data) );
  }

}
