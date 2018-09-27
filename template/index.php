<?php
  $tc_camp_setting = json_decode(get_option('wp_option_campaign_et'),true);
?>
<div class="wrap tc_wrap" style="opacity: 1;">
	<div id="poststuff" class="metabox-holder tc-settings">
            <form action="" method="post" name="save_Active Campaign_options" enctype="multipart/form-data">
                <div id="store_settings" class="postbox">
                    <h3>
                        <span>
                            Active Campaign Options
                        </span>
                    </h3>
                    <div class="inside">
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><label for="list_id">Disable Active Campaign</label></th>
                                    <td><input type="checkbox" id="disable_campaign" value="" <?php echo ($tc_camp_setting['disable_campaign'] == 'true') ? 'checked' : '' ?>>
                                    <p class="description">Check to disable Active Campaign submission</p></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="api_key">API Key</label></th>
                                    <td><input type="text" id="api_key" value="<?php echo $tc_camp_setting['api_key'] ?>" class="regular-text">
                                        <p class="description">Set the Active Campaign API key.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="url_account">URL Account Domain</label></th>
                                    <td><input type="text" id="url_account" value="<?php echo $tc_camp_setting['url_account'] ?>" class="regular-text">
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="list_id">List ID</label></th>
                                    <td><input type="text" id="list_id" value="<?php echo $tc_camp_setting['list_ID'] ?>" class="regular-text"> <a href="#" class="tc-campaign-test-list">Test newsletter submission</a><div class="tc-show-message"></div>
                                      <p class="description">Set the Campaign list ID</p>
                                      <div class="notification_check_list"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="status">Status</label></th>
                                    <td>
                                      <input type="radio" name="status" value="1" <?php echo ($tc_camp_setting['status'] == 'true') ? 'checked' : '' ?>> active
                                      <input type="radio" name="status" value="2" <?php  echo ($tc_camp_setting['status'] == 'false') ? 'checked' : '' ?>> unsubscribed<br>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="instantresponders">Instant Responders</label></th>
                                    <td>
                                      <input type="checkbox" id="instantresponders" value="" <?php echo ($tc_camp_setting['instantresponders'] == 'true') ? 'checked' : '' ?>>
                                      <p class="description">ticked if you don't want to sent instant autoresponders</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- inside -->
                </div><!-- store-settings -->
                <input type="button" name="save_campaign_options_et" id="save_campaign_options_et" class="button button-primary" value="Save Changes">
                </form>
        </div><!-- poststuff -->
</div>
