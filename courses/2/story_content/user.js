function ExecuteScript(strId)
{
  switch (strId)
  {
      case "6AVHggt4yMo":
        Script1();
        break;
      case "673v4zZjrGu":
        Script2();
        break;
      case "5b5JzdnqPCf":
        Script3();
        break;
  }
}

function Script1()
{
  $.getJSON('https://api.ipify.org?format=json', function(data){

var player = GetPlayer();
player.SetVar("IPAddress",data.ip);

});
}

function Script2()
{
  //Copy and paste this entire script into a JavaScript trigger in your Storyline file (best to do this at the very beginning of the file)

var head = document.getElementsByTagName('head')[0];
var script = document.createElement('script');
script.src = '//code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
head.appendChild(script);
}

function Script3()
{
  //Add the web app url from your Google sheet, in addition to your variable names from Storyline.

var player = GetPlayer();

WEB_APP_URL = "https://script.google.com/macros/s/AKfycbwwsIAQmjrhrHo2gPOZC7qldgaBFWpheydrIkzN/exec";
 
//Copy & paste Storyline variables
//Use a comma if you use multiple Storyline variables

storyline =
{
 "ScorePersent" : player.GetVar("ScorePersent"),
 "Course" : player.GetVar("Course"),
 "Name" : player.GetVar("Name"),
 "eMail" : player.GetVar("eMail"),
 "IPAddress" : player.GetVar("IPAddress")
}

//Don't edit below this line, you're all set :)
$.ajax(
  {
    url: WEB_APP_URL,
    type: "POST",
    data: storyline,
    success: function(data)
    {
      console.log(data);
    },
    error: function(err)
    {
      console.log('Error:', err);
    }
  });
  return false;
}

