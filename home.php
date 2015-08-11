<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CraftNanny</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    
  </head>
  <body>
    
      <div class="row">
        <div class="large-12 columns top_bar">
         <span style="font-weight:bold;font-size:36px;color:#1b9bff">
          
           CraftNanny
         </span>
        </div>
 
      </div>
      
      <div class="row">
        <div class="large-12 columns thin_bar">
         
        </div>
 
      </div>


      <div class="row">  
      <div class="large-3 columns">
        <p>
          <div id='cssmenu'>
            <ul>
               <li class='active'><a href='home.php'><span>Home</span></a></li>
             </ul>
          </div>
          <soan id="menu_headers">Monitoring</span>
          <div id='cssmenu'>
               
             <ul>  
               <li><a href='tracking.php'><span>Player Tracking</span></a></li>
               <li><a href='energy.php'><span>Energy Storage</span></a></li>
               <li><a href='fluid.php'><span>Fluid Storage</span></a></li>
               </ul>
          </div>
          <soan id="menu_headers">Controls</span>
          <div id='cssmenu'>
               
             <ul>  
               <li><a href='redstone.php'><span>Redstone Controls</span></a></li>
               <li><a href='rednet.php'><span>Rednet Controls</span></a></li>
               <li><a href='custom.php'><span>Custom Module</span></a></li>
               
               </ul>
          </div>
          <soan id="menu_headers">Admin</span>
          <div id='cssmenu'>
               
             <ul>  
               <li class='last'><a href='rules.php'><span>Set Rules</span></a></li>
               <li class='last'><a href='notifications.php'><span>Set Notifications</span></a></li>
               <li class='last'><a href='logout.php'><span>Log out</span></a></li>
            </ul>
          </div>
   
      </div>
        
      
      <div class="large-9 columns">
        <div class="row">
          
          
          
    
        <h3 style="color:#0099FF" id="welcome"></h3>
        <span style="font-weight:bold;font-size:16px;color:red">Setup modules <a href="setup.php">here</a></span>
       
          <!--<div class="large-12 columns notifications" id="">
             <strong style="color:#0099FF">Active Rules:</strong>
             <p>
             Coming soon. 
             Examples: energy storage reaches 0%, set redstone module output
          </div>
        </div> 
        <p>
  		  <div class="row">
          <div class="large-12 columns notifications" id="">
             <strong style="color:#0099FF">Email Notifications:</strong>
             <p>
             Coming soon. 
             Set email alerts for certain events. Energy or fluid reach x% or player x enters base.
          </div>
        </div>-->
        
        <p>
          
  		  <div class="row">
        <div class="large-4 columns modules" id="sensor_modules">
           <strong style="color:#0099FF">Player Modules:</strong>
           <div class="no_modules" id="no_player_modules">
            <span>No Player Modules Connected</span>
          </div>
        </div>
        
        <div class="large-4 columns modules" id="energy_modules">
          <strong style="color:#0099FF">Energy Modules:</strong>
          <div class="no_modules">
            <span>No Energy Modules Connected</span>
          </div>
        </div>
        
        <div class="large-4 columns modules" id="fluid_modules">
          <strong style="color:#0099FF">Fluid Modules:</strong>
          <div class="no_modules" id = "no_fluid_modules">
            <span>No Fluid Modules Connected</span>
          </div>
         </div>
      
        </div>
        
        <p>
          
       <div class="row">
        <div class="large-4 columns modules" id="redstone_modules">
           <strong style="color:#0099FF">Redstone Modules:</strong>
           <div class="no_modules" id="no_redstone_modules">
            <span>No Redstone Modules Connected</span>
          </div>
        </div>
        
        <div class="large-4 columns modules" id="energy_modules">
          <strong style="color:#0099FF">Rednet Modules</strong>
          <div class="no_modules" id="no_player_modules">
            <span>No Rednet Modules Connected</span>
          </div>
        </div>
        
        <div class="large-4 columns modules" id="fluid_modules">
         <strong style="color:#0099FF">stub</strong>
         </div>
      
        </div>
        
     
      
     
     
                
        </div>
      </div>
        

     

    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
    <script src="js/login_check.js"></script>
    <script src="js/home.js"></script>
	
  </body>
</html>