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

def process_content(content: str) -> list[str]:
    """Removes new lines from raw text. Returns a list of paragraphs."""
    paragraphs = content.split("\n\n")
    for paragraph in paragraphs:
        paragraph = paragraph.replace("\n", "")
    return paragraphs

def add_p_html_tags(paragraph_list: list[str]) -> str:
    tagged_paragraph_list = []
    for paragraph in paragraph_list:
        tagged_paragraph_list.append(f"<p>{paragraph}</p>")
    return "".join(tagged_paragraph_list)

def export_json(post_dict: dict[str, str]) -> None:
    with open(f"{post_dict['title']}.json", "w") as output:
        dump(post_dict, output)

def export_text(post_dict: dict[str, str]) -> None:
    post_string = "\n".join([post['title'],
                             post['author'],
                             post['date'],
                             post['content']
                             ])
    with open(f"{post_dict['title']}.txt", "w") as output:
        output.write(post_string)

if __name__ == "__main__":
    template = Path(argv[1])
    post = get_post_parts(template)
    post["content"] = add_p_html_tags(process_content(post["content"]))
    if argv[2] == "json":
        export_json(post)
    elif argv[2] == "text":
        export_text(post)

