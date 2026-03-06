// MANIRAKOZE Philbert    25/32820



function like(id){
    fetch("action.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"like=1&post="+id
    }).then(()=>{ location.reload(); });
}

function report(id){
    fetch("action.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"report=1&post="+id
    }).then(()=>{ alert("Post reported"); });
}

setInterval(function(){
    fetch("index.php")
    .then(res=>res.text())
    .then(data=>{
        let parser = new DOMParser();
        let doc = parser.parseFromString(data,"text/html");
        let newposts = doc.getElementById("posts");
        document.getElementById("posts").innerHTML = newposts.innerHTML;
    });
},10000);