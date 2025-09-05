 <div class="home-boxes">
   <div>
     <a href="<?php echo home_url() ?>"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/dclmn-alt-2.png" style="height: 160px;"></a>
   </div>
   <div>
     <h4><a href="/voting/">Voter Center</a></h4>
     <div class="padding">
       <ul>
         <li><a href="<?php echo home_url('voting/#register') ?>">Register to Vote</a></li>
         <li><a href="<?php echo home_url('voting/#mail-in') ?>">Get Mail-in Ballot</a></li>
         <li><a href="<?php echo home_url('voting/#polling-place') ?>">Find Polling Place</a></li>
         <li><a href="<?php echo home_url('voting/#status') ?>">Check Voter Status</a></li>

         <?php /*<li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationApplication.aspx" target="_blank">Register to Vote</a></li>
         <li><a href="https://www.pa.gov/en/agencies/vote.html" target="_blank">Get Mail-in Ballot</a></li>
         <li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationStatus.aspx" target="_blank">Find Polling Place</a></li>
         <li><a href="https://www.pavoterservices.pa.gov/Pages/VoterRegistrationStatus.aspx" target="_blank">Check Voter Status</a></li> */?>
       </ul>
     </div>
   </div>
   <div>
     <h4><a href="/get-involved/">Get Involved</a></h4>
     <div class="padding">
       <ul>
         <li><a href="<?php echo home_url('get-involved/') ?>">Volunteer</a></li>
         <li><a href="<?php echo home_url('elected-officials/') ?>">Elected Officials</a></li>
         <li><a href="<?php echo home_url('committee-people/') ?>">Committee People</a></li>
         <li><a href="<?php echo home_url('get-involved/') ?>">Donate</a></li>
       </ul>
     </div>
   </div>
   <div>
     <h4><a href="/events/">Events</a></h4>
     <div class="padding">
       <ul>
         <li><a href="<?php echo home_url('events/category/meetings/') ?>">Meetings</a></li>
         <li><a href="<?php echo home_url('events/category/campaign-dates/') ?>//">Campaign Dates</a></li>
         <li><a href="<?php echo home_url('events/category/election-dates/') ?>//">Election Dates</a></li>
         <li><a href="<?php echo home_url('events/') ?>">More</a></li>
       </ul>
     </div>
   </div>
 </div>