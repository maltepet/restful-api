# restful-api
A sample RESTful API I built as a learning tool. This API powers a micropost social site, similar to twitter. 
You can view the sample application at: <a href="http://social.mattaltepeter.com/">http://social.mattaltepeter.com/</a>. 
The API lives at: <a href="http://rest.mattaltepeter.com/">http://rest.mattaltepeter.com</a> and calls can be made to that URI, such as: <a href="http://rest.mattaltepeter.com/users/profile/21">http://rest.mattaltepeter.com/users/profile/21</a> 

Result of the above API call:
<pre>
{"success":true,"data":{"user_id":"21","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png","bio":"Do you see any Teletubbies in here? Do you see a slender plastic tag clipped to my shirt with my name printed on it? Do you see a little Asian child with a blank expression on his face sitting outside on a mechanical helicopter?","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","favorites":{"84":0,"81":0,"79":0,"77":0,"76":0,"75":0},"favorite_count":0,"followers":2,"following":0,"post_count":6,"posts":[{"post_id":"84","body":"test","time":"2015-01-19 00:17:36","attachment":null,"user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"},{"post_id":"81","body":"Test post","time":"2015-01-15 09:29:26","attachment":"http:\/\/rest.mattaltepeter.com\/uploads\/54b7eae65c02b.jpg","user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"},{"post_id":"79","body":"test","time":"2015-01-13 10:50:43","attachment":null,"user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"},{"post_id":"77","body":"try posting ","time":"2015-01-13 10:37:55","attachment":null,"user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"},{"post_id":"76","body":"Deadpool drawing","time":"2015-01-12 23:42:16","attachment":"http:\/\/rest.mattaltepeter.com\/uploads\/54b4be4901b07.jpg","user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"},{"post_id":"75","body":"test","time":"2015-01-12 23:41:42","attachment":null,"user_id":"21","name":"TWF0dCBBbHRlcGV0ZXI=","screen_name":"bWFsdGVwZXQ=","avatar":"http:\/\/rest.mattaltepeter.com\/uploads\/54bcae23436c5.png"}]}}</pre>

Names and screen names are base64_encoded, so you will have to decode those on your own when you receive the result. Also, you should base64_encode() names, usernames, emails, and passwords on login and registration before making the API call. 

Example API Call to register a user using the ApiCall class located <a href="https://github.com/maltepet/restful-api/blob/master/social/classes/apicall.php">here</a> (assuming you have a form that posts to a block of code containing this data: 

<pre>
$data = array('email' => base64_encode($_POST['email']), 'password' => base64_encode($_POST['password']), 'screen_name' => base64_encode($_POST['screen_name']), 'name' => base64_encode($_POST['name']));

$request = new ApiCall('users/register'); //ends up being http://rest.mattaltepeter.com/users/register
$r = $request->post($data);
</pre>

When you create a new instance of the ApiCall class, you need to pass the URL of the API action you wish to use. The ApiCall appends this URL to the full URL of the API action. 

If the user is registered successfully, you will receive a standard php object [if you are doing this in php] (because the ApiCall class json_decodes the result from the API) containing the following: 

<pre>
['success'] => true, ['data'] => {['name'] => NAME, ['user_id'] => USER_ID, ['screen_name'] => SCREEN_NAME, ['avatar'] => AVATAR
</pre>

You can go through the <a href="https://github.com/maltepet/restful-api/blob/master/rest/controllers/UserController.php">UserController.php</a> and the <a href="https://github.com/maltepet/restful-api/blob/master/rest/controllers/UserController.php">PostController.php</a> classes to see a full list of actions that are available and the data they rely on. The API appends 'Action' to the end of the action passed, so the function in the UserController class that corresponds to registering (users/register) becomes registerAction(). 
