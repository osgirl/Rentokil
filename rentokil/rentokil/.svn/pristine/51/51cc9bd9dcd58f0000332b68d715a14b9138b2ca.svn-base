
$(document).ready(function()
{
editSchool();
})
var Db = false;var valuepupil;var schoolnamevalue;
var optionArr=[];var id;var production_status;var status;var schoolid;var prodOrigValue;

 function compareRecord(val)
	{
     numb=id.substring(val);
	var tableschoolid=$("#schoolid"+numb+"").val();

$.each(SchoolArray, function (index, value) {

//inline error for same school names

                 if(tableschoolid==value.school_id){
                 if(valuepupil == value.pupilsno && val ==6) 
			         {
						
								   Db=false;
					}
					if(valuepupil != value.pupilsno && val ==6) 
				   {
					Db = true;
				  }
                 if(schoolnamevalue == value.schoolname && val ==10)
			        {
					
                     Db=false;
					}
					if(schoolnamevalue != value.schoolname && val ==10)
				   {
					Db = true;
				  }
                }
				 if(tableschoolid!=value.school_id)
				{
				 if(schoolnamevalue == value.schoolname)
				 {
				
				 //schoolnamevalue.focus red
				
				  $("#schoolnamevalue"+numb+"").each(function(){
				  $(this).focus(function(){
                               ModalFieldUI(this); 
							   });
							   });
				 
				 }
				
				}
    }); 
}
/************** edit school****************/
var nIncrement=0;
var tabschoolid,prodid;var bStatus;var t;
function editSchool(){
  $('input[type=text]').bind("keypress keyup",function(){
$("#btnSave").removeAttr("disabled");
$("#btnSave").text("Save");
 })
$('input[id^="pupils"]').bind("keypress keyup", function(){
  valuepupil="";id="";
         id = $(this).attr("id");
		  valuepupil=$(this).val();
           setTimeout(function(){
            compareRecord(6);
	if(Db){  
	 $("#btnSave").text('Saving')
	
	Db = false;
    appendToArray(6);
	} 
           },10000);
	 });
 $('input[id^="pupils"]').bind("blur",function(){
   valuepupil="";id="";
   
   id = $(this).attr("id");
    valuepupil=$(this).val();	
	compareRecord(6);
	if(Db){  
	 $("#btnSave").text('Saving')
	Db = false;
    appendToArray(6);
	}
	})
	
	var ncount =0;
$('input[id^="schoolname"]').bind("keypress keyup",function(){

  schoolnamevalue="";id="";
       id = $(this).attr("id");
       schoolnamevalue=$(this).val();
        setTimeout(function() {
                   compareRecord(10);
				   if(Db){  
				   Db = false;
             $("#btnSave").text('Saving')
             appendToArray(10);}        
                       },10000);
})

$('input[id^="schoolname"]').bind("blur",function(){
	schoolnamevalue =""; id="";
	id = $(this).attr("id");
	schoolnamevalue=$(this).val();
	compareRecord(10);
	if(Db){  
	Db = false;
	 $("#btnSave").text('Saving')
           appendToArray(10);
	}
	})

	$('select[id^="selectprod"]').change(function(){
	 $("#btnSave").removeAttr("disabled");
	id = $(this).attr("id");
	 $("#btnSave").text('Saving')
	appendToArray(10);
	});
	
	$('input[type="checkbox"]').click(function(){
	 $("#btnSave").removeAttr("disabled");
	id = $(this).attr("id");
	 $("#btnSave").text('Saving')
	appendToArray(12);
	})
	
	$('button[id^="AddschoolDisablebtn"]').click(function(){
	
	   id= $(this).attr("id");
	 var getValue = id.substring(19);
	  tabschoolid=$("#schoolid"+getValue+"").val();
	  prodid=$("#selectprod"+getValue+"").val();
	   $("#btnSave").text('Saving')
	 appendToArray(19);
	nIncrement =0;
	 $.each(SchoolArray, function (index, value){
           if(value.prodid == tabschoolid || value.prodid == prodid) 
		   {
					 nIncrement++;	
					 }
})			 
       		 

	
	alert(nIncrement);
	if(nIncrement >1)
				{
				      $('#disableBtnDialogBox').modal('show');
					  bStatus= false; //1-disable
			    }
				else
				{
				 bStatus= true; //0-enable
				var index = 0,
                 messg = [
                           "Enable", 
                           "Disable"
                    ];
                $(this).text(function(index, text){
               index = $.inArray(text, messg);
                return messg[++index % messg.length];
               });
                }
				updateSchoolstatus();
      });
				}

function updateSchoolstatus(){

$.ajax({
        url: BACKENDURL +"customeradmin/update_school_status",
        type: "post",
 
         data:  {
          session_id: localStorage["SESSIONID"],
          school_id: tabschoolid,
          contract_id:localStorage.getItem("contractid"),
          status:bStatus,
        },
        dataType: "json",
        crossDomain: true,
       success: function(data){
			if(data.session_status) {
			if(data.error == 0){
                
                   //alert ("sucess");
					 }
					 }
					 },
	 error:function(xhr, textStatus, error){
                        alert(error);
					 }
					 });
				}
				
			 
function disabletoggle(prodid){
				
	//alert("---------disabletoggle-------");
	
	for( var i=0; i< SchoolArray.length; i++)
	{
           if(SchoolArray[i].prodid == prodid || SchoolArray[i].prodid == 1) 
					 nIncrement++;			 
	}	
				if(nIncrement >1)
				{
				      $('#disableBtnDialogBox').modal('show');
					  bStatus= false; //1-disable
			    }
				else
				{
				 bStatus= true; //0-enable
				var index = 0,
                 messg = [
                       "Enable", 
                       "Disable"
                            ];
                $(this).text(function(index, text){
               index = $.inArray(text, messg);
                return messg[++index % messg.length];
               });
                }
     }
	 
	 
var nFirst=0;	
function appendToArray(nVal){
 //alert("---------appendToArray-------");
    getRow(nVal); 
		if(!change){
	  commonArray();   
	}
	}			
					
var change;
function getRow(count){
// alert("---------getRow-------");
	change=false;
	var numb = id.substring(count);
	contract_id =localStorage["contractid"]
    school_name=$("#schoolname"+numb+"").val();   
    selectpro=$("#selectprod"+numb+"").val();
	pupils_no =$("#pupils"+numb+"").val();
	production_status=$("#dataCheckbox"+numb+"").is(':checked');
	schoolid=$("#schoolid"+numb+"").val();
	if(production_status==false)
	production_status=0;
	else
	production_status=1;
	
	if ( $("#AddschoolDisablebtn"+numb+"").text() == "Disable")
	{
	status = 0;
	}
	else
	{
	status= 1; //enable
	}
	prodOrigValue=$("#selectprod"+numb+" :selected").text()
	
	$.each(optionArr, function (index, value){
	if(schoolid==value.school_id){
	value.contract_id=contract_id;
	value.school_name=school_name;
	value.production_id=selectpro;
	value.pupils_no=pupils_no;
	value.production_status=production_status;
	value.status=status;
	change=true;
	}
	})
	
	}
var bajax =false;

function commonArray(){
//alert("---------commonArray-------");
    
	if((schoolid!=null)||(school_name!=null)||(pupils_no!=null)||(selectpro!=null))
	{
	optionArr.push({"school_id":schoolid,"contract_id":contract_id ,"school_name":school_name, "production_id":selectpro,"pupils_no":pupils_no,"production_status":production_status,"status":status});
     }
    if((optionArr.length>0) && (bajax == false))
    {
     bajax=true;
     passSchoolValToDb();
    }
	
}

$("#btnSave").click(function(){
  $("#btnSave").text('Saving');
passSchoolValToDb ();
})
 var svalue,sname,spupil,sprodschool,sStatus,sprodstatus;
 
function passSchoolValToDb (){

  
	 $.ajax({
        url: BACKENDURL +"customeradmin/edit_schools",
        type: "post",
 
        data:  {
          session_id: localStorage["SESSIONID"],
          schools_edit: optionArr,
        },
        dataType: "json",
        crossDomain: true,
        success: function(data){
			if(data.session_status) {
			         if(data.error == 0){
                      $("#btnSave").attr("disabled", true);					 
					  $("#btnSave").text('Saved');
					 
					setTimeout(function(){ $("#btnSave").text('Save');},3000);
					bajax=false;
		 $.each(optionArr, function (index, value)
		 {
         svalue= value.school_id;
         sname= value.school_name;
		 spupil= value.pupils_no;
		 sprodschool=value.production_id;
		 sprodstatus=value.production_status;
		 sStatus= value.status;
		 
		    $.each(SchoolArray, function (index, value) {
                    if(svalue==value.school_id){
                               value.schoolname =sname;
			                   value.pupilsno=spupil;
							   value.production_id=sprodschool;
							   value.production_status=sprodstatus;
	                           value.status=sStatus;
		                                      }	

			   
                        });
           });

	}}
	
	 optionArr=[];
        },
        error:function(xhr, textStatus, error){
           alert(error);
			
      }
	  }); }

	