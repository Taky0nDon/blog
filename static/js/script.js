const displayPost = (b) => {
    const urlToQueryPhp = "/ReadDb.php";
    const postTitle = b.target.innerHTML;
    fetch(urlToQueryPhp, {
        method: "POST",
        headers: { "Content-Type": "application/json", },
        body: JSON.stringify({ title: postTitle })
    }).
        then(response => response.json()).
        then(data => updatePost(data));
}

const updatePost = (postData) => {
    const postObject = {
        title: document.getElementById("postTitle"),
        author: document.getElementById("postAuthor"),
        date: document.getElementById("postDate"),
        content: document.getElementById("postContent")
    }
    postObject.title.innerText = postData.title;
    postObject.date.innerText = postData.date;
    postObject.content.innerHTML = postData.content;
}


// Make buttons a class with update option? Or just make it an object?
const buttons = {
    current: document.querySelectorAll(".blogPostButton"),
    update() {
        this.current = document.querySelectorAll(".blogPostButton")
    },
}

for (const button of buttons.current) {
    button.addEventListener("click", displayPost);
}
