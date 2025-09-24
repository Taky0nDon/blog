const getTitle = (b) => {
    console.log(typeof(b));
    const urlToQueryPhp = "http://localhost:8181/php/ReadDb.php";
    const postTitle = b.target.innerHTML;
    const postData = fetch(urlToQueryPhp, {
        method: "POST",
        headers: { "Content-Type": "application/json", },
        body: JSON.stringify({ id: postTitle })
    }).
        then(response => response.json()).
        then(data => console.log(data));
    console.log(postData);
}

const postDiv = document.getElementById("postDisplay");
const titleDiv = document.getElementById("postTitle");
const dateDiv = document.getElementById("postDate");
const articleText = document.getElementById("postContent");
 
const buttons = document.querySelectorAll(".blogPostButton");

for (const button of buttons){
    button.addEventListener("click", getTitle);
}
