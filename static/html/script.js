const postDiv = document.getElementById("postDisplay");
const titleDiv = document.getElementById("postTitle");
const dateDiv = document.getElementById("postDate");
const articleText = document.getElementById("postContent");
 
const buttons = document.querySelectorAll(".blogPostButton");

for (const button of buttons){
    button.addEventListener("click", () => {
        const urlToQueryPhp = "http://localhost:3000/ReadDb.php";
        const postTitle = button.innerHTML;
        const postData = fetch(urlToQueryPhp, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ id: postTitle }),
            }).
                then(response => response.json()).
                then(data => console.log(data));
    });
}
