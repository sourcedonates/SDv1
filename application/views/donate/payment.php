<?php
/*
 *  SourceDonates - A donator interface and management system for Source game servers and various Forum Systems.
 *  Copyright (C) 2012 Werner "Arrow768" Maisl
 *
 *  This Software may only be hosted by the copyright holder
 *  You are not allowed to copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software
 *  You are not allowed to host this Software on your own
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 *  INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 *  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 *  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 *  If you have any questions about this Software, fell free to send me a email:
 *  arrow768 AT sourcedonates DOT com
 */
?>

<div class="container clearfix content" role="main">
      <div class="block clearfix">
        <section class="progress float-right  clearfix">
          <div class="step one done block"><a href="<? echo base_url('index.php/donate');?>"><span class="bold">1.</span> <?php echo $this->lang->line('donate_choose_plan');?></a></div>
          <div class="step two done block"><a href="<? echo base_url('index.php/donate/details/'.$slug);?>"><span class="bold">2.</span> <?php echo $this->lang->line('donate_confirm_plan');?></a></div>
          <div class="step three done block last"><a href="#"><span class="bold">3.</span> <?php echo $this->lang->line('donate_complete_payment');?></a></div>
        </section>
      </div>
    
      <section class="payment clearfix">

        <article class="summary one evenheight">
          <h3>Summary</h3>
          <div class="text-block">
              <span class="bold"><?=$plan->plan_name?><?=$site_plan_suffix?></span>
              <ul class="clean-list">
                <li><?=$plan->plan_price?> <?=$community_currency_long?></li>
                <li>
                <?php if($plan->plan_time === "0"): ?>
                    &#x221E; Days
                <?php else: ?>
                  <?php if($plan->plan_time % 30 === 0): ?>
                      <?php if($plan->plan_time/30 === 1): ?>
                          <?php echo $plan->plan_time/30?> <?php echo $this->lang->line('donate_month');?>
                      <?php else: ?>
                          <?php echo $plan->plan_time/30?> <?php echo $this->lang->line('donate_months');?>
                      <?php endif; ?>
                  <?php else: ?>
                      <?=$plan->plan_time?> <?php echo $this->lang->line('donate_days');?>
                  <?php endif;?>
                <?php endif; ?>
                </li>
                <?php /*if ($hats_use === '1'): ?><li><?=$hats_num?> <?=$hats_name?></li><?php endif; */?>
                <?php /*if ($skins_use === '1'): ?><li><?=$skins_num?> <?=$skins_name?></li><?php endif; */?>
              </ul>
          </div>

          <div class="text-block">
              <span class="bold"><?php echo $this->lang->line('donate_donating_to');?>:</span>
              <ul class="clean-list">
                <li><?=$community_name?></li>
                <li><?=$community_email?></li>
                <li><?=$community_url?></li>
              </ul>
          </div>

          <div class="text-block">
              <span class="bold"><?php echo $this->lang->line('donate_description');?>:</span>
              <?=$plan->plan_description?>
          </div>

        </article>
        <article class="ringen two evenheight">
          
        </article>
        <article class="fields form three evenheight">
          <form id="sourcedonatesform" class="validate" action="<? echo base_url('/index.php/process/donate');?>" method="post">

            <label for="nickname"><?php echo $this->lang->line('donate_nickname');?>:</label> 
            <div class="icon nickname">
              <input class="nickname" type="text" id="nickname" name="nickname" placeholder="<?php echo $this->lang->line('donate_nickname_desc');?>" <?php if($got_user_data === "1")echo 'value="'.$forum_username.'"'; ?>/>
            </div>

            <div class="clearfix">
              <label for="steamid"><?php echo $this->lang->line('donate_steamid');?>:</label> 
              <div class="icon steamid">
                <input  type="steamID validate" id="steamid" name="steamid" placeholder="STEAM_0:1:0000000" value="<?=$steamid?>"/>
              </div>
              <div class="avatar">
                <span  id="steamid-container" class="image-wrap" >
                  <img id="steamid-avatar" src="<? echo base_url('img/avatar.jpg');?>">
                </span>
              </div>

              </div>

            <label for="email"><?php echo $this->lang->line('donate_email');?>:</label> 
            <span class="attention">
              <p><?php echo $this->lang->line('donate_email_warning');?></p>
              <div class="icon email">
                <input class="email" type="email" id="email" name="email" placeholder="<?php echo $this->lang->line('donate_email_desc');?>" <?php if($got_user_data === "1")echo 'value="'.$forum_email.'"'; ?>/>
              </div>
            </span>

            <label for="payment-provider"><?php echo $this->lang->line('donate_payment_method');?>:</label>
            <div class="icon">
              <select name="payment-provider" class="select" id="payment-provider" for="payment-provider">
                <option value=""><?php echo $this->lang->line('donate_payment_clickme');?></option>
                <?php if($use_paypal === "1"): ?><option value="paypal">paypal</option><?php endif; ?>
                <?php if($use_paygol === "1"): ?><option value="paygol">paygol</option><?php endif; ?>
                <?php if($use_paysafe === "1"): ?><option value="paysafe">paysafe</option><?php endif; ?>
                <?php if($use_ideal === 1):?>
                    <option disabled="disabled" value="iDeal"><?php echo $this->lang->line('donate_payment_ideal');?>:</option>
                    <option disabled="disabled" value="#####">############</option>
                    <?php foreach($banks as $bank_id => $bank_name):?>
                        <option value="<?php echo htmlspecialchars($bank_id) ?>">--<?php echo htmlspecialchars($bank_name) ?></option>
                    <?php endforeach;?>
                <?php endif;?>
              </select>
            </div>

            <input type="hidden" name="planid" value="<?=$plan->plan_id?>">

            <div class="tos">
              <input class="checkbox" type="checkbox" id="tos" name="tos" value="tos">
              <label for="tos">I agree to the Terms of Service <a class="tosview" href="#">(view here)</a></label>
            </div>
            <input class="submit" type="submit" value="<?php echo $this->lang->line('donate_proceed');?>" />
            <a class="gift button" href=""></a>
          </form>
          <div class="steam"></div>
        </article>
  
      </section>
    </div> <!-- end container -->


    <!-- Gift lightbox -->
    <div class="gift-container form clearfix">
      <div class="gift-icon"></div>
      <div class="divider"></div>
      <form id="giftform"  class="validate" action="<? echo base_url('index.php/process/donate');?>" method="post">
        <label for="nickname-gift"><?php echo $this->lang->line('donate_friend_nickname');?>:</label> 
        <div class="icon nickname">
          <input class="nickname" type="text" id="nickname-gift" name="nickname" placeholder="<?php echo $this->lang->line('donate_friend_nickname_desc');?>" />
        </div>

        <div class="clearfix">
          <label for="steamid-gift"><?php echo $this->lang->line('donate_friend_steamid');?>:</label> 
          <div class="icon steamid">
            <input  type="steamID validate" id="steamid-gift" name="steamid" placeholder="STEAM_0:1:0000000" />
          </div>
          <div class="avatar">
            <span  id="steamid-container" class="image-wrap" >
              <img id="steamid-avatar-gift" src="<? echo base_url('img/avatar.jpg');?>">
            </span>
          </div>
        </div>

        <label for="email-gift"><?php echo $this->lang->line('donate_friend_email');?>:</label> 
        <div class="icon email">
          <input class="email" type="email" id="email-gift" name="email" placeholder="<?php echo $this->lang->line('donate_friend_email_desc');?>" />
        </div>

       <label for="payment-provider-gift"><?php echo $this->lang->line('donate_payment_method');?>:</label>
        <div class="icon">
          <select name="payment-provider" class="select" id="payment-provider-gift" for="payment-provider">
            <option value=""><?php echo $this->lang->line('donate_payment_clickme');?></option>
            <?php if($use_paypal === "1"): ?><option value="paypal">paypal</option><?php endif; ?>
            <?php if($use_paygol === "1"): ?><option value="paygol">paygol</option><?php endif; ?>
            <?php if($use_paysafe === "1"): ?><option value="paysafe">paysafe</option><?php endif; ?>
            <?php if($use_ideal === 1):?>
                <option disabled="disabled" value="iDeal"><?php echo $this->lang->line('donate_payment_ideal');?>:</option>
                <option disabled="disabled" value="#####">############</option>
                <?php foreach($banks as $bank_id => $bank_name):?>
                    <option value="<?php echo htmlspecialchars($bank_id) ?>">--<?php echo htmlspecialchars($bank_name) ?></option>
                <?php endforeach;?>
            <?php endif;?>
          </select>
        </div>


        <input type="hidden" name="planid" value="<?=$plan->plan_id?>">

        <div class="tos">
          <input class="checkbox" type="checkbox" id="tosgift" name="tosgift" value="tosgift">
          <label for="tosgift">I agree to the Terms of Service <a class="tosview" href="#">(view here)</a></label>
        </div>

        <input id="proceed" class="submit" type="submit" value="proceed  >>" />
      </form>
    </div> <!-- end giftform -->

    <section class="toslightbox">
      <h2>Terms of Service</h2>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
      <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>
    </section>

    <script>
    $(document).ready(function () {
      $('#sourcedonatesform').isHappy({
        fields: {
          // reference the field you're talking about, probably by `id`
          // but you could certainly do $('[name=name]') as well.
          '#nickname': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_nickname');?>'
          },
          '#steamid': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_steamid');?>',
            test: happy.steamid // this can be *any* function that returns true or false
          },
          '#payment-provider': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_provider');?>'
          },
          '#email': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_email');?>',
            test: happy.email // this can be *any* function that returns true or false
          },
          '#tos': {
            required: true,
            message: 'you need to agree with our Terms of Service'
          }
        }
      });

      $('#giftform').isHappy({
        fields: {
          // reference the field you're talking about, probably by `id`
          // but you could certainly do $('[name=name]') as well.
          '#nickname-gift': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_friend_nickname');?>'
          },
          '#steamid-gift': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_friend_steamid');?>',
            test: happy.steamid // this can be *any* function that returns true or false
          },
          '#payment-provider-gift': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_friend_provider');?>'
          },
          '#email-gift': {
            required: true,
            message: '<?php echo $this->lang->line('donate_error_friend_email');?>',
            test: happy.email // this can be *any* function that returns true or false
          }
        }
      });

      //Check if checkbox is checked before submitting
      $('#sourcedonatesform').submit(function() {
          if ($('input:checkbox', this).is(':checked')){
              // everything's fine...
          } else {
              alert('You have to agree to the Terms of Service');
              return false;
          }
      });

      $('#giftform').submit(function() {
          if ($('input:checkbox', this).is(':checked')){
              // everything's fine...
          } else {
              alert('You have to agree to the Terms of Service');
              return false;
          }
      });

    });

    </script>