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
          <div class="step one done block"><a href="#"><span class="bold">1.</span> <?php echo $this->lang->line('donate_choose_plan');?></a></div>
          <div class="step two block"><a href="#"><span class="bold">2.</span> <?php echo $this->lang->line('donate_confirm_plan');?></a></div>
          <div class="step three block last"><a href="#"><span class="bold">3.</span> <?php echo $this->lang->line('donate_complete_payment');?></a></div>
        </section>
      </div>
    
      <section class="packages clearfix">
          <?php $i=0; ?>
          <?php foreach($plans as $row):?>
              <?php $last=''; $i+=1;?>
              <?php if($i === 4) $last='last'; ?>
              <article>
                  <a href="<?php echo base_url('index.php/donate/details/'.$row->plan_id)?>" class="package">
                    <span class="tooltip"><?=$row->plan_description?></span>
                      <div class="absolute-center">
                          <h2><?=$row->plan_name?></h2>
                          <?php if($row->plan_time === '0'):?>
                              <p>&#x221E;</p>
                          <?php else:?>
                              <?php if($row->plan_time % 30 === 0): ?>
                                  <?php if($row->plan_time/30 === 1): ?>
                                      <p><?php echo $row->plan_time/30?> <?php echo $this->lang->line('donate_month');?></p>
                                  <?php else: ?>
                                      <p><?php echo $row->plan_time/30?> <?php echo $this->lang->line('donate_months');?></p>
                                  <?php endif; ?>
                              <?php else: ?>
                                  <p><?=$row->plan_time?> <?php echo $this->lang->line('donate_days');?></p>
                              <?php endif;?>
                          <?php endif;?>
                          <span class="price"><?=$currency?><?=$row->plan_price?></span>
                      </div>
                      <span class="overflow"><div class="color" style="background-color: <?=$row->plan_color?>"></div></span>
                  </a>
              </article>
            
          <?php endforeach; ?>
      </section>
    </div>