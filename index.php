<?php
/**
 * Plugin Name: campaign plugin
 * Plugin URI: efe.com.vn
 * Description: integrate list user ticket in list
 * Version: 1.0
 * Author: hau.nguyen
 * Author URI: hau.nguyen@efe.com.vn
 * License: GPLv2
 */
 if (!defined('ABSPATH'))
     exit; // Exit if accessed directly

 if (!class_exists('TC_Campaign')) {

     class TC_Campaign {

         var $plugin_name = 'Campaign';
         var $admin_name = 'Campaign';
         var $version = '1.0';
         var $title = 'Tickera Campaign';
         var $name = 'tc';
         var $dir_name = 'campaign';
         var $location = 'plugins';
         var $plugin_dir = '';
         var $plugin_url = '';
         function __construct() {
             add_filter('tc_settings_new_menus', array(&$this, 'tc_settings_new_menus_additional'));
             add_action('tc_settings_menu_tickera_Campaign', array(&$this, 'tc_settings_menu_tickera_mailchimp_show_page'));
         }
         function tc_delete_info_plugins_list($plugins) {
             $plugins[$this->name] = $this->title;
             return $plugins;
         }
         function tc_settings_new_menus_additional($settings_tabs) {
             $settings_tabs['tickera_Campaign'] = __('Active Campaign', 'tc');
             return $settings_tabs;
         }
         function tc_settings_menu_tickera_mailchimp_show_page() {
             require_once( $this->plugin_dir . 'template/index.php' );
         }

     }

 }
  $tc_campign = new TC_Campaign();
  add_action('admin_head', 'style_admin_head');
  function style_admin_head(){ ?>
    <style>
      .error_return_check_list{
        color:red;
        font-weight: bold;
      }
      .success_return_check_list{
        color: green;
        font-weight: bold;
      }
    </style>
  <?php }
  add_action('wp_head', 'myplugin_ajaxurl');
  function myplugin_ajaxurl() {
   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
  }

 add_action('admin_enqueue_scripts', 'include_script_fb');
 function include_script_fb() {
     wp_enqueue_script('axios-js', 'https://unpkg.com/axios/dist/axios.min.js');
     wp_enqueue_script('qs-js', 'https://cdnjs.cloudflare.com/ajax/libs/qs/6.5.2/qs.min.js');
 }

 add_action('admin_footer', 'tc_script_save');
 $tc_camp_setting = json_decode(get_option('wp_option_campaign_et'),true);
 if($tc_camp_setting['disable_campaign'] == 'false'){
   add_action('wp_footer', 'events_save_campaign');
 }

 function tc_script_save(){ ?>
   <script type="text/javascript">
     jQuery(document).ready(function($){
       $('#save_campaign_options_et').click(function(){
         jQuery.ajax({
             url: ajaxurl,
             type: 'post',
             data: {
                 'action': 'save_option_fn_campign',
                 'data': {
                   disable_campaign : $('#disable_campaign')[0].checked,
                   api_key : $('#api_key').val(),
                   url_account : $('#url_account').val(),
                   list_ID : $('#list_id').val(),
                   status: $('input[name="status"]')[0].checked,
                   instantresponders : $('#instantresponders')[0].checked,
              }
             },
             success: function (data) {
                 // alert(123);
             },
             error: function (errorThrown) {
                 console.log(errorThrown);
             }
         });
       });
       $('.tc-campaign-test-list').click(function(){
         jQuery.ajax({
             url: ajaxurl,
             type: 'post',
             data: {
                 'action': 'check_exits_list',
                 'data': $('#list_id').val()
             },
             success: function (data) {
               var json_return = JSON.parse(data);
               if(json_return.result_code == 0){
                 $('.notification_check_list').html('<p class="error_return_check_list">'+json_return.result_message+'</p>');
               }else{
                 $('.notification_check_list').html('<p class="success_return_check_list">'+json_return.result_message+'</p>');
               }
             },
             error: function (errorThrown) {
                 console.log(errorThrown);
             }
         });
       })
     })
   </script>
 <?php }
 function events_save_campaign(){ ?>
   <script type="text/javascript">
     jQuery(document).ready(function($){
       $('#proceed_to_checkout').click(function(){
         var id_ticket = $('input[name="ticket_cart_id[]"]').val();
         var ticket_quantity = $('input[name="ticket_quantity[]"]').val();
         var arr_data = new Array();
         for (var i = 0; i < ticket_quantity; i++) {
           arr_data.push({
             first_name: $('input[name="owner_data_first_name_post_meta['+id_ticket+']['+ i +']"]').val(),
             last_name: $('input[name="owner_data_last_name_post_meta['+id_ticket+']['+ i +']"]').val(),
             email : $('input[name="owner_data_owner_email_post_meta['+id_ticket+']['+ i +']"]').val(),
             phone : $('input[name="owner_data_tc_ff_phonenumber_tcfn_502_post_meta['+id_ticket+']['+ i +']"]').val()
           });
         }
         var check_valid = '';
         $.each(arr_data, function( index, value ) {
            Object.values(value).filter((item) => {
              if(item == null || item == ''){
                check_valid = false;
              }
            })
          });
          if($('input[name="buyer_data_first_name_post_meta"]').val() != '' || $('input[name="buyer_data_last_name_post_meta"]').val() != '' || $('input[name="buyer_data_email_post_meta"]').val() != ''){
            check_valid = false;
          }
          if(check_valid == ''){
            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    'action': 'post_submit_campaign',
                    'data' : arr_data
                },
                success: function (data) {

                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
          }
       })
     })
   </script>
 <?php }
/* ajax save option campaign */
add_action('wp_ajax_save_option_fn_campign', 'save_option_fn_campign');
function save_option_fn_campign(){
  $data = $_POST['data'];
  $var_sr = json_encode($data);
  update_option('wp_option_campaign_et', $var_sr);
  wp_die();
}
/* check exits list id */
add_action('wp_ajax_check_exits_list', 'check_exits_list');
function check_exits_list(){
  $tc_camp_setting = json_decode(get_option('wp_option_campaign_et'),true);
  $id_list = $_POST['data'];
  require('campaignApi/check_list.php');
  _api_check_list($tc_camp_setting, $id_list);
  wp_die();
}
/* end check exits list id */


add_action('wp_ajax_post_submit_campaign', 'post_submit_campaign');
add_action('wp_ajax_nopriv_post_submit_campaign', 'post_submit_campaign');
function post_submit_campaign(){
  $data = $_POST['data'];
  $tc_camp_setting = json_decode(get_option('wp_option_campaign_et'),true);
  require('campaignApi/contact.php');
  foreach ($data as $key => $value) {
    _api_add_contact($tc_camp_setting,$value);
  };
  wp_die();
}
?>
