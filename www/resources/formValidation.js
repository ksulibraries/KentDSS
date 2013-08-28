// A utility function that returns true if a string contains only
// whitespace characters.
function isBlank(e)
{
  if (e.value == null || e.value == "")
    return true;

  for(var i = 0; i < e.value.length; i++)
  {
     var c = e.value.charAt(i);
     if ((c != ' ') &&
         (c != '\n') &&
         (c != '\t'))
        return false;
  }
  return true;
}

// Checks if a required field is blank
function checkBlank(e)
{
  if (isBlank(e))
  {
    alert(e.description + " must be filled in.");
    return false;
  }
  return true;
}

// Checks if a required option has been selected
function checkSelected(e)
{
  if (isBlank(e))
  {
    alert(e.description + " must be selected.");
    return false;
  }
  return true;
}

// Checks if a field is numeric.
// If the optional min property is set, it checks it is greater than
// its value
// If the optional max property is set, it checks it is less than
// its value
function checkNumber(e)
{
  var v = parseFloat(e.value);

  if (isNaN(v))
  {
    alert(e.description + " must be a number");
    return false;
  }

  if ((e.minNumber != null) && (v < e.minNumber))
  {
    alert(e.description +
          " must be greater than or equal to " + e.minNumber);
    return false;
  }

  if (e.maxNumber != null && v > e.maxNumber)
  {
    alert(e.description +
          " must be less than or equal to " + e.maxNumber);
    return false;
  }

  return true;
}

// Checks if a field looks like a date in the 99/99/9999 format
function checkDate(e)
{
  var slashCount = 0;
  if (e.value.length != 10)
  {
    alert(e.description +
          " must have the format 99/99/9999" +
          " and be 10 characters in length");
    return false;
  }

  for(var j = 0; j < e.value.length; j++)
  {
    var c = e.value.charAt(j);

    if ((c == '/'))
       slashCount++;

    if (c != '/' && (c < '0' || c > '9'))
    {
      alert(e.description +
            " can contain only numbers and forward-slashes");
      return false;
    }
  }

  if (slashCount != 2)
  {
    alert(e.description +
          " must have the format 99/99/9999");
    return false;
  }

  return true;
}

// Checks if a field contains any whitespace
function checkWhitespace(e)
{
  var seenAt = false;

  for(var j = 0; j < e.value.length; j++)
  {
     var c = e.value.charAt(j);

     if ((c == ' ') || (c == '\n') || (c == '\t'))
     {
       alert(e.description +
             " must not contain spaces, tabs, or newlines");
       return false;
     }
  }
  return true;
}

// Now check for fields that are supposed to be emails.
// Only checks that there's one @ symbol and no whitespace
function checkEmail(e)
{
  var seenAt = false;

  for(var j = 0; j < e.value.length; j++)
  {
    var c = e.value.charAt(j);

    if ((c == ' ') || (c == '\n') || (c == '\t'))
    {
      alert(e.description + 
            " must not contain spaces, tabs, or newlines");
      return false;
    }

    if ((c == '@') && (seenAt == true))
    {
      alert(e.description + " must contain only one @");
      return false;
    }

    if ((c == '@'))
      seenAt = true;
  }

  if (seenAt == false)
  {
    alert(e.description + " must contain one @");
    return false;
  }
  return true;
}

// This is the function that performs <form> validation.
// It is invoked from the onSubmit( ) event handler.
// The handler should return whatever value this function
// returns.
function verify(f)
{

  // Loop through the elements of the form, looking for all
  // text and textarea elements. Report errors using a post validation,
  // field-by-field approach
  for(var i = 0; i < f.length; i++)
  {
     var e = f.elements[i];
		 //alert(e.type);
     
     if (((e.type == "text") || (e.type == "textarea") || (e.type == "password") || (e.type == "file")))
     {
        // first check if the field is empty and shouldn't be
        if (e.isRequired && !checkBlank(e))
          return false;

        // Now check for fields that are supposed to be numeric.
        if (!isBlank(e) && e.isNumeric && !checkNumber(e))
          return false;

        // Now check for fields that are supposed to be dates
        if (!isBlank(e) && e.isDate && !checkDate(e))
          return false;

        // Now check for fields that are supposed to be emails
        if (!isBlank(e) && e.isEmail && !checkEmail(e))
          return false;

        // Now check for fields that are supposed
        // not to have whitespace
        if (!isBlank(e) && e.hasNoSpaces && !checkWhitespace(e))
          return false;
     } // if (type is text or textarea)
     
     if (e.type == "select-one")
     {
        // first check if the field is empty and shouldn't be
        if (e.isRequired && !checkSelected(e))
          return false;
     }

     if (e.type == "checkbox")
     {
        //  see if the field is checked
        if (e.isRequired && !e.checked) {
        	alert(e.description);
          return false;
         }
     }

  } // for each character in field

  // There were no errors if we got this far
  return true;
}