# Menu-driven CRUD operations on a list of books.
books = []
while True:
    print("\\n1.Add 2.View 3.Update 4.Delete 5.Exit")
    choice = input("Choose: ")
    if choice == "1":
        books.append({"id": input("Book ID: "), "title": input("Title: "),
                      "author": input("Author: ")})
        print("Book added.")
    elif choice == "2":
        for book in books: print(book)
        if not books: print("No books.")
    elif choice == "3":
        book_id = input("Book ID: ")
        book = next((b for b in books if b["id"] == book_id), None)
        if book:
            book["title"] = input("New title: ")
            book["author"] = input("New author: ")
            print("Book updated.")
        else: print("Book not found.")
    elif choice == "4":
        book_id = input("Book ID: ")
        old_length = len(books)
        books = [b for b in books if b["id"] != book_id]
        print("Deleted." if len(books) < old_length else "Book not found.")
    elif choice == "5": break
    else: print("Invalid choice.")
