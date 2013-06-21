<?php
/*
Plugin Name: Google Language Translator
Plugin URI: http://www.studio88design.com/plugins/google-language-translator
Version: 1.8
Description: The MOST SIMPLE Google Translator plugin.  This plugin adds Google Translator to your website by using a single shortcode, [google-translator]. Settings include: layout style, hide/show specific languages, hide/show Google toolbar, and hide/show Google branding. Add the shortcode to pages, posts, and widgets.
Author: Rob Myrick
Author URI: http://www.studio88design.com/
*/

function toggle_dropdown_script($hook_suffix) {
  global $my_settings_page;
  if ($my_settings_page == $hook_suffix) {
    wp_enqueue_script( 'my-script', plugins_url('/language-dropdown.js',__FILE__), array('jquery'));
	
  if (get_option('googlelanguagetranslator_language_option')=='all') {
    wp_register_style( 'settings.css', plugins_url('/settings.css', __FILE__), array(), '1.0', 'all' );
    wp_enqueue_style( 'settings.css' );
    }
  }
}

add_filter('widget_text', 'do_shortcode');

add_action('admin_menu', 'googlelanguagetranslator_menu_options');

add_option("googlelanguagetranslator_active","0");

add_option("googlelanguagetranslator_language_option","all");

add_shortcode( 'google-translator', 'google_translator_shortcode');

function google_translator_shortcode() {
    if (get_option('googlelanguagetranslator_display')=='Vertical'){
    return googlelanguagetranslator_vertical();
    }

    elseif(get_option('googlelanguagetranslator_display')=='Horizontal'){
    return googlelanguagetranslator_horizontal();
    }
   
    if (get_option('googlelanguagetranslator_toolbar')=='Yes'){
    return googlelanguagetranslator_toolbar_yes();
    }

    elseif(get_option('googlelanguagetranslator_toolbar')=='No'){
    return googlelanguagetranslator_toolbar_no();
	add_action ('wp_head','googlelanguagetranslator_toolbar_no');
    }
  
    if (get_option('googlelanguagetranslator_showbranding')=='Yes'){
    return googlelanguagetranslator_showbranding_yes();
	add_action ('wp_head','googlelanguagetranslator_showbranding_yes');
    }

    elseif(get_option('googlelanguagetranslator_showbranding')=='No'){
    return googlelanguagetranslator_showbranding_no();
	add_action ('wp_head','googlelanguagetranslator_showbranding_no');
   }
}

if (get_option('googlelanguagetranslator_toolbar')=='Yes') {
  add_action ('wp_head','googlelanguagetranslator_toolbar_yes');
}

if (get_option('googlelanguagetranslator_toolbar')=='No') {
  add_action ('wp_head','googlelanguagetranslator_toolbar_no');
}

if (get_option('googlelanguagetranslator_showbranding')=='Yes') {
  add_action ('wp_head','googlelanguagetranslator_showbranding_yes');
}

if (get_option('googlelanguagetranslator_showbranding')=='No') {
  add_action ('wp_head','googlelanguagetranslator_showbranding_no');
}

function googlelanguagetranslator_menu_options(){
    global $my_settings_page;
    
    add_action( 'admin_enqueue_scripts', 'toggle_dropdown_script');
  
    $my_settings_page = add_options_page('Google Language Translator', 'Google Language Translator', 'manage_options', 'googlelanguagetranslator-menu-options', 'googlelanguagetranslator_menu');
	
	if(isset($_POST['googlelanguagetranslator_update_options'])){
	    update_option('googlelanguagetranslator_language',$_POST['googlelanguagetranslator_language']);
		update_option('googlelanguagetranslator_display',$_POST['googlelanguagetranslator_display']);
	    update_option('googlelanguagetranslator_toolbar',$_POST['googlelanguagetranslator_toolbar']);
	    update_option('googlelanguagetranslator_showbranding',$_POST['googlelanguagetranslator_showbranding']);
	    update_option('googlelanguagetranslator_language_option',$_POST['googlelanguagetranslator_language_option']);
	}	
  
    if(isset($_POST['googlelanguagetranslator_update_options'])) {
	     $get_language_choices = get_option ('language_display_settings');
	     $get_language_choices['af'] = $_POST['af'];
	     $get_language_choices['sq'] = $_POST['sq'];
	     $get_language_choices['ar'] = $_POST['ar'];
	     $get_language_choices['hy'] = $_POST['hy'];
	     $get_language_choices['az'] = $_POST['az'];
	     $get_language_choices['eu'] = $_POST['eu'];
	     $get_language_choices['be'] = $_POST['be'];
	     $get_language_choices['bn'] = $_POST['bn'];
	     $get_language_choices['bg'] = $_POST['bg'];
	     $get_language_choices['ca'] = $_POST['ca'];
	     $get_language_choices['zh-CN'] = $_POST['zh-CN'];
	     $get_language_choices['zh-TW'] = $_POST['zh-TW'];
	     $get_language_choices['cs'] = $_POST['cs'];
	     $get_language_choices['hr'] = $_POST['hr'];
	     $get_language_choices['da'] = $_POST['da'];
	     $get_language_choices['nl'] = $_POST['nl'];
	     $get_language_choices['en'] = $_POST['en'];
	     $get_language_choices['eo'] = $_POST['eo'];
	     $get_language_choices['et'] = $_POST['et'];
	     $get_language_choices['tl'] = $_POST['tl'];
	     $get_language_choices['fi'] = $_POST['fi'];
	     $get_language_choices['fr'] = $_POST['fr'];
	     $get_language_choices['gl'] = $_POST['gl'];
	     $get_language_choices['ka'] = $_POST['ka'];
	     $get_language_choices['de'] = $_POST['de'];
	     $get_language_choices['el'] = $_POST['el'];
	     $get_language_choices['gu'] = $_POST['gu'];
	     $get_language_choices['ht'] = $_POST['ht'];
	     $get_language_choices['iw'] = $_POST['iw'];
	     $get_language_choices['hi'] = $_POST['hi'];
	     $get_language_choices['hu'] = $_POST['hu'];
	     $get_language_choices['is'] = $_POST['is'];
	     $get_language_choices['id'] = $_POST['id'];
	     $get_language_choices['ga'] = $_POST['ga'];
	     $get_language_choices['it'] = $_POST['it'];
	     $get_language_choices['ja'] = $_POST['ja'];
	     $get_language_choices['kn'] = $_POST['kn'];
	     $get_language_choices['ko'] = $_POST['ko'];
	     $get_language_choices['lo'] = $_POST['lo'];
	     $get_language_choices['la'] = $_POST['la'];
	     $get_language_choices['lv'] = $_POST['lv'];
	     $get_language_choices['lt'] = $_POST['lt'];
	     $get_language_choices['mk'] = $_POST['mk'];
	     $get_language_choices['ms'] = $_POST['ms'];
	     $get_language_choices['mt'] = $_POST['mt'];
	     $get_language_choices['no'] = $_POST['no'];
	     $get_language_choices['fa'] = $_POST['fa'];
	     $get_language_choices['pl'] = $_POST['pl'];
	     $get_language_choices['pt'] = $_POST['pt'];
	     $get_language_choices['ro'] = $_POST['roo'];
	     $get_language_choices['ru'] = $_POST['ru'];
	     $get_language_choices['sr'] = $_POST['sr'];
	     $get_language_choices['sk'] = $_POST['sk'];
	     $get_language_choices['sl'] = $_POST['sl'];
	     $get_language_choices['es'] = $_POST['es'];
	     $get_language_choices['sw'] = $_POST['sw'];
	     $get_language_choices['sv'] = $_POST['sv'];
	     $get_language_choices['ta'] = $_POST['ta'];
	     $get_language_choices['te'] = $_POST['te'];
	     $get_language_choices['th'] = $_POST['th'];
	     $get_language_choices['tr'] = $_POST['tr'];
	     $get_language_choices['uk'] = $_POST['uk'];
	     $get_language_choices['ur'] = $_POST['ur'];
	     $get_language_choices['vi'] = $_POST['vi'];
	     $get_language_choices['cy'] = $_POST['cy'];
	     $get_language_choices['yi'] = $_POST['yi'];
         update_option('language_display_settings', $get_language_choices); 
    }
}

function googlelanguagetranslator_menu(){
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<div class="wrap" style="width:70%">
	  <div id="icon-options-general" class="icon32"></div>
      <h2>Google Language Translator</h2>
      <div id="poststuff" class="metabox-holder has-right-sidebar" >
      <div class="postbox" style="width: 100%">
      <h3>Settings</h3>
<form method="post" action="options.php">
            <?php wp_nonce_field('update-options');?>
            <table width="100%" border="0" cellspacing="8" cellpadding="0" class="form-table">
              <tr>
				<td style="width:30%">Plugin Status:</td>
				<td><input class="activate" type="checkbox"  name="googlelanguagetranslator_active" id="googlelanguagetranslator_active" value="1" <?php if(get_option('googlelanguagetranslator_active')==1){echo "checked";}?> />
                Click Here to Activate Google Language Translator</td>
              </tr>
			  
			  <tr>
				<td>Choose the original language of your website</td>
				<td>
				<select name="googlelanguagetranslator_language" id="googlelanguagetranslator_language" style="width:100px">
				  <option value="en" <?php if(get_option('googlelanguagetranslator_language')=='en'){echo "selected";}?>>English</option>
				  <option value="es" <?php if(get_option('googlelanguagetranslator_language')=='es'){echo "selected";}?>>Spanish</option>
				  <option value="fr" <?php if(get_option('googlelanguagetranslator_language')=='fr'){echo "selected";}?>>French</option>
				  <option value="it" <?php if(get_option('googlelanguagetranslator_language')=='it'){echo "selected";}?>>Italian</option>
				  <option value="da" <?php if(get_option('googlelanguagetranslator_language')=='da'){echo "selected";}?>>Danish</option>
				  <option value="ja" <?php if(get_option('googlelanguagetranslator_language')=='ja'){echo "selected";}?>>Japanese</option>
				  <option value="zh-CN" <?php if(get_option('googlelanguagetranslator_language')=='zh-CN'){echo "selected";}?>>Chinese</option>
				  <option value="ru" <?php if(get_option('googlelanguagetranslator_language')=='ru') {echo "selected";}?>>Russian</option>
				  </select>
				</td>
			  </tr>
			  
			  <tr>
				<td>What translation languages will you display to website visitors?</td>
				<td>
				  <input type="radio" name="googlelanguagetranslator_language_option" id="googlelanguagetranslator_language_option" value="all" <?php if(get_option('googlelanguagetranslator_language_option')=='all'){echo "checked";}?>/> All Languages<br/>
				  <input type="radio" name="googlelanguagetranslator_language_option" id="googlelanguagetranslator_language_option" value="specific" <?php if(get_option('googlelanguagetranslator_language_option')=='specific'){echo "checked";}?>/> Specific Languages
				</td>
			  </tr>
			  
			  <tr>
				<td colspan="2">
				  <div class="languages" style="width:25%; float:left">
					<?php $get_language_choices = get_option ('language_display_settings'); ?>
					<div><input type="checkbox" name="af" value="1"<?php checked( isset ( $get_language_choices['af'] ) ); ?> /> Afrikaans</div>
					<div><input type="checkbox" name="sq" value="1"<?php checked( isset ( $get_language_choices['sq'] ) ); ?> /> Albanian</div>
					<div><input type="checkbox" name="ar" value="1"<?php checked( isset ( $get_language_choices['ar'] ) ); ?> /> Arabic</div>
                    <div><input type="checkbox" name="hy" value="1"<?php checked( isset ( $get_language_choices['hy'] ) ); ?> /> Armenian</div>
                    <div><input type="checkbox" name="az" value="1"<?php checked( isset ( $get_language_choices['az'] ) ); ?> /> Azerbaijani</div>                  
                    <div><input type="checkbox" name="eu" value="1"<?php checked( isset ( $get_language_choices['eu'] ) ); ?> /> Basque</div>                    
                    <div><input type="checkbox" name="be" value="1"<?php checked( isset ( $get_language_choices['be'] ) ); ?> /> Belarusian</div>                    
                    <div><input type="checkbox" name="bn" value="1"<?php checked( isset ( $get_language_choices['bn'] ) ); ?> /> Bengali</div>                    
                    <div><input type="checkbox" name="bg" value="1"<?php checked( isset ( $get_language_choices['bg'] ) ); ?> /> Bulgarian</div>                    
                    <div><input type="checkbox" name="ca" value="1"<?php checked( isset ( $get_language_choices['ca'] ) ); ?> /> Catalan</div>                    
                    <div><input type="checkbox" name="zh-CN" value="1"<?php checked( isset ( $get_language_choices['zh-CN'] ) ); ?> /> Chinese</div>                    
                    <div><input type="checkbox" name="zh-TW" value="1"<?php checked( isset ( $get_language_choices['zh-TW'] ) ); ?> /> Chinese (Han)</div>                    
                    <div><input type="checkbox" name="cs" value="1"<?php checked( isset ( $get_language_choices['cs'] ) ); ?> /> Croatian</div>                    
                    <div><input type="checkbox" name="hr" value="1"<?php checked( isset ( $get_language_choices['hr'] ) ); ?> /> Czech</div>                    			
                    <div><input type="checkbox" name="da" value="1"<?php checked( isset ( $get_language_choices['da'] ) ); ?> /> Danish</div>                    
                    <div><input type="checkbox" name="nl" value="1"<?php checked( isset ( $get_language_choices['nl'] ) ); ?> /> Dutch</div>                    				
                    <div><input type="checkbox" name="en" value="1"<?php checked( isset ( $get_language_choices['en'] ) ); ?> /> English</div>   
				</div>
				  
				  <div class="languages" style="width:25%; float:left">
                    <div><input type="checkbox" name="eo" value="1"<?php checked( isset ( $get_language_choices['eo'] ) ); ?> /> Esperanto</div>                      
                    <div><input type="checkbox" name="et" value="1"<?php checked( isset ( $get_language_choices['et'] ) ); ?> /> Estonian</div>                   
                    <div><input type="checkbox" name="tl" value="1"<?php checked( isset ( $get_language_choices['tl'] ) ); ?> /> Filipino</div>                     
                    <div><input type="checkbox" name="fi" value="1"<?php checked( isset ( $get_language_choices['fi'] ) ); ?> /> Finnish</div>                    
                    <div><input type="checkbox" name="fr" value="1"<?php checked( isset ( $get_language_choices['fr'] ) ); ?> /> French</div>                     
                    <div><input type="checkbox" name="gl" value="1"<?php checked( isset ( $get_language_choices['gl'] ) ); ?> /> Galician</div>                    
                    <div><input type="checkbox" name="ka" value="1"<?php checked( isset ( $get_language_choices['ka'] ) ); ?> /> Georgian</div>                    
                    <div><input type="checkbox" name="de" value="1"<?php checked( isset ( $get_language_choices['de'] ) ); ?> /> German</div>                  
                    <div><input type="checkbox" name="el" value="1"<?php checked( isset ( $get_language_choices['el'] ) ); ?> /> Greek</div>                    
                    <div><input type="checkbox" name="gu" value="1"<?php checked( isset ( $get_language_choices['gu'] ) ); ?> /> Gujarati</div>       
                    <div><input type="checkbox" name="ht" value="1"<?php checked( isset ( $get_language_choices['ht'] ) ); ?> /> Haitian</div>                     			
                    <div><input type="checkbox" name="iw" value="1"<?php checked( isset ( $get_language_choices['iw'] ) ); ?> /> Hebrew</div>                  
                    <div><input type="checkbox" name="hi" value="1"<?php checked( isset ( $get_language_choices['hi'] ) ); ?> /> Hindi</div>          
                    <div><input type="checkbox" name="hu" value="1"<?php checked( isset ( $get_language_choices['hu'] ) ); ?> /> Hungarian</div>               
                    <div><input type="checkbox" name="is" value="1"<?php checked( isset ( $get_language_choices['is'] ) ); ?> /> Icelandic</div>                 
                    <div><input type="checkbox" name="id" value="1"<?php checked( isset ( $get_language_choices['id'] ) ); ?> /> Indonesian</div>                   
                    <div><input type="checkbox" name="ga" value="1"<?php checked( isset ( $get_language_choices['ga'] ) ); ?> /> Irish</div>  
				</div>
				  
				  <div class="languages" style="width:25%; float:left">
                    <div><input type="checkbox" name="it" value="1"<?php checked( isset ( $get_language_choices['it'] ) ); ?> /> Italian</div>                      
                    <div><input type="checkbox" name="ja" value="1"<?php checked( isset ( $get_language_choices['ja'] ) ); ?> /> Japanese</div>                   
                    <div><input type="checkbox" name="kn" value="1"<?php checked( isset ( $get_language_choices['kn'] ) ); ?> /> Kannada</div>                     
                    <div><input type="checkbox" name="ko" value="1"<?php checked( isset ( $get_language_choices['ko'] ) ); ?> /> Korean</div>                    
                    <div><input type="checkbox" name="lo" value="1"<?php checked( isset ( $get_language_choices['lo'] ) ); ?> /> Lao</div>                     
                    <div><input type="checkbox" name="la" value="1"<?php checked( isset ( $get_language_choices['la'] ) ); ?> /> Latin</div>                    
                    <div><input type="checkbox" name="lv" value="1"<?php checked( isset ( $get_language_choices['lv'] ) ); ?> /> Latvian</div>                    
                    <div><input type="checkbox" name="lt" value="1"<?php checked( isset ( $get_language_choices['lt'] ) ); ?> /> Lithuanian</div>                  
                    <div><input type="checkbox" name="mk" value="1"<?php checked( isset ( $get_language_choices['mk'] ) ); ?> /> Macedonian</div>                    
                    <div><input type="checkbox" name="ms" value="1"<?php checked( isset ( $get_language_choices['ms'] ) ); ?> /> Malay</div>       
                    <div><input type="checkbox" name="mt" value="1"<?php checked( isset ( $get_language_choices['mt'] ) ); ?> /> Maltese</div>                     			
                    <div><input type="checkbox" name="no" value="1"<?php checked( isset ( $get_language_choices['no'] ) ); ?> /> Norwegian</div>                  
                    <div><input type="checkbox" name="fa" value="1"<?php checked( isset ( $get_language_choices['fa'] ) ); ?> /> Persian</div>          
                    <div><input type="checkbox" name="pl" value="1"<?php checked( isset ( $get_language_choices['pl'] ) ); ?> /> Polish</div>               
                    <div><input type="checkbox" name="pt" value="1"<?php checked( isset ( $get_language_choices['pt'] ) ); ?> /> Portuguese</div>                 
                    <div><input type="checkbox" name="ro" value="1"<?php checked( isset ( $get_language_choices['ro'] ) ); ?> /> Romanian</div>                   
                    <div><input type="checkbox" name="ru" value="1"<?php checked( isset ( $get_language_choices['ru'] ) ); ?> /> Russian</div>  
				</div>
				  
				  <div class="languages" style="width:25%; float:left">
 					<div><input type="checkbox" name="sr" value="1"<?php checked( isset ( $get_language_choices['sr'] ) ); ?> /> Serbian</div>                      
                    <div><input type="checkbox" name="sk" value="1"<?php checked( isset ( $get_language_choices['sk'] ) ); ?> /> Slovak</div>                   
                    <div><input type="checkbox" name="sl" value="1"<?php checked( isset ( $get_language_choices['sl'] ) ); ?> /> Slovenian</div>                     
                    <div><input type="checkbox" name="es" value="1"<?php checked( isset ( $get_language_choices['es'] ) ); ?> /> Spanish</div>                    
                    <div><input type="checkbox" name="sw" value="1"<?php checked( isset ( $get_language_choices['sw'] ) ); ?> /> Swahili</div>                     
                    <div><input type="checkbox" name="sv" value="1"<?php checked( isset ( $get_language_choices['sv'] ) ); ?> /> Swedish</div>                    
                    <div><input type="checkbox" name="ta" value="1"<?php checked( isset ( $get_language_choices['ta'] ) ); ?> /> Tamil</div>                    
                    <div><input type="checkbox" name="te" value="1"<?php checked( isset ( $get_language_choices['te'] ) ); ?> /> Telugu</div>                  
                    <div><input type="checkbox" name="th" value="1"<?php checked( isset ( $get_language_choices['th'] ) ); ?> /> Thai</div>                    
                    <div><input type="checkbox" name="tr" value="1"<?php checked( isset ( $get_language_choices['tr'] ) ); ?> /> Turkish</div>       
                    <div><input type="checkbox" name="uk" value="1"<?php checked( isset ( $get_language_choices['uk'] ) ); ?> /> Ukranian</div>                     			
                    <div><input type="checkbox" name="ur" value="1"<?php checked( isset ( $get_language_choices['ur'] ) ); ?> /> Urdu</div>                  
                    <div><input type="checkbox" name="vi" value="1"<?php checked( isset ( $get_language_choices['vi'] ) ); ?> /> Vietnamese</div>          
                    <div><input type="checkbox" name="cy" value="1"<?php checked( isset ( $get_language_choices['cy'] ) ); ?> /> Welsh</div>               
                    <div><input type="checkbox" name="yi" value="1"<?php checked( isset ( $get_language_choices['yi'] ) ); ?> /> Yiddish</div>                 
				  </div>
			<div style="clear:both"></div>
			
				</td><td>
				
				</td>
			  </tr>
				  
              <tr>
                <td>Display options:</td>
                <td>
                <select name="googlelanguagetranslator_display" id="googlelanguagetranslator_display" style="width:100px;">
                <option value="Vertical" <?php if(get_option('googlelanguagetranslator_display')=='Vertical'){echo "selected";}?>>Vertical</option>
                <option value="Horizontal" <?php if(get_option('googlelanguagetranslator_display')=='Horizontal'){echo "selected";}?>>Horizontal</option>
                </select>                </td>
              </tr>

              <tr>
                <td>Show Google Toolbar?</td>
                <td>
                <select name="googlelanguagetranslator_toolbar" id="googlelanguagetranslator_toolbar" style="width:100px;">
                <option value="Yes" <?php if(get_option('googlelanguagetranslator_toolbar')=='Yes'){echo "selected";}?>>Yes</option>
                <option value="No" <?php if(get_option('googlelanguagetranslator_toolbar')=='No'){echo "selected";}?>>No</option>
                </select>                </td>
              </tr>

              <tr>
                <td>Show Google Branding?</td>
                <td>
                <select name="googlelanguagetranslator_showbranding" id="googlelanguagetranslator_showbranding" style="width:100px;">
                <option value="Yes" <?php if(get_option('googlelanguagetranslator_showbranding')=='Yes'){echo "selected";}?>>Yes</option>
                <option value="No" <?php if(get_option('googlelanguagetranslator_showbranding')=='No'){echo "selected";}?>>No</option>
                </select>                </td>
              </tr>
              
              <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="button-primary" value="<?php _e('Update Option')?>" name="googlelanguagetranslator_update_options" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			  
			  <tr>
				<td>Copy/Paste This Shortcode:</td>
                <td>
				  [google-translator]
                </td>
              </tr>
              
          </table>
<input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="googlelanguagetranslator_active" />
        </form>
       </div> 
       </div>
       
       <div id="poststuff" class="metabox-holder" style="float: left; width: 48%;">
      <div class="postbox">
      <h3>Another Plugin You Might Like:</h3>
	  <table class="form-table" width="100%">
       	  <tr><td align="left" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                
                <tr>
                  <td>
                      <div>
                        <div>
                          <div>
                            <a href="http://wordpress.org/extend/plugins/malware-finder/" target="_blank">Malware Finder</a>
                            This plugin enables you to look inside all your WordPress files at once to find malicious code.<br>
                          </div>
                        </div>
                      </div>
                  </td>
                </tr>

                <tr>
                  <td><strong>You can also download this plugin at <a href="http://www.studio88design.com" target="_blank">www.studio88design.com</a></strong></td>
                </tr>
              </table>
            </td>
        	</tr>
        </table></div></div>
       <div id="poststuff" class="metabox-holder" style="float: right; width: 48%;">
       <div class="postbox">
      <h3>Please Consider A Donation</h3>
      <div class="inside">
      If you like this plugin and find it useful, help keep this plugin actively developed by clicking the donate button <br /><br />
       <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="robertmyrick@hotmail.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Support Studio 88 Design and help us bring you more Wordpress goodies!  Any donation is kindly appreciated.  Thank you!">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

                  <br />
               <br />
            </div>
         </div>
	  </div>
	</div>
      
	<?php
}

function googlelanguagetranslator_included_languages() {
  if ( get_option('googlelanguagetranslator_language_option')=='specific') { 
	   $get_language_choices = get_option ('language_display_settings');
	     //print_r($get_language_choices);
	      
	     foreach ($get_language_choices as $key=>$value) {
	           if($value == 1) {
				  $items[] = $key;
	            }
			}
	     $comma_separated = implode(",",array_values($items));
	
	if ( get_option('googlelanguagetranslator_display') == 'Vertical') {
	     $lang .= 'includedLanguages:\''.$comma_separated.'\'';
	     return $lang;
	} elseif ( get_option('googlelanguagetranslator_display') == 'Horizontal') {
	     $lang .= 'includedLanguages:\''.$comma_separated.'\',';
	     return $lang;
	}
  } 
}
  
function googlelanguagetranslator_vertical(){ 
    $language_choices = googlelanguagetranslator_included_languages();
	if(get_option('googlelanguagetranslator_active')==1){
		$str.='<div id="google_translate_element"></div><script>
	function googleTranslateElementInit() {
	  new google.translate.TranslateElement({
		pageLanguage: \''.get_option('googlelanguagetranslator_language').'\', '.$language_choices.'
	  }, \'google_translate_element\');
	}
	</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
		return '<div>'.$str.'</div>';
	}
}

function googlelanguagetranslator_horizontal(){
    $language_choices = googlelanguagetranslator_included_languages();
	if(get_option('googlelanguagetranslator_active')==1){
		$str.='<div id="google_translate_element"></div><script>
	function googleTranslateElementInit() {
	  new google.translate.TranslateElement({
		pageLanguage: \''.get_option('googlelanguagetranslator_language').'\', '.$language_choices.' layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
	  }, \'google_translate_element\');
	}
	</script><script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>';
		return '<div>'.$str.'</div>';
	}
}

function googlelanguagetranslator_toolbar_yes(){
  if(get_option('googlelanguagetranslator_active')==1) { ?>
<style type="text/css">
#google_translate_element {color: transparent;}
.goog-te-gadget .goog-te-combo {margin: 2px 0px !important;}
.goog-tooltip {display: none !important;}
.goog-tooltip:hover {display: none !important;}
.goog-text-highlight {background-color: transparent !important; border: none !important;box-shadow: none !important;}
</style>
<?php
  }
}

function googlelanguagetranslator_toolbar_no(){
  if(get_option('googlelanguagetranslator_active')==1) { ?>
<style type="text/css">
.goog-te-banner-frame{visibility:hidden !important;}
body {top:0px !important;}
</style>
<?php
  }
}


function googlelanguagetranslator_showbranding_yes() {
  if(get_option('googlelanguagetranslator_active')==1) { ?>
<style type="text/css">
.goog-te-gadget .goog-te-combo {margin: 2px 0px !important;}
.goog-tooltip {display: none !important;}
.goog-tooltip:hover {display: none !important;}
.goog-text-highlight {background-color: transparent !important; border: none !important; box-shadow: none !important;}
</style>
<?php
  }
}

function googlelanguagetranslator_showbranding_no() {
  if(get_option('googlelanguagetranslator_active')==1) { ?>
<style type="text/css">
#google_translate_element a {display: none !important;}
.goog-te-gadget {color:transparent !important;}
.goog-te-gadget .goog-te-combo {margin: 2px 0px !important;}
.goog-tooltip {display: none !important;}
.goog-tooltip:hover {display: none !important;}
.goog-text-highlight {background-color: transparent !important; border: none !important; box-shadow: none !important;}
</style>
<?php
  }
}
?>