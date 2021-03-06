let dropdown_options = document.querySelector(".selectable-dropdown .dropdown_options");
let dropdown_selection = document.querySelector(".selectable-dropdown .dropdown_selection");

if(auth == null)
    window.location.replace("index.php");

let allchannels = api.channel.get('all', [200])
.then(response => response.json())
.then(allchannels => {
    allchannels.data.forEach(channel => {
        let div = document.createElement('div');
        div.setAttribute('id', channel.channelid);
        div.textContent = channel.channelname.toUpperCase();
        dropdown_options.appendChild(div);
    })
})
.then(() => bindDropdownOptions());

let form_post = document.querySelector('#new_post_post');
form_post.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_post.querySelector('input[name="post_title"]').value;
    let content = form_post.querySelector('textarea').value;
    let channelid = dropdown_selection.getAttribute('selectionid');
    
    if(title == "" && content == "" && channelid == null) alert("Please fill the form.");
    else if(channelid == null) alert("Please select a Channel.");
    else if(title == "") alert("Please add a title.");
    else if(content == "") alert("Please add text.");
    else {
        api.story.post({
            channelid: channelid,
            authorid: auth.userid
        }, {
            storyTitle: title,
            storyType: 'text',
            content: content
        }).then(() => window.location.replace('index.php'));
    }
});

let form_img = document.querySelector('#new_post_image');
form_img.addEventListener('submit', event =>{
    event.preventDefault();
    let title = form_img.querySelector('input[name="post_title"]').value;
    let img = form_img.querySelector('input[name="upload-file"]').files[0];
    let channelid = dropdown_selection.getAttribute('selectionid');
       
    if(title == "" && img == undefined && channelid == null) alert("Please fill the form.");
    else if(title == "") alert("Please add a title.");
    else if(img == undefined) alert("Please upload an image");
    else if(channelid == null) alert("Please select a Channel");
    else{
        const formData = new FormData(event.target);
        api.fetch('upload', '', {
            method: 'POST',
            body: formData,
            contentType: false,
            processData: false,
        }).then(r => r.json())
        .then( r =>    
            api.story.post({
                channelid: channelid,
                authorid: auth.userid
            }, {
                storyTitle: title,
                storyType: 'image',
                content: r.info.imagefile,
                imageid: r.id
            }, [201])
            .then( r => r.json())
            .then(r => window.location.replace('post.php?id=' + r.data.storyid))
        );
    }
});

let form_title = document.querySelector('#new_post_title');
form_title.addEventListener('submit', event => {
    event.preventDefault();
    let title = form_title.querySelector('input[name="post_title"]').value;
    let channelid = dropdown_selection.getAttribute('selectionid');
    if(title == "" && channelid == null) alert("Please fill the form.");
    else if(channelid == null) alert("Please select a Channel.");
    else if(title == "") alert("Please add a title.");
    else{
        api.story.post({
            channelid: channelid,
            authorid: auth.userid
        }, {
            storyTitle: title,
            storyType: 'title'
        }).then(() => window.location.replace('index.php'));
    }
});