
// Function to validate is a string is numeric
function IsNumeric(sText)
{
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;

	  
	for (i = 0; i < sText.length && IsNumber == true; i++) 
	{ 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) 
		{
			IsNumber = false;
		}
	}
	return IsNumber;
}


function openURL(theLINK)
{
	// get the value of CARD ID
	cardid = document.theForm.choose_list.value;
	
	// get value of CARDNUMBER and concatenate if any of the values is numeric
	cardnumber = document.theForm.cardnumber.value;

	if ( (!IsNumeric(cardid)) && (!IsNumeric(cardnumber)) ){
		alert('CARD ID or CARDNUMBER must be numeric');
		return;	
	}

	goURL = cardid + "&cardnumber=" +document.theForm.cardnumber.value;
	
	addcredit = 0;
	// get calue of credits
	addcredit = document.theForm.addcredit.value;
	
	description = '';
	// get calue of credits
	description = document.theForm.description.value;
	
	
		
	if ( (addcredit == 0) || (!IsNumeric(parseFloat(addcredit))) ){
		alert ('Please , Fill credit box with a numeric value'); 
		return;
	}	
	
	// redirect browser to the grabbed value (hopefully a URL)
	self.location.href = theLINK + goURL + "&addcredit="+addcredit +"&description="+description;
	
	return false;
	
}

function clear_textbox()
{
if (document.theForm.cardnumber.value == "enter cardnumber")
	document.theForm.cardnumber.value = "";
} 

function clear_textbox2()
{
	if (document.theForm.choose_list.value == "enter ID Card")
		document.theForm.choose_list.value = "";
}
