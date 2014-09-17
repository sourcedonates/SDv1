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
      <h2 class="float-left package-titel" style="border-bottom-color: <?=$plan->plan_color?>"><?=$plan->plan_name?></h2>
      <div class="float-rigth block clearfix">
        <section class="progress float-right  clearfix">
          <div class="step one done block"><a href="<? echo base_url('index.php/donate');?>"><span class="bold">1.</span> <?php echo $this->lang->line('donate_choose_plan');?></a></div>
          <div class="step two done block"><a href="#"><span class="bold">2.</span> <?php echo $this->lang->line('donate_confirm_plan');?></a></div>
          <div class="step three block last"><a href="#"><span class="bold">3.</span> <?php echo $this->lang->line('donate_complete_payment');?>t</a></div>
        </section>
      </div>
      
      
      <section class="package-overview clearfix">
          <?php foreach($category as $cat): ?>
          <article>
              <h3><?=$cat->category_name ?></h3>
              <?php foreach($items as $im): ?>
                <?php $item_plan_array = explode(",",$im->plan_id); ?>
                <?php if(in_array($plan->plan_id,$item_plan_array)):?>
                  <?php if($im->category_id === $cat->category_id): ?>
                      <div><?=$im->item_name?><?php if($im->item_picture != ""):?><span><img src="<? echo base_url($im->item_picture)?>" alt="<?=$im->item_name?>"> </span><?php endif;?></div>
                  <?php endif;?>
                <?php endif;?>
              <?php endforeach;?>
          </article>
          <?php endforeach; ?>

      </section>
      <a class="button style-two float-right vink" href="<? echo base_url('index.php/donate/payment/'.$plan->plan_id)?>"><span></span><?php echo $this->lang->line('donate_continue_payment');?></a>
    </div>
