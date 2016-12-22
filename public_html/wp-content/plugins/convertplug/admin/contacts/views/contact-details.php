<?php

/**
 * Checks to see if the specified email address has a Gravatar image.
 *
 * @param $email_address  The email of the address of the user to check
 * @return                Whether or not the user has a gravatar
 * @since 1.0
 */
function has_gravatar( $email_address ) {

  // Build the Gravatar URL by hasing the email address
  $url = 'http://www.gravatar.com/avatar/' . md5( strtolower( trim ( $email_address ) ) ) . '?d=404';

  // Now check the headers...
  $headers = @get_headers( $url );

  // If 200 is found, the user has a Gravatar; otherwise, they don't.
  return preg_match( '|200|', $headers[0] ) ? true : false;

} // end example_has_gravatar


  $list_id = $_GET['list'];                     //  Get current mailer by ID
  $provider = $list_name = '';

  //  Find current mailer
  $smile_lists  = get_option('smile_lists');    //  Get all mailers
  if( $smile_lists )  {
    if( isset( $smile_lists[ $list_id ] ) ) {
      $list       = $smile_lists[$list_id];
      $list_name  = $list['list-name'];
      $provider   = $list['list-provider'];
    }
  }


  $mailer   = str_replace(" ","_",strtolower( trim( $provider ) ) );
  $listName = str_replace(" ","_",strtolower( trim( $list_name ) ) );

  if( $mailer !== "convert_plug" ){
    $listOption = "cp_".$mailer."_".$listName;
    $contacts = get_option($listOption);
  } else {
    $listOption = "cp_connects_".$listName;
    $contacts = get_option($listOption);
  } ?>

<style type="text/css">
#the-list tr:last-child td,
#the-list tr:last-child th {
    width: 50%;
}
.bsf-connect-user-info h2 {
    margin-top: 0;
    margin-bottom: 10px;
}
.bsf-connect-user-info hr {
    margin-top: 30px;
}
.bsf-connect-user-info .bend-head-logo:before {
    background-image: none !important;
}
.bsf-connect-user-info table.bsf-connect-optins,
.bsf-connect-user-info table.bsf-connect-optins tr,
.bsf-connect-user-info table.bsf-connect-optins td {
    vertical-align: top !important;
}

.user-thumb,
.user-icon {
  width: 100%;
  display: block;
  margin: 0;
}
.user-thumb {
  padding: 0;
  margin-bottom: -15px;
}
.user-icon {
  height: 100px;
  border-radius: 0;
  border: none;
  padding: 0;
  line-height: 90px;
  text-align: center;
  margin: 0 0 -20px;
  background: #fff;
}
.user-icon i {
    line-height: 1;
    font-size: 45px;
    display: inline-block;
    text-align: center;
    color: #0094DD;
}
span.connect-list-gravtar-img {
    width: 100%;
    height: 100%;
}
.manage-column label {
    color: #444;
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}
</style>
<div class="wrap about-wrap bsf-connect bsf-connect-list bsf-connect-user-info bend">
    <div class="wrap-container">

      <!-- Detailed User Info -->
      <?php

      //  Current User
      $currentUser  = ( isset( $_GET['id'] ) ) ? $_GET['id'] : '';
      $currentEmail = ( isset( $_GET['email'] ) ) ? $_GET['email'] : '';

      if( !empty( $contacts ) ){
        foreach( $contacts as $key => $list ){

          $email  = isset( $list['email'] ) ? $list['email'] : 'NA';
          $name   = ( isset( $list['name'] ) && $list['name'] !== "" ) ? $list['name'] : 'NA';
          $date   = date("j M Y",strtotime($list['date']));
          $user_id =  ( isset( $list['user_id'] ) && $list['user_id'] !== "" ) ? $list['user_id'] : '';

          //  Check Current User
          if( empty( $currentUser ) ) {
            $is_valid = ( $currentEmail === $email ) ? true : false;
          } else {
            $is_valid = ( $currentUser ===  $user_id ) ? true : false;
          }

          if( $is_valid ) { ?>

          <div class="bend-heading-section bsf-connect-header bsf-connect-list-header <?php if( empty( $contacts ) ){ echo 'bsf-connect-empty-header'; } ?>">
            <h1><span class="cp-strip-text" style="max-width: 460px;top: 10px;" title="<?php if( !empty($name) ) { echo esc_attr( $name ); } else { echo __( 'Subscriber Details', 'smile' ); } ?>"><?php if( !empty($name) ) { echo esc_attr( $name ); } else { echo __( 'Subscriber Details', 'smile' ); } ?></span> <a class="add-new-h2" href="?page=contact-manager&view=contacts&list=<?php echo $_GET['list']; ?>"><?php _e('Back to Campaigns List', 'smile'); ?></a></h1>
            <h3 style="margin-bottom: 55px;"><?php if( $email != 'NA' ) { echo esc_attr( $email ); } else { echo esc_attr( $date ); } ?></h3>
            <div class="bend-head-logo">
              <?php
                //  has Gravatar?
                if( has_gravatar( $email ) ) {
                  echo '<div class="user-thumb"><span class="connect-list-gravtar-img">' . get_avatar( $email ,'96','https://support.brainstormforce.com/wp-content/uploads/2015/07/default-gravtar.png' ) . '</span></div>';
                } else {
                  echo '<div class="user-icon"><i class="connects-icon-head"></i></div>';
                } ?>
              <div class="bend-product-ver">
                <?php echo esc_attr( $date ); ?>
              </div>
            </div>
          </div><!-- bend-heading section -->

          <div class="bend-content-wrap">
            <hr class="bsf-extensions-lists-separator" style="margin: 22px 0px 30px 0px;"></hr>
            <div class="container bsf-connect-content">
              <table  class="wp-list-table widefat fixed bsf-connect-optins bsf-connect-optins-list">
                <tbody id="the-list" class="smile-style-data">

                  <tr>
                    <td scope="col" class="manage-column">
                      <label><?php echo strtoupper( __( 'Email Address', 'smile' ) ); ?></label>
                      <?php echo esc_attr( $email ); ?>
                    </td>
                    <td scope="col" class="manage-column">
                      <label><?php echo strtoupper( __( 'Date', 'smile' ) ); ?></label>
                      <?php echo esc_attr( $date ); ?>
                    </td>
                  </tr>

                  <tr>
                    <?php
                    //  Show all the details though loop
                    //  except 'email' & 'date'
                    unset( $list['date'] );
                    if( isset( $list['email'] ) ) {
                       unset($list['email']);
                    }

                    //  Build 2x2 <td> for rest of the fields
                    $i = 0;
                    ksort($list);
                    foreach ($list as $k => $v ) {

                      if( isset($v) && !empty($v) ) {
                          $i++ ;
                          if ($i % 2 == 0){
                            $i / 2; ?>
                              <td scope="col" class="manage-column">
                                <label><?php echo strtoupper( __( $k , 'smile' ) ); ?></label>
                                <?php echo esc_attr( $v ); ?>
                              </td>
                            <?php
                          } else { ?>
                            </tr>
                            <tr><td scope="col" class="manage-column">
                                <label><?php echo strtoupper( __( $k , 'smile' ) ); ?></label>
                                <?php echo esc_attr( $v ); ?>
                              </td>
                              <?php
                          }
                      }
                    } ?>
                  </tr>
                  <?php }
                } ?>
          </tbody>
        </table>
      </div>
    </div><!-- .bend-content-wrap -->
    <?php  } ?>
  </div><!-- .wrap-container -->
</div><!-- .wrap -->
