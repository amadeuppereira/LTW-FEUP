var divs = document.querySelectorAll("#account ul>*");

for(let i = 0; i < divs.length; i++){
    divs[i].addEventListener("click", function(){
        divs[i].classList.add("profile_options_selected");
        for(let n = 0; n < divs.length; n++){
            if(n != i){
                divs[n].classList.remove("profile_options_selected");
            }
        }
    });
}

var contentDiv = document.querySelector("#profile_content");

document.querySelector("#account_overview").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Account Overview</h1>';
});

document.querySelector("#edit_profile").addEventListener("click", function(){
    contentDiv.innerHTML = `<h1>Edit Profile</h1>
    <div class="profile_content_inside">
    <form action="#" method="get">
      <div id="profile_button">
        <div id="profile_info">
          Username <input type="text" name="username" value="Amadeu Pereira">
          Email <input type="email" name="email" value="amadeupereira@gmail.com">
          New Password <input type="password" name="password">
          Retype Password <input type="password" name="repeat_password">
          Update Profile Picture <input type="file" name="fileToUpload">
        </div>
        <div id="button_profile">
          <input type="submit" value="Save changes">
        </div>
      </div>
    </form>
    </div>`;
});

document.querySelector("#my_posts").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Posts</h1>';
});

document.querySelector("#my_comments").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Comments</h1>';
});

document.querySelector("#my_saved_posts").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>My Saved Posts</h1>';
});

document.querySelector("#logout").addEventListener("click", function(){
    contentDiv.innerHTML = '<h1>Logout</h1>';
});