function ExecuteScript(strId)
{
  switch (strId)
  {
      case "6pwarwjFDBK":
        Script1();
        break;
      case "5hkwYlPpCpw":
        Script2();
        break;
      case "5emxhw63RHx":
        Script3();
        break;
      case "6QGZbhZ7yTE":
        Script4();
        break;
      case "5zd1s8P711J":
        Script5();
        break;
      case "6qsoT9sJyRU":
        Script6();
        break;
      case "6AWtLUrJJyP":
        Script7();
        break;
  }
}

function Script1()
{
  var player = GetPlayer();

function findLMSAPI(win) {
  if (win.hasOwnProperty("GetStudentID")) return win;

  else if (win.parent == win) return null;

  else return findLMSAPI(win.parent);
}

var lmsAPI = findLMSAPI(this);
var myName = lmsAPI.GetStudentName();
var array = myName.split(',');
var newName = array[0]; // you can also try array[1]
player.SetVar("lname", newName);
}

function Script2()
{
  var player = GetPlayer();

function findLMSAPI(win) {
  if (win.hasOwnProperty("GetStudentID")) return win;

  else if (win.parent == win) return null;

  else return findLMSAPI(win.parent);
}

var lmsAPI = findLMSAPI(this);
var myName = lmsAPI.GetStudentName();
var array = myName.split(',');
var newName = array[0]; // you can also try array[1]
player.SetVar("lname", newName);
}

function Script3()
{
  $.getJSON('https://api.ipify.org?format=json', function(data){

var player = GetPlayer();
player.SetVar("IPAddress",data.ip);

});
}

function Script4()
{
  //Copy and paste this entire script into a JavaScript trigger in your Storyline file (best to do this at the very beginning of the file)

var head = document.getElementsByTagName('head')[0];
var script = document.createElement('script');
script.src = '//code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
head.appendChild(script);
}

function Script5()
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

function Script6()
{
  var player = GetPlayer();

function findLMSAPI(win) {
  if (win.hasOwnProperty("GetStudentID")) return win;

  else if (win.parent == win) return null;

  else return findLMSAPI(win.parent);
}

var lmsAPI = findLMSAPI(this);
var myName = lmsAPI.GetStudentName();
var array = myName.split(',');
var newName = array[0]; // you can also try array[1]
player.SetVar("lname", newName);
}

function Script7()
{
  var player = GetPlayer();

function findLMSAPI(win) {
  if (win.hasOwnProperty("GetStudentID")) return win;

  else if (win.parent == win) return null;

  else return findLMSAPI(win.parent);
}

var lmsAPI = findLMSAPI(this);
var myName = lmsAPI.GetStudentName();
var array = myName.split(',');
var newName = array[1]; // you can also try array[1]
player.SetVar("fname", newName);
}

