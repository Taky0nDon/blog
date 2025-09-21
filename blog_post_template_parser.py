from pathlib import Path
from json import dump
from sys import argv

def get_post_parts(path: Path) -> dict[str, str]:
    with open(path) as template_file:
        template_content = template_file.read()

    post_parts = {}
    post_parts["title"], post_parts["author"],\
    post_parts["date"], post_parts["content"] = template_content.split("\n", maxsplit=3)
    return post_parts

def add_p_html_tags(post_content: str) -> str:
    processed_content = ""
    paragraphs = post_content.split("\n\n")
    for paragraph in paragraphs:
        processed_content += f"<p>{paragraph}</p>"
    return processed_content

if __name__ == "__main__":
    template = Path(argv[1])
    post = get_post_parts(template)
    final_content = add_p_html_tags(post["content"])
    print(final_content)
    with open("post.json", "w") as output:
        dump(post, output)
