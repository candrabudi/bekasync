 <div class="sidebar">
     <div class="brand-logo" style="background: none">
         <a class="full-logo" href="index.html">
             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d7/Coat_of_arms_of_Bekasi.png/640px-Coat_of_arms_of_Bekasi.png" width="42" alt="">
         </a>
     </div>
     <div class="menu">
         <ul>
             <li>
                 <a href="{{ route('dashboard.call_center.index') }}">
                     <span><i class="fi fi-rr-dashboard"></i></span>
                     <span class="nav-text">Dashboard</span>
                 </a>
             </li>
             <li>
                 <a href="{{ route('incidents.index') }}">
                     <span><i class="fi fi-rr-phone-call"></i></span>
                     <span class="nav-text">112</span>
                 </a>
             </li>
             <li>
                 <a href="/omni-channel">
                     <span><i class="fi fi-rr-comment-alt-dots"></i></span>
                     <span class="nav-text">Omni</span>
                 </a>
             </li>
             <li>
                 <a href="/settings">
                     <span><i class="fi fi-rr-settings-sliders"></i></span>
                     <span class="nav-text">Pengaturan</span>
                 </a>
             </li>
         </ul>

     </div>
 </div>
