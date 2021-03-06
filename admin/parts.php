<?php
session_start();
include_once ('../auth.php');
require_once('../connect.php');
$result="";$items="";
//check if form posted
if(isset($_POST['submit'])){
    
    //insert query- to feed posted tblitemmaster
    $insertQuery = "INSERT INTO `tblitemmaster` (`EquipmentID`,`PartNumber`, `MaxUnit`,`ItemDescription`,`ApprovalReq`) VALUES 
    ('".$mysqli->real_escape_string($_POST['EquipmentID'])."',
     '".$mysqli->real_escape_string($_POST['PartNumber'])."',
     '".$mysqli->real_escape_string($_POST['MaxUnit'])."',
     '".$mysqli->real_escape_string($_POST['ItemDescription'])."',
     '".$mysqli->real_escape_string($_POST['Active'])."');";
    
    $inserted = $mysqli->query($insertQuery);//run created query to feed data into mysql database
    if(!$inserted){
        die('Please contact your developer !! <br><br>'.$mysqli->error.' '.$insertQuery);
    }
    $msg='Part details have been added successfully.';
}


//fetch data for the line item
$qry="SELECT * FROM  `tblitemmaster` , tblequipment WHERE  `tblitemmaster`.`EquipmentID` = tblequipment.`EquipmentID`";
$result_data = $mysqli->query($qry) or die($mysqli->error.' '.$qry);
if($result_data->num_rows<=0){$msg='No matched items in the database!!!';$items='NOT_FOUND';}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<title> Parts Ordering:: Parts </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" type="text/css"  href="../css/smart-forms.css">
    <link rel="stylesheet" type="text/css"  href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css"  href="../css/as.css">

    <!--[if lte IE 9]>
    	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>    
        <script type="text/javascript" src="js/jquery.placeholder.min.js"></script>
    <![endif]-->    
    
    <!--[if lte IE 8]>
        <link type="text/css" rel="stylesheet" href="css/smart-forms-ie8.css">
    <![endif]-->
    <script src="../js/jquery.min.js" type="text/javascript"></script>
    <script src="../js/plugins.js" type="text/javascript"></script>
    <script src="j../s/scripts.js" type="text/javascript"></script>
    <style>table#pd-table thead th, table#pd-table tbody td {padding: 0.5em 0.4em .5em .4em;}.wrap-2{max-width: 950px;}.smart-forms button[disabled]{opacity: 1!important;background-color: #319ACD!important;}
    .smart-forms .gui-input.extra-light{font-size:15px;border: 1px solid black!important;color: black!important;}</style>
       
</head>

<body class="woodbg">

	<div class="smart-wrap">
    	<div class="smart-forms smart-container wrap-2">
    
            <div class="form-header header-primary" id="menu">
            	<h4><i class="fa fa-flask"></i><a href="dashboard.php" class="site-title">Parts Ordering</a>:: Admin </h4>
                <a class="toggle" href="#"><i class="fa fa-bars"></i>Menu</a>
    			<ul id="menuse">
                    <li><a href="user-account.php" class="">Add User</a></li>
                    <li><a href="parts.php" class="">Parts List</a></li>
                    <li><a href="approved-program-director.php" class="">Approved Program Directors</a></li>
                    <li><a href="approved-orders.php" class="">Approved Orders</a></li>
                    <li><a href="#" onclick="$('#logoutForm').submit();" class="">Logout</a><form action="index.php" style="display: none;" method="POST" id="logoutForm"><input type="hidden" name="logout" value="true" /></form></li>
                    <!--<li><a href="#">|| <?php echo $_SESSION['USER_INFO']['FirstName'].' -'.$_SESSION['USER_INFO']['SiteName'];?></a></li>-->
                    
    			</ul>
            </div><!-- end .form-header section -->
   	    
            
            <form method="post" action="" id="form-ui" enctype="multipart/form-data" onsubmit="return validate_lineItem();">
            	<div class="form-body">
                    
                    <?php if(isset($msg)){ ?>
                        <div class="section notification-close">
                        
                            <div class="notification alert-success spacer-t10">
                                <p><?php echo $msg;?></p>
                                <a href="javascript:void(0);" class="close-btn" onclick="document.getElementsByClassName('notification-close')[0].style.display='none';">&times;</a>                                  
                            </div><!-- end .notification section -->
                                               
                        </div>
                    <?php } ?>
                    

                    
                    <div class="spacer-t40 spacer-b40">
                    	<div class="tagline"><span>::Add Parts details </span></div><!-- .tagline -->
                    </div>
                    
                    <div class="frm-row">
                    
                        
                        <div class="colm colm6">
                        
                            <div class="section">
                                <label for="EquipmentID" class="field-label">Equipment</label>
                                <label for="EquipmentID" class="field select">
                                <?php
                                //fetch soil types from database
                                $qry="SELECT * FROM `tblequipment`";
                                $result_equipment = $mysqli->query($qry);//run created query to feed data into mysql database
                                if(!$result_equipment){
                                    echo('There is no Equipment in the database.');
                                }else{   ?>
                                    <select required="true" id="EquipmentID" name="EquipmentID">
                                        <option value=""> Select Equipment </option>
                                    <?php
                                    while($equipment=$result_equipment->fetch_assoc()){
                                        echo '<option value="'.$equipment['EquipmentID'].'">'.$equipment['Equipment'].'</option>';
                                    }?>
                                    </select>
                                    <i class="arrow"></i>
                                <?php } ?>     
                                </label>  
                            </div><!-- end section -->
                        </div>
                        <div class="colm colm6">    
                            <div class="section">
                                <label for="PartNumber" class="field-label">Part Number</label>
                                <label for="PartNumber" class="field">
                                    <input type="text"  required="true" id="PartNumber" name="PartNumber" class="gui-input" placeholder="Enter Part Number">
                                </label>
                            </div><!-- end section -->
                        </div>
                        
                        <div class="colm colm6">
                            <div class="section">
                                <label for="MaxUnit" class="field-label">Enter Maximum Quantity</label>
                                <label for="MaxUnit" class="field">
                                    <input required="true" type="text" name="MaxUnit" id="MaxUnit" class="gui-input" placeholder="Enter Maximum Quantity">
                                </label>
                            </div><!-- end section -->
                        </div><!-- end .colm6 section -->
                        
                        <div class="colm colm4">    
                            <div class="section heading">
                                <label class="field">
                                    <button class="button" disabled="">Part Status:</button>
                                </label>
                            </div><!-- end section -->                            
                        </div>
                        <div class="colm colm2">    
                            <div class="section">
                                <label for="Yes" class="option block">
                                    <input type="radio" name="Active" id="Yes" value="Yes">
                                    <span class="radio"></span> Active
                                </label>
                            </div><!-- end section -->
                        </div>
                        <div class="colm colm2">   
                            <div class="section">
                                <label for="No" class="option block">
                                    <input type="radio" name="Active" id="No" value="No" checked="">
                                    <span class="radio"></span> In-Active
                                </label>
                            </div><!-- end section -->                            
                        </div>
                        
                        <div class="section">
                        	<label for="ItemDescription" class="field-label">Add Description</label>
                            <label for="ItemDescription" class="field prepend-icon">
                            	<textarea class="gui-textarea" id="ItemDescription" name="ItemDescription" placeholder="Add Description"></textarea>
                                <label for="ItemDescription" class="field-icon"><i class="fa fa-comments"></i></label>
                                <span class="input-hint"> 
                                	<strong>Hint:</strong> Don't be negative or off topic! just be awesome... 
                                </span>   
                            </label>
                        </div><!-- end section -->
                                                               
                    </div><!-- end .frm-row section -->                    
                                                                                             
                </div><!-- end .form-body section -->
                <div class="spacer-t40 spacer-b30">
                	<div class="tagline"><span><input type="submit" name="submit" id="submit" class="button btn-primary" value="Add Parts"></span></div><!-- .tagline -->
                </div>
            </form><br />
            <div class="wrap-2">
                <div class="spacer-t40 spacer-b30">
                	<div class="tagline"><span> ::Parts List </span></div><!-- .tagline -->
                </div>
                
                <div class="frm-row">
                        <?php 
                        if($items!='NOT_FOUND'){
                        ?>
                        <table id="pd-table">
                            <thead><tr><th>Equipment</th><th>Part Number</th><th>Description</th><th>Quantity</th><th>Active</th><th>Controls</th></tr></thead>
                            <?php while($item=$result_data->fetch_assoc()){ ?>
                                <tr>
                                    <td><?php echo $item['Equipment'];?></td>
                                    <td><?php echo $item['PartNumber'];?></td>
                                    <td><?php echo $item['ItemDescription'];?></td>
                                    <td><?php echo $item['MaxUnit'];?></td>
                                    <td><?php echo $item['ApprovalReq'];?></td>
                                    <td><a title="Edit" href="edit-parts.php?id=<?php echo $item['ItemID'];?>"><i class="icon-edit"></i></a></td>
                                </tr>
                            <?php }  ?>
                        </table>
                        <?php } ?>
                        
                                                               
                    </div>
                
                <div class="form-footer">
                	Footer
                </div><!-- end .form-footer section -->
            </div>
        </div><!-- end .smart-forms section -->
        
    </div><!-- end .smart-wrap section -->
    
    <div></div><!-- end section -->
<!-- delete record -->
<form action="" name="del_form" id="del_form" method="POST" ><input type="hidden" name="del_id" id="del_id" /></form>
<!-- related script-->
<script src="../js/pd-s.js" type="text/javascript"></script>
</body>
</html>